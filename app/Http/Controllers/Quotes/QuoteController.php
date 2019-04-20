<?php

namespace App\Http\Controllers\Quotes;

use PDF;
use File;
use App\Quote;
use App\Destination;
use App\CustomerOrder;
use App\Mail\Quote as MailQuote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

class QuoteController extends Controller
{

    public function filter (Request $request)
    {
        $query = Quote::query();

        if($request->search) {
            $query->where('id', 'LIKE', '%'.$request->search.'%');
        }

        $quotes = $query->with('customerOrder','quoteDetails')->WhereStatus(2)->orderBy($request->input('orderBy.column'), $request->input('orderBy.direction'))->paginate($request->input('pagination.per_page'));

        return $quotes;
    }

    public function store (Request $request)
    {

        $this->validate($request, [
            'customer_order_id' => 'required',
        ]);
 
        $request->merge(['currency'  => $request->currency['id']]);

        $quote = Quote::updateOrCreate(
            [
                'customer_order_id' => $request->customer_order_id,
                'status' => 1,
            ],
            $request->all()
        );

        $quote->save();
        
        if(!File::exists(storage_path('app/public/pdf'))){
            File::makeDirectory(storage_path('app/public/pdf'));
        }

        if(isset($request->status) && $request->status == 2){
            $quote = $quote->load('customerOrder','quoteDetails');
            $quote->include = str_replace("\n","\n \r",$quote->include);
            $quote->policy = str_replace("\n","\n \r",$quote->policy);
            $quote->payment = str_replace("\n","\n \r",$quote->payment);
            $user = auth()->user();

            $quote->user_id = $user->id;
            
            $destinations = Destination::whereIn('id',explode(',',$quote->customerOrder->travel_destination))
                ->get();

            $pdf = PDF::loadView('quotes.pdf.generate',compact('quote','user','destinations'))
                ->setOptions(['font_dir' => public_path().'/fonts/dompdf/fonts/','defaultFont' => 'Helvetica','isHtml5ParserEnabled' => true])
                ->setPaper('a4', 'portrait')->save(storage_path('app/public/pdf/'."{$quote->customerOrder->customer->full_name}_{$quote->id}.pdf"));
            $quote->path = "{$quote->customerOrder->customer->full_name}_{$quote->id}.pdf";
            $quote->save();

            CustomerOrder::find($request->customer_order_id)->update([
                'status' => 2
            ]);
        }

        return $quote;
    }

    public function update(Quote $quote)
    {
      
        if(!File::exists(storage_path('app/public/pdf'))){
            File::makeDirectory(storage_path('app/public/pdf'));
        }

        if(isset($quote->status) && $quote->status == 2){
            $quote = $quote->load('customerOrder','quoteDetails');
            $quote->include = str_replace("\n","\n \r",$quote->include);
            $quote->policy = str_replace("\n","\n \r",$quote->policy);
            $quote->payment = str_replace("\n","\n \r",$quote->payment);
            $user = auth()->user();
            $quote->user_id = $user->id;
            $destinations = Destination::whereIn('id',explode(',',$quote->customerOrder->travel_destination))
                ->get();

            $pdf = PDF::loadView('quotes.pdf.generate',compact('quote','user','destinations'))
                ->setOptions(['font_dir' => public_path().'/fonts/dompdf/fonts/','defaultFont' => 'Helvetica','isHtml5ParserEnabled' => true])
                ->setPaper('a4', 'portrait')->save(storage_path('app/public/pdf/'."{$quote->customerOrder->customer->full_name}_{$quote->id}.pdf"));
            $quote->path = "{$quote->customerOrder->customer->full_name}_{$quote->id}.pdf";
            $quote->save();
        }

        return $quote;
    }

    public function show ($quote)
    {
        return Quote::with('customerOrder')->WhereStatus(1)->findOrFail($quote);
    }   

    public function all()
    {
        return Quote::with('customerOrder')->WhereStatus(2)->get();
    }

    public function getQuote ($quote)
    {   
        $quotes = Quote::with('customerOrder','quoteDetails')->findOrFail($quote);
        $destinations =  Destination::whereIn('id',explode(',',$quotes->customerOrder->travel_destination))
            ->get();
        $collection = collect($quotes);

        return $collection->put('travel_destination', $destinations);
    }

    public function sendQuote(Request $request)
    {
        $quote = Quote::find($request->id);
        
        if(isset($quote->customerOrder->customer->email)) {
              Mail::to($quote->customerOrder->customer->email)->send(new MailQuote($quote));
        }
    }

    public function destroy ($quote)
    {
        return Quote::destroy($quote);
    }
}
