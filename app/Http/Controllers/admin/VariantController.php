<?php

namespace App\Http\Controllers\Admin;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Variant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VariantController extends Controller
{
//    verient

public function getVerient()
{
    $data = Variant::all();
    return DataTables::of($data)->make(true);
}
public function showverient()
{
    return view('admin.verient.index');
}

public function storeverient(Request $request)
{
    // Validation
    $request->validate([
        'name' => 'required|string|max:255',
        'values' => 'required|array',
        'values.*' => 'string'
    ]);


    $variant = Variant::create([
        'name' => $request->name,
        'values' =>$request->values,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Variant created successfully',
        'data' => $variant
    ]);
}
//   Edit //
  public function getID($id)
{
    $variant = Variant::findOrFail($id);
    return response()->json($variant);
}
public function updateverient(Request $request)
{
    $request->validate([
        'variant_id' => 'required|exists:variants,id', // hidden field from modal
        'name' => 'required|string|max:255',
        'values' => 'required|array',
        'values.*' => 'string'
    ]);

    $variant = Variant::findOrFail($request->variant_id);

    $variant->update([
        'name' => $request->name,
        'values' => $request->values, // values is casted as array in model
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Variant updated successfully',
        'data' => $variant
    ]);
}

public function destroyverient($id)
{
    $Variant = Variant::findOrFail($id);
    $Variant->delete();

    return response()->json(['success' => 'Variant deleted successfully!']);
}

}
