<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $user = User::all();
        return response()->json(['statusCode' => 200 ,'data' =>   UserResource::collection($user)] ,200);
    }
}
