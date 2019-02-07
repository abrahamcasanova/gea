<?php

namespace App\Http\Controllers\Groups;

use App\Group;
use App\Subgroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupController extends Controller
{
    public function all()
    {
        return Group::with('subgroup')->Active()->get();
    }

    public function subgroup(Request $request)
    {
        return Subgroup::WhereGroupId($request->id)->Active()->get();
    }
}
