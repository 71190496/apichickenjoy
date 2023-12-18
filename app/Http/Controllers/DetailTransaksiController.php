<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use App\Models\DetailTransaksi;
use App\Http\Resources\PesananResource;
use App\Http\Resources\DetailTransaksiResource;
use Barryvdh\DomPDF\Facade\PDF;

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
        $items = $request->input('items');
        $totalHarga = 0;
        $totalPesanan = 0;

        // Membuat detail transaksi dengan waktu_transaksi saat ini
        $detailTransaksi = DetailTransaksi::create([
            'id_user' => $request->input('id_user'),
            'metode_pembayaran' => $request->input('metode_pembayaran'),
            'waktu_transaksi' => now(),
            'nama_pelanggan' => $request->input('nama_pelanggan'),
        ]); 

        foreach ($items as $item) {
            $menu = Menu::find($item['id_menu']);

            if (!$menu) {
                return response()->json(['statusCode' => 404, 'error' => 'Menu tidak ditemukan.'], 404);
            } 

            $totalHarga += $menu->harga * $item['jumlah_pesanan'];
            $totalPesanan += $item['jumlah_pesanan'];

            

            // Menambahkan entri ke dalam tabel Pesanan
            $detailTransaksi->pesanan()->create([
                'id_menu' => $menu->id_menu,
                'jumlah_pesanan' => $item['jumlah_pesanan'],
                'total_harga' => $menu->harga * $item['jumlah_pesanan'],
                'catatan' => $item['catatan'] ?? null,
            ]);
 
        }

        // Mengupdate total harga dan total pesanan pada detail transaksi
        $detailTransaksi->update([
            'total_harga' => $totalHarga,
            'jumlah_pesanan' => $totalPesanan,
        ]);

        return response()->json([
            'statusCode' => 201,
            'message' => 'Pesanan dibuat',
            'data' => new DetailTransaksiResource($detailTransaksi),
        ], 201);
    }

    public function generatePdf($id)
    {
        $detailTransaksi = DetailTransaksi::with('user', 'pesanan.menu')->findOrFail($id);

        // HTML content
        $html = view('nota', ['detailTransaksi' => $detailTransaksi])->render();


        // Load PDF
        $pdf = PDF::loadHtml($html)->setPaper('A4', 'portrait')->setWarnings(false);


        // Download PDF
        return $pdf->stream('nota.pdf');
    }
}
