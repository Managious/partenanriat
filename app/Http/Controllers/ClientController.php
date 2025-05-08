<?php
namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{
    public function index(Request $request)
    {   
        if($request->ajax())
        {
            $clients = Client::query();
            return DataTables::of($clients)
                ->addColumn('action', function ($client) {
                    return '
                        <button class="btn btn-sm btn-warning edit-btn" title="Edit"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger delete-btn" title="Delete"> <i class="fas fa-trash"></i></span></button>
                    ';
                })
                ->make(true);
        }

        return response()->json([]);
    }

    public function store(ClientRequest $request)
    {
        $validated = $request->validated(); 
        Client::create($validated);

        return response()->json(['message' => 'Client created successfully!']);
    }

    public function update(ClientRequest $request, $id)
    {
        $client = Client::findOrFail($id);
        $client->update($request->validated());

        return response()->json(['message' => 'Client updated successfully!']);
    }

    public function destroy($id)
    {
       $client = Client::findOrFail($id);
       $client->delete();

       return response()->json(['message' => 'Client deleted successfully.']);
    }
}