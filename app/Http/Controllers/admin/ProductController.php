<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Variant;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{

    public function create(){
        $categories = Category::all();
        $variants = Variant::all();
        return view('admin.products.create_product', compact('categories', 'variants'));
    }
    public function index(){
        $categories = Category::all();
        $variants = Variant::all();
        return view('admin.products.index', compact('categories', 'variants'));
    }
 public function getVariantFields($id)
{
    $variant = Variant::find($id);
    if(!$variant) return response()->json([]);

    // values array
    $values = $variant->values ?? [];
    // Response: simple array of values
    return response()->json($values);
}



public function getDataTable()
{
    $data = Product::with(['category:id,name','variant:id,name'])->select('products.*');
    return DataTables::of($data)
        ->addColumn('price', fn($row) => $row->price)
        ->addColumn('stock', fn($row) => $row->stock)
        ->addColumn('discount', fn($row) => $row->discount)
        ->addColumn('slug', fn($row) => $row->slug)
        ->addColumn('category_name', fn($row) => $row->category->name ?? '')
        ->addColumn('variant_name', fn($row) => $row->variant->name ?? '')
        ->addColumn('meta_description', fn($row) => Str::words($row->meta_description, 5, '...'))
        ->addColumn('content_description', fn($row) => Str::words($row->content_description, 5, '...')) // fix
        ->addColumn('images', function($row){
            $images = json_decode($row->images) ?? [];
            if(count($images) > 0 && $images[0]){
                return '<img src="'.asset('uploads/products/'.$images[0]).'" width="50" height="50" style="object-fit:cover;border-radius:5px;">';
            }
            return 'No Image';
           })
                ->addColumn('action', fn($row) => '<button class="btn btn-sm btn-primary editBtn" data-id="'.$row->id.'"><i class="bi bi-pencil-fill"></i></button> <button class="btn btn-sm btn-danger deleteBtn" data-id="'.$row->id.'"><i class="bi bi-trash-fill"></i></button>')
        ->rawColumns(['action','images'])
        ->make(true);
}
public function generateSlug(Request $request){
    $title = $request->title;
    $slug = Str::slug($title);
    return response(['slug'=>$slug]);

}


public function store(Request $request)
{


 $request->validate([
    'title' => 'required|string|max:255',
    'keyword' => 'required|string|max:255',
    'slug' => 'required|string',
    'price' => 'required|integer',
    'stock' => 'required|integer|min:0',
    'discount' => 'nullable|numeric|min:0|max:100',
    'meta_description' => 'required|string',
    'content_description' => 'required|string',
    'category_id' => 'required|exists:categories,id',
    'variant_id' => 'required|exists:variants,id',
    'variant_list' => 'required|array',
    'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:10240'
   ]);


    $imageNames = [];
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $img) {
            $name = time() . '_' . $img->getClientOriginalName();
            $img->move(public_path('uploads/products'), $name);
            $imageNames[] = $name;
        }
    }

    $product = Product::create([
        'title' => $request->title,
        'keyword' => $request->keyword,
        'slug' => $request->slug,
        'price' => $request->price,
        'stock'=> $request->stock,
        'discount' => $request->discount,
        'meta_description' => $request->meta_description,
        'content_description' => $request->content_description,
        'category_id' => $request->category_id,
        'variant_id' => $request->variant_id,
        'variant_list' => json_encode($request->variant_list),
        'images' => json_encode($imageNames),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Product saved successfully!',
        'data' => $product
    ]);
}



public function edit($id)
{
    $product = Product::find($id);
    if(!$product) {
        return response()->json(['success' => false, 'message' => 'Product not found']);
    }

    $images = json_decode($product->images, true) ?? [];
    $variantList = json_decode($product->variant_list, true) ?? []; // <-- add this

    return response()->json([
        'success' => true,
        'data' => $product,
        'images' => $images,
        'variant_list' => $variantList, // <-- send variant_list for Select2
    ]);
}




   public function update(Request $request)
{
    $validator = Validator::make($request->all(), [
    'title' => 'required|string|max:255',
    'keyword' => 'required|string|max:255',
    'slug' => 'required|string',
    'price' => 'required|numeric',
    'stock' => 'required|integer|min:0',
    'discount' => 'nullable|integer',
    'meta_description' => 'required|string',
    'content_description' => 'required|string',
    'category_id' => 'required|exists:categories,id',
    'variant_id' => 'required|exists:variants,id',
    'variant_list' => 'required|array',
    'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:10240'
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
    }

    $product = Product::find($request->product_id);

    if (!$product) {
        return response()->json(['success' => false, 'message' => 'Product not found!'], 404);
    }

    // Existing images
    $imageNames = json_decode($product->images, true) ?? [];

    // Upload new images
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $img) {
            $name = time() . '_' . $img->getClientOriginalName();
            $img->move(public_path('uploads/products'), $name);
            $imageNames[] = $name;
        }
    }

    // Update product
    $product->update([
        'title' => $request->title,
        'keyword' => $request->keyword,
        'slug' => $request->slug,
        'price' => $request->price,
        'stock'=> $request->stock,
        'discount' => $request->discount,
        'meta_description' => $request->meta_description,
        'content_description' => $request->content_description,
        'category_id' => $request->category_id,
        'variant_id' => $request->variant_id,
        'variant_list' => json_encode($request->variant_list),
        'images' => json_encode($imageNames),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Product updated successfully!',
        'data' => $product
    ]);
 }


 public function deleteImage(Request $request)
  {
    $product = Product::find($request->product_id);
    $imageName = $request->image;

    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'Product not found'
        ]);
    }

    // Delete image file from server
    $imagePath = public_path('uploads/products/' . $imageName);
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    // Remove image from product's images array
    $images = json_decode($product->images, true);
    if (($key = array_search($imageName, $images)) !== false) {
        unset($images[$key]);
        $product->images = json_encode(array_values($images));
        $product->save();
    }

    return response()->json([
        'success' => true,
        'message' => 'Image deleted successfully'
    ]);
}

public function destroy($id)
{
    $product = Product::find($id);
    if(!$product){
        return response()->json(['success' => false, 'message' => 'Product not found'], 404);
    }

    // Delete images from server
    if($product->images){
        $images = json_decode($product->images, true);
        if(is_array($images)){
            foreach($images as $img){
                $path = public_path('uploads/products/'.$img);
                if(file_exists($path)){
                    unlink($path);
                }
            }
        }
    }

    $product->delete();

    return response()->json(['success' => true, 'message' => 'Product deleted successfully!']);
}


}
