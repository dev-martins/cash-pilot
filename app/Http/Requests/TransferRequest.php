<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
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
            'email'  => 'required|email|exists:users,email',
            'amount' => 'required|numeric|min:0.01',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'O campo email é obrigatório!',
            'email.email' => 'O campo email deve ser um email válido!',
            'email.exists' => 'Email não registrado!',
            'amount.required' => 'O campo amount é obrigatório!',
            'amount.numeric' => 'O campo amount deve ser o tipo numeric!',
            'amount.min' => 'O campo amount deve ser ter o valor minimo de 0.01!',
        ];
    }
}
