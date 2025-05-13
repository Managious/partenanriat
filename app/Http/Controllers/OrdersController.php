<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use Illuminate\Http\Request;
use App\Models\Order;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Product;
use Illuminate\Support\Facades\Log;


class OrdersController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $orders = Order::with('client', 'product')
                ->orderBy('invoice_number')
                ->get()
                ->groupBy('invoice_number')
                ->flatMap(function ($group) {
                    return $group->map(function ($order, $index) {
                        $order->show_invoice = $index === 0; // only first row shows invoice/client
                        return $order;
                    });
                });
    
            return DataTables::of($orders)
                ->addColumn('invoice_number', function ($order) {
                    return $order->show_invoice ? $order->invoice_number : '';
                })
                ->addColumn('product_name', function ($order) {
                    return optional($order->product)->product_name ?? '-';
                })
                ->addColumn('client_name', function ($order) {
                    return $order->show_invoice ? optional($order->client)->client_name : '';
                })
                ->addColumn('product_sale_price', function ($order) {
                    return $order->product_sale_price;
                })
                ->addColumn('product_discount_1', function ($order) {
                    return $order->product_discount_1;
                })
                ->addColumn('action', function ($order) {
                    return '
                        <button class="btn btn-sm btn-warning edit-btn" data-id="' . $order->id . '">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="' . $order->id . '">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    
        return response()->json(['message' => 'Invalid request'], 400);
    }
    

    public function store(OrderRequest $request)
    {
     try {
        $validated = $request->validated();

        $product = Product::where('product_id', $validated['product_id'])->first();

        if (!$product || $product->product_stock < $validated['quantity']) {
            return response()->json(['message' => 'Insufficient stock'], 422);
            }

        // Create order
        $order = Order::create($validated);

        // Reduce stock
        $product->decrement('product_stock', $validated['quantity']);

        return response()->json($order, 201);
        } catch (\Exception $e) {
        Log::error('storeCart failed', [
        'message' => $e->getMessage(),
        'line' => $e->getLine(),
        'file' => $e->getFile(),
        ]);

        return response()->json(['message' => 'Error saving order'], 500);    }
                            
    }

    public function update(OrderRequest $request, Order $order)
    {
        $order->update($request->validated());
        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(null, 204);
    }
    public function publicPlace(Request $request)
    {
        //  find a matching client by user name
        $client = \App\Models\Client::where('client_name', auth()->user()->name)->first();
    
        if (!$client) {
            return response()->json(['message' => 'Client not found for this user'], 404);
        }
    
        //  Merge the client's ID into the request for validation
        $request->merge([
            'client_id' => $client->client_id,
        ]);
    
        //  Validate input using the OrderRequest rules
        $validated = app(OrderRequest::class)->validated();
    
        // Check product stock
        $product = Product::where('product_id', $validated['product_id'])->first();
    
        if (!$product || $product->product_stock < $validated['quantity']) {
            return response()->json(['message' => 'Insufficient stock'], 422);
        }
    
        //  Create the order and adjust  stock
        $order = Order::create($validated);
        $product->decrement('product_stock', $validated['quantity']);
    
        return response()->json(['message' => 'Order created'], 201);
    }
    public function storeCart(Request $request)
    {
        try {
            //  testing (no auth)
            $user = auth()->user() ?? (object)['name' => 'driss san'];
    
            // 🔎 Find matching client
            $client = \App\Models\Client::where('client_name', $user->name)->first();
    
            if (!$client) {
                return response()->json(['message' => 'Client not found for this user'], 404);
            }
    
            // Common fields to apply to each order
            $common = [
                'client_id' => $client->client_id,
                'invoice_nature' => $request->input('invoice_nature', 'Standard'),
                'invoice_sale_point' => $request->input('invoice_sale_point', 'Web Store'),
                'invoiced_by' => $request->input('invoiced_by', $user->name ?? 'System'),
                'invoiced_on' => now(),
                'ordered_by' => $user->name ?? 'System',
                'ordered_on' => now(),
            ];
    
            $orders = [];
    
            foreach ($request->products as $item) {
                //  Get the product from DB
                $product = Product::where('product_id', $item['product_id'])->first();
    
                //  Fail if not found or not enough stock
                if (!$product || $product->product_stock_1 < $item['quantity']) {
                    return response()->json([
                        'message' => 'Insufficient stock for product: ' . ($product->product_name ?? 'Unknown')
                    ], 422);
                }
    
                //  Merge item and common data
                $orderData = array_merge($common, [
                    'product_id' => $item['product_id'],
                    'product_sale_price' => $item['product_sale_price'],
                    'product_discount_1' => $item['product_discount_1'] ?? 0,
                    'quantity' => $item['quantity'],
                ]);
    
                // Create the order
                $order = Order::create($orderData);
    
                //  Decrease stock
                $product->decrement('product_stock_1', $item['quantity']);
    
                $orders[] = $order;
            }
    
            return response()->json(['message' => 'Cart order placed', 'orders' => $orders], 201);
    
        } catch (\Exception $e) {
            Log::error('storeCart failed', [
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            ]);
            return response()->json(['message' => 'Error saving cart order'], 500);
        }
    }
    
   
    
}