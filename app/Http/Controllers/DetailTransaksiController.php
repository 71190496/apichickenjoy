<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use App\Models\DetailTransaksi;
use App\Http\Resources\PesananResource;
use App\Http\Resources\DetailTransaksiResource;

class DetailTransaksiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:User,id_user',
            'items' => 'required|array|min:1',
            'items.*.id_menu' => 'required|exists:Menu,id_menu',
            'items.*.jumlah_pesanan' => 'required|integer|min:1',
            'metode_pembayaran' => 'required',
            'nama_pelanggan' => 'required',
        ]);
        // $id_user = $request->input('id_user');
        // dd($id_user);
        // $metode_pembayaran = $request->input('metode_pembayaran');
        // dd($metode_pembayaran);
        $items = $request->input('items');
        // dd($items);
        // $nama_pelanggan = $request->input('nama_pelanggan');
        // dd($nama_pelanggan);
        $totalHarga = 0;
        $totalPesanan = 0;

        // Membuat detail transaksi dengan waktu_transaksi saat ini
        $detailTransaksi = DetailTransaksi::create([
            'id_user' => $request->input('id_user'),
            'metode_pembayaran' => $request->input('metode_pembayaran'),
            'waktu_transaksi' => now(),
            'nama_pelanggan' => $request->input('nama_pelanggan'),
        ]);
        // dd($detailTransaksi);

        foreach ($items as $item) {
            $menu = Menu::find($item['id_menu']);

            if (!$menu) {
                return response()->json(['error' => 'Menu not found.'], 404);
            }

            // // Memeriksa ketersediaan stok
            // if ($menu->jumlah_stok < $item['jumlah_pesanan']) {
            //     return response()->json(['error' => 'Stok tidak cukup untuk menu ' . $menu->nama_menu], 400);
            // }

            $totalHarga += $menu->harga * $item['jumlah_pesanan'];
            $totalPesanan += $item['jumlah_pesanan'];

            // Membuat relasi detail transaksi dengan menu yang dipesan
            // $detailTransaksi->menus()->attach($menu->id_menu, [
            //     'jumlah_pesanan' => $item['jumlah_pesanan'],
            //     'total_harga' => $menu->harga * $item['jumlah_pesanan'],
            // ]);

            // Menambahkan entri ke dalam tabel Pesanan
            $detailTransaksi->pesanan()->create([
                'id_menu' => $menu->id_menu,
                'jumlah_pesanan' => $item['jumlah_pesanan'],
                'total_harga' => $menu->harga * $item['jumlah_pesanan'],
                'catatan' => $item['catatan'] ?? null,
            ]);

            

            // // Mengurangi stok menu
            // $menu->update([
            //     'jumlah_stok' => $menu->jumlah_stok - $item['jumlah_pesanan'],
            // ]);
        }

        // Mengupdate total harga dan total pesanan pada detail transaksi
        $detailTransaksi->update([
            'total_harga' => $totalHarga,
            'jumlah_pesanan' => $totalPesanan,
        ]);

        return response()->json(new DetailTransaksiResource($detailTransaksi), 201);
    }
}
