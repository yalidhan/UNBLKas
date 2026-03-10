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
use App\Http\Controllers\TransactionAuditController;
use App\Http\Controllers\AuditNoteController;
Use App\Http\Controllers\PeriodClosingController;
Use App\Http\Controllers\PeriodOpenRequestController;

 

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
Route::get('/transactions/{transaction}/bukti/preview',[TransactionController::class, 'previewBukti'])->name('transactions.bukti.preview');
Route::resource('transaksi',TransactionController::class);


Route::get('pertanggungjawaban', [ReportController::class,'lpjPage'])->name('lpjPage');
Route::get('pertanggungjawaban/cetak', [ReportController::class,'lpjCetak'])->name('lpjCetak');

Route::get('realisasianggaran', [ReportController::class,'realisasiPage'])->name('realisasiPage');
Route::get('realisasianggaran/cetak', [ReportController::class,'realisasiCetak'])->name('realisasiCetak');
Route::get('realisasianggaran/logtransaksi', [ReportController::class,'logTransaksi'])->name('logTransaksi');
Route::get('/laporan/realisasi/csv', [ReportController::class, 'realisasiCsv'])
    ->name('realisasi.csv');


Route::get('posisikas', [ReportController::class,'posisikasPage'])->name('posisikasPage');
Route::get('posisikas/cetak', [ReportController::class,'posisikasCetak'])->name('posisikasCetak');

Route::get('period', [PeriodClosingController::class,'periodClosingPage'])->name('periodClosingPage');
Route::post('/period/close',
    [PeriodClosingController::class, 'close']
)->name('period.close');
Route::get('openperiod', [PeriodOpenRequestController::class,'periodOpenPage'])->name('periodOpenPage');
Route::post('/period/request', [PeriodOpenRequestController::class, 'requestOpen'])
    ->name('period.request');

Route::middleware(['auth'])->group(function () {

    // halaman SPI approval
    Route::get('/period/requests',
        [PeriodOpenRequestController::class, 'index'])
        ->name('period.requests.index');

    // approve
    Route::post('/period/requests/{id}/approve',
        [PeriodOpenRequestController::class, 'approve'])
        ->name('period.requests.approve');

    // reject
    Route::post('/period/requests/{id}/reject',
        [PeriodOpenRequestController::class, 'reject'])
        ->name('period.requests.reject');
});
Route::get('/period/my-requests',
    [PeriodOpenRequestController::class, 'myRequests'])
    ->name('period.requests.mine');
Route::post('/period/request/{id}/mark-read',
    [PeriodOpenRequestController::class, 'markRead'])
    ->name('period.requests.markRead');

Route::get('report-perencanaan/cetak', [ReportController::class,'perencanaanCetak'])->name('perencanaanCetak');

Route::resource('transaction_audits', TransactionAuditController::class);

Route::get('/audit-notes/{audit}', [AuditNoteController::class, 'getByAudit'])
    ->name('audit-notes.by-audit');
Route::post('/audit-notes', [AuditNoteController::class, 'store'])
    ->name('audit-notes.store');
Route::put('/audit-notes/{note}', [AuditNoteController::class, 'update'])
    ->name('audit-notes.update');
Route::delete('/audit-notes/{note}', [AuditNoteController::class, 'destroy'])
    ->name('audit-notes.destroy');