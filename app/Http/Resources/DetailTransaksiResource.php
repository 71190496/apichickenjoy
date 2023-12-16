<?php

namespace App\Http\Resources;

use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailTransaksiResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            // 'id_transaksi' => $this->id_transaksi,
            // 'id_user' => $this->id_user,
            'nama_pelanggan' => $this->nama_pelanggan,
            'jumlah_pesanan' => $this->jumlah_pesanan,
            'total_harga' => $this->total_harga,
            'metode_pembayaran' => $this->metode_pembayaran,
            'waktu_transaksi' => [
                'hari' => Carbon::parse($this->created_at)->translatedFormat('l'),
                'tanggal' => Carbon::parse($this->created_at)->translatedFormat('j F Y'),
                'jam' => Carbon::parse($this->created_at)->format('H:i:s'),
            ],
            'nama_karyawan' => $this->user->nama_karyawan,
            'menu_dipesan' => $this->pesanan->sortBy('created_at')->map(function ($pesanan) {

                return [
                    // 'id_menu' => $menu->id_menu,
                    'nama_menu' => $pesanan->menu->nama_menu, 
                    'catatan' => $pesanan->catatan,
                    'jumlah_pesanan' => $pesanan->jumlah_pesanan,
                    'total_harga' => $pesanan->total_harga, 
                ];
            }),
        ];
    }
}
