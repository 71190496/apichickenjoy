<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Resources\PesananResource;
use Illuminate\Database\QueryException;

class PesananController extends Controller
{
    public function showSummary(Request $request)
    {
        $criteria = $request->header('criteria');

        switch ($criteria) {
            case 'today':
                $data = $this->getDataToday();
                break;

            case 'yesterday':
                $data = $this->getDataYesterday();
                break;

            case 'this-week':
                $data = $this->getThisWeekData();
                break;

            case 'this-month':
                $data = $this->getThisMonthData();
                break;

            default:
                return response()->json(['statusCode' => 404, 'error' => 'Invalid criteria provided.'], 400);
        }

        return response()->json($data);
    }

    private function getDataToday()
    {
        $today = Carbon::today();
        $pesanan = Pesanan::with('menu')
            ->whereDate('created_at', $today)
            ->get();

        return $this->transformSummaryResponse($pesanan);
    }

    private function getDataYesterday()
    {
        $yesterday = Carbon::yesterday();
        $pesanan = Pesanan::with('menu')
            ->whereDate('created_at', $yesterday)
            ->get();

        return $this->transformSummaryResponse($pesanan, 'Data penjualan belum ada.');
    }

    private function getThisWeekData()
    {
        $thisWeekStart = Carbon::now()->startOfWeek();
        $thisWeekEnd = Carbon::now()->endOfWeek();

        $pesanan = Pesanan::with('menu')
            ->whereBetween('created_at', [$thisWeekStart, $thisWeekEnd])
            ->get();

        return $this->transformSummaryResponse($pesanan, 'Data penjualan minggu ini belum ada.');
    }

    private function getThisMonthData()
    {
        $thisMonth = Carbon::now()->startOfMonth();

        $pesanan = Pesanan::with('menu')
            ->whereDate('created_at', '>=', $thisMonth)
            ->get();

        return $this->transformSummaryResponse($pesanan, 'Data penjualan bulan ini belum ada.');
    }


    private function transformSummaryResponse($pesanan, $errorMessage = null)
    {
        $summary = [
            'statusCode' => $pesanan->isEmpty() ? 200 : 200,
            'message' => $pesanan->isEmpty() ? ($errorMessage ?: 'Data penjualan tidak ada.') : 'Data penjualan ditemukan.',
            'pendapatan' => 0,
            'jumlah_pesanan' => $pesanan->sum('jumlah_pesanan'),
            'data' => []
        ];

        $menuCounts = [];

        foreach ($pesanan as $item) {
            $menuNama = $item->menu->nama_menu;
            $harga = $item->menu->harga;
            $jumlah = $item->jumlah_pesanan;
            $pendapatan = $harga * $jumlah;

            // Jika menu sudah ada dalam array, tambahkan jumlahnya
            if (isset($menuCounts[$menuNama])) {
                $menuCounts[$menuNama]['jumlah'] += $jumlah;
            } else {
                $hargaFormatted = (float) $harga;
                // Jika menu belum ada, tambahkan menu baru dengan jumlahnya
                $menuCounts[$menuNama] = [
                    'menu' => $menuNama,
                    'harga' => $hargaFormatted,
                    'jumlah' => $jumlah
                ];
            }
            $summary['pendapatan'] += $pendapatan;
        }

        // Konversi array asosiatif ke format array yang diinginkan untuk respons
        foreach ($menuCounts as $menu) {
            $summary['data'][] = $menu;
        }

        return $summary;
    }
}
