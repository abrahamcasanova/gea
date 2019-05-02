<?php

namespace App\Http\Controllers\Reports;

use DB;
use App\Sale;
use App\Event;
use App\Quote;
use App\Payment;
use Carbon\Carbon;
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
    			$report = Event::with('sale')->whereBetween('date', array("{$request->initial_date} 00:00:00","{$request->final_date} 23:59:59"))->get();
    			break;
    		case 'Reporte de cotizaciones':
    			$report = Quote::with('customerOrder','user')->whereBetween('created_at', array("{$request->initial_date} 00:00:00","{$request->final_date} 23:59:59"))->get();
    			break;
    		case 'Reporte de ventas':
    			$report = Sale::with('user','quote','saleDetail')->whereBetween('created_at', array("{$request->initial_date} 00:00:00","{$request->final_date} 23:59:59"))->get();
    			break;
        case 'Reporte de pagos':
          $report = Payment::with('user','customer','sale')
              ->groupBy('type_of_payment')
              ->whereBetween('created_at', array("{$request->initial_date} 00:00:00","{$request->final_date} 23:59:59"))->get();
      		/*$report = Payment::with('user','customer','sale')
              ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
              ->groupBy('type_of_payment')->groupBy('user_id')->groupBy('id')
              ->whereBetween('created_at', array("{$request->initial_date} 00:00:00","{$request->final_date} 23:59:59"))->get();*/
    			break;
    	}

  		if(count($report) == 0 ){
  			return 'Sin datos';
  		}else{
        switch ($request->type_report) {
          case 'Reporte de pagos':
            $filename = "Reporte de pagos_".date('Y_m_d H_i_s');
            Excel::load(storage_path('excel/exports/Reporte de pagos.xls'), function($doc) use($report,$request){
             $sheet = $doc->getActiveSheet();
             $number = 6;
             $numberTotal = 0;

             foreach ($report as $key => $value) {
                 $report_detail = Payment::with('user','customer','sale')
                     ->where('type_of_payment',$value->type_of_payment)
                     ->whereBetween('created_at', array("{$request->initial_date} 00:00:00","{$request->final_date} 23:59:59"))
                     ->orderBy('created_at')->get();
                //dd($report_detail);
                $total = 0;

                foreach ($report_detail as $key_detail => $detail) {
                    $sum = $number + $key_detail;
                    $sheet->setCellValue("D{$sum}" , $detail->user->name);
                    $sheet->setCellValue("E{$sum}" , floatval($detail->price));
                    $sheet->setCellValue("F{$sum}" , $detail->type_of_payment);
                    $sheet->setCellValue("G{$sum}" , $detail->created_at);
                    $sheet->setCellValue("H{$sum}" , $detail->id);
                    $sheet->setCellValue("I{$sum}" , $detail->note);
                    $total += floatval($detail->price);

                }
                //$fixnumber = intval($sum) - intval($sum - 1);
                $number += count($report_detail) + 2;
                $numberTotal = $number - 2;

                $sheet->setCellValue("D{$numberTotal}" , 'TOTAL');
                $sheet->setCellValue("E{$numberTotal}" , floatval($total));
                $doc->getActiveSheet()->getStyle("D{$numberTotal}")->getFont()->setBold(true);
                $doc->getActiveSheet()->getStyle("E{$numberTotal}")->getFont()->setBold(true);
                /*
                $numberTotal = $sum + 1;

                */

             }
           })->setFilename($filename)->store('xls', storage_path('excel/exports'));
           return "excel/exports/{$filename}.xls";
          break;
        }

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
    				            	$status = null;
    				            	switch ($value['status']) {
    				            		case 2:
    				            			$status = 'Pendiente por cerrar';
    				            			break;
    				            		case 3:
    				            			$status = 'Cotización cerrada';
    				            			break;
    				            		case 4:
    				            			$status = 'No viajo';
    				            			break;
    				            	}
    			 					//dd($value['created_at']);
    								setlocale(LC_ALL, 'es_ES');
    								$fecha = Carbon::parse($value['customerOrder']['travel_date']);
    								$fecha->format("F"); // Inglés.
    								$mes = $fecha->formatLocalized('%B');// mes en idioma español

    				                $collection->push([
    				                    'Folio'    	  => $value['id'],
    				                    'Agente'	    => isset($value['user']) ? $value['user']['name']:null,
    				                    'Fecha'  	    => $value['created_at'],
    				                    'Mes'		      => $value['customerOrder']['travel_month'],
    				                    'Cliente' 	  => $value['customerOrder']['customer']['full_name'],
    				                    'Destinos'	  => implode(', ', $destinations->pluck('name')->toArray()),
    				                    'Fecha de viaje' => "{$value['customerOrder']['travel_date']} al {$value['customerOrder']['travel_end_date']}",
    				                    'Estatus'	  => $status
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

    				                $sumRatePrice = ProductDetailSale::where('quote_id',$value['quote_id'])->where('sale_id',$value['id'])->sum('rate_price');

    				                $payment = Payment::where('sale_id',$value['id'])->sum('price');
    				                $balance = floatval($sum_payment - $payment);
    				                $collection->push([
    				                    'Folio'    	  => $value['id'],
    				                    'Agente'	  => $value['user']['name'],
    				                    'Fecha'  	  => $value['created_at'],
    				                    'Cliente' 	  => $value['quote']['customerOrder']['customer']['full_name'],
    				                    'Fecha de viaje' => "{$value['quote']['customerOrder']['travel_date']} al {$value['quote']['customerOrder']['travel_end_date']}",
    				                    'Hotel/Servicio'	=> isset($value['saleDetail']) ? implode(', ',$value['saleDetail']->pluck('product.name')->toArray()):null,
    				                    'Precio Total'		 => $sum_payment,
    				                    'Saldo'			 => $balance,
    				                    'Tarifa Neta'	 => $sumRatePrice,
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
