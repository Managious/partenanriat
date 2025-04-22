<x-layout>
    <div class="dashboard">
        <h1>Ajouter un client</h1>
        
        <form action="{{ route('clients.store') }}" method="POST" class="auth-form" style="max-width: 800px;">
            @csrf
            @include('clients.form')
            
            <div style="margin-top: 2rem; text-align: center;">
                <button type="submit" class="btn-submit">Enregistrer le client</button>
                <a href="{{ route('clients.index') }}" class="btn-cancel">Annuler</a>
            </div>
        </form>
    </div>
</x-layout>