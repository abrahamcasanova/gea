<?php

namespace App\Http\Controllers\Reports;

use App\Sale;
use App\Event;
use App\Quote;
use App\Payment;
use App\Destination;
use App\ProductDetailSale;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function getReport(Request $request){

    	switch ($request->type_report) {
    		case 'Pagos pendientes por cobrar':
    			$report = Event::with('sale')->whereBetween('date', array($request->initial_date,$request->final_date))->get();
    			break;
    		case 'Reporte de cotizaciones':
    			$report = Quote::with('customerOrder')->whereBetween('created_at', array($request->initial_date,$request->final_date))->get();
    			
    			break;
    		case 'Reporte de ventas':
    			$report = Sale::with('user','quote','saleDetail')->whereBetween('created_at', array($request->initial_date,$request->final_date))->get();

    			break;
    	}
		
		
		if(count($report) == 0 ){
			return 'Sin datos';
		}else{
			Excel::create($request->type_report, function($excel) use($report,$request) {
		    	
		        if(count($report) > 0 ){
		        	$collection = collect();
		        	switch ($request->type_report) {
			    		case 'Pagos pendientes por cobrar':
			    			foreach ($report as $key => $value) {
				            	$payment = Payment::where('sale_id',$value['sale_id'])->latest('updated_at')->first();
				                $collection->push([
				                    'Folio'    	  => $value['id'],
				                    'Fecha'  	  => $value['date'],
				                    'Cliente' 	  => $value['sale']['quote']['customerOrder']['customer']['full_name'],
				                    'Cantidad a cobrar' => floatval($value['sale']['amount_receivable']),
				                    'Titulo_Calendario' => $value['title'],
				                    'Fecha limite de pago del cliente' => $value['date_payment_limit'],
				                    'Fecha de pago del proveedor' => $value['date_payment_supplier'],
				                    'Fecha ultimo pago'			=> isset($payment) ? $payment->updated_at:null,
				                    'Monto'						=> isset($payment) ? $payment->price:null,
				                    'Moneda'					=> $value['sale']['quote']['currency']

				                ]);
				            }
			    			$excel->sheet($request->type_report, function($sheet) use($collection){
							    $sheet->fromArray($collection);
							    $sheet->setAutoSize(true);
							    $sheet->setColumnFormat(array(
								    'D' => '0.00'
								));
							});
			    			break;
			    		case 'Reporte de cotizaciones':
			    			foreach ($report as $key => $value) {
				            	
				            	$destinations = Destination::whereIn('id',explode(',',$value->customerOrder->travel_destination))->get();

				                $collection->push([
				                    'Folio'    	  => $value['id'],
				                    'Fecha'  	  => $value['created_at'],
				                    'Cliente' 	  => $value['customerOrder']['customer']['full_name'],
				                    'Destinos'	  => implode(', ', $destinations->pluck('name')->toArray()),
				                    'Fecha de viaje' => "{$value['customerOrder']['travel_date']} al {$value['customerOrder']['travel_end_date']}",
				                    'Estatus'	  => $value['status'] == 2 ? 'Pendiente por cerrar':'Cotización cerrada ' 
				                ]);
				            }

				            $excel->sheet($request->type_report, function($sheet) use($collection){
							    $sheet->fromArray($collection);
							    $sheet->setAutoSize(true);
							    $sheet->setColumnFormat(array(
								    'D' => '0.00'
								));
							});
			    			break;
			    		case 'Reporte de ventas':
			    			foreach ($report as $key => $value) {
				            					                
				                $sum_payment = ProductDetailSale::where('quote_id',$value['quote_id'])->where('sale_id',$value['id'])->sum('price');
				                
				                $payment = Payment::where('sale_id',$value['id'])->sum('price');
				                $balance = floatval($sum_payment - $payment);
				                $collection->push([
				                    'Folio'    	  => $value['id'],
				                    'Fecha'  	  => $value['created_at'],
				                    'Cliente' 	  => $value['quote']['customerOrder']['customer']['full_name'],
				                    'Fecha de viaje' => "{$value['quote']['customerOrder']['travel_date']} al {$value['quote']['customerOrder']['travel_end_date']}",
				                    'Precio Total'		 => $sum_payment,
				                    'Saldo'			 => $balance,
				                    'Fecha anticipo' => "{$value['date_advance']}"
				                ]);
				            }

				            $excel->sheet($request->type_report, function($sheet) use($collection){
							    $sheet->fromArray($collection);
							    $sheet->setAutoSize(true);
							    $sheet->setColumnFormat(array(
								    'D' => '0.00'
								));
							});
			    			break;
			    	}
		            
		        }			 
			})->store('xls', storage_path('excel/exports'));
			
			return "excel/exports/{$request->type_report}.xls";
		}
		
		//$headers = ['Content-Type: application/vnd.ms-excel'];
        //return response()->download(storage_path("excel/exports/Pagos.xls"), 'Pagos.xls', $headers);
    }
} 
