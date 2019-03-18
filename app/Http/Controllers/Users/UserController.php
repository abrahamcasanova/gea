<?php

namespace App\Http\Controllers\Users;

use Kreait\Firebase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use Avatar;

use App\User;

class UserController extends Controller
{
    public function filter (Request $request)
    {
        $query = User::query();

        if($request->search) {
            $query->where('name', 'LIKE', '%'.$request->search.'%');
        }

        $users = $query->orderBy($request->input('orderBy.column'), $request->input('orderBy.direction'))
                    ->paginate($request->input('pagination.per_page'));

        $users->load('roles');

        return $users;
    }

    public function show ($user)
    {
        return User::findOrFail($user);
    }

    public function store (Request $request)
    {
        $this->validate($request, [
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|string',
            'roles'     => 'required|array',
            'phone'     => 'required|numeric',
            'cellphone' => 'nullable|numeric',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'cellphone' => $request->cellphone,
            'password'  => Hash::make($request->password)
        ]);

        $rolesNames = array_pluck($request->roles, ['name']);
        $user->assignRole($rolesNames);

        $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
        Storage::put('avatars/'.$user->id.'/avatar.png', (string) $avatar);

        return $user;
    }

    public function update (Request $request)
    {
        $this->validate($request, [
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users,email,'.$request->id,
            'password'  => 'string|nullable',
            'roles'     => 'required|array',
            'phone'     => 'required|numeric',
            'cellphone' => 'nullable|numeric',
        ]);

        $user = User::find($request->id);
        $user->fill($request->all());
        
        if ($user->name != $request->name) {
            $avatar = Avatar::create($request->name)->getImageObject()->encode('png');
            Storage::put('avatars/'.$user->id.'/avatar.png', (string) $avatar);
            $user->name = $request->name;
        }
        if ($user->email != $request->email) {
            $user->email = $request->email;
        }
        if ($request->password != '') {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $rolesNames = array_pluck($request->roles, ['name']);
        $user->syncRoles($rolesNames);

        return $user;
    }

    public function all()
    {
        return User::all();
    }

    public function destroy ($user)
    {
        return User::destroy($user);
    }

    public function count ()
    {
        return User::count();
    }

    public function getUserRoles ($user)
    {
        $user = User::findOrFail($user);
        $user->getRoleNames();

        return $user;
    }

    public function calendar(Request $request)
    {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.config('firebase.firebase'));

        $firebase = (new Factory)->withServiceAccount($serviceAccount)
            ->withDatabaseUri(config('firebase.firebase_uri'))->create();

        $database = $firebase->getDatabase();
        $user = auth()->user();
        $database->getReference("user/{$user->id}")->set($request->all());
        return 'success';


    }

}
