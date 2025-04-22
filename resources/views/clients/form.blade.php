<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
    <!-- Colonne de gauche -->
    <div class="form-group">
        <label for="client_id">ID Client *</label>
        <input type="text" name="client_id" id="client_id" 
               value="{{ old('client_id', $client->client_id ?? '') }}" 
               {{ isset($client) ? 'readonly' : '' }}>
        @error('client_id')<div class="error-message">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
        <label for="client_name">Nom *</label>
        <input type="text" name="client_name" id="client_name" 
               value="{{ old('client_name', $client->client_name ?? '') }}">
        @error('client_name')<div class="error-message">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
        <label for="client_city">Ville</label>
        <input type="text" name="client_city" id="client_city" 
               value="{{ old('client_city', $client->client_city ?? '') }}">
    </div>

    <div class="form-group">
        <label for="client_zone">Zone</label>
        <input type="text" name="client_zone" id="client_zone" 
               value="{{ old('client_zone', $client->client_zone ?? '') }}">
    </div>

    <div class="form-group">
        <label for="client_type">Type</label>
        <input type="text" name="client_type" id="client_type" 
               value="{{ old('client_type', $client->client_type ?? '') }}">
    </div>

    <!-- Colonne de droite -->
    <div class="form-group">
        <label for="client_address">Adresse</label>
        <input type="text" name="client_address" id="client_address" 
               value="{{ old('client_address', $client->client_address ?? '') }}">
    </div>

    <div class="form-group">
        <label for="client_email">Email</label>
        <input type="email" name="client_email" id="client_email" 
               value="{{ old('client_email', $client->client_email ?? '') }}">
    </div>

    <div class="form-group">
        <label for="client_phone">Téléphone</label>
        <input type="text" name="client_phone" id="client_phone" 
               value="{{ old('client_phone', $client->client_phone ?? '') }}">
    </div>

    <div class="form-group">
        <label for="client_discount">Remise (%)</label>
        <input type="number" name="client_discount" id="client_discount" 
               value="{{ old('client_discount', $client->client_discount ?? 0) }}">
    </div>
</div>