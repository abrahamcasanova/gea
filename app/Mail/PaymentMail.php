<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payment,$customer;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payment,$customer)
    {
        $this->payment = $payment;
        $this->customer = $customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('contacto@appnovasolutions.com', 'New Sun Travel')
            ->markdown('emails.customer_payment')->subject('Correo de confirmación de pago');
    }
}
