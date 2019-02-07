<?php

namespace App\Http\Controllers\Customers;

use Storage;
use App\Role;
use App\User;
use App\Profile;
use App\Customer;
use App\CustomerDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function filter (Request $request)
    {
        $query = Customer::query();

        if($request->search) {
            $query->where('first_name', 'LIKE', '%'.$request->search.'%');
        }

        $customers = $query->orderBy($request->input('orderBy.column'), $request->input('orderBy.direction'))
                    ->paginate($request->input('pagination.per_page'));

        $customers->load('group','subgroup');

        return $customers;
    }

    public function show ($customer)
    {
        return Customer::findOrFail($customer);
    }

    public function store (Request $request)
    {
        $request->merge(['type_of_person' => $request->type_of_person['id']]);
        $request->merge(['office_id'      => $request->office_id['id']]);
        $request->merge(['group_id'       => $request->group_id['id']]);
        $request->merge(['subgroup_id'    => $request->subgroup_id['id']]);

        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed',
            'password_confirmation' => 'required|min:6',
            'type_of_person' => 'required'
        ]);

        $user = new User($request->all());
        $user->password = Hash::make($request->password);
        $user->save();
        $user->assignRole('customers');

        $customer = Customer::create($request->all());
        $customer->user_id = $user->id;
        $customer->save();

        $profile = Profile::create($request->all());
        $profile->user_id = $user->id;
        $profile->save();

        return $user;
    }

    public function update (Request $request)
    {
        $this->validate($request, [
            'user.name' => 'required|string',
            'email' => 'required|email|unique:customers,email,'.$request->id,
            'password' => 'string|nullable'
        ]);
        $request->merge(['type_of_person' => $request->type_of_person['id']]);
        $request->merge(['office_id'      => isset($request->office) ? $request->office['id']:null]);
        $request->merge(['group_id'       => isset($request->group) ? $request->group['id']:null]);
        $request->merge(['subgroup_id'    => isset($request->subgroup) ? $request->subgroup['id']:null]);

        $customer = Customer::find($request->id);
        $customer->fill($request->all())->save();

        $user = User::find($request->user_id);
        $user->fill($request->user)->save();

        $profile = Profile::WhereUserId($request->user_id)->first();
        $profile->fill($request->user['profile'])->save();

        return $user;
    }

    public function destroy ($customer)
    {
        $customers = Customer::find($customer);
        User::destroy($customers->user_id);
        return Customer::destroy($customer);
    }

    public function count ()
    {
        return Customer::count();
    }

    public function getCustomer ($customer)
    {
        return Customer::with('user','office','group','subgroup')->findOrFail($customer);
    }

    public function upload(Request $request)
    {
        //
        if($request->totalChunks == 1){
            $path = Storage::putFileAs('public/customers_file', $request->file('file'), $request->filename);
            $path = str_replace("public/","",$path);
            $customer_document = CustomerDocument::create([
                'name'        => $request->filename,
                'url'         => $path,
                'customer_id' => $request->customer_id,
            ]);
        }else{

            Storage::putFileAs('public/customers_file', $request->file('file'), "{$request->chunkNumber}_{$request->identifier}");

            if ($request->chunkNumber === $request->totalChunks) {

                $target_file = $request->identifier;
                $final_file_path = fopen(storage_path("app/public/customers_file/{$request->filename}"), "ab");

              for ($i = 1; $i <= $request->totalChunks; $i++) {
                  $file_chunk = "{$i}_{$target_file}";
                  if ( $final_file_path ) {
                      $in = fopen(storage_path("app/public/customers_file/{$file_chunk}"), "rb");
                      if ( $in ) {
                          while ( $buff = fread( $in, 104857600 ) ) {
                              fwrite($final_file_path, $buff);
                          }
                      }
                      if(fclose($in)) {
                          unlink(storage_path("app/public/customers_file/{$file_chunk}"));
                      }
                  }
              }
              fclose($final_file_path);
              $path = "customers_file/{$request->filename}";
              $customer_document = CustomerDocument::create([
                  'name'        => $request->filename,
                  'url'         => $path,
                  'customer_id' => $request->customer_id,
              ]);

            }

        }

        return null;
    }

    public function getDocs(Request $request)
    {
        return response()->json(CustomerDocument::orderBy('created_at','DESC')->
            WhereCustomerId($request->customer_id)->get());
    }



}
