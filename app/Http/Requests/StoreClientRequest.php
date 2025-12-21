<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
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
            'type' => [
                'required',
                'string',
                Rule::in(['company', 'agency', 'startup', 'government', 'ngo', 'individual'])
            ],
            'company_name' => 'required|string|min:3|max:150',
            'industry'       => 'required|string|max:100',
            'employee_count' => 'required|string|max:50',
            'address'        => 'required|string|max:500',
            'website' => 'nullable|url|max:255',
            'about' => 'required|string|min:50',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'skills' => 'required|array|min:1',
            'skills.*' => 'exists:skills,id',
        ];
    }

    /**
     * Pesan Error Custom (UX Friendly)
     */
    public function messages(): array
    {
        return [
            'type.required'          => 'Mohon pilih jenis entitas/perusahaan.',
            'type.in'                => 'Jenis entitas yang dipilih tidak valid.',

            'company_name.required'  => 'Nama perusahaan/instansi wajib diisi.',
            'company_name.min'       => 'Nama perusahaan terlalu pendek.',

            'industry.required'      => 'Mohon pilih industri yang sesuai.',
            'employee_count.required' => 'Mohon pilih jumlah karyawan.',

            'address.required'       => 'Alamat kantor wajib diisi untuk kredibilitas.',

            'website.url'            => 'Format website salah. Gunakan https://...',

            'about.required'         => 'Deskripsi singkat tentang perusahaan wajib diisi.',
            'about.min'              => 'Deskripsi terlalu singkat. Ceritakan minimal 50 karakter.',

            'logo.image'             => 'File logo harus berupa gambar.',
            'logo.max'               => 'Ukuran logo maksimal 3MB.',

            'cover_image.image'      => 'File banner harus berupa gambar.',
            'cover_image.max'        => 'Ukuran banner maksimal 5MB.',
            'skills.required' => 'Mohon pilih minimal satu keahlian (skill) yang Anda cari.',
            'skills.min'      => 'Pilih minimal 1 skill agar Expert bisa menemukan Anda.',
        ];
    }
}
