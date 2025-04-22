<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'client_name' => 'required|string|max:50|unique:clients,client_name,' . $this->client_id . ',client_id',
            'client_city' => 'nullable|string|max:50',
            'client_zone' => 'nullable|string|max:50',
            'client_type' => 'nullable|string|max:20',
            'client_address' => 'nullable|string|max:100',
            'client_email' => 'nullable|email|max:50',
            'client_phone' => 'nullable|string|max:14',
            'client_discount' => 'nullable|integer|min:0|max:100',
            'created_by' => 'nullable|string',
            'updated_by' => 'nullable|string',
        ];

        if ($this->isMethod('post')) {
            $rules['client_id'] = 'required|string|max:20|unique:clients,client_id';
        }

        return $rules;
    }
}
