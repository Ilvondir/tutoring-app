<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols(), 'confirmed'],
            'role' => ['required', 'in:teacher,student'],
        ];
    }

    /**
     * Set the name for custom name.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => 'Nazwa',
            'email' => 'Email',
            'password' => 'Hasło',
            'password_confirmation' => 'Potwierdzenie hasła',
            'role' => 'Rola',
        ];
    }
}
