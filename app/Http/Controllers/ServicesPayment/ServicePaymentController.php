<?php

namespace App\Http\Controllers\ServicesPayment;

use App\ServicePayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServicePaymentController extends Controller
{
    public function store (Request $request)
    {
        $this->validate($request, [
            'amount'       => 'required|numeric',
            'date'         => 'required|date',
            'service_id'   => 'required',
        ]);

        return ServicePayment::create($request->all());
    }

    public function getService($service){
        return ServicePayment::whereYear('date',  date('Y'))
            ->whereMonth('date', date('m'))->Active()->where('service_id',$service)->get();
    }

    public function all ()
    {
      return ServicePayment::with('service')->whereYear('date',  date('Y'))
          ->whereMonth('date', date('m'))->Active()->get();
    }
}
