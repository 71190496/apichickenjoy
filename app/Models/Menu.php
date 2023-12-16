<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $table = 'Menu';
    protected $primaryKey = 'id_menu';
    protected $fillable = ['nama_menu', 'harga', 'kategori', 'image', 'jumlah_stok'];

    
}
