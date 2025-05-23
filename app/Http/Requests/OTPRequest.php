<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OTPRequest extends FormRequest
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
            'otp_token' => 'required|uuid',
            'otp' => 'required|digits:6',
        ];
    }

    public function messages(): array
    {
        return [
            'otp_token.required' => 'Campo otpToken é obrigatório!',
            'otp_token.uuid' => 'Campo otpToken deve ser do tipo uuid!',
            'otp.required' => 'O campo Otp é obrigatório!',
            'otp.digits' => 'O campo Otp deve conter 6 digitos!',
        ];
    }
}
