<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage users');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all()->pluck('name');
        $important_roles = config('permission.important');
        $permissions = Permission::all()->pluck('name');
        $url = [
            'getPermissions' => route("backend.userRole.getPermissions"),
            'updatePermission' => route("backend.userRole.updatePermission")
        ];
        return view("backend.user.userRole", compact('roles', 'important_roles', 'permissions', 'url'));
    }

    public function getPermissions(Request $request) {
        $data = $request->validate([
            'role' => 'required|string'
        ]);
        return Role::findByName($request->role)->getPermissionNames()->all();
    }
    public function updatePermission(Request $request) {
        $data = $request->validate([
            'role' => 'required|string',
            'permission' => 'required|string',
            'value' => 'required|boolean',
        ]);

        $role = Role::findByName($request->role);

        if ($request->value) {
            $role->givePermissionTo($request->permission);
        } else {
            $role->revokePermissionTo($request->permission);
        }

        return $role->getPermissionNames()->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function role_store(Request $request)
    {
        $data = $request->validate([
            'role' => 'required|string',
        ]);

        Role::create(['name' => $data['role']]);

        return redirect()->back()->with('status', "A new role: '{$data['role']}' was created!");
    }
    public function role_destroy(Request $request)
    {
        $data = $request->validate([
            'role' => 'required|string',
        ]);

        // check if the roles to be deleted are important roles
        $important = config('permission.important');
        if (in_array($data['role'], $important)) {
            return redirect()->back()->withErrors(["You can't delete these important roles: " . implode(", ", $important)]);
        }

        Role::where('name', $data['role'])->delete();

        return redirect()->back()->with('status', "Role '{$data['role']}' was deleted!");
    }
    public function permission_store(Request $request)
    {
        $data = $request->validate([
            'permission' => 'required|string',
        ]);

        Permission::create(['name' => $data['permission']]);

        return redirect()->back()->with('status', "A new permission: '{$data['permission']}' was created!");
    }
    public function permission_destroy(Request $request)
    {
        $data = $request->validate([
            'permission' => 'required|string',
        ]);

        Permission::where('name', $data['permission'])->delete();

        return redirect()->back()->with('status', "Permission '{$data['permission']}' was deleted!");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
