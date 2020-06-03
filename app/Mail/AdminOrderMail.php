<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $title;
    public $setting;
    public $address;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order, $title)
    {
        $this->address = $order->userAddress;
        $this->order = $order;
        $this->title = $title;
        $this->setting = app('settings');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.adminOrderEmail')->subject($this->title);
    }
}
