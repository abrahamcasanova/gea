<?php

namespace App\Http\Controllers\SupplierPayment;

use App\SupplierPayment;
use App\ProductDetailSale;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplierPaymentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'amount'                 => 'required|numeric|not_in:0',
            'date_confirmation'      => 'required',
            'type_of_payment'        => 'required',
            'type_of_voucher'        => 'required',
        ]);
        $request->merge(['type_of_payment_id' => $request->type_of_payment['id']]);
        $request->merge(['type_of_voucher' => $request->type_of_voucher['name']]);

        $suplierPayment = SupplierPayment::create($request->all());
        $user = auth()->user();
        $suplierPayment->user_id = $user->id;
        $suplierPayment->save();

        $product = ProductDetailSale::find($request->product_detail_sale_id);
        $sumSupplierPayment = SupplierPayment::WhereProductDetailSaleId($product->id)->sum('amount');

        if(floatval($product->rate_price) <= floatval($sumSupplierPayment)){
            $product->liquidate = 1;
            if(!isset($product->date_liquidate)){
                $product->date_liquidate = date('Y-m-d');
            }
            $product->save();
        }

        return response()->json(true);
    }

    public function getPayments($id)
    {
        $product = ProductDetailSale::find($id);
        $collection = collect($product);
        $supplierPayments = SupplierPayment::with('typeOfPayment','user')->WhereProductDetailSaleId($id)
            ->Active()->get();

        return $collection->put('supplierPayments',$supplierPayments);
    }
}
