<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\UpdatePasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\PlanningController;

 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index']);
Auth::routes();
Route::post('login', [LoginController::class,'login']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('pengguna',PenggunaController::class);
Route::get('statuspengguna/{id}',[PenggunaController::class,'status']);

Route::get('updatepassword',[UpdatePasswordController::class,'edit'])->name('updatepassword');
Route::put('updatepassword',[UpdatePasswordController::class,'update']);

Route::resource('departement',DepartementController::class);
Route::get('departementstat/{id}',[DepartementController::class,'status']);

Route::resource('akun',AccountController::class);
Route::get('akunstat/{id}',[AccountController::class,'status']);

Route::put('anggaran/rincian{id}',[BudgetController::class,'updateRincianA'])->name('updateRincianA');
Route::delete('anggaran/rincian{id}',[BudgetController::class,'destroyRincianA'])->name('destroyRincianA');
Route::post('anggaran/rincian',[BudgetController::class,'storeRincianA'])->name('storeRincianA');
Route::resource('anggaran',BudgetController::class);

Route::put('perencanaan/rincian{id}',[PlanningController::class,'updateRincianP'])->name('updateRincianP');
Route::delete('perencanaan/rincian{id}',[PlanningController::class,'destroyRincianP'])->name('destroyRincianP');
Route::post('perencanaan/rincian',[PlanningController::class,'storeRincianP'])->name('storeRincianP');
Route::resource('perencanaan',PlanningController::class);

Route::get('transaksi/transfer/{id}',[TransactionController::class,'showTransfer'])->name('showTransfer');
Route::put('transaksi/transfer{id}',[TransactionController::class,'updateTransfer'])->name('updateTransfer');
Route::post('transaksi/rincian',[TransactionController::class,'storeRincian'])->name('storeRincian');
Route::delete('transaksi/rincian{id}',[TransactionController::class,'destroyRincian'])->name('destroyRincian');
Route::put('transaksi/rincian{id}',[TransactionController::class,'updateRincian'])->name('updateRincian');
Route::resource('transaksi',TransactionController::class);

Route::get('pertanggungjawaban', [ReportController::class,'lpjPage'])->name('lpjPage');
Route::get('pertanggungjawaban/cetak', [ReportController::class,'lpjCetak'])->name('lpjCetak');

Route::get('realisasianggaran', [ReportController::class,'realisasiPage'])->name('realisasiPage');
Route::get('realisasianggaran/cetak', [ReportController::class,'realisasiCetak'])->name('realisasiCetak');

Route::get('posisikas', [ReportController::class,'posisikasPage'])->name('posisikasPage');
Route::get('posisikas/cetak', [ReportController::class,'posisikasCetak'])->name('posisikasCetak');

Route::get('report-perencanaan/cetak', [ReportController::class,'perencanaanCetak'])->name('perencanaanCetak');