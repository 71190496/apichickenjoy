<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function register(Request $request)
    {


        // $sha512PasswordFromKotlin = $request->password;
        // $bcryptPassword = Hash::make($sha512PasswordFromKotlin);
        // dd($bcryptPassword);

        // // Pastikan hanya admin yang dapat mengakses rute registrasi
        // if (auth()->user() && auth()->user()->role !== 'admin') {
        //     throw ValidationException::withMessages([
        //         'message' => ['Unauthorized access.'],
        //     ]);
        // }

        try {
            $request->validate([
                'username' => 'required|unique:User',
                'password' => 'required|min:6',
                'nama_karyawan' => 'required',
            ]);

            $user = User::create([
                'username' => $request->username,
                'nama_karyawan' => $request->input('nama_karyawan'),
                'password' => Hash::make($request->password), 
                'role' => 'karyawan',
            ]);

            return response()->json([
                'statusCode' => 200,
                'message' => 'Registrasi Berhasil',
                'user' => $user->only(['id_user', 'nama_karyawan', 'username', 'role']),
            ]);
        } catch (ValidationException $e) {
            // Penanganan kesalahan validasi
            return response()->json([
                'statusCode' => 400,
                'message' => 'Registrasi Gagal',
                'errors' => $e->errors(),
            ]);
        } catch (QueryException $e) {
            // Penanganan kesalahan basis data (misalnya, unik constraint)
            return response()->json([
                'statusCode' => 400,
                'message' => 'Registrasi Gagal',
                'errors' => ['username' => ['Username sudah digunakan']],
            ]);
        } catch (\Exception $e) {
            // Penanganan kesalahan umum
            return response()->json([
                'statusCode' => 500,
                'message' => 'Terjadi kesalahan server.',
            ]);
        }
    }



    public function login(Request $request)
    {

        // $user = User::where('username', $request->username)->first();
        // $password = $request->password;
        // // $bcryptPassword = Hash::make($password);
        // // dd($bcryptPassword);
        // if ($user && Hash::check($password, $user->password)) {
        //     // dd($password);
        //     // Login berhasil 
        //     return response()->json(['message' => 'Login berhasil']);
        // } else {
        //     // Login gagal
        //     return response()->json(['message' => 'Username atau password salah'], 401);
        // }



        try {
            $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);

            $user = User::where('username', $request->username)->first();

            if (!$user) {
                throw ValidationException::withMessages([
                    'message' => ['Username salah.'],
                ]);
            }

            if (!Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'message' => ['Password salah.'],
                ]);
            }

            // Tambahkan kondisi untuk memeriksa peran admin
            if ($user->role !== 'admin' && $user->role != 'karyawan') {
                throw ValidationException::withMessages([
                    'message' => ['Unauthorized access.'],
                ]);
            }

            // Buat token berdasarkan peran pengguna
            $tokenName = $user->role === 'admin' ? 'Login Admin' : 'Login Karyawan';
            $plainTextToken = $user->createToken($tokenName)->plainTextToken;

            $response = [
                'statusCode' => 200,
                'message' => 'Login Berhasil',
                'user_id' => $user->id_user,
                'token_name' => $tokenName,
                'plain_text_token' => $plainTextToken,
            ];

            // Convert the array to JSON and return the JSON response
            return response()->json($response);
        } catch (ValidationException $e) {
            // Penanganan kesalahan validasi (kredensial salah, akses tidak diizinkan)
            return response()->json([
                'statusCode' => 401,
                'message' => 'Validasi Error',
                'errors' => $e->errors(),
            ], 401);
        } catch (\Exception $e) {
            // Penanganan kesalahan umum
            return response()->json([
                'statusCode' => 500,
                'message' => 'Internal Server Error',
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'statusCode' => 200,
            'message' => 'Logout berhasil',
        ]);
    }
}
