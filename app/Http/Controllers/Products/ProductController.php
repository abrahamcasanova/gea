<?php

namespace App\Http\Controllers\Products;

use App\Product;
use Pusher\Pusher;
use App\QuoteDetail;
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
            'name'          => 'required|string|unique:products',
            'category'      => 'required',
            'product_types' => 'required'
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
            'name'          => 'required|string|unique:products,name,' .$request->id,
            'product_types' => 'required',
            'category'      => 'required',
        ]);

        $request->merge(['product_type_id' => $request->product_types['id']]);
        $product = Product::find($request->id);
        if($request->base64 != null){

            $base64_image = $request->input('url_image'); // base64 encoded
            @list($type, $file_data) = explode(';', $base64_image);
            @list(, $file_data) = explode(',', $file_data);
            $imageName = str_random(10).'.'.'png';
            Storage::put('public/product_img/'.$imageName, base64_decode($file_data));

            $request->merge([
                'product_type_id' => $request->product_types['id'],
                'url_image' => $imageName

            ]);

            if(file_exists(storage_path('app/public/product_img/'.$product->url_image))){
                unlink(storage_path('app/public/product_img/'.$product->url_image));
            }
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


    public function getProductByQuote($quoteId)
    {
        return QuoteDetail::with('product')->WhereQuoteId($quoteId)->get();
    }

    public function getProductByQuoteSpecial($quoteId)
    {

        $quoteDetail = QuoteDetail::with('product')->WhereQuoteId($quoteId)->get();
        $products =  Product::whereNotIn('id',$quoteDetail->pluck('product_id')->toArray())
            ->get();
        $collectionProduct = collect([]);

        $collection = collect($quoteDetail);
        if(count($products) > 0 ){
            foreach ($products as $key => $value) {
                $collection->push([
                    'category'    => null,
                    'created_at'  => null,
                    'description' => null,
                    'id'          => $value['id'],
                    'price'       => null,
                    'product'     => $value,
                    'product_id'  => $value['id'],
                    'quote_id'    => null,
                    'status'      => null,
                    'updated_at'  => null,
                ]);
            }
        }

        return $collection;
    }

    public function count ()
    {
        return Product::count();
    }

    public function all()
    {
        return Product::active()->get();
    }

    public function exportWord(Product $product){
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Begin code
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path("word/template_products.docx"));
        $templateProcessor->setValue('nameProduct', $product->name);
        $templateProcessor->setValue('category', $product->category);
        $templateProcessor->setValue('description', $product->description);
        $templateProcessor->setValue('location', $product->location);
        $templateProcessor->setValue('extraComments', $product->extra_comments);

        if(Storage::disk('public')->exists('product_img/{$product->url_image}')){
            $templateProcessor->setImageValue('CompanyLogo', storage_path("app/public/product_img/{$product->url_image}"));
        }

        try {
            $templateProcessor->saveAs(storage_path("word/{$product->name}.docx"));
            $headers = ['Content-Type: application/msword'];
            return response()->download(storage_path("word/{$product->name}.docx"), 'file.docx', $headers);

        } catch (Exception $e) {
            return $e;
        }

    }
}
