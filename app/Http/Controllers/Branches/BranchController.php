<?php

namespace App\Http\Controllers\Branches;

use App\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class BranchController extends Controller
{
    public function filter (Request $request)
    {
        $query = Branch::query();

        if($request->search) {
            $query->where('name', 'LIKE', '%'.$request->search.'%');
        }

        $customers = $query->orderBy($request->input('orderBy.column'), $request->input('orderBy.direction'))
                    ->paginate($request->input('pagination.per_page'));

        return $customers;
    }

    public function show ($customer)
    {
        return Branch::findOrFail($customer);
    }

    public function store (Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $branch = Branch::create($request->all());

        return $branch;
    }

    public function update (Request $request)
    {
        $this->validate($request, [
            'name' => 'required|name|unique:branches,name,'.$request->id,
        ]);

        $branch = Branch::find($request->user_id);
        $branch->fill($request->user)->save();

        return $branch;
    }

    public function destroy ($branch)
    {
        return Branch::destroy($branch);
    }

    public function count ()
    {
        return Branch::count();
    }

    public function getBranch ($branch)
    {
        return Branch::findOrFail($branch);
    }

    public function all()
    {
        return Branch::Active()->get();
    }

}
