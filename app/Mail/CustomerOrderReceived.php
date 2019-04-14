<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerOrderReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $customerOrder;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customerOrder)
    {
        $this->customerOrder = $customerOrder;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('contacto@appnovasolutions.com', 'New Sun Travel')
            ->markdown('emails.received')->subject('Correo de confirmación');
    }
}
