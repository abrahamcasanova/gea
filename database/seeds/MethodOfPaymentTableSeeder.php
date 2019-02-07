<?php

use Illuminate\Database\Seeder;
use App\MethodOfPayment;

class MethodOfPaymentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MethodOfPayment::create(array(
            'name' => 'MENSUAL',
            'value'        => 12,
        ));
        MethodOfPayment::create(array(
            'name' => 'TRIMESTRAL',
            'value'        => 4,
        ));
        MethodOfPayment::create(array(
            'name'=> 'SEMESTRAL',
            'value'        => 2,
        ));
        MethodOfPayment::create(array(
            'name' => 'ANUAL',
            'value'        => 1,
        ));

    }
}
