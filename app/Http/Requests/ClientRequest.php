<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_name' => 'required|string|max:50|unique:clients,client_name,' . $this->id,
            'client_city' => 'nullable|string|max:50',
            'client_zone' => 'nullable|string|max:50',
            'client_type' => 'nullable|string|max:20',
            'client_address' => 'nullable|string|max:100',
            'client_email' => 'nullable|email|max:50',
            'client_phone' => 'nullable|string|max:14',
            'client_discount' => 'nullable|integer|min:0|max:100',
        ];
    }
}