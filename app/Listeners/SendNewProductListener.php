<?php

namespace App\Listeners;

use App\Events\SendNewProduct;
use App\Jobs\SendNewProductMailJob;

class SendNewProductListener
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
    public function handle(SendNewProduct $event): void
    {
        $emails = $event->product['users'];
        $product = $event->product['productName'];

        foreach ($emails as $email) {
            dispatch(new SendNewProductMailJob($email, $product));
        }
    }
}
