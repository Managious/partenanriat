<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) 
        {
            $products = Product::query();
            return DataTables::of($products)
                ->addColumn('action', function ($product) {
                    return '
                        <button class="btn btn-sm btn-warning edit-btn" title="Edit"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger delete-btn" title="Delete"><i class="fas fa-trash"></i></button>
                    ';
                })
                ->make(true);
        }

        return response()->json([]);
    }

    public function store(ProductRequest $request)
    {
        $validated = $request->validated();
        Product::create($validated);

        return response()->json(['message' => 'Produit ajouté avec succès']);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id); // Changed from where('product_id', $id)
        return response()->json($product);
    }

    public function update(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id); // Changed from where('product_id', $id)
        $product->update($request->validated());

        return response()->json(['message' => 'Produit mis à jour avec succès']);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id); // Changed from where('product_id', $id)
        $product->delete();

        return response()->json(['message' => 'Produit supprimé avec succès']);
    }
}

