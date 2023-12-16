<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request) 
    {
         return[
            'id_menu'=>$this->id_menu,
            'nama_menu'=>$this->nama_menu, 
            'harga'=>$this->harga, 
            'kategori'=>$this->kategori, 
            'image'=>$this->image, 
            'jumlah_stok'=>$this->jumlah_stok, 
         ];
    }
}
