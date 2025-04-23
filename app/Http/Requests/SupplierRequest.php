<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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

            'supplier_name' => 'required|string|max:50',
            'supplier_city' => 'nullable|string|max:50',
            'supplier_zone' => 'nullable|string|max:50',
            'supplier_type' => 'nullable|string|max:20',
            'supplier_address' => 'nullable|string|max:100',
            'supplier_email' => 'nullable|email|max:50',
            'supplier_phone' => 'nullable|string|max:14',
            'created_by' => 'nullable|string',
            'updated_by' => 'nullable|string',
        ];
        if ($this->isMethod('post')) {
            $rules['supplier_id'] = 'required|string|max:20|unique:suppliers,supplier_id';
        }
        
        return $rules;
    }
}
