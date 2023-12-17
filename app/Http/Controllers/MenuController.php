<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\MenuResource;
use Illuminate\Validation\ValidationException;

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
        try {
            $request->validate([
                'nama_menu' => 'required',
                'harga' => 'required|numeric',
                'kategori' => 'required',
            ]);

            $menu = Menu::create([
                'nama_menu' => $request->input('nama_menu'),
                'harga' => $request->input('harga'),
                'kategori' => $request->input('kategori'),
            ]);

            return response()->json(['statusCode' => 201, 'message' => 'Menu berhasil ditambahkan', 'data' => new MenuResource($menu)], 201);
        } catch (ValidationException $e) {
            // Tanggapi jika validasi gagal
            return response()->json(['statusCode' => 422, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
        $request->validate([
            'nama_menu' => 'required',
            'harga' => 'required|numeric',
            'kategori' => 'required',
        ]);

        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json(['statusCode' => 404, 'error' => 'Menu tidak ditemukan.'], 404);
        }

        $menu->update([
            'nama_menu' => $request->input('nama_menu'),
            'harga' => $request->input('harga'),
            'kategori' => $request->input('kategori'),
        ]);

        return response()->json(['statusCode' => 201, 'message' => 'Menu berhasil diedit', 'data' => new MenuResource($menu)], 201);
        } catch (ValidationException $e) {
            // Tanggapi jika validasi gagal
            return response()->json(['statusCode' => 422, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        }
    }

    public function destroy($id_menu)
    {
        $menu = Menu::find($id_menu);

        if (!$menu) {
            return response()->json(['statusCode' => 404, 'error' => 'Menu tidak ditemukan'], 404);
        }

        $menu->delete();

        return response()->json(['statusCode' => 200, 'message' => 'Menu berhasil dihapus'], 200);
    }

    public function kategori()
    {
        $categories = Menu::select('kategori')->distinct()->get();

        if ($categories->isEmpty()) {
            return response()->json(['status' => 404, 'message' => 'Kategori tidak ditemukan', 'data' => []], 404);
        }

        return response()->json(['statusCode' => 200, 'message' => 'Kategori ditemukan', 'data' => $categories->pluck('kategori')], 200);

    }

    
}
