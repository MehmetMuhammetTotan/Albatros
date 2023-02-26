<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
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
        if (auth()->guard('admin')->attempt($validated)) {
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

    public function postProfilBilgiGuncelle(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . auth()->guard('admin')->user()->id,
            'password' => 'required|string|min:6',
        ]);
        if (Hash::check($request->password, auth()->guard('admin')->user()->password)) {
            auth()->guard('admin')->user()->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);;
            return redirect()->back()->with("success", "Profil bilgileriniz başarıyla güncellendi");
        } else {
            return redirect()->back()->withErrors(["Mevcut şifreniz hatalı"]);
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

    public function getKullanicilar(){
        $kullanicilar = Admin::all();
        $viewData = Array();
        $viewData["kullanicilar"] = $kullanicilar;
        return view("admin.adminlte.sayfalar.kullanicilar", $viewData);
    }
}
