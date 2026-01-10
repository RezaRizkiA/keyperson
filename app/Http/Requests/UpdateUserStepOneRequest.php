<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UpdateUserStepOneRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $userId = Auth::id();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $userId],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone,' . $userId],
            'password' => [
                'nullable',
                'confirmed',
                'regex:/^\S+$/',
                Password::defaults(),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar oleh user lain.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.unique' => 'Nomor telepon ini sudah terdaftar oleh user lain.',
            'password.regex' => 'Password tidak boleh mengandung spasi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.letters' => 'Password wajib mengandung huruf.',
            'password.mixed' => 'Password wajib mengandung huruf besar dan kecil.',
            'password.numbers' => 'Password wajib mengandung angka.',
            'password.symbols' => 'Password wajib mengandung simbol (!@#$%^&* dll).',
        ];
    }
}
