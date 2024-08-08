<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterKeluargaRequest extends FormRequest
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
            'nik' => 'required|min:16|max:16',
            'nama_lengkap' => 'required',
            'alamat' => 'required',
            'desa' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'no_telp' => 'required|min:10|max:13',
            'puskesmas_id' => 'required',
        ];
    }
}
