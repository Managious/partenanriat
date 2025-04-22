<x-layout>
    <div class="dashboard">
        <h1>Modifier le produit</h1>
        
        <form action="{{ route('products.update', $product) }}" method="POST" class="auth-form" style="max-width: 800px;">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="product_name" value="{{ $product->product_name }}" required>
                </div>
                
                <div class="form-group">
                    <label>Marque</label>
                    <input type="text" name="product_brand" value="{{ $product->product_brand }}">
                </div>
                
                <div class="form-group">
                    <label>Catégorie</label>
                    <input type="text" name="product_category" value="{{ $product->product_category }}">
                </div>
                
                <div class="form-group">
                    <label>Sous-catégorie</label>
                    <input type="text" name="product_subcategory" value="{{ $product->product_subcategory }}">
                </div>
                
                <div class="form-group">
                    <label>Fournisseur</label>
                    <input type="text" name="product_supplier" value="{{ $product->product_supplier }}" required>
                </div>
                
                <div class="form-group">
                    <label>CMUP</label>
                    <input type="number" step="0.01" name="product_cmup" value="{{ $product->product_cmup }}" required>
                </div>
                
                <div class="form-group">
                    <label>Prix</label>
                    <input type="number" step="0.01" name="product_price" value="{{ $product->product_price }}" required>
                </div>
                
                <div class="form-group">
                    <label>Emplacement</label>
                    <input type="text" name="product_location" value="{{ $product->product_location }}">
                </div>
                
                <div class="form-group">
                    <label>Code EAN</label>
                    <input type="text" name="product_ean_code" value="{{ $product->product_ean_code }}">
                </div>
                
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" step="0.01" name="product_stock_1" value="{{ $product->product_stock_1 }}" required>
                </div>
                
                <div class="form-group" style="grid-column: span 2;">
                    <label>Image (URL)</label>
                    <input type="url" name="product_image_link" value="{{ $product->product_image_link }}">
                </div>
            </div>
            
            <div style="margin-top: 2rem; text-align: center;">
                <button type="submit" class="btn-submit">Mettre à jour le produit</button>
                <a href="{{ route('products.index') }}" class="btn-cancel" style="margin-left: 1rem;">Annuler</a>
            </div>
        </form>
    </div>
</x-layout>