<?php

namespace App\Http\Controllers\CustomerOrder;

use Storage;
use App\Role;
use App\User;
use App\Profile;
use App\CustomerOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerOrderController extends Controller
{
    public function filter (Request $request)
    {
        $query = CustomerOrder::query();

        if($request->search) {
            $query->where('first_name', 'LIKE', '%'.$request->search.'%');
        }

        $customers = $query->with('customer')->orderBy($request->input('orderBy.column'), $request->input('orderBy.direction'))
                    ->paginate($request->input('pagination.per_page'));

        return $customers;
    }

    public function show ($customer)
    {
        return CustomerOrder::with('customer')->findOrFail($customer);
    }

    public function store (Request $request)
    {
        $request->merge(['type_of_person' => $request->type_of_person['id']]);
        $request->merge(['level' => $request->level['id']]);
        $request->merge(['customer_id' => $request->recommended['id']]);

        $this->validate($request, [
            'first_name' => 'required|string',
            'email' => 'required|email|unique:customers',
            'type_of_person' => 'required',
        ]);

        $customer = CustomerOrder::create($request->all());
        $customer->save();

        return $customer;
    }

    public function update (Request $request)
    {
        $this->validate($request, [
            'travel_month'        => 'required',
            'travel_date'         => 'required|',
            'cellphone'           => 'required|',
            'customer.email'      => 'required|',
            'call_time'           => 'required|',
            'travel_destination'  => 'required',
            'number_adults'       => 'required',
            'number_childs'       => 'required',
            'services'            => 'required',
            'with_us'             => 'required',
        ]);

        $request->merge(['travel_month' => $request->travel_month['id']]);
        $request->merge(['with_us' => $request->with_us['id']]);
        $customer = CustomerOrder::find($request->id);

        $customer->fill($request->all())->save();


        return $customer;
    }

    public function destroy ($customer)
    {
        $customers = CustomerOrder::find($customer);
        User::destroy($customers->user_id);
        return CustomerOrder::destroy($customer);
    }

    public function count ()
    {
        return CustomerOrder::count();
    }

    public function order(Request $request,$customer)
    {
        $url = URL::temporarySignedRoute(
            'customers.order', now()->addMinutes(30), ['customer_id' => $customer]
        );

        return redirect($url);
    }

    public function orderWhatsapp(Request $request){
        return URL::temporarySignedRoute(
            'customers.order', now()->addMinutes(30), ['customer_id' => $request->customer_id]
        );
    }

    public function storeOrder(Request $request)
    {
        $this->validate($request, [
            'travel_month'        => 'required',
            'travel_date'         => 'required|',
            'cellphone'           => 'required|',
            'email'               => 'required|',
            'call_time'           => 'required|',
            'travel_destination'  => 'required',
            'number_adults'       => 'required',
            'number_childs'       => 'required',
            'services'            => 'required',
            'with_us'             => 'required',
        ]);

        $request->merge(['travel_month' => $request->travel_month['id']]);
        $request->merge(['with_us' => $request->with_us['id']]);

        return CustomerOrder::updateOrCreate([
            'customer_id' => $request->customer_id
            ],
            $request->all()
        );
    }

    public function getCustomer ($customer)
    {
        return CustomerOrder::findOrFail($customer);
    }

    public function all()
    {
        return CustomerOrder::Active()->get();
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
