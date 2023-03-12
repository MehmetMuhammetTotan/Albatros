<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PersonelGrubu extends Model
{
    use HasFactory;
    protected $table = "personel_gruplari"; // Tablo adı

    protected $fillable = [
        'grup_adi',
        'grup_aciklamasi',
        'ust_grup',
    ]; // Dolu olması gereken alanlar


    protected $hidden = [

    ]; // Gizli alanlar

}
