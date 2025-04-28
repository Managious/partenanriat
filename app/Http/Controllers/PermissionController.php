<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $permission = Permission::query();
            return DataTables::of($permission)
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

    public function store(PermissionRequest $request)
    {
        $validated = $request->validated();
        Permission::create($validated);

        return response()->json(['message' => 'Permission created successfully!']);
    }

    public function update(PermissionRequest $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->update($request->validated());

        return response()->json(['message' => 'Permission updated successfully!']);
    }

    public function delete($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json(['message' => 'Permission deleted successfully!']);
    }
}
