<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class HomeworkStoreRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'student_id' => ['required', 'integer', 'exists:users,id'],
            'description' => ['nullable', 'string'],
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
            'title' => 'Tytuł',
            'student_id' => 'ID Studenta',
            'description' => 'Opis',
        ];
    }
}
