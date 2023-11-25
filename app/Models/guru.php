<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Model Guru
class Guru extends Model
{
    protected $fillable = ['nama_guru', 'terasik', 'terkiller', 'terinspiratif'];

    public function suara()
    {
        return $this->morphMany(Suara::class, 'votable');
    }

    protected $table = 'guru';
}

