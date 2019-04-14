<?php

namespace App\Http\Controllers\Destinations;

use Pusher\Pusher;
use App\QuoteDetail;
use App\Destination;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DestinationController extends Controller
{

    public function filter (Request $request)
    {
        $query = Destination::query();

        if($request->search) {
            $query->where('name', 'LIKE', '%'.$request->search.'%');
        }

        $product = $query->orderBy($request->input('orderBy.column'), $request->input('orderBy.direction'))
                    ->paginate($request->input('pagination.per_page'));

        return $product;
    }

    public function search (Request $request)
    {
        $query = Destination::query();

        if($request->search) {
            $query->where('name', 'LIKE', '%'.$request->search.'%');
        }

        $destination = $query->get();
        return $destination;
    }

    public function store (Request $request)
    {
        $this->validate($request, [
            'name'          => 'required|string|unique:destinations',
        ]);

        $product = new Destination;
        $product->fill($request->all())->save();
 
        return $product;
    }

    public function update (Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:destinations,name,' .$request->id,
        ]);

        $destination = Destination::find($request->id);

        $destination->fill($request->all())->save();

        return $destination;
    }

    public function destroy ($destination)
    {

        return Destination::destroy($destination);
    }

    public function getDestination ($destination)
    {
        return Destination::findOrFail($destination);
    }


    public function getDestinationByQuote($quoteId)
    {
        return QuoteDetail::WhereQuoteId($quoteId)->get();
    }

    public function count ()
    {
        return Destination::count();
    }

    public function all()
    {
        return Destination::Active()->get();
    }

}
