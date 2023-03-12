<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\AdminYetkiler;
use App\Models\PersonelGrubu;
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
                return redirect()->back()->withErrors(["Hesabınız pasif durumdadır. Lütfen yönetici ile iletişime geçiniz."]);
            }
            if ((auth()->guard('admin')->user()->deleted_at != null)) {
                auth()->guard('admin')->logout();
                return redirect()->back()->withErrors(["Email veya şifre hatalı"]);
            }
            return redirect()->route('admin.index.get');
        } else {
            return redirect()->back()->withErrors(["Email veya şifre hatalı"]);
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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (Admin::where("email", $request->email)->where("deleted_at", NULL)->first() != null) {
            return redirect()->back()->withErrors(["Bu email adresi zaten kullanımda"])->withInput();
        }

        $admin = new Admin();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->status = ($request->aktiflik == "on") ? 1 : 0;
        if (!$admin->save()) {
            return redirect()->back()->withErrors(["Kullanıcı eklenirken bir hata oluştu"])->withInput();
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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (Admin::where("email", $request->email)->where("deleted_at", NULL)->where("id", "!=", auth()->guard('admin')->user()->id)->first() != null) {
            return redirect()->back()->withErrors(["Bu email adresi zaten kullanımda"])->withInput();
        }

        if (Hash::check($request->password, auth()->guard('admin')->user()->password)) {
            auth()->guard('admin')->user()->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);;
            return redirect()->back()->with("success", "Profil bilgileriniz başarıyla güncellendi");
        } else {
            return redirect()->back()->withErrors(["Mevcut şifreniz hatalı"])->withInput();
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
            return redirect()->back()->with("success", "Şifreniz başarıyla güncellendi");
        } else {
            return redirect()->back()->withErrors(["Mevcut şifreniz hatalı"]);
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
        if ($id == auth()->guard('admin')->user()->id) return redirect()->back()->withErrors(["Kendinizi silemezsiniz"]);
        $kullanici = Admin::find($id);
        $kullanici->deleted_at = Carbon::now();
        if (!$kullanici->save()) {
            return redirect()->back()->withErrors(["Kullanıcı silinirken bir hata oluştu"]);
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
            return redirect()->back()->withErrors(["Personel grubu silinirken bir hata oluştu"])->withInput();
        }
        return redirect()->route("admin.personel_gruplari.get")->with("success", "Personel grubu başarıyla silindi");
    }
}
