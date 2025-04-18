<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Roles;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RolesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Roles::query();
            return DataTables::of($roles)
                ->addColumn('action', function ($role) {
                    return '
                        <button class="btn btn-sm btn-warning edit-btn" title="Edit"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger delete-btn" title="Delete"> <i class="fas fa-trash"></i></span></button>
                    ';
                })
                ->make(true);
        }

        return response()->json([]);
    }

    // Store a new role
    public function store(RoleRequest $request)
    {
        $validated = $request->validated();
        Roles::create($validated);

        return response()->json(['message' => 'Role created successfully']);
    }

    // Show a specific role (for editing)
    public function show($id)
    {
        $role = Roles::findOrFail($id);
        return response()->json($role);
    }

    // Update a role
    public function update(RoleRequest $request, $id)
    {
        $role = Roles::findOrFail($id);
        $role->update($request->validated());

        return response()->json(['message' => 'Role updated successfully']);
    }

    // Delete a role
    public function destroy($id)
    {
        $role = Roles::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }

    // Fetch roles for dropdown in user modal
    public function list()
    {
        $roles = Roles::select(['id', 'name'])->get();
        return response()->json($roles);
    }

}
