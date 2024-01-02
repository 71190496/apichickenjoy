<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id_menu' => $this->id_menu,
            'nama_menu' => $this->nama_menu,
            'harga' => $this->harga,
            'kategori' => $this->kategori,
            'jumlah_stok' => $this->jumlah_stok,
            'image' => $this->getImageUrl(),
        ];
    }
    protected function getImageUrl()
    {
        if ($this->image) {
            // Membuat string acak maksimal 3 karakter
            $randomString = Str::random(3);

            return url("api/menu/{$this->id_menu}/image/{$randomString}");
        }

        return null;
    }
}
