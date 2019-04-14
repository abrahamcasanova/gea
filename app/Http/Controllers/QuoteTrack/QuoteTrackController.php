<?php

namespace App\Http\Controllers\QuoteTrack;

use App\Sale;
use DateTime;
use App\Quote;
use DateTimeZone;
use Pusher\Pusher;
use Carbon\Carbon;
use App\QuoteTrack;
use Kreait\Firebase;
use App\CustomerOrder;
use App\ProductDetailSale;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use App\Http\Controllers\Controller;

class QuoteTrackController extends Controller
{
    //
    public function storeTrack(Request $request)
    {
        $this->validate($request, [
            'comments'     => 'required|string',
            'user_id'      => 'required|array',
            'contact_date' => 'required|date',
            'track_status' => 'required|array',
            'quote_id'     => 'required|array',
        ]);

        $request->merge(['user_id' => $request->user_id['id']]);
        $request->merge(['track_status' => $request->track_status['value']]);
        $request->merge(['quote_id' => $request->quote_id['id']]);
        

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.config('firebase.firebase'));

        $firebase = (new Factory)->withServiceAccount($serviceAccount)
            ->withDatabaseUri(config('firebase.firebase_uri'))->create();

        $quote_track = QuoteTrack::create($request->all());
        $quote_track->load('quote','user');
        $database = $firebase->getDatabase();
        if($request->track_status == 'Reservado' || $request->track_status == 'No viajo'){
            $database->getReference("quote_tracks/{$quote_track->quote_id}")->remove();
            if($request->track_status == 'No viajo'){
                //Status 4 es eliminado parcial
                Quote::find($request->quote_id)->update([
                    'status' => 4
                ]);    
            }
        }else{
            $database->getReference("quote_tracks/{$quote_track->quote_id}")->set($quote_track->toArray());
        }

        $options = array(
            'cluster' => 'us2',
            'encrypted' => true
        );

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $pusher->trigger('task-channel', 'task-event', $quote_track->toArray());

        return $quote_track;
    }

    public function getTrack(Request $request)
    {
        date_default_timezone_set('UTC');
        $array = array();
        $timeline = collect();
        $quote_tracks = QuoteTrack::with('quote','user')->orderBy('created_at','DESC')
            ->WhereQuoteId($request->id)->get();

        if(isset($quote_tracks)){
            foreach ($quote_tracks as $key => $value) {

                $testArray = [
                    'indigo'                => 'indigo',
                    'cyan lighten-1'        => 'cyan lighten-1',
                    'red lighten-1'         => 'red lighten-1',
                    'green lighten-1'       => 'green lighten-1',
                    'indigo lighten-1'      => 'indigo lighten-1',
                    'light-blue darken-1'   => 'light-blue darken-1',
                    'teal lighten-1'        => 'teal lighten-1',
                    'teal lighten-1'        => 'teal lighten-1',
                    'amber lighten-1'       => 'amber lighten-1',
                    'deep-orange darken-1'  => 'deep-orange darken-1',
                    'blue-grey darken-1'    => 'blue-grey darken-1',
                    'blue accent-2'         => 'blue accent-2',
                    'red'                   => 'red',
                    'pink'                  => 'pink',
                    'purple'                => 'purple',
                    'deep-purple'           => 'deep-purple ',
                ];

                $array = [
                    'color'         => array_random($testArray),
                    'icon'          => 'fas fa-hourglass-start',
                    'comment'       => $value->comments,
                    'contact_date'  => $value->contact_date,
                    'user'          => isset($value->user) ? $value->user->name:null,
                    'status'        => $value->track_status,
                ];
                $timeline->push($array);
            }
        }
        
        return $timeline;
    }

    public function countTracing()
    {
        $collection = QuoteTrack::groupBy('quote_id')
            ->selectRaw('count(quote_id) as total,quote_id')
            ->whereBetween('updated_at',[date('Y-m-d')." 00:00:00",date('Y-m-d')." 23:59:59"])
            ->get();
        return count($collection);
    }

    public function countSold()
    {
        return $collection = Sale::selectRaw('count(*) as total')
            ->whereBetween('created_at',[date('Y-m-d')." 00:00:00",date('Y-m-d')." 23:59:59"])
            ->first();
    }

    public function countQuote()
    {
        return $collection = CustomerOrder::selectRaw('count(*) as total')
            ->whereBetween('updated_at',[date('Y-m-d')." 00:00:00",date('Y-m-d')." 23:59:59"])
            ->where('status',1)->first();
    }

    public function topProducts()
    {
        $fromDate = Carbon::now()->subDay(6)->startOfWeek()->toDateString();
        $tillDate = Carbon::now()->toDateString();
        $product = ProductDetailSale::groupBy('product_id')
            ->selectRaw('count(product_id) as total,product_id')->with('sale','product')->whereHas('sale', function ($query) use($fromDate,$tillDate) {
            $query->whereBetween('updated_at',["{$fromDate} 00:00:00","{$tillDate} 23:59:59"]);
        })->orderBy('total','DESC')->take(5)->get();
        
       return response()->json([$fromDate,$tillDate,$product]);
    }

    public function count ()
    {
        return $collection = QuoteTrack::groupBy('quote_id')
            ->selectRaw('count(*) as total, quote_id')
            ->get();
    }

}