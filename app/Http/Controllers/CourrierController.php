<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourrierRequest;
use App\Models\Courrier;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CourrierController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $courriers = Courrier::query();
            return DataTables::of($courriers)
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
    public function store(CourrierRequest $request)
    {
        $validated = $request->validated();
        Courrier::create($validated);

        return response()->json(['message' => 'Courrier created successfully']);
    }

    // Show a specific role (for editing)
    public function show($id)
    {
        $courrier = Courrier::findOrFail($id);
        return response()->json($courrier);
    }

    // Update a role
    public function update(CourrierRequest $request, $id)
    {
        $courrier = Courrier::findOrFail($id);
        $courrier->update($request->validated());

        return response()->json(['message' => 'Courrier updated successfully']);
    }

    // Delete a role
    public function destroy($id)
    {
        $courrier = Courrier::findOrFail($id);
        $courrier->delete();

        return response()->json(['message' => 'Courrier deleted successfully']);
    }

    // Fetch roles for dropdown in user modal
    public function list()
    {
        $courriers = Courrier::select(['id', 'name'])->get();
        return response()->json($courriers);
    }
}
