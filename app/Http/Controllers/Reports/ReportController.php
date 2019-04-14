<?php

namespace App\Http\Controllers\Reports;

use Maatwebsite\Excel\Facades\Excel;
use App\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function getReport(Request $request){
		 $events = Event::with('sale')->whereBetween('date', array($request->initial_date,$request->final_date))->get();
		Excel::create('Pagos', function($excel) use($request) {
		    $events = Event::with('sale')->whereBetween('date', array($request->initial_date,$request->final_date))->get();
			
	        if(count($events) > 0 ){
	        	$collection = collect();
	            foreach ($events as $key => $value) {
	                $collection->push([
	                    'Folio'    	  => $value['id'],
	                    'Fecha'  	  => $value['date'],
	                    'Cliente' 	  => $value['sale']['quote']['customerOrder']['customer']['full_name'],
	                    'Cantidad a cobrar' => floatval($value['sale']['amount_receivable']),
	                    'Titulo_Calendario' => $value['title'],
	                    'Fecha limite de pago del cliente' => $value['date_payment_limit'],
	                    'Fecha de pago del proveedor' => $value['date_payment_supplier'],
	                ]);
	            }
	        }
		    $excel->sheet('Pagos', function($sheet) use($collection) {
		    $sheet->fromArray($collection);
		    $sheet->setAutoSize(true);
		    $sheet->setColumnFormat(array(
			    'D' => '0.00'
			));
		});
		 
		})->store('xls', storage_path('excel/exports'));
		return "excel/exports/Pagos.xls";
		//$headers = ['Content-Type: application/vnd.ms-excel'];
        //return response()->download(storage_path("excel/exports/Pagos.xls"), 'Pagos.xls', $headers);
    }
} 
