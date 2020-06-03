<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderTransactionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $user;
    public $ordertransaction;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user , $order , $ordertransaction ,$settings)
    {
        $this->user = $user;
        $this->order = $order;
        $this->ordertransaction = $ordertransaction;
        $this->settings = $settings;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('knet.orderTransactionEmail');
    }
}
