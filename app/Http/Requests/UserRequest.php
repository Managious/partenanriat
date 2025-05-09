<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UserRequest extends FormRequest
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
            'name' => 'required|string|max:30',
            'username' => 'required|string|max:25|unique:users,username,' . $this->id,
            'email' => [
            'required',
            'email',
            Rule::unique('users')->ignore($this->user)
        ],
            'role_id' => 'required|integer',
            'is_active' => 'boolean',
        ];

        // Quand on crée un utilisateur (POST), on exige un mot de passe
        if ($this->isMethod('post')) {
            $rules['password'] = 'required|string|min:6';
        }

        return $rules;
    }
}
