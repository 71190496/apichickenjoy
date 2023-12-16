<?php

namespace App\Exports;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailTransaksi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExcelExport implements FromView 
{

    protected $invoices;

    public function __construct($invoices)
    {
        $this->invoices = $invoices;
    }

    public function view(): View
    {
        return view('exports.invoices', [
            'invoices' => $this->invoices,
        ]);
    }

    
}
