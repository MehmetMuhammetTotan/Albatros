<?php

use Illuminate\Support\Facades\DB;


function tabloGetir($tabloAdi, $where = array())
{
    return DB::table($tabloAdi)->where($where)->get();
}

function siteAyarlariCek(){
    $veriler = json_decode(DB::table("site_ayarlari")->get(), true);
    $return = array();
    foreach ($veriler as $veri){
        $return[$veri["anahtar"]] = $veri["deger"];
    }
    return $return;

}
