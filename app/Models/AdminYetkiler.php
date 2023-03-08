<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminYetkiler extends Model
{
    use HasFactory;
    protected $table = "admin_yetkileri"; // Tablo adı

    protected $fillable = [
        'admin_id',
        'yetkiler',
    ]; // Dolu olması gereken alanlar


    protected $hidden = [

    ]; // Gizli alanlar
}
