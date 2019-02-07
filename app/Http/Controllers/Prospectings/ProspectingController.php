<?php

namespace App\Http\Controllers\Prospectings;

use Pusher\Pusher;
use App\Prospecting;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Illuminate\Http\Request;
use App\Events\NewProspecting;
use Kreait\Firebase\ServiceAccount;
use App\Http\Controllers\Controller;

class ProspectingController extends Controller
{
    public function filter (Request $request)
    {
        $query = Prospecting::query();

        if($request->search) {
            $query->where('first_name', 'LIKE', '%'.$request->search.'%');
        }

        $prospecting = $query->with('branches')->orderBy($request->input('orderBy.column'), $request->input('orderBy.direction'))
                    ->paginate($request->input('pagination.per_page'));

        return $prospecting;
    }

    public function store (Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|numeric',
            'cellphone' => 'required|numeric',
            'branches' => 'required|array',
            'email' => 'required|email|unique:prospectings',
        ]);

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.config('firebase.caos_json'));

        $firebase = (new Factory)->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://caos-4fa8a.firebaseio.com/')->create();

        $database = $firebase->getDatabase();

        $request->merge(['branch_id' => $request->branches['id']]);
        $prospecting = Prospecting::create($request->all());

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

        $pusher->trigger('task-channel', 'task-event', $prospecting->toArray());

        $database->getReference("prospectings/{$prospecting->id}")->set($prospecting->toArray());

        return $prospecting;
    }

    public function update (Request $request)
    {

        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|numeric',
            'cellphone' => 'required|numeric',
            'branches' => 'required|array',
            'email' => 'required|email|unique:customers,email,'.$request->id,
        ]);
        $request->merge(['branch_id' => $request->branches['id']]);

        $prospecting = Prospecting::find($request->id);
        $prospecting->fill($request->all())->save();

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.config('firebase.caos_json'));
        $firebase = (new Factory)->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://caos-4fa8a.firebaseio.com/')->create();

        $database = $firebase->getDatabase();
        $database->getReference("prospectings/{$prospecting->id}")->update($prospecting->toArray());

        return $prospecting;
    }

    public function destroy ($prospecting)
    {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.config('firebase.caos_json'));

        $firebase = (new Factory)->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://caos-4fa8a.firebaseio.com/')->create();

        $database = $firebase->getDatabase();
        $database->getReference("prospectings/{$prospecting}")->remove();
        $database->getReference("prospecting_tracks/$prospecting")->remove());

        return Prospecting::destroy($prospecting);
    }

    public function getProspecting ($customer)
    {
        return Prospecting::with('branches')->findOrFail($customer);
    }

    public function count ()
    {
        return Prospecting::count();
    }

    public function all()
    {
        return Prospecting::with('branches')->Active()->get();
    }
}
