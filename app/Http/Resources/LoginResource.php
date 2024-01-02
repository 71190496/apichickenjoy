<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    public function toArray($request)
    {
        $user = User::where('username', $request->username)->first();
        $tokenName = $user->role === 'admin' ? 'Login Admin' : 'Login Karyawan';
        $plainTextToken = $user->createToken($tokenName)->plainTextToken;
        return [
            'statusCode' => 200,
            'id_user' => $this->id_user,
            'message' => $tokenName,
            'username' => $this->username,
            'plain_text_token' => $plainTextToken,
            'image' => $this->getImageUrl(),
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
