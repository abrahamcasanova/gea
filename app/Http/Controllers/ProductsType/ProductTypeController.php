<?php

namespace App\Http\Controllers\ProductsType;

use Pusher\Pusher;
use App\ProductType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductTypeController extends Controller
{
    public function filter (Request $request)
    {
        $query = ProductType::query();

        if($request->search) {
            $query->where('first_name', 'LIKE', '%'.$request->search.'%');
        }

        $productType = $query->orderBy($request->input('orderBy.column'), $request->input('orderBy.direction'))
                    ->paginate($request->input('pagination.per_page'));

        return $productType;
    }

    public function store (Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:product_types',
            'clabe' => 'required|string|unique:product_types',
        ]);

        $request->merge(['branch_id' => $request->branches['id']]);
        $productType = ProductType::create($request->all());

        return $productType;
    }

    public function update (Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string|unique:product_types,name,'.$request->id,
            'clabe' => 'required|string|unique:product_types,clabe,'.$request->id,
        ]);

        $productType = ProductType::find($request->id);
        $productType->fill($request->all())->save();

        return $productType;
    }

    public function destroy ($productType)
    {
        return ProductType::destroy($productType);
    }

    public function getProductType ($customer)
    {
        return ProductType::findOrFail($customer);
    }

    public function count ()
    {
        return ProductType::count();
    }

    public function all()
    {
        return ProductType::Active()->get();
    }
}
