<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'product_name' => 'required|string|max:100',
            'product_brand' => 'nullable|string|max:50',
            'product_category' => 'nullable|string|max:50',
            'product_subcategory' => 'nullable|string|max:50',
            'product_supplier' => 'required|string|max:20',
            'product_cmup' => 'required|numeric',
            'product_price' => 'required|numeric',
            'product_location' => 'nullable|string|max:20',
            'product_ean_code' => 'nullable|string|max:50',
            'product_stock_1' => 'required|numeric',
            'product_image_link' => 'nullable|string',
            'created_by' => 'nullable|string',
            'updated_by' => 'nullable|string',
        ];
        // if ($this->isMethod('post')) {
        //     $rules['product_id'] = 'required|string|max:20|unique:products,product_id';
        // }
        
        return $rules;
    }
}
