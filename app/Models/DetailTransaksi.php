<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'DetailTransaksi';

    protected $fillable = [
        'id_user',
        'jumlah_pesanan',
        'total_harga',
        'metode_pembayaran', 
        'nama_pelanggan',
        'waktu_transaksi',
    ];

    protected $dates = ['waktu_transaksi'];

    protected $primaryKey = 'id_transaksi';

    public function menus(): BelongsToMany
    {
        return $this
            ->belongsToMany(Menu::class, 'Pesanan', 'id_transaksi', 'id_menu')
            ->withPivot([
                'jumlah_pesanan', 'total_harga'
            ]);
    }

    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'id_transaksi', 'id_transaksi');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    
}
