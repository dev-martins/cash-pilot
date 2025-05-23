<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->mixedCase()     // pelo menos uma maiúscula e uma minúscula
                    ->letters()       // deve conter letras
                    ->numbers()       // deve conter números
                    ->symbols()       // deve conter símbolos (caracteres especiais)
                    ->uncompromised() // verifica se a senha não está em listas de senhas vazadas
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Esse email já está sendo utilizado!',
            'email.email' => 'Precisa ser um email válido!',
            'email.required' => 'Email é obrigatório!',
            'name.max' => 'Tamanho máximo para campo nome excedido!',
            'name.string' => 'Campo nome deve ser um string!',
            'name.required' => 'Nome é obrigatório!',
            'password.required' => 'Campo senha é obrigatório',
            'password.string' => 'Campo senha deve ser uma string',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
            'password.mixed' => 'A senha deve conter letras maiúsculas e minúsculas.',
            'password.letters' => 'A senha deve conter pelo menos uma letra.',
            'password.numbers' => 'A senha deve conter pelo menos um número.',
            'password.symbols' => 'A senha deve conter pelo menos um caractere especial.',
            'password.uncompromised' => 'Essa senha já apareceu em um vazamento de dados. Escolha outra.',
        ];
    }
}
