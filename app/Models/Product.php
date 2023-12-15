<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    public function options()
    {
        return $this->hasManyThrough(Option::class, Variant::class, 'product_id', 'id', 'id', 'id')
            ->distinct();
    }

    public function getFullProductData($id)
    {
        $product = $this->with('variants')->find($id);


        $lowestPricedVariant = $product->variants->min(function ($variant) {
            return $variant;
        });
    
        $MaxPricedVariant = $product->variants->max(function ($variant) {
            return $variant;
        });
        // Add a computed property for the lowest priced variant
        $product->lowestPricedVariant = $lowestPricedVariant->price;
        $product->max_price = $MaxPricedVariant->price;
    
        // Add a computed property for all options
        $product->allOptions = $this->getAllOptionsAttribute();
       $product->defaultVariant = $lowestPricedVariant;
    
        return $product;
    }

    public function getAllOptionsAttribute()
    {
        $options = [];

        foreach ($this->variants as $variant) {
            $variantOptions = json_decode($variant->options->pluck('values')->flatten()->toJson(), true);
            $options = array_merge($options, $variantOptions);
        }

        return $options;
    }



}

