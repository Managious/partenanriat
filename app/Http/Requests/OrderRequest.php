<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
        return [
            //'invoice_number' => 'required|numeric|unique:orders,invoice_number',
            'invoice_nature' => 'required|string|max:20',
            'invoice_date' => 'nullable|date',
            'invoice_sale_point' => 'nullable|string|max:50',
            'ordered_by' => 'nullable|string|max:100',
            'ordered_on' => 'nullable|date',
            'invoiced_by' => 'nullable|string|max:100',
            'invoiced_on' => 'nullable|date',
            'delivered_by' => 'nullable|string|max:100',
            'delivered_on' => 'nullable|date',
            'payment_type' => 'nullable|string|max:20',
            'payment_amount' => 'nullable|numeric',
            'client_id' => 'required|exists:clients,client_id',
            'product_id' => 'required|exists:products,product_id',
            'product_sale_price' => 'required|numeric',
            'product_discount_1' => 'nullable|numeric',
            'quantity' => 'required|integer|min:1',

        ];
    }
    
}