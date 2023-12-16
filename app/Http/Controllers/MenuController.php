<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Http\Resources\MenuResource;
use Illuminate\Http\Response;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::query();

        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $menu = $query->get();

        if ($menu->isEmpty()) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        return response()->json(['statusCode' => 200 ,'data' =>   MenuResource::collection($menu)] ,200);
    }


    public function store(Request $request)
    {
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

        return response()->json(['message' => 'Menu berhasil ditambahkan', 'data' => new MenuResource($menu)], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_menu' => 'required',
            'harga' => 'required|numeric',
            'kategori' => 'required',
        ]);

        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json(['error' => 'Menu tidak ditemukan.'], 404);
        }

        $menu->update([
            'nama_menu' => $request->input('nama_menu'),
            'harga' => $request->input('harga'),
            'kategori' => $request->input('kategori'),
        ]);

        return response()->json(['message' => 'Menu berhasil diperbarui', 'data' => new MenuResource($menu)], 200);
    }

    public function destroy($id_menu)
    {
        $menu = Menu::find($id_menu);

        if (!$menu) {
            return response()->json(['error' => 'Menu tidak ditemukan'], 404);
        }

        $menu->delete();

        return response()->json(['message' => 'Menu berhasil dihapus'], 200);
    }
}
