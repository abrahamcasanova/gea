<?php

namespace App\Http\Controllers\TypeOfPayments;

use App\TypeOfPayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TypeOfPaymentController extends Controller
{
	public function filter (Request $request)
    {
        $query = TypeOfPayment::query();

        if($request->search) {
            $query->where('name', 'LIKE', '%'.$request->search.'%');
        }

        $type_payments = $query->orderBy($request->input('orderBy.column'), $request->input('orderBy.direction'))
                    ->paginate($request->input('pagination.per_page'));

        return $type_payments;
    }

    public function store (Request $request)
    {
        $this->validate($request, [
            'name'          => 'required|string|unique:type_of_payments',
        ]);

        $type_of_payments = new TypeOfPayment;
        $type_of_payments->fill($request->all())->save();
 
        return $type_of_payments;
    }

    public function destroy ($type_of_payments)
    {

        return TypeOfPayment::destroy($type_of_payments);
    }

    public function update (Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:type_of_payments,name,' .$request->id,
        ]);

        $type_payments = TypeOfPayment::find($request->id);

        $type_payments->fill($request->all())->save();

        return $type_payments;
    }


    public function getTypePayment ($type_of_payments)
    {
        return TypeOfPayment::findOrFail($type_of_payments);
    }

    public function all()
    {
        return TypeOfPayment::Active()->get();
    }
}
