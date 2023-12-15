<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Variant extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'title', 'price', 'stock', 'is_in_stock',
    ];

    public function options()
    {
        return $this->belongsToMany(Option::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function getIsDefaultAttribute()
    {
        return $this->isDefaultVariant();
    }

    public function isDefaultVariant()
    {
        return $this->id === $this->product->default_variant_id;
    }


}