<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Http\Resources\MenuResource;
use Illuminate\Support\Facades\Storage;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Gd\Imagine;
use Illuminate\Support\Facades\Response;


class MenuController extends Controller
{
    public function index(Request $request)
    {
        $menu = Menu::all();

        if ($menu->isEmpty()) {
            return response()->json(['statusCode' => 400, 'message' => 'Menu ditemukan',], 404);
        }

        return response()->json(['statusCode' => 200, 'message' => 'Menu ditemukan', 'data' => MenuResource::collection($menu)], 200);
    }

    public function indexByCategory($kategori)
    {
        $query = Menu::query();
        $query->where('kategori', $kategori);

        $menus = $query->get();

        if ($menus->isEmpty()) {
            return response()->json(['statusCode' => 404, 'message' => 'Kategori tidak ditemukan'], 404);
        }

        return response()->json(['statusCode' => 200, 'message' => 'Kategori ditemukan', 'data' => MenuResource::collection($menus)], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required',
            'harga' => 'required|numeric',
            'kategori' => 'required',
            'jumlah_stok' => 'nullable|integer',
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

            // Menentukan rasio aspek yang diinginkan (4:3)
            $targetAspectRatio = 4 / 3;

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
        }

        // Simpan data di dalam database
        $menu = Menu::create([
            'nama_menu' => $request->input('nama_menu'),
            'harga' => $request->input('harga'),
            'kategori' => $request->input('kategori'),
            'jumlah_stok' => $request->input('jumlah_stok'),
            'image' => $imageData,
        ]);

        // Dapatkan URL gambar
        $imageUrl = url("api/menu/{$menu->id_menu}/image");

        $responseData = [
            'status_code' => 201,
            'message' => 'Menu berhasil ditambahkan',
            'data' => new MenuResource($menu),
            'image_url' => $imageUrl,
        ];

        return response()->json($responseData, 201);
    }


    public function getImage($id)
    {
        // Cari menu berdasarkan ID
        $menu = Menu::find($id);

        // Jika menu tidak ditemukan, kirim respons 404
        if (!$menu) {
            return response()->json(['message' => 'Menu not found'], 404);
        }

        // Ambil data gambar dari menu
        $imageData = $menu->image;

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


    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_menu' => 'required',
            'harga' => 'required|numeric',
            'kategori' => 'required',
            'jumlah_stok' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json(['error' => 'Menu tidak ditemukan.'], 404);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Menggunakan Imagine untuk crop gambar dengan rasio 4:3
            $imagine = new \Imagine\Gd\Imagine();
            $imagePath = $image->getRealPath();
            $image = $imagine->open($imagePath);

            // Menentukan rasio aspek yang diinginkan (4:3)
            $targetAspectRatio = 4 / 3;

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
            $menu->update(['image' => $imageData]);
        }

        $menu->update([
            'nama_menu' => $request->input('nama_menu'),
            'harga' => $request->input('harga'),
            'kategori' => $request->input('kategori'),
            'jumlah_stok' => $request->input('jumlah_stok'),
        ]);

        // Dapatkan URL gambar
        $imageUrl = url("api/menu/{$menu->id_menu}/image");

        $responseData = [
            'status_code' => 200,
            'message' => 'Menu berhasil diperbarui',
            'data' => new MenuResource($menu),
            'image_url' => $imageUrl,
        ];

        return response()->json($responseData, 200);
    }


    public function destroy($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json(['error' => 'Menu tidak ditemukan'], 404);
        }

        $menu->delete();

        return response()->json(['message' => 'Menu berhasil dihapus'], 200);
    }

    public function kategori(Request $request)
    {
        $categories = Menu::select('kategori')->distinct()->get();

        if ($categories->isEmpty()) {
            return response()->json(['status' => 404, 'message' => 'Kategori tidak ditemukan', 'data' => []], 404);
        }

        return response()->json(['statusCode' => 200, 'message' => 'Kategori ditemukan', 'data' => $categories->pluck('kategori')], 200);
    }
}
