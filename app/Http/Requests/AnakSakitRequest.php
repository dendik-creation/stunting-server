<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnakSakitRequest extends FormRequest
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
            'nama_anak' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'usia' => 'required|string',
            'tinggi_badan' => 'required|numeric',
            'berat_badan' => 'required|numeric',
            'penyakit_penyerta' => 'required|array',
            'ibu_bekerja' => 'required|boolean',
            'pendidikan_ibu' => 'required|string',
            'riwayat_lahir_anak' => 'required|string',
            'penyakit_komplikasi' => 'required|array',
            'orang_tua_merokok' => 'required|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'nama_anak.required' => 'Nama anak wajib diisi',
            'jenis_kelamin.required' => 'Jenis kelamin wajib diisi',
            'usia.required' => 'Usia wajib diisi',
            'tinggi_badan.required' => 'Tinggi badan wajib diisi',
            'berat_badan.required' => 'Berat badan wajib diisi',
            'penyakit_penyerta.required' => 'Penyakit penyerta wajib diisi',
            'ibu_bekerja.required' => 'Status ibu bekerja wajib diisi',
            'pendidikan_ibu.required' => 'Pendidikan ibu wajib diisi',
            'riwayat_lahir_anak.required' => 'Riwayat lahir anak wajib diisi',
            'penyakit_komplikasi.required' => 'Penyakit komplikasi wajib diisi',
            'orang_tua_merokok.required' => 'Orang tua merokok wajib diisi',
        ];
    }
}
