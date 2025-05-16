<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RolesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) 
        {
            $roles = Role::query();
            return DataTables::of($roles)
                ->addColumn('action', function ($role) {
                    return '
                        <button class="btn btn-sm btn-warning edit-btn" title="Edit"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger delete-btn" title="Delete"><i class="fas fa-trash"></i></button>
                        <button class="btn btn-sm btn-secondary permission-btn" title="Permission"><i class="fas fa-lock"></i></button>
                        ';
                })
                ->make(true);
        }

        return response()->json([]);
    }

    public function store(RoleRequest $request)
    {
        try {
            $validated = $request->validated();
            Role::create($validated);
    
            return response()->json(['message' => 'Role created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating role'
            ], 500);
        }
    }

    public function update(RoleRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->update($request->validated());

        return response()->json(['message' => 'Role updated successfully']);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }

    public function list()
    {
        $roles = Role::select(['id', 'name'])->get();
        return response()->json($roles);
    }

    public function permissions($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return $role->permissions; 
    }

    public function savePermissions(Request $request, $roleId)
    {
        $request->validate([
            'permission_ids' => 'present|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        $role = Role::findOrFail($roleId);
        $role->permissions()->sync($request->permission_ids);

        return response()->json(['message' => 'Permissions updated successfully.']);
    }

}
