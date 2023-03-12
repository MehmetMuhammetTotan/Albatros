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
    return abort(403, 'Çok yakında...');
});


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
        Route::get('/kullanicilar/ekle', [App\Http\Controllers\AdminController::class, 'getKullanicilarEkle'])->name('kullanicilar_ekle.get');
        Route::get('/kullanicilar/yetkiler/{id}', [App\Http\Controllers\AdminController::class, 'getKullanicilarYetkiler'])->name('kullanicilar_yetkiler.get');
        Route::post('/kullanicilar/yetkiler-aktif-pasif/{id}', [App\Http\Controllers\AdminController::class, 'postKullanicilarYetkilerAktifPasif'])->name('kullanicilar_yetkiler_aktif_pasif.post');
        Route::get('/kullanicilar/duzenle/{id}', [App\Http\Controllers\AdminController::class, 'getKullanicilarDuzenle'])->name('kullanicilar_duzenle.get');
        Route::post('/kullanicilar/duzenle/{id}', [App\Http\Controllers\AdminController::class, 'postKullanicilarDuzenle'])->name('kullanicilar_duzenle.post');
        Route::get('/kullanicilar/sil/{id}', [App\Http\Controllers\AdminController::class, 'getKullanicilarSil'])->name('kullanicilar_sil.get');
        Route::post('/kullanicilar/ekle', [App\Http\Controllers\AdminController::class, 'postKullanicilarEkle'])->name('kullanicilar_ekle.post');
        Route::post('/kullanicilar/aktif-pasif', [App\Http\Controllers\AdminController::class, 'postKullanicilarAktifPasif'])->name('kullanicilar_aktif_pasif.post');

        Route::get('/personeller', [App\Http\Controllers\AdminController::class, 'getPersoneller'])->name('personeller.get');
        Route::get('/personeller/ekle', [App\Http\Controllers\AdminController::class, 'getPersonellerEkle'])->name('personeller_ekle.get');
        Route::post('/personeller/ekle', [App\Http\Controllers\AdminController::class, 'postPersonellerEkle'])->name('personeller_ekle.post');
        Route::get('/personeller/duzenle/{id}', [App\Http\Controllers\AdminController::class, 'getPersonellerDuzenle'])->name('personeller_duzenle.get');
        Route::post('/personeller/duzenle/{id}', [App\Http\Controllers\AdminController::class, 'postPersonellerDuzenle'])->name('personeller_duzenle.post');
        Route::get('/personeller/sil/{id}', [App\Http\Controllers\AdminController::class, 'getPersonellerSil'])->name('personeller_sil.get');

        Route::get('/personel-gruplari', [App\Http\Controllers\AdminController::class, 'getPersonelGruplari'])->name('personel_gruplari.get');
        Route::get('/personel-gruplari/ekle', [App\Http\Controllers\AdminController::class, 'getPersonelGruplariEkle'])->name('personel_gruplari_ekle.get');
        Route::post('/personel-gruplari/ekle', [App\Http\Controllers\AdminController::class, 'postPersonelGruplariEkle'])->name('personel_gruplari_ekle.post');
        Route::get('/personel-gruplari/duzenle/{id}', [App\Http\Controllers\AdminController::class, 'getPersonelGruplariDuzenle'])->name('personel_gruplari_duzenle.get');
        Route::post('/personel-gruplari/duzenle/{id}', [App\Http\Controllers\AdminController::class, 'postPersonelGruplariDuzenle'])->name('personel_gruplari_duzenle.post');
        Route::get('/personel-gruplari/sil/{id}', [App\Http\Controllers\AdminController::class, 'getPersonelGruplariSil'])->name('personel_gruplari_sil.get');
    });
});
