<?php

namespace App\Console\Commands;

use App\Sale;
use Carbon\Carbon;
use App\Mail\DayPaymentLimit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EmailDayPaymentLimit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:day_payment_limit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para enviar recordatorios por correo de fecha limite de pago a clientes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        date_default_timezone_set('America/Mexico_City');
        $tomorrow = Carbon::now()->addDays(1)->format('Y-m-d');
    
        $sales = Sale::with('quote','payments')->where('date_payment_limit',$tomorrow)->get();
        foreach( $sales as $sale ) {
            $balance = floatval($sale->price - $sale->payments->sum('price'));
            if($sale->quote->customerOrder->customer->email) {
                Mail::to($sale->quote->customerOrder->customer->email)
                    ->send(new DayPaymentLimit($sale,$balance));
            }
        }
        $this->info('Avisos de fecha limite de pago han sido enviados un dia antes al cliente.');
    }
}
