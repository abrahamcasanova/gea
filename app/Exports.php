<?php

namespace App\Exports;

use App\Event;
use Maatwebsite\Excel\Concerns\FromCollection;

class InvoicesExport implements FromCollection
{
    public function collection(Request $request)
    {
    	return $events = Event::all();
    }
}