<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource; 

class AuthResource extends JsonResource
{  
    public function toArray($request) 
    {
         return[
            'id_user'=>$this->id_user,
            'nama_karyawan'=>$this->nama_karyawan,
            'username'=>$this->username, 
            'role'=>$this->role,
            'image'=>$this->getImageUrl(),
         ];
    }

    protected function getImageUrl()
    {
        if ($this->image) {
            return url("api/user/{$this->id_user}/image");
        }

        return null;
    }
}
