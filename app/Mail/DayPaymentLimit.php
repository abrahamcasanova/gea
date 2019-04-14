<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DayPaymentLimit extends Mailable
{
    use Queueable, SerializesModels;

    public $sale,$balance;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sale,$balance)
    {
        $this->sale = $sale;
        $this->balance = $balance;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('contacto@appnovasolutions.com', 'New Sun Travel')
            ->markdown('emails.day_payment_limit')->subject('Aviso de fecha limite de pago');
    }
}
