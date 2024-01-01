<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Pesanan;
use App\Exports\ExcelExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export()
    {
        // Mengambil semua pengguna yang memiliki role 'admin'
        $admins = User::where('role', 'admin')->get();

        // Memeriksa apakah pengguna saat ini termasuk dalam daftar admin
        $currentUser = auth()->user();
        if (!$admins->contains($currentUser)) {
            return response()->json([
                'statuscode' => 403,
                'message' => 'Unauthorized. Only admins can export data.'], 403);
        }

        $invoices = Pesanan::with('menu', 'transaksi.user')->get()->groupBy('menu.kategori');

        return Excel::download(new ExcelExport($invoices), 'invoices.xlsx');
    }
}
