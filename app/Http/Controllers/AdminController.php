<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\AdminYetkiler;
use App\Models\Personel;
use App\Models\PersonelGrubu;
use App\Models\SiteAyarlari;
use Faker\Provider\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Array_;
use stdClass;

class AdminController extends Controller
{
    public function getGirisYap()
    {
        return view('admin.adminlte.sayfalar.login');
    }

    public function postGirisYap(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($request->remember == 'on') {
            Cookie::queue('email', $request->email, 60 * 24 * 30);
        } else {
            Cookie::queue(Cookie::forget('email'));
        }
        if (auth()->guard('admin')->attempt($validated) and Admin::where("deleted_at", null)->where("email", $request->email)->first() != "NULL") {
            if (!(auth()->guard('admin')->user()->status)) {
                auth()->guard('admin')->logout();
                return redirect()->route("admin.giris_yap.get")->withErrors(["Hesabınız pasif durumdadır. Lütfen yönetici ile iletişime geçiniz."]);
            }
            if ((auth()->guard('admin')->user()->deleted_at != null)) {
                auth()->guard('admin')->logout();
                return redirect()->route("admin.giris_yap.get")->withErrors(["Email veya şifre hatalı"]);
            }
            return redirect()->route('admin.index.get');
        } else {
            return redirect()->route("admin.giris_yap.get")->withErrors(["Email veya şifre hatalı"]);
        }
    }

    public function getCikisYap()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.giris_yap.get');
    }

    public function index()
    {
        return view('admin.adminlte.sayfalar.index');
    }

    public function getProfil()
    {
        return view("admin.adminlte.sayfalar.profil");
    }

    public function postKullanicilarEkle(Request $request)
    {
        if(@kullanicininYetkileri()["kullanicilar"]["ekle"] != "on") abort(403, "YETKİNİZ YOK");
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->route("admin.kullanicilar.get")->withErrors($validator)->withInput();
        }

        if (Admin::where("email", $request->email)->where("deleted_at", NULL)->first() != null) {
            return redirect()->route("admin.kullanicilar.get")->withErrors(["Bu email adresi zaten kullanımda"])->withInput();
        }

        $admin = new Admin();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->status = ($request->aktiflik == "on") ? 1 : 0;
        if (!$admin->save()) {
            return redirect()->route("admin.kullanicilar.get")->withErrors(["Kullanıcı eklenirken bir hata oluştu"])->withInput();
        }
        if (AdminYetkiler::where("admin_id", $admin->id)->first() == null) {
            $yetkiler = new AdminYetkiler();
            $yetkiler->admin_id = $admin->id;
            $yetkiler->yetkiler = json_encode(array());
            $yetkiler->save();
        }
        return redirect()->route("admin.kullanicilar.get")->with("success", "Kullanıcı başarıyla eklendi");
    }

    public function postProfilBilgiGuncelle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->route("admin.profil.get")->withErrors($validator)->withInput();
        }

        if (Admin::where("email", $request->email)->where("deleted_at", NULL)->where("id", "!=", auth()->guard('admin')->user()->id)->first() != null) {
            return redirect()->route("admin.profil.get")->withErrors(["Bu email adresi zaten kullanımda"])->withInput();
        }

        if (Hash::check($request->password, auth()->guard('admin')->user()->password)) {
            auth()->guard('admin')->user()->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);;
            return redirect()->route("admin.profil.get")->with("success", "Profil bilgileriniz başarıyla güncellendi");
        } else {
            return redirect()->route("admin.profil.get")->withErrors(["Mevcut şifreniz hatalı"])->withInput();
        }
    }

    public function postProfilSifreGuncelle(Request $request)
    {
        $validated = $request->validate([
            'password_old' => 'required|string|min:6',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if (Hash::check($request->password_old, auth()->guard('admin')->user()->password)) {
            auth()->guard('admin')->user()->update([
                'password' => Hash::make($request->password),
            ]);
            return redirect()->route("admin.profil.get")->with("success", "Şifreniz başarıyla güncellendi");
        } else {
            return redirect()->route("admin.profil.get")->withErrors(["Mevcut şifreniz hatalı"]);
        }
    }

    public function getKullanicilar()
    {
        if(@kullanicininYetkileri()["kullanicilar"]["goruntule"] != "on") abort(403, "YETKİNİZ YOK");
        $kullanicilar = Admin::all()->where("deleted_at", null);
        $viewData = array();
        $viewData["kullanicilar"] = $kullanicilar;
        return view("admin.adminlte.sayfalar.kullanicilar.kullanicilar", $viewData);
    }

    public function postKullanicilarAktifPasif(Request $request)
    {
        if(@kullanicininYetkileri()["kullanicilar"]["duzenle"] != "on") return response()->json(["status" => false, "message" => "BAŞARISIZ! Bu işlem için yetkiniz bulunmamaktadır"]);
        $id = $request->id;
        $kullanici = Admin::find($id);
        if ($kullanici->id == auth()->guard('admin')->user()->id and $kullanici->status) return response()->json(["status" => false, "message" => "BAŞARISIZ! Kendi hesabınızın durumunu değiştiremezsiniz"]);
        $kullanici->status = !$kullanici->status;
        if (!$kullanici->save()) {
            return response()->json(["status" => false, "message" => "BAŞARISIZ! Veritabanında güncelleme işlemi yapılamadı"]);
        } else {
            return response()->json(["status" => true, "message" => "BAŞARILI! Kullanıcı durumu başarıyla güncellendi"]);
        }
    }

    public function getKullanicilarEkle()
    {
        if(@kullanicininYetkileri()["kullanicilar"]["ekle"] != "on") abort(403, "YETKİNİZ YOK");
        return view("admin.adminlte.sayfalar.kullanicilar.ekle");
    }

    public function getKullanicilarSil($id)
    {
        if(@kullanicininYetkileri()["kullanicilar"]["sil"] != "on") abort(403, "YETKİNİZ YOK");
        if ($id == auth()->guard('admin')->user()->id) return redirect()->route("admin.kullanicilar.get")->withErrors(["Kendinizi silemezsiniz"]);
        $kullanici = Admin::find($id);
        $kullanici->deleted_at = Carbon::now();
        if (!$kullanici->save()) {
            return redirect()->route("admin.kullanicilar.get")->withErrors(["Kullanıcı silinirken bir hata oluştu"]);
        } else {
            return redirect()->route("admin.kullanicilar.get")->with("success", "Kullanıcı başarıyla silindi");
        }
    }

    public function getKullanicilarDuzenle($id)
    {
        if(@kullanicininYetkileri()["kullanicilar"]["duzenle"] != "on") abort(403, "YETKİNİZ YOK");
        if (!is_numeric($id)) return redirect()->route("admin.kullanicilar.get")->withErrors(["Kullanıcı bulunamadı"]);
        if (Admin::where("deleted_at", null)->where("id", $id)->first() == null) return redirect()->route("admin.kullanicilar.get")->withErrors(["Kullanıcı bulunamadı"]);
        return view("admin.adminlte.sayfalar.kullanicilar.duzenle", ["kullanici" => Admin::find($id)]);
    }

    public function postKullanicilarDuzenle($id)
    {
        if(@kullanicininYetkileri()["kullanicilar"]["duzenle"] != "on") abort(403, "YETKİNİZ YOK");
        if (!is_numeric($id)) return redirect()->route("admin.kullanicilar.get")->withErrors(["Kullanıcı bulunamadı"]);
        if (Admin::where("deleted_at", null)->where("id", $id)->first() == null) return redirect()->route("admin.kullanicilar.get")->withErrors(["Kullanıcı bulunamadı"]);
        $kullanici = Admin::find($id);
        $kullanici->name = request("name");
        $kullanici->email = request("email");
        (request("password") != "") ? $kullanici->password = Hash::make(request("password")) : null;
        if (!$kullanici->save()) {
            return redirect()->back()->withErrors(["Kullanıcı güncellenirken bir hata oluştu"])->withInput();
        } else {
            return redirect()->route("admin.kullanicilar.get")->with("success", "Kullanıcı başarıyla güncellendi");
        }
    }

    public function getKullanicilarYetkiler($id)
    {
        if(@kullanicininYetkileri()["kullanicilar"]["yetkiler"] != "on") abort(403, "YETKİNİZ YOK");
        if (!is_numeric($id)) return redirect()->route("admin.kullanicilar.get")->withErrors(["Kullanıcı bulunamadı"]);
        if (Admin::where("deleted_at", null)->where("id", $id)->first() == null) return redirect()->route("admin.kullanicilar.get")->withErrors(["Kullanıcı bulunamadı"]);
        if (AdminYetkiler::where("admin_id", $id)->first() == null) {
            $yetkiler = new AdminYetkiler();
            $yetkiler->admin_id = $id;
            $yetkiler->yetkiler = json_encode(array());
            $yetkiler->save();
        }
        return view("admin.adminlte.sayfalar.kullanicilar.yetkiler", ["kullanici" => Admin::find($id), "yetkiler" => AdminYetkiler::where("admin_id", $id)->first()]);
    }

    public function postKullanicilarYetkilerAktifPasif($id, Request $request){
        if(@kullanicininYetkileri()["kullanicilar"]["yetkiler"] != "on") return response()->json(["status" => false, "message" => "BAŞARISIZ! Bu işlem için yetkiniz bulunmamaktadır"]);
        if (!is_numeric($id)) return json_encode(["status" => false, "message" => "Kullanıcı bulunamadı"]);
        if (Admin::where("deleted_at", null)->where("id", $id)->first() == null) return json_encode(["status" => false, "message" => "Kullanıcı bulunamadı"]);;
        if (AdminYetkiler::where("admin_id", $id)->first() == null) {
            $yetkiler = new AdminYetkiler();
            $yetkiler->admin_id = $id;
            $yetkiler->yetkiler = json_encode(array());
            $yetkiler->save();
        }
        $yetkiler = AdminYetkiler::where("admin_id", $id)->first();
        $gelenYetkiler = json_decode($yetkiler->yetkiler, true);
        if($request->durum == "true"){
            $gelenYetkiler[$request->menu][$request->alt_menu] = "on";
        }else{
            unset($gelenYetkiler[$request->menu][$request->alt_menu]);
        }
        $yetkiler->yetkiler = json_encode($gelenYetkiler);
        if(!$yetkiler->save()){
            return json_encode(["status" => false, "message" => "Kullanıcı yetkileri güncellenirken bir hata oluştu"]);
        }
        return json_encode(["status" => true, "message" => "Kullanıcı yetkileri başarıyla güncellendi"]);
    }

    public function getPersonelGruplari(){
        if(@kullanicininYetkileri()["personel_gruplari"]["goruntule"] != "on") abort(403, "YETKİNİZ YOK");
        return view("admin.adminlte.sayfalar.personel_gruplari.personel_gruplari", ["personel_gruplari" => tabloGetir("personel_gruplari_view")]);
    }

    public function getPersonelGruplariEkle(){
        if(@kullanicininYetkileri()["personel_gruplari"]["ekle"] != "on") abort(403, "YETKİNİZ YOK");
        return view("admin.adminlte.sayfalar.personel_gruplari.ekle", ["personel_gruplari" => PersonelGrubu::where("deleted_at", null)->get()]);
    }

    public function postPersonelGruplariEkle(Request $request){
        if(@kullanicininYetkileri()["personel_gruplari"]["ekle"] != "on") abort(403, "YETKİNİZ YOK");
        $validator = Validator::make($request->all(), [
            'grup_adi' => 'required|string|min:3',
            'ust_grup' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $personelGrubu = new PersonelGrubu();
        $personelGrubu->grup_adi = $request->grup_adi;
        $personelGrubu->ust_grup = $request->ust_grup;
        $personelGrubu->grup_aciklamasi = $request->grup_aciklamasi;
        if(!$personelGrubu->save()){
            return redirect()->back()->withErrors(["Personel grubu eklenirken bir hata oluştu"])->withInput();
        }
        return redirect()->route("admin.personel_gruplari.get")->with("success", "Personel grubu başarıyla eklendi");
    }

    public function getPersonelGruplariDuzenle($id){
        if(@kullanicininYetkileri()["personel_gruplari"]["duzenle"] != "on") abort(403, "YETKİNİZ YOK");
        if (!is_numeric($id)) return redirect()->route("admin.personel_gruplari.get")->withErrors(["Personel grubu bulunamadı"]);
        $getPersonelGrubu = PersonelGrubu::where("deleted_at", null)->where("id", $id)->first();
        if ($getPersonelGrubu == null) return redirect()->route("admin.personel_gruplari.get")->withErrors(["Personel grubu bulunamadı"]);
        return view("admin.adminlte.sayfalar.personel_gruplari.duzenle", ["get_personel_grubu" => $getPersonelGrubu, "personel_gruplari" => PersonelGrubu::where("deleted_at", null)->get()]);
    }

    public function postPersonelGruplariDuzenle($id, Request $request){
        if(@kullanicininYetkileri()["personel_gruplari"]["duzenle"] != "on") abort(403, "YETKİNİZ YOK");
        if (!is_numeric($id)) return redirect()->route("admin.personel_gruplari.get")->withErrors(["Personel grubu bulunamadı"]);
        $getPersonelGrubu = PersonelGrubu::where("deleted_at", null)->where("id", $id)->first();
        if ($getPersonelGrubu == null) return redirect()->route("admin.personel_gruplari.get")->withErrors(["Personel grubu bulunamadı"]);
        $validator = Validator::make($request->all(), [
            'grup_adi' => 'required|string|min:3',
            'ust_grup' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $getPersonelGrubu->grup_adi = $request->grup_adi;
        $getPersonelGrubu->ust_grup = $request->ust_grup;
        $getPersonelGrubu->grup_aciklamasi = $request->grup_aciklamasi;
        if(!$getPersonelGrubu->save()){
            return redirect()->back()->withErrors(["Personel grubu düzenlenirken bir hata oluştu"])->withInput();
        }
        return redirect()->route("admin.personel_gruplari.get")->with("success", "Personel grubu başarıyla düzenlendi");
    }

    public function getPersonelGruplariSil($id){
        if(@kullanicininYetkileri()["personel_gruplari"]["sil"] != "on") abort(403, "YETKİNİZ YOK");
        if (!is_numeric($id)) return redirect()->route("admin.personel_gruplari.get")->withErrors(["Personel grubu bulunamadı"]);
        $getPersonelGrubu = PersonelGrubu::where("deleted_at", null)->where("id", $id)->first();
        if ($getPersonelGrubu == null) return redirect()->route("admin.personel_gruplari.get")->withErrors(["Personel grubu bulunamadı"]);
        $getPersonelGrubu->deleted_at = date("Y-m-d H:i:s");
        if(!$getPersonelGrubu->save()){
            return redirect()->route("admin.personel_gruplari.get")->withErrors(["Personel grubu silinirken bir hata oluştu"])->withInput();
        }
        return redirect()->route("admin.personel_gruplari.get")->with("success", "Personel grubu başarıyla silindi");
    }

    public function getPersoneller(){
        if(@kullanicininYetkileri()["personeller"]["goruntule"] != "on") abort(403, "YETKİNİZ YOK");
        return view("admin.adminlte.sayfalar.personeller.personeller", ["personeller" => tabloGetir("personeller_view")]);
    }

    public function getPersonellerEkle(){
        if(@kullanicininYetkileri()["personeller"]["ekle"] != "on") abort(403, "YETKİNİZ YOK");
        return view("admin.adminlte.sayfalar.personeller.ekle", ["personel_gruplari" => PersonelGrubu::where("deleted_at", null)->get()]);
    }

    public function postPersonellerEkle(Request $request){
        if(@kullanicininYetkileri()["personeller"]["ekle"] != "on") abort(403, "YETKİNİZ YOK");
        $validator = Validator::make($request->all(), [
            'ad' => 'required|string|min:3',
            'soyad' => 'required|string|min:3',
            'resim' => 'required|file|mimes:jpeg,png,jpg|max:10240', // 10 MB
            'grup' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($request->hasFile('resim')) {
            $file = $request->file('resim');
            if ($file->isValid()) {
                $fileName = md5(time()) . '.' . $file->getClientOriginalExtension();
                if (!$file->move(public_path('uploads/resimler'), $fileName)) {
                    // Dosya yüklenirken hata oluştu
                    return redirect()->back()->withErrors(['error' => 'Dosya yüklenirken hata oluştu'])->withInput();
                }
            } else {
                // Dosya geçersiz
                return redirect()->back()->withErrors(['error' => 'Resim geçersiz'])->withInput();
            }
        }else{
            return redirect()->back()->withErrors(['error' => 'Resim yüklenirken hata oluştu'])->withInput();
        }

        $personel = new Personel();
        $personel->ad = $request->ad;
        $personel->soyad = $request->soyad;
        $personel->resim = 'uploads/resimler/'.$fileName;
        $personel->personel_grubu_id = $request->grup;
        if(!$personel->save()){
            unlink(public_path($personel->resim));
            return redirect()->back()->withErrors(["Personel eklenirken bir hata oluştu"])->withInput();
        }
        return redirect()->route("admin.personeller.get")->with("success", "Personel başarıyla eklendi");
    }

    public function getPersonellerDuzenle($id){
        if(@kullanicininYetkileri()["personeller"]["duzenle"] != "on") abort(403, "YETKİNİZ YOK");
        if (!is_numeric($id)) return redirect()->route("admin.personeller.get")->withErrors(["Personel bulunamadı"]);
        $getPersonel = Personel::where("deleted_at", null)->where("id", $id)->first();
        if ($getPersonel == null) return redirect()->route("admin.personeller.get")->withErrors(["Personel bulunamadı"]);
        return view("admin.adminlte.sayfalar.personeller.duzenle", ["get_personel" => $getPersonel, "personel_gruplari" => PersonelGrubu::where("deleted_at", null)->get()]);
    }

    public function postPersonellerDuzenle($id, Request $request){
        if(@kullanicininYetkileri()["personeller"]["duzenle"] != "on") abort(403, "YETKİNİZ YOK");
        if (!is_numeric($id)) return redirect()->route("admin.personeller.get")->withErrors(["Personel bulunamadı"]);
        $getPersonel = Personel::where("deleted_at", null)->where("id", $id)->first();
        if ($getPersonel == null) return redirect()->route("admin.personeller.get")->withErrors(["Personel bulunamadı"]);
        if(@kullanicininYetkileri()["personeller"]["ekle"] != "on") abort(403, "YETKİNİZ YOK");
        $validator = Validator::make($request->all(), [
            'ad' => 'required|string|min:3',
            'soyad' => 'required|string|min:3',
            'resim' => 'nullable|file|mimes:jpeg,png,jpg|max:10240', // 10 MB
            'grup' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($request->hasFile('resim')) {
            $file = $request->file('resim');
            if ($file->isValid()) {
                $fileName = md5(time()) . '.' . $file->getClientOriginalExtension();
                if (!$file->move(public_path('uploads/resimler'), $fileName)) {
                    // Dosya yüklenirken hata oluştu
                    return redirect()->back()->withErrors(['error' => 'Dosya yüklenirken hata oluştu'])->withInput();
                }
            } else {
                // Dosya geçersiz
                return redirect()->back()->withErrors(['error' => 'Resim geçersiz'])->withInput();
            }
        }

        $eskiPersonelResim = $getPersonel->resim;
        $getPersonel->ad = $request->ad;
        $getPersonel->soyad = $request->soyad;
        if ($request->hasFile('resim')) {
            $getPersonel->resim = 'uploads/resimler/'.$fileName;
        }
        $getPersonel->personel_grubu_id = $request->grup;
        if(!$getPersonel->save()){
            unlink(public_path('uploads/resimler/'.$fileName));
            return redirect()->back()->withErrors(["Personel düzenlenirken bir hata oluştu"])->withInput();
        }
        if ($request->hasFile('resim')) {
            unlink(public_path($eskiPersonelResim));
        }
        return redirect()->route("admin.personeller.get")->with("success", "Personel başarıyla düzenlendi");
    }

    public function getPersonellerSil($id){
        if(@kullanicininYetkileri()["personeller"]["sil"] != "on") abort(403, "YETKİNİZ YOK");
        if (!is_numeric($id)) return redirect()->route("admin.personeller.get")->withErrors(["Personel bulunamadı"]);
        $getPersonel = Personel::where("deleted_at", null)->where("id", $id)->first();
        if ($getPersonel == null) return redirect()->route("admin.personeller.get")->withErrors(["Personel bulunamadı"]);
        $getPersonel->deleted_at = date("Y-m-d H:i:s");
        if(!$getPersonel->save()){
            return redirect()->route("admin.personeller.get")->withErrors(["Personel silinirken bir hata oluştu"])->withInput();
        }
        return redirect()->route("admin.personeller.get")->with("success", "Personel başarıyla silindi");
    }

    public function getSiteAyarlari(){
        if(@kullanicininYetkileri()["site_ayarlari"]["goruntule"] != "on") abort(403, "YETKİNİZ YOK");
        return view("admin.adminlte.sayfalar.site_ayarlari", ["site_ayarlari" => SiteAyarlari::all()]);
    }

    public function postSiteAyarlariTemelBilgiler(Request $request){
        if(@kullanicininYetkileri()["site_ayarlari"]["temel_bilgiler"] != "on") abort(403, "YETKİNİZ YOK");
        $validator = Validator::make($request->all(), [
            'site_adi' => 'required|string|min:3',
            'site_aciklama' => 'required|string|min:3',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $site_adi = SiteAyarlari::where("anahtar", "site_adi")->first();
        $site_adi->deger = $request->site_adi;
        $site_aciklama = SiteAyarlari::where("anahtar", "site_aciklama")->first();
        $site_aciklama->deger = $request->site_aciklama;

        if(!$site_adi->save() || !$site_aciklama->save()){
            return redirect()->back()->withErrors(["Site ayarları düzenlenirken bir hata oluştu"])->withInput();
        }
        return redirect()->route("admin.site_ayarlari.get")->with("success", "Site ayarları başarıyla düzenlendi");
    }

    public function postSiteAyarlariSiteResimleri(Request $request){
        if(@kullanicininYetkileri()["site_ayarlari"]["site_resimleri"] != "on") abort(403, "YETKİNİZ YOK");
        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|file|mimes:jpeg,png,jpg|max:10240', // 10 MB
            'favicon' => 'nullable|file|mimes:ico|max:10240', // 10 MB
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            if ($file->isValid()) {
                $fileName = md5(time()) . '.' . $file->getClientOriginalExtension();
                if (!$file->move(public_path('uploads/resimler'), $fileName)) {
                    // Dosya yüklenirken hata oluştu
                    return redirect()->back()->withErrors(['error' => 'Site logosu yüklenirken hata oluştu'])->withInput();
                }else{
                    $site_logo = SiteAyarlari::where("anahtar", "logo")->first();
                    $eskiLogo = $site_logo->deger;
                    $site_logo->deger = 'uploads/resimler/'.$fileName;
                    if(!$site_logo->save()){
                        unlink(public_path('uploads/resimler/'.$fileName));
                        return redirect()->back()->withErrors(["Site logosu düzenlenirken bir hata oluştu"])->withInput();
                    }else{
                        unlink(public_path($eskiLogo));
                    }
                }
            } else {
                // Dosya geçersiz
                return redirect()->back()->withErrors(['error' => 'Site logosu geçersiz'])->withInput();
            }
        }

        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            if ($file->isValid()) {
                $fileName = md5(time()) . '.' . $file->getClientOriginalExtension();
                if (!$file->move(public_path('uploads/resimler'), $fileName)) {
                    // Dosya yüklenirken hata oluştu
                    return redirect()->back()->withErrors(['error' => 'Favicon yüklenirken hata oluştu'])->withInput();
                }else{
                    $site_favicon = SiteAyarlari::where("anahtar", "favicon")->first();
                    $eskiFavicon = $site_favicon->deger;
                    $site_favicon->deger = 'uploads/resimler/'.$fileName;
                    if(!$site_favicon->save()){
                        unlink(public_path('uploads/resimler/'.$fileName));
                        return redirect()->back()->withErrors(["Favicon düzenlenirken bir hata oluştu"])->withInput();
                    }else{
                        unlink(public_path($eskiFavicon));
                    }
                }
            } else {
                // Dosya geçersiz
                return redirect()->back()->withErrors(['error' => 'Favicon geçersiz'])->withInput();
            }
        }
        return redirect()->route("admin.site_ayarlari.get")->with("success", "Site resimleri başarıyla düzenlendi");
    }

    public function postSiteAyarlariIletisimBilgileri(Request $request){
        if(@kullanicininYetkileri()["site_ayarlari"]["iletisim_bilgileri"] != "on") abort(403, "YETKİNİZ YOK");
        $validator = Validator::make($request->all(), [
            'adres' => 'required|string|min:3',
            'telefon' => 'required|string|min:3',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $site_adres = SiteAyarlari::where("anahtar", "adres")->first();
        $site_adres->deger = $request->adres;
        $site_telefon = SiteAyarlari::where("anahtar", "telefon")->first();
        $site_telefon->deger = $request->telefon;
        $site_email = SiteAyarlari::where("anahtar", "email")->first();
        $site_email->deger = $request->email;

        if(!$site_adres->save() || !$site_telefon->save() || !$site_email->save()){
            return redirect()->back()->withErrors(["İletişim bilgileri düzenlenirken bir hata oluştu"])->withInput();
        }
        return redirect()->route("admin.site_ayarlari.get")->with("success", "İletişim bilgileri başarıyla düzenlendi");
    }

    public function postSiteAyarlariSosyalMedya(Request $request){
        if(@kullanicininYetkileri()["site_ayarlari"]["sosyal_medya"] != "on") abort(403, "YETKİNİZ YOK");
        $validator = Validator::make($request->all(), [
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'instagram' => 'nullable|url',
            'youtube' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $site_facebook = SiteAyarlari::where("anahtar", "facebook")->first();
        $site_facebook->deger = $request->facebook;
        $site_twitter = SiteAyarlari::where("anahtar", "twitter")->first();
        $site_twitter->deger = $request->twitter;
        $site_instagram = SiteAyarlari::where("anahtar", "instagram")->first();
        $site_instagram->deger = $request->instagram;

        if(!$site_facebook->save() || !$site_twitter->save() || !$site_instagram->save()){
            return redirect()->back()->withErrors(["Sosyal medya hesapları düzenlenirken bir hata oluştu"])->withInput();
        }
        return redirect()->route("admin.site_ayarlari.get")->with("success", "Sosyal medya hesapları başarıyla düzenlendi");
    }
}
