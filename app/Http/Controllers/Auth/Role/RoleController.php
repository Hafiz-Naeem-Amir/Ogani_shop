<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }
  public function create(){
    return view('roles.create');
  }
    public function store(Request $request)
    {
     $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name|max:255'
     ]);
     if($validator->fails())
     {
        return redirect()->back()->withErrors($validator)->withInput();
     }
     else
     {
        $role = Role::create(['name' => $request->name]);
        return redirect()->route('role.index')->with('success', 'Role created successfully.');
     }
    }
    public function edit($id)
    {     $role = Role::findOrFail($id);

        return view('roles.edit', compact('role'));
    }
    public function update(Request $request, $id)
    {
        $role = Role::findById($id);
        $validator = Validator::make($request->all(), [
          'name' => 'required|unique:roles,name',
        ]);
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else
        {
            $role->name = $request->name;
            $role->save();
            return redirect()->route('role.index')->with('success', 'Role updated successfully.');
        }
    }
   public function destroy($id)
{
    $role = Role::findOrFail($id);
    $role->delete();

    return redirect()->route('role.index')->with('success', 'Role deleted successfully.');
}

}
