<?php

use Illuminate\Support\Facades\DB;


function tabloGetir($tabloAdi, $where = array())
{
    return DB::table($tabloAdi)->where($where)->get();
}
