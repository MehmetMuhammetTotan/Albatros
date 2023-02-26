<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');


Route::prefix('/yonetim')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/giris-yap', [\App\Http\Controllers\AdminController::class, 'getGirisYap'])->name('giris_yap.get');
        Route::post('/giris-yap', [\App\Http\Controllers\AdminController::class, 'postGirisYap'])->name('giris_yap.post');
    });
    Route::middleware('auth:admin')->group(function () {
        Route::get('/cikis-yap', [\App\Http\Controllers\AdminController::class, 'getCikisYap'])->name('cikis_yap.get');
        Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('index.get');
        Route::get('/profil', [App\Http\Controllers\AdminController::class, 'getProfil'])->name('profil.get');
        Route::post('/profil-bilgi', [App\Http\Controllers\AdminController::class, 'postProfilBilgiGuncelle'])->name('profil_bilgi_guncelle.post');
        Route::post('/profil-sifre', [App\Http\Controllers\AdminController::class, 'postProfilSifreGuncelle'])->name('sifremi_guncelle.post');
        Route::get('/kullanicilar', [App\Http\Controllers\AdminController::class, 'getKullanicilar'])->name('kullanicilar.get');
    });
});
