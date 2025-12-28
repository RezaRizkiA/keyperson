<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
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
    public function rules()
    {
        return [
            'expert_id' => 'required|exists:experts,id',
            'skill_id'  => 'nullable|exists:skills,id', // Nullable for flexibility, but should be validated per flow
            'date_time' => 'required|date|after:now',
            'hours'     => 'required|integer|min:1|max:8',
            'topic'     => 'nullable|string|max:500',
            'type'      => 'required|in:individual,group',
            // Validasi Array Tamu:
            // 1. Wajib array jika ada data
            // 2. Maksimal 5 orang
            'guests'    => 'nullable|array|max:5',
            // Validasi isi array: Setiap item harus email yang valid & unik
            'guests.*'  => 'email|distinct',
        ];
    }

    public function messages()
    {
        return [
            'skill_id.exists' => 'Selected skill is not valid.',
            'guests.max' => 'You can only invite up to 5 colleagues.',
            'guests.*.email' => 'Please provide valid email addresses for your guests.',
        ];
    }
}
