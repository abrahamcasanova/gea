<?php

namespace App\Http\Controllers\Confirmations;

use App\ProductDetailSale;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfirmController extends Controller
{
    public function getDetails(Request $request)
    {
        return ProductDetailSale::with('product','quote','sale','supplier')
            ->WhereNotNullSaleId()->get();
    }
}
