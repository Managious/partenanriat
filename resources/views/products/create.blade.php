<x-layout>
    <div class="dashboard">
        <h1>Ajouter un produit</h1>
        
        <form action="{{ route('products.store') }}" method="POST" class="auth-form" style="max-width: 800px;">
            @csrf
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                <div class="form-group">
                    <label>ID Produit</label>
                    <input type="text" name="product_id" value="{{ $nextId }}" readonly>
                    <small class="text-muted">Généré automatiquement</small>
                </div>
                
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="product_name" required>
                </div>
                
                <div class="form-group">
                    <label>Marque</label>
                    <input type="text" name="product_brand">
                </div>
                
                <div class="form-group">
                    <label>Catégorie</label>
                    <input type="text" name="product_category">
                </div>
                
                <div class="form-group">
                    <label>Sous-catégorie</label>
                    <input type="text" name="product_subcategory">
                </div>
                
                <div class="form-group">
                    <label>Fournisseur</label>
                    <input type="text" name="product_supplier" required>
                </div>
                
                <div class="form-group">
                    <label>CMUP</label>
                    <input type="number" step="0.01" name="product_cmup" required>
                </div>
                
                <div class="form-group">
                    <label>Prix</label>
                    <input type="number" step="0.01" name="product_price" required>
                </div>
                
                <div class="form-group">
                    <label>Emplacement</label>
                    <input type="text" name="product_location">
                </div>
                
                <div class="form-group">
                    <label>Code EAN</label>
                    <input type="text" name="product_ean_code">
                </div>
                
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" step="0.01" name="product_stock_1" required>
                </div>
                
                <div class="form-group" style="grid-column: span 2;">
                    <label>Image (URL)</label>
                    <!-- <input type="url" name="product_image_link"> -->
                    <input type="url" id="image-url" placeholder="https://example.com/image.jpg" />
                    <small id="validation-message" style="color: red;"></small>

                    <script>
                        const urlInput = document.getElementById('image-url');
                        const validationMessage = document.getElementById('validation-message');

                        urlInput.addEventListener('input', async function() {
                        const url = urlInput.value.trim();
                        validationMessage.textContent = "";

                        // 1. Vérifie si l'URL a une extension d'image valide
                        const allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif|\.webp)$/i;
                        if (!url.match(allowedExtensions)) {
                            validationMessage.textContent = "❌ L'URL doit se terminer par .jpg, .png, .gif ou .webp";
                            return;
                        }

                        // 2. Vérifie si le fichier distant est une image (via Content-Type)
                        try {
                            const response = await fetch(url, { method: 'HEAD' });
                            const contentType = response.headers.get('Content-Type');

                            if (!contentType || !contentType.startsWith('image/')) {
                                validationMessage.textContent = "❌ L'URL ne pointe pas vers une image valide.";
                                return;
                            }

                            validationMessage.textContent = "✅ Image valide !";
                            validationMessage.style.color = "green";
                        } catch (error) {
                        validationMessage.textContent = "❌ Impossible de vérifier l'URL (erreur réseau ou CORS).";
                        }
                        });
                    </script>
                </div>
            </div>
            
            <div style="margin-top: 2rem; text-align: center;">
                <button type="submit" class="btn-submit">Ajouter le produit</button>
            </div>
        </form>
    </div>
</x-layout>