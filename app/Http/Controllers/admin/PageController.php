<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Page;

class PageController extends Controller
{
    public function get() {
        return view('admin.pages.create');
    }

    public function getData() {
        $pages = Page::query();
        return DataTables::of($pages)->make(true);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'p_type_name' => 'required|string|max:255',
            'p_slug'      => 'required|string|max:255',
            'p_name'      => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $page = Page::create($request->only('p_type_name', 'p_name', 'p_slug'));

        return response()->json([
            'status' => 'success',
            'message' => 'Page added successfully',
            'data' => $page
        ]);
    }

    public function edit($id) {
        $page = Page::findOrFail($id);
        return response()->json($page);
    }

    public function update(Request $request, $id) {
        $page = Page::findOrFail($id);
        $page->update($request->only('p_type_name', 'p_slug', 'p_name'));

        return response()->json([
            'status' => 'success',
            'message' => 'Page updated successfully'
        ]);
    }

    public function destroy($id) {
        $page = Page::findOrFail($id);
        $page->delete(); // Soft delete
        return response()->json([
            'status' => 'success',
            'message' => 'Page deleted successfully'
        ]);
    }
}
