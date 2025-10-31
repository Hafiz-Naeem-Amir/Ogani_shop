<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\Page;

class ContentController extends Controller
{
    public function index()
    {
        $pag = Page::select('id','p_type_name')->get(); // Ensure 'id' selected
        return view('admin.pages.content', compact('pag'));
    }

    public function getData()
 {
    $contents = Content::with('page');

    return DataTables::of($contents)
        ->addColumn('page_name', function($row){
            return $row->page->p_type_name ?? '';
        })
        ->addColumn('image', function($row){
            return $row->image ? '<img src="/'.$row->image.'" width="50"/>' : 'No Image';
        })
       ->addColumn('action', function($row){
    return '
        <button class="btn btn-sm btn-primary editBtn" data-id="'.$row->id.'">
            <i class="fas fa-edit"></i>
        </button>
        <button class="btn btn-sm btn-danger deleteBtn" data-id="'.$row->id.'">
            <i class="fas fa-trash"></i>
        </button>
    ';
})

        ->rawColumns(['action','image'])
        ->make(true);
}

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_id' => 'required|integer|exists:pages,id',
            'h1'      => 'nullable|string|max:255',
            'h2'      => 'nullable|string|max:255',
            'h3'      => 'nullable|string|max:255',
            'p1'      => 'nullable|string|max:255',
            'p2'      => 'nullable|string|max:255',
            'title'   => 'nullable|string|max:255',
            'design'  => 'nullable|string|max:255',
            'keyword' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only('page_id','h1','h2','h3','p1','p2','title','design','keyword','content');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'_'.$image->getClientOriginalName();
            $image->move(public_path('uploads/content'), $imageName);
            $data['image'] = 'uploads/content/' . $imageName;
        }

        $content = Content::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Content added successfully',
            'data' => $content
        ]);
    }

  public function edit($id){
    $content = Content::findOrFail($id);
    return response()->json($content);
}
 public function update(Request $request, $id)
{
    $content = Content::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'page_id' => 'required|integer|exists:pages,id',
        'h1' => 'required|string|max:255',
        'h2' => 'nullable|string|max:255',
        'h3' => 'nullable|string|max:255',
        'p1' => 'nullable|string|max:255',
        'p2' => 'nullable|string|max:255',
        'title' => 'nullable|string|max:255',
        'design' => 'nullable|string|max:255',
        'keyword' => 'nullable|string|max:255',
        'content' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'errors' => $validator->errors()
        ], 422);
    }

    $data = $request->only([
        'page_id', 'h1', 'h2', 'h3', 'p1', 'p2',
        'title', 'design', 'keyword', 'content'
    ]);

    // Handle image upload
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('uploads/content'), $imageName);
        $data['image'] = 'uploads/content/' . $imageName;
    }

    $content->update($data);

    return response()->json([
        'status' => 'success',
        'message' => 'Content updated successfully',
        'data' => $content
    ]);
}

public function destroy($id)
{
    $content = Content::findOrFail($id);
    $content->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Content deleted successfully'
    ]);
}



}
