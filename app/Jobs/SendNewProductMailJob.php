<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewProductMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;

    protected $product;

    /**
     * Create a new job instance.
     */
    public function __construct($email, $product)
    {
        $this->email = $email;
        $this->product = $product;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::send('mails.send_new_product', ['product' => $this->product], function ($message) {
            $message->from(ENV('MAIL_FROM_ADDRESS'), ENV('MAIL_FROM_NAME'));
            $message->subject('Notify New Product');
            $message->to($this->email);
        });
    }
}
