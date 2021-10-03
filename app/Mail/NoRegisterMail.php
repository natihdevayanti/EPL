<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Order;

class NoRegisterMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $order;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Pesanan Anda Berhasil Dibuat ' . $this->order->invoice)
        ->view('emails.no_register')
        ->with([
            'order' => $this->order
        ]);;
    }
}
