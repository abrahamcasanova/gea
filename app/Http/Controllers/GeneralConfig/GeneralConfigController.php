<?php

namespace App\Http\Controllers\GeneralConfig;

use App\GeneralConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GeneralConfigController extends Controller
{
    public function all()
    {
        return GeneralConfig::first();
    }

    public function update (Request $request)
    {
        $this->validate($request, [
            'maximum_expenses' => 'required|numeric',
        ]);

        return GeneralConfig::updateOrCreate(['id' => $request->id],$request->except('_token'));
    }
}
