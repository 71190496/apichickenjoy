<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            max-height: 100%;
            margin: 0 auto;
            padding: 20px;
            font-size: 33px;
        }

        .left {
            text-align: left;
            display: inline-block;
            width: 48%;
        }

        .right {
            text-align: right;
            display: inline-block;
            width: 48%;
            text-align: right;
            float: right;
        }

        .header {
            margin-bottom: 30px;
            text-align: center;
        }

        .footer {
            text-align: center;
            position: absolute;
            font-size: 24px;
            bottom: 0;
            left: 0;
            right: 0;
        }


        .separator {
            border-top: 1px solid #ccc;
            margin: 20px 0;
        }

        .details {
            margin-bottom: 10px;
            overflow: hidden;
        }

        .menu-item {
            display: flex;
            justify-content: space-between;
        }


        .total {
            margin-top: 20px;
            font-weight: bold;
            overflow: hidden;
        }

        .item-left {
            text-align: left;
            display: inline-block;
            width: 48%;
        }

        .item-right {
            text-align: right;
            display: inline-block;
            width: 48%;
            text-align: right;
            float: right;
        }
    </style>

</head>
<body>
    <div class="header">
        <h1>Chiken Joy</h1>
        <p style="font-size: 32px;">Jl. Dr. Wahidin Sudirohusodo No.5-25</p>
        <p style="font-size: 30px;">Telp : 082329561274</p>
    </div>

    <div class="separator"></div>

    <div class="details">
        <div class="left">
            <p>13 Desember 2023</p>
            <p>Nomor Nota</p>
            <p>Kasir</p>
            <p>Pelanggan</p>
        </div>
    
        <div class="right">
            <p>20:43</p>
            <p>{{ $detailTransaksi->id_transaksi . '-' . \Carbon\Carbon::parse($detailTransaksi->created_at)->format('dmY') }}</p>
            <p>{{ $detailTransaksi->user->nama_karyawan }}</p>
            <p>{{ $detailTransaksi->nama_pelanggan }}</p>
        </div>
    </div>
    

    <div class="separator"></div>

    @foreach($detailTransaksi->pesanan as $pesanan)
    <div class="menu-item">
        <div class="item-left">
            <p>{{ $pesanan->menu->nama_menu }} 
                <span>{{ $pesanan->jumlah_pesanan }}x <span> @ IDR {{ number_format($pesanan->menu->harga, 0, ',', '.') }}</span>
            </p>
            <p style="font-style: italic; color: #666; font-size: 30px;">{{ $pesanan->catatan ?? '-' }}</p>
        </div>

        <div class="item-right">
            <p>IDR {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
        </div>
    </div>
    @endforeach


    <div class="separator"></div>

    <div class="total details">
        <div class="left">
            <p>Total Harga</p>
        </div>

        <div class="right">
            <p>IDR {{ number_format($detailTransaksi->total_harga, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="separator"></div>

    <div class="details">
        <div class="left">
            <p>Metode Pembayaran</p>
        </div>

        <div class="right">
            <p>{{ $detailTransaksi->metode_pembayaran }}</p>
        </div>
    </div>
    <div class="footer">
        <h1>Terima Kasih</h1>
    </div>
</body>
</html>
