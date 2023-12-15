<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Option extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'values'];


    public function variants()
    {
        return $this->belongsToMany(Variant::class);
    }
}

