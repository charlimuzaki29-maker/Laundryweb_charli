<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLayananRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_layanan' => ['required', 'string', 'max:100'],
            'harga_per_kg' => ['required', 'numeric', 'min:0'],
            'estimasi_waktu' => ['required', 'string', 'max:100'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'harga_per_kg.required' => 'Harga per kg wajib diisi.',
            'harga_per_kg.numeric' => 'Harga per kg harus berupa angka.',
            'harga_per_kg.min' => 'Harga per kg tidak boleh bernilai negatif.',
            'estimasi_waktu.required' => 'Estimasi waktu selesai wajib diisi.',
        ];
    }
}
