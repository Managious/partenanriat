<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Client::query())
                ->addColumn('action', function ($client) {
                    return '
                        <button class="btn btn-sm btn-warning edit-btn"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger delete-btn"><i class="fas fa-trash"></i></button>
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

    public function update(ClientRequest $request, $id)
    {
        $client = Client::findOrFail($id);
        $client->update($request->validated());

        return response()->json(['message' => 'Client updated successfully']);
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return response()->json(['message' => 'Client deleted successfully']);
 
    }
   



}