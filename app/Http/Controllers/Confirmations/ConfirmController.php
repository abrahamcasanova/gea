<?php

namespace App\Http\Controllers\Confirmations;

use App\ProductDetailSale;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfirmController extends Controller
{
    public function getDetails(Request $request)
    {
        $product_detail_sale = ProductDetailSale::with('product','quote','sale','supplier','supplierPayment')
            ->WhereNotNullSaleId()->get();

        return $product_detail_sale = $product_detail_sale->map(function($product) {
            return $product;
        });
    }
}
