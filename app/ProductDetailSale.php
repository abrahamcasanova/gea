<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductDetailSale extends Model
{
 	protected $table = 'product_detail_sales';

    protected $fillable = [
      'price',
      'rate_price',
      'confirmation',
      'date_payment_supplier',
      'status',
      'product_id',
      'supplier_id',
      'quote_id',
      'sale_id'
    ];

    public function product(){
        return $this->hasOne(Product::class,'id','product_id')->with('product_types');
    }

    public function quote()
    {
        return $this->hasOne(Quote::class,'id','quote_id')->with('customerOrder','quoteDetails');
    }

    public function sale()
    {
        return $this->hasOne(Sale::class,'id','sale_id');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class,'id','supplier_id');
    }

    public function supplierPayment()
    {
        return $this->hasMany(SupplierPayment::class,'product_detail_sale_id','id')
            ->with('typeOfPayment','user');
    }
    
    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function scopeWhereNotNullSaleId($query){
        return $query->whereNotNull('sale_id');
    }
}
