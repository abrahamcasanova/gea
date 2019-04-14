<?php

namespace App\Http\Controllers\Roles;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Module;

class PermissionController extends Controller
{
    public function count () {
        return Permission::count();
    }

    public function filter (Request $request)
    {
        $query = Permission::query();

        if($request->search) {
            $query->where('name', 'LIKE', '%'.$request->search.'%');
        }

        $roles = $query->orderBy($request->input('orderBy.column'), $request->input('orderBy.direction'))
                    ->paginate($request->input('pagination.per_page'));

        $roles->load('permissions','roles', 'users');

        return $roles;
    }

    public function store (Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:permissions',
            'display_name' => 'required|string|unique:permissions',
            'modules' => 'required'
        ]);

        if(isset($request->modules)){
            $permission = Permission::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'module_id' => $request->modules['id']
            ]);
        }
        return $permission;
    }

    public function getModules(){
        $modules = Module::all();
        return $modules;
    }

    public function getPermissionModulesPermissions($permission)
    {
        $permission = Permission::with('roles', 'users')->findOrFail($permission);
        $module = Module::findOrFail($permission->module_id);
        return [$permission,$module];
    }

    public function update (Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:roles,name,'.$request->id,
            'display_name' => 'required|string|unique:roles,display_name,'.$request->id
        ]);

        $permission = Permission::find($request->id);

        if ($permission->name != $request->name) {
            $permission->name = $request->name;
        }

        if ($permission->display_name != $request->display_name) {
            $permission->display_name = $request->display_name;
        }
        
        if(isset($request->module_id)){
            $permission->module_id = $request->module_id['id'];
        }

        $permission->save();
    }

    function check($permissionName) {
       if (! Auth::user()->hasPermissionTo($permissionName)) {
            abort(403);
       }
       return response('', 204);
    }

    public function destroy ($permission)
    {
        return Permission::destroy($permission);
    }
}
