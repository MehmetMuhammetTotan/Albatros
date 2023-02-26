<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Admin extends Authenticatable
{
    use HasFactory;
    protected $table = "admins"; // Tablo adı

    protected $fillable = [
        'name',
        'email',
        'password',
    ]; // Dolu olması gereken alanlar


}
