<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Resources\PesananResource;

class PesananController extends Controller
{
    public function show($date)
    {
        $pesanan = Pesanan::with('menu')
            ->whereDate('created_at', $date)
            ->get();

        return $this->generateSummaryResponse($pesanan);
    }

    public function showToday()
    {
        $today = Carbon::today();
        $pesanan = Pesanan::with('menu')
            ->whereDate('created_at', $today)
            ->get();

        return $this->generateSummaryResponse($pesanan);
    }

    public function showYesterday()
    {
        $yesterday = Carbon::yesterday();
        $pesanan = Pesanan::with('menu')
            ->whereDate('created_at', $yesterday)
            ->get();

        $errorMessage = 'Data penjualan belum ada sampai minggu lalu.';
        return $this->generateSummaryResponse($pesanan, $errorMessage);
    }

    public function showLastWeek()
    {
        $lastWeek = Carbon::now()->subWeek();
        $pesanan = Pesanan::with('menu')
            ->whereDate('created_at', '>=', $lastWeek)
            ->get();

        return $this->generateSummaryResponse($pesanan);
    }

    public function showLastMonth()
    {
        $lastMonth = Carbon::now()->subMonth();
        $pesanan = Pesanan::with('menu')
            ->whereDate('created_at', '>=', $lastMonth)
            ->get();

        return $this->generateSummaryResponse($pesanan);
    }

    private function generateSummaryResponse($pesanan, $errorMessage = null)
    {
        // Kelompokkan pesanan berdasarkan id_menu
        $groupedPesanan = $pesanan->groupBy('id_menu');

        // Hitung informasi ringkasan
        $summary = [
            'hari' => optional($pesanan->first())->created_at->translatedFormat('l'),
            'tanggal' => optional($pesanan->first())->created_at->translatedFormat('j F Y'),
            'total_harga' => $pesanan->sum('total_harga'),
            'jumlah_pesanan' => $pesanan->sum('jumlah_pesanan'),
            'items' => [],
        ];

        foreach ($groupedPesanan as $idMenu => $pesananMenu) {
            $menu = $pesananMenu->first()->menu;

            $summary['items'][] = [
                'nama_menu' => $menu->nama_menu,
                'harga_menu' => $menu->harga,
                'jumlah_pesanan' => $pesananMenu->sum('jumlah_pesanan'),
                'total_harga_menu' => $pesananMenu->sum('total_harga'),
            ];
        }

        $result = [
            'statusCode' => $pesanan->isEmpty() ? 404 : 200,
            'message' => $pesanan->isEmpty() ? ($errorMessage ?: 'Data penjualan tidak ada.') : 'Data penjualan ditemukan.',
            'data' => [
                'summary' => $summary,
            ],
        ];
    
        return response()->json($result, $result['statusCode']);
    }
}
