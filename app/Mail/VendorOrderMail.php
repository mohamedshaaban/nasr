<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VendorOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $user;
    public $title;
    public $setting;
    public $address;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order, $user, $title)
    {
        $this->user = $user;
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
        return $this->markdown('emails.vendorOrderEmail')->subject($this->title);
    }
}
