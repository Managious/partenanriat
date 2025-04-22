<x-layout>
    <div class="dashboard">
        <h1>Liste des clients</h1>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <a href="{{ route('clients.create') }}" class="btn-primary">Ajouter un client</a>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: var(--primary); color: white;">
                        <th style="padding: 1rem; text-align: left;">ID</th>
                        <th style="padding: 1rem; text-align: left;">Nom</th>
                        <th style="padding: 1rem; text-align: left;">Ville</th>
                        <th style="padding: 1rem; text-align: left;">Zone</th>
                        <th style="padding: 1rem; text-align: left;">Email</th>
                        <th style="padding: 1rem; text-align: left;">Téléphone</th>
                        <th style="padding: 1rem; text-align: left;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                        <tr style="border-bottom: 1px solid var(--neutral-light);">
                            <td style="padding: 1rem;">{{ $client->client_id }}</td>
                            <td style="padding: 1rem;">{{ $client->client_name }}</td>
                            <td style="padding: 1rem;">{{ $client->client_city }}</td>
                            <td style="padding: 1rem;">{{ $client->client_zone }}</td>
                            <td style="padding: 1rem;">{{ $client->client_email }}</td>
                            <td style="padding: 1rem;">{{ $client->client_phone }}</td>
                            <td style="padding: 1rem; display: flex; gap: 0.5rem;">
                                <a href="{{ route('clients.edit', $client->client_id) }}" style="padding: 0.5rem 1rem; background-color: #ffc107; color: #000; text-decoration: none; border-radius: 5px;">Modifier</a>
                                <form action="{{ route('clients.destroy', $client->client_id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 0.5rem 1rem; background-color: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer;" onclick="return confirm('Supprimer ce client ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layout>