<?php

namespace App\Http\Resources\Keluarga;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nik' => $this->nik,
            'nama_lengkap' => $this->nama_lengkap,
            'alamat' => $this->alamat,
            'desa' => $this->desa,
            'rt' => $this->rt,
            'rw' => $this->rw,
            'no_telp' => $this->no_telp,
            'is_approved' => $this->is_approved,
            'is_free_stunting' => $this->is_free_stunting,
            'puskesmas' => $this->puskesmas,
            'latest_tingkat_kemandirian' => $this->tingkat_kemandirian->last(),
            'latest_kesehatan_lingkungan' => $this->kesehatan_lingkungan->last(),
        ];
    }
}
