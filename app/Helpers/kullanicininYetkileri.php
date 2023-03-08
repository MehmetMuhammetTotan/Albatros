<?php

function kullanicininYetkileri(){
    $kullanici_id = auth()->guard('admin')->user()->id;
    $yetkiler = \App\Models\AdminYetkiler::where('admin_id', $kullanici_id)->first();
    return json_decode($yetkiler->yetkiler,true);
}
