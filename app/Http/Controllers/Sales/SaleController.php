<?php

namespace App\Http\Controllers\Sales;

use PDF;
use App\Sale;
use App\Quote;
use Carbon\Carbon;
use App\QuoteDetail;
use Kreait\Firebase;
use App\CustomerOrder;
use Kreait\Firebase\Factory;
use Illuminate\Http\Request;
use App\Mail\Sale as MailSale;
use Kreait\Firebase\ServiceAccount;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;



class SaleController extends Controller
{

    public function filter (Request $request)
    {
        $query = Sale::query();

        if($request->search) {
            $query->where('id', 'LIKE', '%'.$request->search.'%');
        }

        $sales = $query->with('product','quote','user','supplier')->Active()->orderBy($request->input('orderBy.column'), $request->input('orderBy.direction'))->paginate($request->input('pagination.per_page'));

        return $sales;
    }

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
            'simple_room'            => 'nullable|numeric',
            'double_room'            => 'nullable|numeric',
            'triple_room'            => 'nullable|numeric',
            'quadruple_room'         => 'nullable|numeric',
            'supplier_id'            => 'required|array',
            'rate_price'             => 'required',
            'confirmation'           => 'required|string',
            'events'                 => 'required|array'
        ]);
        
        $user = auth()->user();

        $request->merge(['product_id'  => $request->product_id['product_id']]);
        $request->merge(['supplier_id' => $request->supplier_id['id']]);
        $request->merge(['schedule'    => $request->schedule['id']]);
        $request->merge(['user_id'     => $user->id]);

        $quote = Quote::find($request->quote_id);

        CustomerOrder::find($quote->customer_order_id)->update([
            'status' => 3
        ]);

        $quote = Quote::find($request->quote_id)->update([
            'status' => 3
        ]);

        QuoteDetail::find($request->quote_detail_id)->update([
            'price' => $request->price
        ]);

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.config('firebase.firebase'));

        $firebase = (new Factory)->withServiceAccount($serviceAccount)
            ->withDatabaseUri(config('firebase.firebase_uri'))->create();

        $database = $firebase->getDatabase();
        $user = auth()->user();
        $sale = Sale::create($request->all());

        if(isset($sale)){
            $sale = $sale->load('product','quote','user','supplier','quoteDetail');
           
            $user = auth()->user();

            $pdf = PDF::loadView('quotes.pdf.sale_generate',compact('sale','user'))
                ->setOptions(['font_dir' => public_path().'/fonts/dompdf/fonts/','defaultFont' => 'Helvetica','isHtml5ParserEnabled' => true])
                ->setPaper('a4', 'portrait')->save(storage_path('app/public/pdf/sales/'."{$sale->quote->customerOrder->customer->full_name}_{$sale->id}.pdf"));
            $sale->path = "{$sale->quote->customerOrder->customer->full_name}_{$sale->id}.pdf";
            $sale->save();
        }

        $database->getReference("events/{$sale->id}")->set($request->events);

        return $sale;
    }

    public function getSale($sale){
        return Sale::with('product','quote','user','supplier','quoteDetail')->findOrFail($sale);
    }

    public function sendSale(Request $request)
    {
        $sale = Sale::find($request->id);
        
        if(!$sale->confirmation){
            return "error";
        }

        if(isset($sale->quote->customerOrder->customer->email)){
            Mail::to($sale->quote->customerOrder->customer->email)->send(new MailSale($sale));
        }
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
    public function destroy ($sale)
    {
        return Sale::destroy($sale);
    }
}
