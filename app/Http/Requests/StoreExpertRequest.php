<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Pastikan user sudah login via middleware di route
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:150',
            'about'       => 'required|string|min:20',
            'price'       => 'required|numeric|min:0',
            'type'        => 'required|array|min:1',
            // Validasi Pivot Table (Array of Skill IDs)
            'skills'      => 'required|array|min:1',
            'skills.*'    => 'exists:skills,id',
            // Validasi Array JSON (Experiences, Socials, Licenses, Gallerys)
            // Kita biarkan nullable/array karena isinya dinamis
            'experiences' => 'nullable|array',
            'licenses'    => 'nullable|array',
            'licenses.*.certification' => 'required_with:licenses|string|max:200',
            'licenses.*.file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'gallerys'    => 'nullable|array',
            'gallerys.*.file' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'socials'     => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            // Pesan error kustom untuk licenses
            // Tanda (*) akan otomatis menangkap index array (0, 1, dst)
            'licenses.*.certification.required_with' => 'Nama sertifikasi wajib diisi jika Anda menambahkan lisensi.',
            'licenses.*.certification.required'      => 'Nama sertifikasi wajib diisi.',
            'licenses.*.certification.max'           => 'Nama sertifikasi maksimal 200 karakter.',

            
            'title.required'  => 'Judul wajib diisi.',
            'about.min'       => 'Deskripsi minimal 20 karakter.',
            'price.required'  => 'Harga sesi wajib diisi.',
            'skills.required' => 'Pilih minimal satu skill.',
        ];
    }
}
