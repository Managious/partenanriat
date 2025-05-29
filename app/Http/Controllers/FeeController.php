<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeeRequest;
use App\Models\Fee;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FeeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Fee::query())
                ->addColumn('action', function ($fee) {
                    return '
                        <button class="btn btn-sm btn-info edit-btn">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function store(FeeRequest $request)
    {
        Fee::create($request->validated());
        return response()->json(['message' => 'Fee created successfully']);
    }

    public function update(FeeRequest $request, Fee $fee)
    {
        $fee->update($request->validated());
        return response()->json(['message' => 'Fee updated successfully']);
    }

    public function destroy(Fee $fee)
    {
        $fee->delete();
        return response()->json(['message' => 'Fee deleted successfully']);
    }

    public function all()
{
    return Fee::all();
}
}
