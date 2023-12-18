<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\DetailTransaksiController;
use App\Http\Controllers\PesananController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route User
Route::get('/user', [UserController::class, 'index']);



// Route menu
Route::get('/menu', [MenuController::class, 'index']);
Route::get('/menu/kategori/{kategori}', [MenuController::class, 'indexByCategory']);
Route::get('/menu/kategori', [MenuController::class, 'kategori']);
// Route::get('/menu/kategori/Minuman', [MenuController::class, 'indexByCategory']);
// Route::get('/menu/kategori/Roti%20Bakar%20Paket%20Rame-rame', [MenuController::class, 'indexByCategory']);
// Route::get('/menu/kategori/Roti%20Bakar%20Paket%20Sendiri', [MenuController::class, 'indexByCategory']);
Route::post('/tambah-menu', [MenuController::class, 'store'])->middleware(['auth:sanctum','role:admin']);
Route::put('/edit-menu/{id}', [MenuController::class, 'update'])->middleware(['auth:sanctum','role:admin']);
Route::delete('/hapus-menu/{id}', [MenuController::class, 'destroy'])->middleware(['auth:sanctum','role:admin']);

// Route Login & Logout
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);


// Route Excel
Route::get('/export', [ExportController::class, 'export']);

// Route Transaksi
Route::post('/tambah-detail-transaksi', [DetailTransaksiController::class, 'store']);
Route::get('/summary-penjualan/{date?}', [PesananController::class, 'show']);
Route::get('/summary-penjualan/today', [PesananController::class, 'showToday']);
Route::get('/summary-penjualan/yesterday', [PesananController::class, 'showYesterday']);
Route::get('/summary-penjualan/last-week', [PesananController::class, 'showLastWeek']);
Route::get('/summary-penjualan/last-month', [PesananController::class, 'showLastMonth']);

//Route Nota
Route::get('/nota-transaksi/{id}', [DetailTransaksiController::class, 'generatePdf']);