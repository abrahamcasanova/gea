<?php

namespace App\Http\Controllers\Prospectings;

use App\User;
use Pusher\Pusher;
use Carbon\Carbon;
use Kreait\Firebase;
use App\ProspectingTrack;
use Kreait\Firebase\Factory;
use Illuminate\Http\Request;
use App\Events\NewProspecting;
use Kreait\Firebase\ServiceAccount;
use App\Http\Controllers\Controller;

class AssistantController extends Controller
{
    public function getAllAssistant()
    {
        $assistants = User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'assistant');
        })->get();
        return isset($assistants) ? $assistants:null;
    }

    public function storeTrack(Request $request)
    {
        $this->validate($request, [
            'note' => 'required|string',
            'prospecting_id' => 'required|array',
            'status_prospecting' => 'required|array',
            'user_assistant_id' => 'required|array',
        ]);

        $request->merge(['prospecting_id' => $request->prospecting_id['id']]);
        $request->merge(['status_prospecting' => $request->status_prospecting['value']]);
        $request->merge(['user_assistant_id' => $request->user_assistant_id['id']]);
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.config('firebase.caos_json'));
        $prospecting_track = ProspectingTrack::create($request->all());
        $firebase = (new Factory)->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://caos-4fa8a.firebaseio.com/')->create();

        $database = $firebase->getDatabase();

        $database->getReference("prospecting_tracks/{$prospecting_track->prospecting_id}")->push($prospecting_track->toArray());
        $database->getReference("prospectings/{$prospecting_track->prospecting_id}")
            ->update([
              'status' => $prospecting_track->status_prospecting
            ]);

        return $prospecting_track;
    }

    public function getTrack(Request $request)
    {
        $array = array();
        $timeline = collect();
        $prospecting_track = ProspectingTrack::with('assistant','prospecting')->orderBy('created_at')
            ->WhereProspectingId($request->id)->get();
        foreach ($prospecting_track as $key => $value) {
              $array = [
                  'type' => 'circle',
                  'tag' => "{$value->created_at->diffForHumans()}",
                  'htmlMode' => false,
                  'color' => 'red',
                  'content' => "{$value->assistant->name} : {$value->note} "
              ];
              $timeline->push($array);
        }
        return $timeline;
    }
}
