<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index(Request $request)
{
    if ($request->ajax()) {
        $suppliers = Supplier::select([
            'supplier_id',
            'supplier_name',
            'supplier_city',
            'supplier_zone'
        ]);

        return DataTables::of($suppliers)
            ->addColumn('action', function ($supplier) {
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_name' => 'required|string|max:255',
            'supplier_city' => 'required|string|max:255',
            'supplier_zone' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Supplier::create($request->all());

        return response()->json(['message' => 'Supplier created successfully']);
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::where('supplier_id', $id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'supplier_name' => 'required|string|max:255',
            'supplier_city' => 'required|string|max:255',
            'supplier_zone' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $supplier->update($request->all());

        return response()->json(['message' => 'Supplier updated successfully']);
    }

    public function destroy($supplier_id)
{
    $supplier = Supplier::where('supplier_id', $supplier_id)->firstOrFail();
    $supplier->delete();

    return response()->json(['message' => 'Supplier deleted successfully']);
}
}

