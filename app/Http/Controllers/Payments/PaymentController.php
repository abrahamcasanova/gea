<?php

namespace App\Http\Controllers\Payments;

use PDF;
use App\Sale;
use App\Payment;
use App\Customer;
use Pusher\Pusher;
use App\QuoteDetail;
use App\Destination;
use App\TypeOfPayment;
use App\Mail\PaymentMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function filter (Request $request)
    {
        $query = Payment::query();

        if($request->search) {
            $query->where('name', 'LIKE', '%'.$request->search.'%');
        }

        $payment = $query->with('user','customer','sale')->orderBy($request->input('orderBy.column'), $request->input('orderBy.direction'))
                    ->paginate($request->input('pagination.per_page'));

        return $payment;
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
    public function store(Request $request)
    {
        $this->validate($request, [
            'price'                  => 'required|numeric|not_in:0',
            'real_price'             => 'required|numeric|not_in:0',
            'type_of_payment.name'   => 'required',
            'percentage'             => 'nullable|numeric',
            'exchange_rate'          => 'nullable|numeric',
        ]);

        $request->merge(['type_of_payment' => $request->type_of_payment['name']]);

        $customer =  Customer::find($request->customer_id);
        $payment = Payment::with('sale')->create($request->all());
        $payment->authorization_number = $request->authorization_number;
        $sale = Sale::with('quote','payments')->where('id',$payment->sale_id)->first();

        $balance = floatval($sale->price - $sale->payments->sum('price'));

        $user = auth()->user();
        
        $destinations = Destination::whereIn('id',explode(',',$payment->sale->travel_destination))
                ->get();

        $pdf = PDF::loadView('payments.pdf.receipt',compact('payment','user','destinations','balance'))
            ->setOptions(['font_dir' => public_path().'/fonts/dompdf/fonts/','defaultFont' => 'Helvetica','isHtml5ParserEnabled' => true])
            ->setPaper('a4', 'portrait')->save(storage_path('app/public/pdf/payments/'."{$payment->id}.pdf"));
        
        $payment->path = "{$payment->id}.pdf";
        $payment->save();
        if(isset($customer->email)){
            Mail::to($customer->email)
                ->send(new PaymentMail($payment,$customer));
        }

        return response()->json(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        $this->validate($request, [
            'price'                  => 'required|numeric|not_in:0',
            'real_price'             => 'required|numeric|not_in:0',
            'type_of_payment.name'   => 'required',
            'percentage'             => 'nullable|numeric',
            'exchange_rate'          => 'nullable|numeric',
        ]);
         
        $request->merge(['type_of_payment' => $request->type_of_payment['name']]);

        $payment = Payment::find($request->id);
        $payment->authorization_number = $request->authorization_number;
        $payment->fill($request->all())->save();

        $sale = Sale::with('quote','payments')->where('id',$payment->sale_id)->first();

        $balance = floatval($sale->price - $sale->payments->sum('price'));

        $user = auth()->user();
        
        $destinations = Destination::whereIn('id',explode(',',$payment->sale->travel_destination))
                ->get();

        $pdf = PDF::loadView('payments.pdf.receipt',compact('payment','user','destinations','balance'))
            ->setOptions(['font_dir' => public_path().'/fonts/dompdf/fonts/','defaultFont' => 'Helvetica','isHtml5ParserEnabled' => true])
            ->setPaper('a4', 'portrait')->save(storage_path('app/public/pdf/payments/'."{$payment->id}.pdf"));
        
        $payment->path = "{$payment->id}.pdf";
        $payment->save();
        
        return $payment;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy($payment)
    {
        return Payment::destroy($payment);
    }

    public function getPayment($payment){
        $user = auth()->user();        
        $payment = Payment::with('user','customer','sale')->findOrFail($payment);
        $type_payment = TypeOfPayment::where('name',$payment->type_of_payment)->first();
        $collection = collect($payment);
        return $collection->put('type_of_payment',$type_payment)->put('current_user', $user);
    }

    public function getPaymentBySale($id){
        return Payment::with('user','customer','sale')->where('sale_id',$id)->get();
    }
}
