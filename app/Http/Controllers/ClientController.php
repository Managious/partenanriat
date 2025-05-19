<?php
namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Requests\ClientRequest;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Client::query())
                ->addColumn('action', function ($client) {
                    return '
                        <button class="btn btn-warning btn-sm edit-btn">Edit</button>
                        <button class="btn btn-danger btn-sm delete-btn">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function store(ClientRequest $request)
    {
        Client::create($request->validated());
        return response()->json(['message' => 'Client created successfully']);
    }

    public function update(ClientRequest $request, Client $client)
    {
        $client->update($request->validated());
        return response()->json(['message' => 'Client updated successfully']);
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return response()->json(['message' => 'Client deleted successfully']);
    }

    public function list()
    {
        return Client::select('id', 'client_name')->get();
    }
}
