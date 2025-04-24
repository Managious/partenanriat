<?php
namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // API : GET
    public function index()
    {
        return response()->json(Client::all());
    }

    // API : POST 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:100',
            'client_email' => 'required|email',
            'client_phone' => 'required|string|max:20',
            'client_city' => 'nullable|string|max:50',
            'client_zone' => 'nullable|string|max:50',
            'client_type' => 'nullable|string|max:50',
            'client_adress' => 'nullable|string|max:255',
        ]);

        $client = Client::create($validated);
        return response()->json($client, 201); // 201 Created
    }

    // API : GET /
    public function show(Client $client)
    {
        return response()->json($client);
    }

    // API : PUT
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:100',
            'client_email' => 'required|email',
            'client_phone' => 'required|string|max:20',
            'client_city' => 'nullable|string|max:50',
            'client_zone' => 'nullable|string|max:50',
            'client_type' => 'nullable|string|max:50',
            'client_adress' => 'nullable|string|max:255',
        ]);

        $client->update($validated);
        return response()->json($client);
    }

    // API : DELETE
    public function destroy(Client $client)
    {
        $client->delete();
        return response()->json(['message' => 'Client deleted successfully.']);
    }
}