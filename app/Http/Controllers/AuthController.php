<?php

namespace App\Http\Controllers;

use App\Models\User;

use Imagine\Image\Box;
use Imagine\Image\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\LoginResource;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function register(Request $request)
    {

        // try {
            $request->validate([
                'username' => 'required|unique:User',
                'password' => 'required|min:6',
                'nama_karyawan' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Inisialisasi $imageData dengan nilai default null
            $imageData = null;

            if ($request->hasFile('image')) {
                $image = $request->file('image');


                // Menggunakan Imagine untuk crop gambar dengan rasio 4:3
                $imagine = new \Imagine\Gd\Imagine();
                $imagePath = $image->getRealPath();
                $image = $imagine->open($imagePath);
                // dd($image);

                // Menentukan rasio aspek yang diinginkan (4:3)
                $targetAspectRatio = 1 / 1;

                // Menghitung dimensi yang diinginkan berdasarkan rasio aspek
                $targetWidth = $image->getSize()->getWidth();
                $targetHeight = round($targetWidth / $targetAspectRatio);

                // Jika tinggi hasil perhitungan lebih besar dari tinggi gambar, kita perlu menghitung ulang
                if ($targetHeight > $image->getSize()->getHeight()) {
                    $targetHeight = $image->getSize()->getHeight();
                    $targetWidth = round($targetHeight * $targetAspectRatio);
                }

                // Menghitung titik awal untuk crop (agar berada di tengah)
                $startPoint = new Point(
                    max(0, ($image->getSize()->getWidth() - $targetWidth) / 2),
                    max(0, ($image->getSize()->getHeight() - $targetHeight) / 2)
                );

                // Crop gambar
                $image->crop($startPoint, new Box($targetWidth, $targetHeight));

                // Simpan gambar ke dalam variabel imageContent
                $imageContent = $image->get('jpeg');
                // dd($image);

                // Mengambil data gambar yang sudah di-encode sebagai base64
                $imageData = base64_encode($imageContent);
                // dd($imageData);
            }

            $user = User::create([
                'username' => $request->username,
                'nama_karyawan' => $request->input('nama_karyawan'),
                'password' => Hash::make($request->password),
                'role' => 'karyawan',
                'image' => $imageData,
            ]);

            // Dapatkan URL gambar
            $imageUrl = url("api/user/{$user->id_user}/image");

            $responseData = [
                'statusCode' => 201,
                'message' => 'Registrasi Berhasil',
                'data' => new AuthResource($user),
            ];
            return response()->json($responseData, 201);
        // } catch (ValidationException $e) {
        //     // Penanganan kesalahan validasi
        //     return response()->json([
        //         'statusCode' => 400,
        //         'message' => 'Registrasi Gagal',
        //         'errors' => $e->errors(),
        //     ], 400);
        // } catch (QueryException $e) {
        //     // Penanganan kesalahan basis data (misalnya, unik constraint)
        //     return response()->json([
        //         'statusCode' => 400,
        //         'message' => 'Registrasi Gagal',
        //         'errors' => ['username' => ['Username sudah digunakan']],
        //     ], 400);
        // } catch (\Exception $e) {
        //     // Penanganan kesalahan umum
        //     return response()->json([
        //         'statusCode' => 500,
        //         'message' => 'Terjadi kesalahan server.',
        //     ]);
        // }
    }

    public function updateUser(Request $request, $id_user)
    {
        // try {
            $request->validate([
                'username' => 'required|unique:User',
                'nama_karyawan' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // $nama = $request->input('username');
            // dd($nama);
            $user = User::find($id_user);

            if (!$user) {
                return response()->json(['error' => 'User tidak ditemukan.'], 404);
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image');

                // Menggunakan Imagine untuk crop gambar dengan rasio 4:3
                $imagine = new \Imagine\Gd\Imagine();
                $imagePath = $image->getRealPath();
                $image = $imagine->open($imagePath);

                // Menentukan rasio aspek yang diinginkan (4:3)
                $targetAspectRatio = 1 / 1;

                // Menghitung dimensi yang diinginkan berdasarkan rasio aspek
                $targetWidth = $image->getSize()->getWidth();
                $targetHeight = round($targetWidth / $targetAspectRatio);

                // Jika tinggi hasil perhitungan lebih besar dari tinggi gambar, kita perlu menghitung ulang
                if ($targetHeight > $image->getSize()->getHeight()) {
                    $targetHeight = $image->getSize()->getHeight();
                    $targetWidth = round($targetHeight * $targetAspectRatio);
                }

                // Menghitung titik awal untuk crop (agar berada di tengah)
                $startPoint = new Point(
                    max(0, ($image->getSize()->getWidth() - $targetWidth) / 2),
                    max(0, ($image->getSize()->getHeight() - $targetHeight) / 2)
                );

                // Crop gambar
                $image->crop($startPoint, new Box($targetWidth, $targetHeight));

                // Simpan gambar ke dalam variabel imageContent
                $imageContent = $image->get('jpeg');

                // Mengambil data gambar yang sudah di-encode sebagai base64
                $imageData = base64_encode($imageContent);

                // Update field 'image' di database
                $user->update(['image' => $imageData]);
            }

            $user->update([
                'username' => $request->input('username'),
                'nama_karyawan' => $request->input('nama_karyawan')
            ]);

            // Dapatkan URL gambar
            $imageUrl = url("api/user/{$user->id_user}/image");

            $responseData = [
                'status_code' => 200,
                'message' => 'Pofil berhasil diperbarui',
                'data' => new AuthResource($user),
            ];

            return response()->json($responseData, 200);
        // } catch (ValidationException $e) {
        //     // Penanganan kesalahan validasi
        //     return response()->json([
        //         'statusCode' => 400,
        //         'message' => 'Pembaruan Gagal',
        //         'errors' => $e->errors(),
        //     ], 400);
        // } catch (QueryException $e) {
        //     // Penanganan kesalahan basis data (misalnya, unik constraint)
        //     return response()->json([
        //         'statusCode' => 400,
        //         'message' => 'Registrasi Gagal',
        //         'errors' => ['username' => ['Username sudah digunakan']],
        //     ], 400);
        // } catch (\Exception $e) {
        //     // Penanganan kesalahan umum
        //     return response()->json([
        //         'statusCode' => 500,
        //         'message' => 'Terjadi kesalahan server.',
        //     ]);
        // }
    }

    public function getImage($id)
    {
        // Cari menu berdasarkan ID
        $user = User::find($id);

        // Jika menu tidak ditemukan, kirim respons 404
        if (!$user) {
            return response()->json(['message' => 'user not found'], 404);
        }

        // Ambil data gambar dari user
        $imageData = $user->image;

        // Jika tidak ada data gambar, kirim respons 404
        if (!$imageData) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        // Decode data Base64
        $decodedImageData = base64_decode(stream_get_contents($imageData));

        // Tentukan tipe konten
        $headers = [
            'Content-Type' => 'image/jpeg', // Ganti dengan tipe konten yang sesuai
        ];

        // Gunakan metode Response::make dan kirimkan konten gambar
        return response($decodedImageData, 200)->withHeaders($headers);
    }

    public function destroy(Request $request, $id_user)
    {
        $karyawan = User::where('id_user', $id_user)->first();

        if (!$karyawan) {
            return response()->json(['statusCode' => 404, 'error' => 'Karyawan not found for this user'], 404);
        }

        $karyawan->delete();

        return response()->json(['statusCode' => 200, 'message' => 'Karyawan deleted successfully'], 200);
    }


    public function login(Request $request)
    { 
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
                    'message' => ['Credential Error'],
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
            $responseData = new LoginResource($user);
            return response()->json($responseData, 200);
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
