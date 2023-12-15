<?php

// app/Notifications/ProductOutOfStockNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductOutOfStockNotification extends Notification
{
    use Queueable;


    public $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Product Out of Stock Notification')
            ->line('The product is now out of stock.')
            ->action('View Product', route('products.show', $notifiable->id));
    }

    public function toArray($notifiable)
    {
        return [
            'productId' => $this->product->id,
            // Additional data to send to the notification
        ];
    }
}
