<?php

namespace App\Http\Controllers;



use App\Models\Pesanan;
use App\Exports\ExcelExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export()
    {
        $invoices = Pesanan::with('menu', 'transaksi.user')->get()->groupBy('menu.kategori'); // Retrieve data from your model

        return Excel::download(new ExcelExport($invoices), 'invoices.xlsx');
    }
}
