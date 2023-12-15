<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use app\Events\ProductOutOfStock;
use app\Notifications\ProductOutOfStockNotification;
use app\Models\User;
class HandleProductOutOfStock implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    use InteractsWithQueue;

    public function handle(ProductOutOfStock $event)
    {
        $product = $event->product;

        // Update the default variant to the one with the lowest price
        $defaultVariant = $product->getDefaultVariant();
        $product->default_variant_id = $defaultVariant->id;
        $product->save();

       
        
        $adminEmail = 'admin@34ml.com';
        $admin = User::where('email', $adminEmail)->first();

        if ($admin) {
            $admin->notify(new ProductOutOfStockNotification($product));
        }
    }
}
