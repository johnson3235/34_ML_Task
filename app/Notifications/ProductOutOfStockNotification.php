<?php

// app/Notifications/ProductOutOfStockNotification.php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ProductOutOfStockNotification extends Notification
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Product Out of Stock Warning.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    // Add other notification channels if needed (broadcast, database, etc.)
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => 'Notification data',
        ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            // Data to store in the database
        ];
    }

    // Define the channels through which the notification should be sent
    public function via($notifiable)
    {
        return ['mail', 'broadcast', 'database'];
    }
}