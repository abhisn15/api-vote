<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

// Model Suara
class Suara extends Model
{
    protected $fillable = ['user_id', 'votable_id', 'votable_type', 'kategori'];

    public function votable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

