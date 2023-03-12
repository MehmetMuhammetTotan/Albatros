<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personel extends Model
{
    use HasFactory;
    protected $table = "personeller"; // Tablo adı

    protected $fillable = [
        'ad',
        'soyad',
        'personel_grubu_id',
    ]; // Dolu olması gereken alanlar


    protected $hidden = [

    ]; // Gizli alanlar
}
