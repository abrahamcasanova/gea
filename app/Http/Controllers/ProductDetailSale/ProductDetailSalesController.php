<?php

namespace App\Http\Controllers\ProductDetailSale;

use App\ProductDetailSale;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductDetailSalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

     public function getByQuote(Request $request){

        return ProductDetailSale::with('product','quote','supplier')->Active()
            ->where('quote_id',$request->quote_id)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_id'            => 'required|array',
            'supplier_id'           => 'required|array',
            'price'                 => 'required',
            'rate_price'            => 'required',
            'confirmation'          => 'required',
            'date_payment_supplier' => 'required'
        ]);

        $request->merge(['product_id'  => $request->product_id['product']['id']]);
        $request->merge(['supplier_id' => $request->supplier_id['id']]);

        ProductDetailSale::create($request->all());

        return ProductDetailSale::with('product','quote','supplier')->Active()
            ->where('quote_id',$request->quote_id)->get();
    }

    public function storeEdit(Request $request){
        $this->validate($request, [
            'product_id'            => 'required|array',
            'supplier_id'           => 'required|array',
            'price'                 => 'required',
            'rate_price'            => 'required',
            'confirmation'          => 'required',
            'date_payment_supplier_table' => 'required'
        ]);

        $request->merge(['product_id'  => $request->product_id['id']]);
        $request->merge(['supplier_id' => $request->supplier_id['id']]);
        $request->merge(['date_payment_supplier'  => $request->date_payment_supplier_table]);


        ProductDetailSale::create($request->all());

        return ProductDetailSale::with('product','quote','supplier')->Active()
            ->where('quote_id',$request->quote_id)->get();
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return ProductDetailSale::destroy($id);
    }
}
