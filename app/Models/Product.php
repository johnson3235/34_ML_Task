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

        $product->lowestPricedVariant = $lowestPricedVariant->price;
        $product->max_price = $MaxPricedVariant->price;

        $product->allOptions = $this->getAllOptionsAttribute();
        $product->defaultVariant = $lowestPricedVariant;


    
        return $product;
    }

    public function getAllOptionsAttribute()
    {
        $options = [];

        foreach ($this->variants as $variant) {
            $variantOptions = json_decode($variant->options->pluck('values')->flatten()->toJson(), true);

        

$flattenedData = array_merge(...array_map('json_decode', $variantOptions));

// Filter out null values
$filteredData = array_filter($flattenedData, function ($value) {
    return $value !== null;
});

// Convert to lowercase
$lowercaseData = array_map('strtolower', $filteredData);

// Convert to a unique list
$uniqueData = array_values(array_unique($lowercaseData));

        }

        return $uniqueData;
    }



}

