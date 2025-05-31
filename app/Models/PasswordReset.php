<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak menggunakan tabel default (password_resets)
    protected $table = 'password_resets';

    // Tentukan kolom yang dapat diisi secara massal
    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];

    // Menentukan apakah kolom timestamps harus otomatis dikelola oleh Eloquent
    public $timestamps = false;
}
