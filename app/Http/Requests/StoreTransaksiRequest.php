<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransaksiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_pelanggan' => ['required', 'string', 'max:150'],
            'no_hp' => ['required', 'string', 'max:20'],
            'layanan_id' => ['required', 'exists:layanans,id'],
            'berat' => ['required', 'numeric', 'min:0.01'],
            'status' => ['nullable', 'in:Masuk,Proses,Selesai'],
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
            'nama_pelanggan.required' => 'Nama pelanggan wajib diisi.',
            'no_hp.required' => 'Nomor HP pelanggan wajib diisi.',
            'layanan_id.required' => 'Silakan pilih jenis layanan.',
            'layanan_id.exists' => 'Layanan yang dipilih tidak valid.',
            'berat.required' => 'Berat cucian (kg) wajib diisi.',
            'berat.numeric' => 'Berat harus berupa angka numerik.',
            'berat.min' => 'Berat cucian minimal 0.01 kg.',
            'status.in' => 'Status pengerjaan harus Masuk, Proses, atau Selesai.',
        ];
    }
}
