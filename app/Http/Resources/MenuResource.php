<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'data' => [
                'id_menu' => $this->id_menu,
                'nama_menu' => $this->nama_menu,
                'harga' => $this->harga,
                'kategori' => $this->kategori,
                'jumlah_stok' => $this->jumlah_stok,
                'image_url' => $this->getImageUrl(),
            ],
        ];
    }
    protected function getImageUrl()
    {
        if ($this->image) {
            return url("api/menu/{$this->id_menu}/image");
        }

        return null;
    }
}
