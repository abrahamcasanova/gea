<?php

namespace App\Http\Controllers\QuoteDetails;

use App\QuoteDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuoteDetailController extends Controller
{
    public function getQuoteDetailsByQuoteId(Request $request){
        return QuoteDetail::with('product')
            ->WhereQuoteId($request->quote_id)->get();
    }

    public function store (Request $request)
    {

        $this->validate($request, [
            'product_id' => 'required|array',
            'price'      => 'required|numeric|not_in:0',
        ]);


        $request->merge(['product_id' => $request->product_id['id']]);
        $request->merge(['supplier_id' => $request->supplier_id['id']]);

        return QuoteDetail::create($request->all());

    }

    public function all()
    {
        return QuoteDetail::active()->get();
    }
    
    public function destroy ($quoteDetail)
    {

        return QuoteDetail::destroy($quoteDetail);
    }
}
