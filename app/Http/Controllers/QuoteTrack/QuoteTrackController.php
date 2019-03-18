<?php

namespace App\Http\Controllers\QuoteTrack;

use DateTime;
use DateTimeZone;
use Pusher\Pusher;
use Carbon\Carbon;
use App\QuoteTrack;
use Kreait\Firebase;
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

        $database->getReference("quote_tracks/{$quote_track->quote_id}")->set($quote_track->toArray());

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

    public function count ()
    {
        return $collection = QuoteTrack::groupBy('quote_id')
            ->selectRaw('count(*) as total, quote_id')
            ->get();
    }

}