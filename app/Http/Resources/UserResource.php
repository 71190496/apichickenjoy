<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request) 
    {
         return[
            'id_user'=>$this->id_user,
            'nama_karyawan'=>$this->nama_karyawan,
            'username'=>$this->username,
            'password'=>$this->password,
            'role'=>$this->role,
            'image'=>$this->getImageUrl(),
         ];
    }

    protected function getImageUrl()
    {
        if ($this->image) {
            $randomString = Str::random(3);

            return url("api/user/{$this->id_user}/image/{$randomString}");
        }

        return null;
    }
}
