<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ExerciseUpdateRequest extends FormRequest
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
            'assignment' => ['required', 'string'],
            'answer' => ['required', 'string'],
            'homework_id' => ['required', 'integer', 'exists:homeworks,id'],
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
            'assignment' => 'Polecenie',
            'answer' => 'Odpowiedź',
            'homework_id' => 'ID Pracy Domowej',
        ];
    }
}
