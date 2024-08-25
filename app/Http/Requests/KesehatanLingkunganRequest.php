<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KesehatanLingkunganRequest extends FormRequest
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
            'data' => 'required|array|min:4',
            'data.*.komponen_kesehatan_id' => 'required|integer',
            'data.*.kriteria_kesehatan_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'data.required' => 'Form wajib diisi.',
            'data.array' => 'Form wajib berupa array.',
            'data.min' => 'Form minimal 4 data.',
        ];
    }
}
