<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteAyarlari extends Model
{
    use HasFactory;
    protected $table = "site_ayarlari"; // Tablo adı

    protected $fillable = [
        'anahtar',
        'deger',
    ]; // Dolu olması gereken alanlar


    protected $hidden = [

    ]; // Gizli alanlar
}
