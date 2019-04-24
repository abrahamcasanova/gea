<?php

namespace App\Http\Controllers\Sales;

use PDF;
use App\Sale;
use DateTime;
use App\Event;
use App\Quote;
use Carbon\Carbon;
use App\QuoteTrack;
use App\QuoteDetail;
use Kreait\Firebase;
use App\Destination;
use App\CustomerOrder;
use App\ProductDetailSale;
use Kreait\Firebase\Factory;
use Illuminate\Http\Request;
use App\Mail\Sale as MailSale;
use App\Mail\CustomerSaleGenerate;
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
            'date_payment_limit'     => 'required|date',
            'date_advance'           => 'required|date',
            'schedule'               => 'nullable',
            'amount_receivable'      => 'nullable|numeric',
            'simple_room'            => 'nullable|numeric',
            'double_room'            => 'nullable|numeric',
            'triple_room'            => 'nullable|numeric',
            'quadruple_room'         => 'nullable|numeric',
            'destinations'           => 'required',
            'events'                 => 'nullable|array'
        ]);


        $collection = collect($request->destinations);

        $user = auth()->user();
        $request->merge(['product_id'  => $request->product_id['product_id']]);
        $request->merge(['supplier_id' => $request->supplier_id['id']]);
        $request->merge(['schedule'    => isset($request->schedule) ? $request->schedule['id']:null]);
        $request->merge(['user_id'     => $user->id]);
        $request->merge(['travel_destination' => implode($collection->pluck('id')->toArray(),',')]);

        $quote = Quote::find($request->quote_id);

        CustomerOrder::find($quote->customer_order_id)->update([
            'status' => 3
        ]);

        $quote = Quote::find($request->quote_id)->update([
            'status' => 3
        ]);

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.config('firebase.firebase'));
        $firebase = (new Factory)->withServiceAccount($serviceAccount)
            ->withDatabaseUri(config('firebase.firebase_uri'))->create();
        $database = $firebase->getDatabase();
        $database->getReference("quote_tracks/{$request->quote_id}")->remove();

        QuoteTrack::create([
            'quote_id'      => $request->quote_id,
            'user_id'       => auth()->user()->id,
            'track_status'  => 'Reservado',
            'contact_date'  => date('Y-m-d'),
            'comments'      => 'Comentario automatico por venta',
            'status'        => 1
        ]);


        if($request->quote_detail_id){
            QuoteDetail::find($request->quote_detail_id)->update([
                'price' => $request->price
            ]);
        }

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.config('firebase.firebase'));

        $firebase = (new Factory)->withServiceAccount($serviceAccount)
            ->withDatabaseUri(config('firebase.firebase_uri'))->create();

        $database = $firebase->getDatabase();
        $user = auth()->user();
        $sale = Sale::create($request->all());


        if(isset($sale)){
            ProductDetailSale::where('quote_id',$request->quote_id)->whereNull('sale_id')
                ->update([
                    'sale_id' => $sale->id
                ]);

            $sale = $sale->load('product','quote','user','supplier','quoteDetail','saleDetail');
            $destinations = Destination::whereIn('id',explode(',',$sale->travel_destination))
                ->get();

            $productSaleDetail = ProductDetailSale::where('quote_id',$request->quote_id)->where('sale_id',$sale->id)->sum('price');

            $sale->price = $productSaleDetail;

            $details = ProductDetailSale::with('product')->where('quote_id',$request->quote_id)
                ->where('sale_id',$sale->id)->get();

            $user = auth()->user();

            $pdf = PDF::loadView('quotes.pdf.sale_generate',compact('sale','user','destinations','details'))
                ->setOptions(['font_dir' => public_path().'/fonts/dompdf/fonts/','defaultFont' => 'Helvetica','isHtml5ParserEnabled' => true])
                ->setPaper('a4', 'portrait')->save(storage_path('app/public/pdf/sales/'."{$sale->quote->customerOrder->customer->full_name}_{$sale->id}.pdf"));
            $sale->path = "{$sale->quote->customerOrder->customer->full_name}_{$sale->id}.pdf";
            $sale->save();

            if($request->events){
                $collection = collect([]);
                foreach ($request->events as $key => $value) {
                    if($this->validateDate($value['date'],'Y-m-d')){
                        $array = [
                            'date'                  => $value['date'],
                            'details'               => $value['details'],
                            'firebase_id'           => $value['id'],
                            'open'                  => $value['open'],
                            'title'                 => $value['title'],
                            'type'                  => $value['type'],
                            'schedule'              => $request->schedule,
                            'date_advance'          => $request->date_advance,
                            'date_payment_limit'    => $request->date_payment_limit,
                            'date_payment_supplier' => $request->date_payment_supplier,
                            'quote_id'              => $request->quote_id,
                            'sale_id'               => $sale->id,
                        ];

                        Event::create($array);
                        $collection->push($array);
                    }
                }
                $database->getReference("events/{$sale->id}")->set($collection);
            }
        }

        $customerSaleGenerate = $sale;

        if(isset($sale->quote->customerOrder->customer->email)){
            Mail::to($sale->quote->customerOrder->customer->email)
                ->send(new CustomerSaleGenerate($customerSaleGenerate,$destinations));
        }

        return $sale;
    }

    function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function getSale($sale){
        $sale = Sale::with('product','quote','user','supplier','quoteDetail')->findOrFail($sale);
        $destinations =  Destination::whereIn('id',explode(',',$sale->travel_destination))
            ->get();

        $collection = collect($sale);
        return $collection->put('travel_destination', $destinations);
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

    public function generateCoupon(Sale $sale){

        $user =  auth()->user();
        $destinations = Destination::whereIn('id',explode(',',$sale->travel_destination))
                ->get();

        $details = ProductDetailSale::with('product','quote')->where('quote_id',$sale->quote_id)
            ->where('sale_id',$sale->id)->get();

        $pdf = PDF::loadView('sales.pdf.coupon',compact('sale','user','destinations','details'))
                ->setOptions(['font_dir' => public_path().'/fonts/dompdf/fonts/','defaultFont' => 'Helvetica','isHtml5ParserEnabled' => true])
                ->setPaper('a4', 'portrait')->save(storage_path('app/public/pdf/sales/coupon/'."{$sale->quote->customerOrder->customer->full_name}_{$sale->id}.pdf"));

       return response()->download(storage_path('app/public/pdf/sales/coupon/'."{$sale->quote->customerOrder->customer->full_name}_{$sale->id}.pdf"), "{$sale->quote->customerOrder->customer->full_name}", [], 'inline');

    }

    public function saveNoteCoupon(Request $request){
        Sale::find(intval($request->sale_id))->update([
            'note' => $request->note
        ]);

        return 'success';
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
        $this->validate($request, [
            'price'                  => 'required|numeric',
            'date_payment_limit'     => 'required|date',
            'date_payment_supplier'  => 'nullable|date',
            'date_advance'           => 'required|date',
            'schedule'               => 'required',
            'amount_receivable'      => 'nullable',
            'simple_room'            => 'nullable|numeric',
            'double_room'            => 'nullable|numeric',
            'triple_room'            => 'nullable|numeric',
            'quadruple_room'         => 'nullable|numeric',
            'events'                 => 'required|array'
        ]);

        $user = auth()->user();

        $request->merge(['product_id'  => $request->product_id[0]['id']]);
        $request->merge(['supplier_id' => isset($request->supplier_id[0]) ? $request->supplier_id[0]['id']:$request->supplier_id['id']]);


        $request->merge(['schedule'    => $request->schedule[0] ? $request->schedule[0]['id']:$request->schedule['id']]);
        $request->merge(['user_id'     => $user->id]);

        $request->merge(['currency' => isset($request->quote['currency'][0]) ? $request->quote['currency'][0]['id']:$request->quote['currency']['id']]);

        if($request->currency){
            Quote::find(intval($request->quote['id']))->update([
                'currency' => $request->currency
            ]);
        }

        //el precio no lo esta cambiando..
        if($request->quote_detail_id){
            QuoteDetail::find($request->quote_detail_id)->update([
                'price' => $request->price
            ]);
        }

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.config('firebase.firebase'));

        $firebase = (new Factory)->withServiceAccount($serviceAccount)
            ->withDatabaseUri(config('firebase.firebase_uri'))->create();

        $database = $firebase->getDatabase();
        $user = auth()->user();


        $collection = collect($request->travel_destination);

        $request->merge(['travel_destination' => implode($collection->pluck('id')->toArray(),',')]);

        $sale = Sale::find($request->id)->fill($request->all());

        if(isset($sale)){

            $user = auth()->user();

            $destinations = Destination::whereIn('id',explode(',',$request->travel_destination))
                ->get();

            $productSaleDetail = ProductDetailSale::where('quote_id',$request->quote['id'])->where('sale_id',$sale->id)->sum('price');

            $sale->price = $productSaleDetail;

            $sale->save();
            $sale = $sale->load('product','quote','user','supplier','quoteDetail');

            $details = ProductDetailSale::with('product')->where('quote_id',$request->quote['id'])
                ->where('sale_id',$sale->id)->get();

            $pdf = PDF::loadView('quotes.pdf.sale_generate',compact('sale','user','destinations','details'))
                ->setOptions(['font_dir' => public_path().'/fonts/dompdf/fonts/','defaultFont' => 'Helvetica','isHtml5ParserEnabled' => true])
                ->setPaper('a4', 'portrait')->save(storage_path('app/public/pdf/sales/'."{$sale->quote->customerOrder->customer->full_name}_{$sale->id}.pdf"));
            $sale->path = "{$sale->quote->customerOrder->customer->full_name}_{$sale->id}.pdf";
            $sale->save();

            if($request->events){

                Event::where('sale_id',$request->id)->delete();
                $collection = collect([]);
                foreach ($request->events as $key => $value) {
                    if($this->validateDate($value['date'],'Y-m-d')){

                        $array = [
                            'date'                  => $value['date'],
                            'details'               => $value['details'],
                            'firebase_id'           => $value['id'],
                            'open'                  => $value['open'],
                            'title'                 => $value['title'],
                            'type'                  => $value['type'],
                            'schedule'              => $request->schedule,
                            'date_advance'          => $request->date_advance,
                            'date_payment_limit'    => $request->date_payment_limit,
                            'date_payment_supplier' => $request->date_payment_supplier,
                            'quote_id'              => $request->quote_id,
                            'sale_id'               => $sale->id,
                        ];
                        Event::create($array);
                        $collection->push($array);
                    }
                }
                $database->getReference("events/{$sale->id}")->set($collection);
            }
        }

        return $sale;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy ($sale)
    {
        $sale = Sale::find($sale);
        Quote::where('id',$sale->quote_id)->delete();
        return Sale::destroy($sale);
    }
}
