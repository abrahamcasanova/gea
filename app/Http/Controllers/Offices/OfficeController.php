<?php

namespace App\Http\Controllers\Offices;

use App\Office;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OfficeController extends Controller
{
    public function all()
    {
        return Office::Active()->get();
    }
}
