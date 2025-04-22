<x-layout>
    <div class="dashboard">
        <h1>Produits</h1>
        
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Marque</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->product_id }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->product_brand }}</td>
                            <td>{{ $product->product_price }} €</td>
                            <td>{{ $product->product_stock_1 }}</td>
                            <td class="action-buttons">
                                <a href="{{ route('products.edit', $product) }}" class="btn-warning">Modifier</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger" onclick="return confirm('Supprimer ce produit ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layout>