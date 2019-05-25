<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierPayment extends Model
{
    protected $table = 'supplier_payments';

    protected $fillable = [
      'amount',
      'date_confirmation',
      'type_of_voucher',
      'type_of_payment_id',
      'authorization_number',
      'number_voucher',
      'product_detail_sale_id',
      'note',
      'status',
      'user_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function typeOfPayment()
    {
        return $this->hasOne(TypeOfPayment::class,'id','type_of_payment_id');
    }

    public function productDetailSale()
    {
        return $this->hasOne(ProductDetailSale::class,'id','product_detail_sale_id')->with('sale');
    }

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function scopeWhereProductDetailSaleId($query,$id){
        return $query->where('product_detail_sale_id',$id);
    }
}
