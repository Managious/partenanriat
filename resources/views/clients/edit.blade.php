<x-layout>
    <div class="dashboard">
        <h1>Modifier le client</h1>
        
        <form action="{{ route('clients.update', $client->client_id) }}" method="POST" class="auth-form" style="max-width: 800px;">
            @csrf
            @method('PUT')
            @include('clients.form', ['client' => $client])
            
            <div style="margin-top: 2rem; text-align: center;">
                <button type="submit" class="btn-submit">Mettre à jour</button>
                <a href="{{ route('clients.index') }}" class="btn-cancel">Annuler</a>
            </div>
        </form>
    </div>
</x-layout>