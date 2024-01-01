<?php

namespace App\Exports;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailTransaksi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExcelExport implements FromView, WithEvents, ShouldAutoSize, WithStyles
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Membuat sheet baru
                $newSheet = new Worksheet(null, 'Transaksi Per Bulan');
                $sheet->getParent()->addSheet($newSheet);

                // Menampilkan data di sheet baru
                $data = Pesanan::orderBy('created_at', 'asc')->get();


                // Mengatur header kolom
                $newSheet->mergeCells('A1:A2');
                $newSheet->setCellValue('A1', 'Tanggal');
                $newSheet->mergeCells('B1:B2');
                $newSheet->setCellValue('B1', 'Transaksi');
                $newSheet->mergeCells('C1:C2');
                $newSheet->setCellValue('C1', 'ID Transaksi');
                $newSheet->mergeCells('D1:O1');
                $newSheet->setCellValue('D1', 'Pendapatan');
                $newSheet->getStyle('D1:O1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $newSheet->mergeCells('P1:P2');
                $newSheet->setCellValue('P1', 'Total Pendapatan');

                // Mengatur alignment untuk sel yang telah digabungkan
                $mergedCells = ['A1:A2', 'B1:B2', 'C1:C2', 'P1:P2'];
                foreach ($mergedCells as $cellRange) {
                    $newSheet->getStyle($cellRange)
                        ->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER) // Tengah
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); // Middle
                }

                // Mengatur lebar kolom untuk kolom A, B, dan C
                $newSheet->getColumnDimension('A')->setWidth(29); // Contoh: lebar 15
                $newSheet->getColumnDimension('B')->setWidth(50); // Contoh: lebar 25
                $newSheet->getColumnDimension('C')->setWidth(11); // Contoh: lebar 20

                // Mengatur lebar kolom untuk kolom P
                $newSheet->getColumnDimension('P')->setWidth(17); // Contoh: lebar 20

                // Menambahkan style border untuk baris 1 dan 2
                $newSheet->getStyle('A1:P2')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Menambahkan nama bulan dari Januari hingga Desember di bawah kolom "Pendapatan"
                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                $totalPendapatanPerBulan = array_fill_keys($months, 0);


                // Mengisi label bulan di baris pertama
                foreach ($months as $index => $month) {
                    $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 4); // Mulai dari kolom D
                    $newSheet->setCellValue($col . '2', $month);
                }


                $totalPendapatanKeseluruhan = 0;
                // Mengisi transaksi
                $row = 3; // Baris pertama data transaksi
                foreach ($data as $item) {
                    $currentMonth = $item->created_at->translatedFormat('M'); // Mengambil format singkat bulan (Jan, Feb, dst.)

                    // Mencari index bulan di array $months
                    $monthIndex = array_search($currentMonth, $months);
                    if ($monthIndex !== false) {
                        // Mengisi data transaksi di kolom yang sesuai dengan bulan
                        $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($monthIndex + 4); // Mulai dari kolom D
                        $newSheet->setCellValue('A' . $row, $item->created_at->translatedFormat('j F Y'));
                        $newSheet->setCellValue('B' . $row, $item->menu->nama_menu);
                        $newSheet->setCellValue('C' . $row, $item->id_transaksi);
                        $newSheet->setCellValue($col . $row, $item->total_harga);

                        // Menambahkan total pendapatan untuk bulan tersebut
                        $totalPendapatanPerBulan[$currentMonth] += $item->total_harga;

                        // Menambahkan total pendapatan transaksi ke total pendapatan keseluruhan
                        $totalPendapatanKeseluruhan += $item->total_harga;
                    }
                    // Mengatur border untuk baris saat ini
                    $newSheet->getStyle('A' . $row . ':P' . $row)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['rgb' => '000000'],
                            ],
                        ],
                    ]);
                    $row++;
                }

                // Menulis total pendapatan per bulan di baris terakhir
                $row++; // Pindah ke baris berikutnya 
                $newSheet->setCellValue('A' . $row, 'Total Pendapatan Per Bulan');
                $newSheet->mergeCells('A' . $row . ':C' . $row);
                foreach ($totalPendapatanPerBulan as $month => $total) {
                    $monthIndex = array_search($month, $months);
                    $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($monthIndex + 4);
                    $newSheet->setCellValue($col . $row, $total);
                    $newSheet->getStyle('A' . $row . ':P' . $row)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['rgb' => '000000'],
                            ],
                        ],
                    ]);
                }

                // Menulis total pendapatan keseluruhan di kolom P baris terakhir 
                $newSheet->setCellValue('P' . $row, $totalPendapatanKeseluruhan); // Menggunakan baris berikutnya untuk menulis jumlahnya
                // Mengatur border untuk total pendapatan keseluruhan
                $newSheet->getStyle('A' . $row . ':P' . $row)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }




    public function styles(Worksheet $sheet)
    {
        $currentRow = 1;

        foreach ($this->invoices as $kategori => $invoicesByCategory) {
            // Mengatur border untuk tabel kategori saat ini
            $startCell = 'A' . $currentRow;
            $endCell = $sheet->getHighestColumn() . ($currentRow + count($invoicesByCategory) + 2); // +2 untuk header dan total

            $sheet->getStyle($startCell . ':' . $endCell)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ]);

            // Memperbarui baris saat ini untuk tabel berikutnya
            $currentRow += count($invoicesByCategory) + 4; // +4 untuk header, total, dan spasi
        }

        // Mengatur border untuk tabel "Total Pendapatan"
        foreach ($this->invoices as $kategori => $invoicesByCategory) {
            // Menghitung jumlah baris berdasarkan jumlah transaksi di kategori tersebut
            $totalCategories = count($this->invoices);

            // Mengatur sel awal dan sel akhir berdasarkan kategori
            $startCellTotal = 'A' . $currentRow;
            $endCellTotal = 'B' . ($currentRow + $totalCategories + 2); // +1 untuk header

            $sheet->getStyle($startCellTotal . ':' . $endCellTotal)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ]);
        }
    }
}
