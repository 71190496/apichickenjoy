<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'Pesanan';

    protected $fillable = [
        'id_transaksi',
        'id_menu',
        'jumlah_pesanan',
        'total_harga',
        'catatan' 
    ];

    protected $primaryKey = 'id_pesanan';

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(DetailTransaksi::class, 'id_transaksi');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'id_menu');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
