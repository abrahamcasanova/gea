<?php

namespace App\Http\Controllers\Services;

use App\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    public function all (Request $request)
    {
        $services = Service::CurrentPayment()->Active()->get();

        return $service = collect($services)->map(function ($service) {
          $service->total = $service->payments->sum('amount');
          return $service;
        });
    }

    public function store (Request $request)
    {
        $this->validate($request, [
            'name'         => 'required|string|unique:services',
            'date_payment' => 'required|numeric',
            'frequency'    => 'nullable|string',
        ]);

        $supplier = Service::create($request->all());

        return $supplier;
    }

    public function getService ($service)
    {
        return Service::findOrFail($service);
    }

    public function update (Request $request)
    {
        $this->validate($request, [
            'name'         => 'required|unique:services,name,'.$request->id,
            'date_payment' => 'required|numeric',
            'frequency'    => 'nullable|string',
        ]);


        $service = Service::find($request->id);
        $service->fill($request->all())->save();

        return $service;
    }

    public function destroy ($service)
    {
        $service = Service::find($service);
        $service->delete();
        return 'success';
    }
}
