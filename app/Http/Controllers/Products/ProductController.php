<?php

namespace App\Http\Controllers\Products;

use App\Product;
use Pusher\Pusher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function filter (Request $request)
    {
        $query = Product::query();

        if($request->search) {
            $query->where('name', 'LIKE', '%'.$request->search.'%');
        }

        $product = $query->with('product_types')->orderBy($request->input('orderBy.column'), $request->input('orderBy.direction'))
                    ->paginate($request->input('pagination.per_page'));

        return $product;
    }

    public function store (Request $request)
    {
        $base64_image = $request->input('url_image'); // base64 encoded
        @list($type, $file_data) = explode(';', $base64_image);
        @list(, $file_data) = explode(',', $file_data);
        $imageName = str_random(10).'.'.'png';
        Storage::put('public/product_img/'.$imageName, base64_decode($file_data));

        $this->validate($request, [
            'name' => 'required|string',
            'clabe' => 'required|unique:products',
            'product_type_id' => 'required'
        ]);

        $request->merge(['product_type_id' => $request->product_types['id']]);
        $product = new Product;
        $product->fill($request->all());
        $product->url_image = $imageName;
        $product->save();
        return $product;
    }

    public function update (Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'product_type_id' => 'required',
            'clabe' => 'required|unique:products,clabe,'.$request->id,
        ]);

        $base64_image = $request->input('url_image'); // base64 encoded
        @list($type, $file_data) = explode(';', $base64_image);
        @list(, $file_data) = explode(',', $file_data);
        $imageName = str_random(10).'.'.'png';
        Storage::put('public/product_img/'.$imageName, base64_decode($file_data));

        $request->merge([
            'product_type_id' => $request->product_types['id'],
            'url_image' => $imageName
        ]);
        $product = Product::find($request->id);
        if(file_exists(storage_path('app/public/product_img/'.$product->url_image))){
            unlink(storage_path('app/public/product_img/'.$product->url_image));
        }
        $product->fill($request->all())->save();

        return $product;
    }

    public function destroy ($product)
    {

        return Product::destroy($product);
    }

    public function getProduct ($product)
    {
        return Product::with('product_types')->findOrFail($product);
    }

    public function count ()
    {
        return Product::count();
    }

    public function all()
    {
        return Product::active()->get();
    }
}
