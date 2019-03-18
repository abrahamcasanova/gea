<?php

namespace App\Http\Controllers\Sales
;

use App\Sale;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Illuminate\Http\Request;
use Kreait\Firebase\ServiceAccount;
use App\Http\Controllers\Controller;



class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store (Request $request)
    {

        $this->validate($request, [
            'product_id'             => 'required|array',
            'price'                  => 'required|numeric',
            'date_payment_limit'     => 'required|date',
            'date_payment_supplier'  => 'required|date',
            'date_advance'           => 'required|date',
            'schedule'               => 'required',
            'amount_receivable'      => 'required',
            'simple_room'            => 'required|numeric',
            'double_room'            => 'required|numeric',
            'triple_room'            => 'required|numeric',
            'quadruple_room'         => 'required|numeric',
            'supplier_id'            => 'required|array',
            'rate_price'             => 'required',
            'confirmation'           => 'nullable|string',
            'events'                 => 'required|array'
        ]);
        dd('stop',$request->events);
        
        $request->merge(['product_type_id' => $request->product_types['id']]);

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.config('firebase.firebase'));

        $firebase = (new Factory)->withServiceAccount($serviceAccount)
            ->withDatabaseUri(config('firebase.firebase_uri'))->create();

        $database = $firebase->getDatabase();
        

        $database->getReference("user/{$user->id}")->set($request->all());

        return $sale = Sale::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        //
    }
}
