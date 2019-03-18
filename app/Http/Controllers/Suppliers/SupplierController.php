<?php

namespace App\Http\Controllers\Suppliers;

use App\Supplier;
use Pusher\Pusher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
  public function filter (Request $request)
  {
      $query = Supplier::query();

      if($request->search) {
          $query->where('first_name', 'LIKE', '%'.$request->search.'%');
      }

      $supplier = $query->orderBy($request->input('orderBy.column'), $request->input('orderBy.direction'))
                  ->paginate($request->input('pagination.per_page'));

      return $supplier;
  }

  public function store (Request $request)
  {
      $this->validate($request, [
          'name' => 'required|string',
          'address' => 'required|string',
          'commission' => 'required|numeric',
          'phone' => 'required|numeric',
          'cellphone' => 'required|numeric',
          'email' => 'nullable|email',
          'note' => 'nullable|string',
      ]);

      $request->merge(['branch_id' => $request->branches['id']]);
      $supplier = Supplier::create($request->all());

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

      $pusher->trigger('task-channel', 'task-event-supplier', $supplier->toArray());
      return $supplier;
  }

  public function update (Request $request)
  {

      $this->validate($request, [
          'name' => 'required|string',
          'address' => 'required|string',
          'commission' => 'required|numeric',
          'phone' => 'required|numeric',
          'cellphone' => 'nullable|numeric',
          'email' => 'nullable|email',
          'note' => 'nullable|string',
      ]);

      $supplier = Supplier::find($request->id);
      $supplier->fill($request->all())->save();

      return $supplier;
  }

  public function destroy ($supplier)
  {
      return Supplier::destroy($supplier);
  }

  public function getSupplier ($customer)
  {
      return Supplier::findOrFail($customer);
  }

  public function count ()
  {
      return Supplier::count();
  }

  public function all()
  {
      return Supplier::Active()->get();
  }
}
