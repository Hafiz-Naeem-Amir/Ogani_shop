<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
{
    $permissions = Permission::all();
    return view('permission.index', compact('permissions'));
}

  public function create(){
    return view('permission.create');
  }
    public function store(Request $request)
    {
     $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions,name|max:255'
     ]);
     if($validator->fails())
     {
        return redirect()->back()->withErrors($validator)->withInput();
     }
     else
     {
        $per = permission::create(['name' => $request->name]);
        return redirect()->route('permission.index')->with('success', 'permission created successfully.');
     }
    }

    public function edit($id)
    {     $per = Permission::findOrFail($id);

        return view('permission.edit', compact('per'));
    }
    public function update(Request $request, $id)
    {
        $per = Permission::findOrFail($id);
        $validator = Validator::make($request->all(), [
           'name' => 'required|unique:permissions,name|max:255'
        ]);
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else
        {
            $per->name = $request->name;
            $per->save();
            return redirect()->route('permission.index')->with('success', 'Role updated successfully.');
        }
    }
   public function destroy($id)
{
     $per = Permission::findOrFail($id);
     $per->delete();

     return redirect()->route('permission.index')->with('success', 'permission haas been deleted deleted successfully.');
}

}
