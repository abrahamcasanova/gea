<?php

use Illuminate\Database\Seeder;
use App\Currency;

class TypeOfCurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::create(array(
            'name' => 'NACIONAL'
        ));

        Currency::create(array(
            'name' => 'DOLARES'
        ));

        Currency::create(array(
            'name' => 'UDIS'
        ));
    }
}
