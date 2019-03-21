<?php

namespace App\Console\Commands;

use App\Customer;
use App\Mail\Birthday;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class HappyBirthday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send email customers birthday';

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

        $customers = Customer::whereMonth('birthdate', '=', date('m'))
            ->whereDay('birthdate', '=', date('d'))->get();
        //dd(date('m'),date('d'));
        foreach( $customers as $customer ) {
          if($customer->email) {
              Mail::to($customer->email)->send(new Birthday($customer));
          }
        }
        $this->info('Los mensajes de felicitacion han sido enviados correctamente');
    }
}
