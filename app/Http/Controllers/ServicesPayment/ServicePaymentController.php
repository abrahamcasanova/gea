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
            'amount'               => 'required|numeric',
            'date'                 => 'required|date',
            'service_id'           => 'required',
            'type_of_payment_id'   => 'required',
        ]);
        $request->merge(['type_of_payment_id' => $request->type_of_payment_id['id']]);

        return ServicePayment::updateOrCreate(
          ['id' => $request->id],
          $request->all()
        );
    }

    public function getService($service){
        return ServicePayment::with('typeOfPayment')->whereYear('date',  date('Y'))
            ->Active()->where('service_id',$service)->get();
    }

    public function all()
    {
        return ServicePayment::with('service')->whereYear('date',  date('Y'))
            ->whereMonth('date', date('m'))->Active()->get();
    }

    public function show($service_payment)
    {
        return ServicePayment::with('typeOfPayment')->findOrFail($service_payment);
    }

    public function destroy ($service)
    {
        ServicePayment::find($service)->update([
            'status' => 2
        ]);
        return 'success';
    }
}
