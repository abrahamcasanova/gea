<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerSaleGenerate extends Mailable
{
    use Queueable, SerializesModels;

    public $customerSaleGenerate,$destinations;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customerSaleGenerate,$destinations)
    {
        $this->customerSaleGenerate = $customerSaleGenerate;
        $this->destinations = $destinations;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('contacto@appnovasolutions.com', 'New Sun Travel')
            ->markdown('emails.customer_sale_generate')->subject('Correo de confirmación de viaje');
    }
}
