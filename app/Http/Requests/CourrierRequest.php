<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourrierRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'courrier_email' => 'nullable|email|max:50',
            'courrier_phone' => 'nullable|string|max:14',
            'created_by' => 'nullable|string',
            'updated_by' => 'nullable|string',
        ];
        
        return $rules;
    }
}
