<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        // Pastikan hanya admin yang dapat mengakses rute registrasi
        if (auth()->user() && auth()->user()->role !== 'admin') {
            throw ValidationException::withMessages([
                'message' => ['Unauthorized access.'],
            ]);
        }

        // $request->validate([
        //     'username' => 'required|unique:users',
        //     'password' => 'required|min:6',
        //     'nama_karyawan' => 'required',
        // ]);

        $user = User::create([
            'username' => $request->username,
            'nama_karyawan' => $request->input('nama_karyawan'),
            'password' => Hash::make($request->password),
            'role' => 'karyawan',
        ]);
        // dd($user);



        $user->id_user;
        $user->save();

        return response()->json([
            'statusCode' => 200,
            'message' => 'Registrasi Berhasil',
            'user' => $user->only(['id_user', 'nama_karyawan', 'username', 'role']),

        ]);
    }


    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Tambahkan kondisi untuk memeriksa peran admin
        if ($user->role !== 'admin' && $user->role != 'karyawan') {
            throw ValidationException::withMessages([
                'username' => ['Unauthorized access.'],
            ]);
        }

        // Buat token berdasarkan peran pengguna
        $tokenName = $user->role === 'admin' ? 'Login Admin' : 'Login Karyawan';
        $plainTextToken = $user->createToken($tokenName)->plainTextToken;

        $response = [
            'user_id' => $user->id_user,
            'token_name' => $tokenName,
            'plain_text_token' => $plainTextToken,
        ];

        // Convert the array to JSON and return the JSON response
        return response()->json($response);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }
}
