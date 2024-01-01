<div style="display: flex;">
    <div style="flex: 1;">
        @foreach ($invoices as $kategori => $invoicesByCategory)
            <h4>{{ $kategori }}</h4>
            @php
                $totalPendapatan = 0;
            @endphp
            <table class="table">
                <thead>
                    <h1>Makanan</h1>
                    <tr>
                        <th>Tanggal</th>
                        <th>Transaksi</th>
                        <th>Jumlah Pesanan</th>
                        <th>Reff</th>
                        <th>Karyawan</th>
                        <th>Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoicesByCategory as $invoice)
                        <tr>
                            <td>{{ $invoice->created_at }}</td>
                            <td>{{ $invoice->menu->nama_menu }}</td>
                            <td>{{ $invoice->jumlah_pesanan }}</td>
                            <td>{{ $invoice->id_transaksi }}</td>
                            <td>{{ $invoice->transaksi->user->nama_karyawan }}</td>
                            <td>{{ $invoice->total_harga }}</td>
                        </tr>
                        @php
                            $totalPendapatan += $invoice->total_harga;
                        @endphp
                    @endforeach
                    <tr>
                        <td colspan="5" style="text-align: right;"><strong>Total Pendapatan:</strong></td>
                        <td>{{ $totalPendapatan }}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    </div>
    <div style="flex: 1;">
        <h2>Total Pendapatan Semua Kategori</h2>

        @php
            $totalPendapatanSemuaKategori = 0;
        @endphp

        <table>
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalPendapatanSemuaKategori = 0;
                @endphp

                @foreach ($invoices as $kategori => $invoicesByCategory)
                    @php
                        $totalPendapatanKategori = 0;
                    @endphp

                    @foreach ($invoicesByCategory as $invoice)
                        @php
                            $totalPendapatanKategori += $invoice->total_harga;
                            $totalPendapatanSemuaKategori += $invoice->total_harga;
                        @endphp
                    @endforeach

                    <tr>
                        <td>{{ $kategori }}</td>
                        <td>{{ $totalPendapatanKategori }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td><strong>Total Semua Kategori</strong></td>
                    <td>{{ $totalPendapatanSemuaKategori }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
