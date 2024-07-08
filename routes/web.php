<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\IndexKegiatanController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\NODINController;
use App\Http\Controllers\NPDController;
use App\Http\Controllers\RincianBelanjaController;
use App\Http\Controllers\SubKegiatanController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/login', function () {
    return view('login', ['title' => 'Login']);
})->name('login')->middleware('guest');
Route::post('/login', [UserController::class, 'login'])->name('login.action')->middleware('guest');


Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/change-password', [DashboardController::class, 'changePassword'])->name('change-password');
    Route::put('/change-password', [DashboardController::class, 'changedPassword'])->name('change-password.action');

    Route::post('/logout', [UserController::class, 'logout'])->name('logout.action');

    Route::group(['prefix' => 'menu', 'as' => 'menu.'], function () {
        Route::get('/{bagian?}', function (Request $request, $bagian = null) {
            $title = 'Pusat Persuratan DPRD Provinsi Jawa Timur';
            $prev_url = $request->input('prev_url', null);
            return view('staff.menu', compact('title', 'prev_url', 'bagian'));
        })->name('index');
    });

    Route::resource('media', MediaController::class)->except(['edit', 'show']);
    Route::get('/media/detail', [MediaController::class, 'show'])->name('media.show');
    Route::post('/media/pengajuan', [MediaController::class, 'pengajuanStore'])->name('media.pengajuan.store');
    Route::get('/media/export', [ExportController::class, 'media'])->name('media.export');

    Route::group(['prefix' => 'users', 'as' => 'user.', 'middleware' => 'role:Super Admin'], function () {
        Route::get('/admin', [UserController::class, 'createAdmin'])->name('admin');
        Route::post('/admin', [UserController::class, 'storeAdmin'])->name('admin.tambah');
        Route::put('/admin/{user}', [UserController::class, 'updateAdmin'])->name('admin.ubah');
        Route::delete('/admin/{user}', [UserController::class, 'deleteAdmin'])->name('admin.hapus');
        Route::get('/staff', [UserController::class, 'createStaff'])->name('staff');
        Route::post('/staff', [UserController::class, 'storeStaff'])->name('staff.tambah');
        Route::put('/staff/{user}', [UserController::class, 'updateStaff'])->name('staff.ubah');
        Route::delete('/staff/{user}', [UserController::class, 'deleteStaff'])->name('staff.hapus');
    });

    Route::group(['prefix' => 'master', 'as' => 'master.', 'middleware' => 'role:Super Admin'], function () {
        Route::resource('/index-kegiatan', IndexKegiatanController::class)->except(['create', 'show', 'edit']);
        Route::resource('/sub-kegiatan', SubKegiatanController::class)->except(['create', 'show', 'edit']);
        Route::resource('/rincian-belanja', RincianBelanjaController::class)->except(['create', 'show', 'edit']);
    });

    Route::group(['prefix' => 'npd/{bagian}', 'as' => 'npd.', 'middleware' => 'role:Super Admin,Admin A,Admin B,Staff'], function () {
        Route::get('/', [NPDController::class, 'index'])->name('index');
        Route::get('/rekening', [NPDController::class, 'create'])->name('tambah');
        Route::post('/rekening', [NPDController::class, 'store'])->name('tambah.action');
        Route::get('/rekening/{alokasiNPD}', [NPDController::class, 'edit'])->name('edit');
        Route::put('/rekening/{alokasiNPD}', [NPDController::class, 'update'])->name('edit.action');
        Route::delete('/rekening/{alokasiNPD}', [NPDController::class, 'destroy'])->name('delete.action');
        Route::get('/detail/{alokasi?}', [NPDController::class, 'show'])->name('detail');
        Route::get('/pengajuan/', [NPDController::class, 'createPengajuan'])->name('pengajuan');
        Route::post('/pengajuan/', [NPDController::class, 'storePengajuan'])->name('pengajuan.action');
        Route::put('/pengajuan/{pengajuanNPD}', [NPDController::class, 'updatePengajuan'])->name('pengajuan.approve');
        Route::get('/export', [ExportController::class, 'npd'])->name('export');
    });

    Route::group(['prefix' => 'nodin/{bagian}', 'as' => 'nodin.', 'middleware' => 'role:Super Admin,Admin A,Admin B,Staff'], function () {
        Route::get('/', [NODINController::class, 'index'])->name('index');
        Route::get('/detail', [NODINController::class, 'show'])->name('detail');
        Route::get('/pengajuan', [NODINController::class, 'create'])->name('pengajuan');
        Route::post('/pengajuan', [NODINController::class, 'store'])->name('pengajuan.action');
        Route::put('/pengajuan/{pengajuanNODIN}', [NODINController::class, 'update'])->name('pengajuan.approve');
        Route::get('/rekap', [NODINController::class, 'rekap'])->name('rekap');
        Route::get('/rekap-list', [NODINController::class, 'listRekap'])->name('rekap.list');
        Route::get('/export', [ExportController::class, 'nodin'])->name('export');
    });
});
