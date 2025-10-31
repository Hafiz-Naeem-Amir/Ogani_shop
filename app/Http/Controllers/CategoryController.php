<?php

namespace App\Http\Controllers;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Variant;


class CategoryController extends Controller
{
public function getData(Request $request)
{
    if ($request->ajax()) {
        $data = Category::get(['id','name','parent_id','is_parent','image','status']);

      return DataTables::of($data)
    ->editColumn('parent_id', function($row) {

        if ($row->parent) {
            return $row->parent->name;
        } else {
            return '-';
        }
    })
    ->make(true);
    }
}


 public function index()
    {
      $categories = Category::where('is_parent', 1)->select('id','name')->get();

        return view('admin.category.index' ,compact('categories'));
    }
public function store(Request $request)
{
    $data = $request->validate([
        'name'      => 'required|string|max:255',
        'status'    => 'required|boolean',
        'is_parent' => 'nullable|boolean',
        'parent_id' => 'nullable|exists:categories,id',
        'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    // ✅ Image Upload
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/categories'), $filename);
        $data['image'] =  $filename;
    }

    // ✅ Parent or Child Logic

    if ($request->is_parent == 1) {
        $data['is_parent'] = 1;
        $data['parent_name'] = $request->name;
        $data['parent_id'] = null;
    } elseif ($request->parent_id) {
        $data['is_parent'] = 0;
        $parent = Category::find($request->parent_id);
        $data['parent_name'] = $parent ? $parent->name : null;
    } else {
        return response()->json([
            'error' => 'Please select either Parent or Child category.'
        ], 422);
    }

    // ✅ Save Category
    Category::create($data);

    return response()->json(['success' => 'Category created successfully!']);
}

 public function edit($id){
     $category   = Category::findOrFail($id);
     return response()->json($category);
 }

public function update(Request $request, $id)
{
    $category = Category::findOrFail($id);

    $data = $request->validate([
        'name'      => 'required|string|max:255',
        'status'    => 'required|boolean',
        'is_parent' => 'nullable|boolean',
        'parent_id' => 'nullable|exists:categories,id',
        'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    // ✅ Agar user ne purani image delete kar di ho
    if ($request->has('remove_image') && $request->remove_image == 1) {
        if ($category->image && file_exists(public_path('uploads/categories/' . $category->image))) {
            unlink(public_path('uploads/categories/' . $category->image));
        }
        $data['image'] = null;
    }

    // ✅ Agar nayi image upload hui ho
    if ($request->hasFile('image')) {
        // Purani image delete
        if ($category->image && file_exists(public_path('uploads/categories/' . $category->image))) {
            unlink(public_path('uploads/categories/' . $category->image));
        }

        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/categories'), $filename);
        $data['image'] = $filename;
    }

    // ✅ Parent logic
    if ($request->is_parent == 1) {
        $data['is_parent'] = 1;
        $data['parent_name'] = $request->name;
        $data['parent_id'] = null;
    } elseif ($request->parent_id) {
        $data['is_parent'] = 0;
        $parent = Category::find($request->parent_id);
        $data['parent_name'] = $parent ? $parent->name : null;
    }

    $category->update($data);

    return response()->json(['success' => 'Category updated successfully!']);
}




public function destroy($id)
{
    $category = Category::findOrFail($id);
    $category->delete();

    return response()->json(['success' => 'Category deleted successfully!']);
}


}
