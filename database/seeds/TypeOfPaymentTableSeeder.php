<?php

use Illuminate\Database\Seeder;
use App\TypeOfPayment;

class TypeOfPaymentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TypeOfPayment::create(array(
            'name' => 'Efectivo'
        ));

        TypeOfPayment::create(array(
            'name' => 'Tarjeta de Credito'
        ));

        TypeOfPayment::create(array(
            'name' => 'Tarjeta de Debito'
        ));

        TypeOfPayment::create(array(
            'name' => 'Cheque'
        ));

        TypeOfPayment::create(array(
            'name' => 'Otro'
        ));

        TypeOfPayment::create(array(
            'name' => 'Transferencia'
        ));
    }
}
