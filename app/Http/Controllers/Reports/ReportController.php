<?php

namespace App\Http\Controllers\Reports;

use DB;
use App\Sale;
use App\Event;
use App\Quote;
use App\Payment;
use Carbon\Carbon;
use App\Destination;
use App\SupplierPayment;
use App\ProductDetailSale;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function showChart(Request $request){
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
              return $report = Payment::with('user','customer','sale')
                ->select('type_of_payment', DB::raw('sum(price) total'))
                ->groupBy('type_of_payment')
                ->whereBetween('created_at', array("{$request->initial_date} 00:00:00","{$request->final_date} 23:59:59"))->get();
      			break;
          case 'Reporte de pagos de confirmaciones (resumen)':
              return $report = SupplierPayment::with('user','typeOfPayment','productDetailSale')
                ->select('type_of_payment', DB::raw('sum(price) total'))
                ->groupBy('type_of_payment')
                ->whereBetween('created_at', array("{$request->initial_date} 00:00:00","{$request->final_date} 23:59:59"))->get();
      			break;
      	}
    }

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
    			break;
        case 'Reporte de pagos de confirmaciones (resumen)':
          $report = SupplierPayment::with('user','typeOfPayment','productDetailSale')
              ->groupBy('type_of_payment_id')
              ->whereBetween('created_at', array("{$request->initial_date} 00:00:00","{$request->final_date} 23:59:59"))->get();
          break;
        case 'Reporte de ventas liquidadas':
          $report = ProductDetailSale::with('quote','sale','supplier')
              ->whereBetween('created_at', array("{$request->initial_date} 00:00:00","{$request->final_date} 23:59:59"))->get();
          break;
        case 'Reporte de pagos vs pago de confirmaciones':
          $report = Payment::with('user','customer','sale')
              ->groupBy('type_of_payment')
              ->whereBetween('created_at', array("{$request->initial_date} 00:00:00","{$request->final_date} 23:59:59"))->get();
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
               $sheet->setCellValue("A2" , "REPORTE DE PAGOS DEL {$request->initial_date} AL {$request->final_date}");
             foreach ($report as $key => $value) {
                 $report_detail = Payment::with('user','customer','sale','userConfirmation')
                     ->where('type_of_payment',$value->type_of_payment)
                     ->whereBetween('created_at', array("{$request->initial_date} 00:00:00","{$request->final_date} 23:59:59"))
                     ->orderBy('created_at')->get();

                $total = 0;

                foreach ($report_detail as $key_detail => $detail) {

                    $status = isset($detail->sale->deleted_at) ?  'Eliminado':'Activo';
                    $sum = $number + $key_detail;

                    $sheet->setCellValue("A{$sum}" , $detail->customer->full_name);
                    $sheet->setCellValue("B{$sum}" , $detail->user->name);
                    $sheet->setCellValue("C{$sum}" , floatval($detail->price));
                    $sheet->setCellValue("D{$sum}" , $status);
                    $sheet->setCellValue("E{$sum}" , $detail->type_of_payment);
                    $sheet->setCellValue("F{$sum}" , $detail->created_at);
                    $sheet->setCellValue("G{$sum}" , $detail->sale->id);
                    $sheet->setCellValue("H{$sum}" , $detail->id);
                    $sheet->setCellValue("I{$sum}" , isset($detail->userConfirmation) ? $detail->userConfirmation->name:null);
                    $sheet->setCellValue("J{$sum}" , $detail->break);
                    $sheet->setCellValue("K{$sum}" , $detail->date_received);
                    $sheet->setCellValue("L{$sum}" , isset($detail->authorization_number) ? $detail->authorization_number:null);
                    $sheet->setCellValue("M{$sum}" , isset($detail->deposit_date) ? $detail->deposit_date:null);
                    $sheet->setCellValue("N{$sum}" , $detail->note);

                    $total += floatval($detail->price);

                }
                //$fixnumber = intval($sum) - intval($sum - 1);
                $number += count($report_detail) + 2;
                $numberTotal = $number - 2;

                $sheet->setCellValue("B{$numberTotal}" , 'TOTAL');
                $sheet->setCellValue("C{$numberTotal}" , floatval($total));
                $doc->getActiveSheet()->getStyle("B{$numberTotal}")->getFont()->setBold(true);
                $doc->getActiveSheet()->getStyle("C{$numberTotal}")->getFont()->setBold(true);
                /*
                $numberTotal = $sum + 1;

                */

             }
           })->setFilename($filename)->store('xls', storage_path('excel/exports'));
           return "excel/exports/{$filename}.xls";
          break;
          case 'Reporte de pagos de confirmaciones (resumen)':
            $filename = "Reporte de pagos de confirmaciones resumen_".date('Y_m_d H_i_s');
            Excel::load(storage_path('excel/exports/Reporte de pagos de confirmaciones resumen.xls'), function($doc) use($report,$request){
             $sheet = $doc->getActiveSheet();
             $number = 6;
             $numberTotal = 0;
             $sheet->setCellValue("B2" , "REPORTE DE PAGOS DE CONFIRMACIONES DEL {$request->initial_date} AL {$request->final_date}");
             foreach ($report as $key => $value) {

                $report_detail = SupplierPayment::with('user','typeOfPayment','productDetailSale')
                    ->where('type_of_payment_id',$value->typeOfPayment->id)
                    ->whereBetween('created_at', array("{$request->initial_date} 00:00:00","{$request->final_date} 23:59:59"))->get();

                $total = 0;
                $sum = $number + $key;

                foreach ($report_detail as $key_detail => $detail) {

                    $sum = $number + $key_detail;

                    $sheet->setCellValue("B{$sum}" , isset($detail->productDetailSale) ? $detail->productDetailSale->id:null);
                    $sheet->setCellValue("C{$sum}" , isset($detail->productDetailSale->sale->quote->customerOrder) ?
                    $detail->productDetailSale->sale->quote->customerOrder->customer->full_name:null);
                    $sheet->setCellValue("D{$sum}" , floatval($detail->amount));
                    $sheet->setCellValue("E{$sum}" , $detail->date_confirmation);
                    $sheet->setCellValue("F{$sum}" , $detail->type_of_voucher);
                    $sheet->setCellValue("G{$sum}" , isset($detail->number_voucher) ? $detail->number_voucher:'PENDIENTE');
                    $sheet->setCellValue("H{$sum}" , isset($detail->typeOfPayment) ? $detail->typeOfPayment->name:null);
                    $sheet->setCellValue("I{$sum}" , $detail->authorization_number);
                    $sheet->setCellValue("J{$sum}" , isset($detail->user) ? $detail->user->name:null);
                    $sheet->setCellValue("K{$sum}" , $detail->note);

                    $total += floatval($detail->amount);

                }

                $number += count($report_detail) + 2;
                $numberTotal = $number - 2;

                $sheet->setCellValue("C{$numberTotal}" , 'TOTAL');
                $sheet->setCellValue("D{$numberTotal}" , floatval($total));
                $doc->getActiveSheet()->getStyle("C{$numberTotal}")->getFont()->setBold(true);
                $doc->getActiveSheet()->getStyle("D{$numberTotal}")->getFont()->setBold(true);

             }
           })->setFilename($filename)->store('xls', storage_path('excel/exports'));
           return "excel/exports/{$filename}.xls";
          break;
          case 'Reporte de ventas liquidadas':
            $filename = "Reporte de ventas liquidadas_".date('Y_m_d H_i_s');
            Excel::load(storage_path('excel/exports/Reporte de pagos de confirmaciones gral.xls'), function($doc) use($report,$request){
             $sheet = $doc->getActiveSheet();
             $number = 6;
             $numberTotal = 0;
             $sheet->setCellValue("B2" , "REPORTE DE PAGOS DE CONFIRMACIONES DEL {$request->initial_date} AL {$request->final_date}");
             foreach ($report as $key => $value) {

                $total = 0;
                $sum = $number + $key;

                $status = isset($detail->sale->deleted_at) ?  'Eliminado':'Activo';

                $sheet->setCellValue("B{$sum}" , $value->id);
                $sheet->setCellValue("C{$sum}" , isset($value->sale->quote->customerOrder) ?
                $value->sale->quote->customerOrder->customer->full_name:null);
                $sheet->setCellValue("D{$sum}" , floatval($value->rate_price));
                $sheet->setCellValue("E{$sum}" , $value->confirmation);
                $sheet->setCellValue("F{$sum}" , isset($value->supplier) ? $value->supplier->name:null);
                $sheet->setCellValue("G{$sum}" , $value->date_payment_supplier);
                $sheet->setCellValue("H{$sum}" , isset($value->supplier) ? $value->supplier->name:null);
                $sheet->setCellValue("I{$sum}" , $value->sale->liquidate == 1 ? 'SI':'NO');
                $sheet->setCellValue("J{$sum}" , $value->liquidate == 1 ? 'SI':'NO');
                $sheet->setCellValue("K{$sum}" , $value->status_pending == 1 ? 'SI':'NO');

                //$fixnumber = intval($sum) - intval($sum - 1);
                $numberTotal = $number - 2;

             }
           })->setFilename($filename)->store('xls', storage_path('excel/exports'));
           return "excel/exports/{$filename}.xls";
          break;
          case 'Reporte de pagos vs pago de confirmaciones':
             $filename = "Reporte de pagos vs pago de confirmaciones_".date('Y_m_d H_i_s');
             Excel::load(storage_path('excel/exports/Reporte de pagos vs pagos de confirmaciones.xls'), function($doc) use($report,$request){
             $sheet = $doc->getActiveSheet();

             $report = Payment::whereBetween('created_at', array("{$request->initial_date} 00:00:00","{$request->final_date} 23:59:59"))->get();

             $group = $report->groupBy('type_of_payment')->map(function ($row) {
                 return $row->sum('price');
             });
             dd($group);


             $number = 6;
             $numberTotal = 0;
               $sheet->setCellValue("B2" , "REPORTE DE PAGOS VS PAGO DE CONFIRMACIONES DEL {$request->initial_date} AL {$request->final_date}");
             foreach ($report as $key => $value) {

                $total = 0;
                $sum = $number + $key;

                $status = isset($detail->sale->deleted_at) ?  'Eliminado':'Activo';

                $sheet->setCellValue("B{$sum}" , $value->id);
                $sheet->setCellValue("C{$sum}" , isset($value->sale->quote->customerOrder) ?
                $value->sale->quote->customerOrder->customer->full_name:null);
                $sheet->setCellValue("D{$sum}" , floatval($value->rate_price));
                $sheet->setCellValue("E{$sum}" , $value->confirmation);
                $sheet->setCellValue("F{$sum}" , isset($value->supplier) ? $value->supplier->name:null);
                $sheet->setCellValue("G{$sum}" , $value->date_payment_supplier);
                $sheet->setCellValue("H{$sum}" , isset($value->supplier) ? $value->supplier->name:null);
                $sheet->setCellValue("I{$sum}" , $value->liquidate == 1 ? 'SI':'NO');
                $sheet->setCellValue("J{$sum}" , $value->status_pending == 1 ? 'SI':'NO');

                //$fixnumber = intval($sum) - intval($sum - 1);
                $numberTotal = $number - 2;

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

    				            	$destinations = isset($value->customerOrder->travel_destination) ?
                            destination::whereIn('id',explode(',',$value->customerOrder->travel_destination))
                            ->get():null;

    				            	$status = null;
    				            	switch ($value['status']) {
    				            		case 2:
    				            			$status = 'Pendiente por cerrar';
    				            			break;
    				            		case 3:
    				            			$status = 'Cotizaci贸n cerrada';
    				            			break;
    				            		case 4:
    				            			$status = 'No viajo';
    				            			break;
    				            	}
    			 					//dd($value['created_at']);
    								setlocale(LC_ALL, 'es_ES');
    								$fecha = Carbon::parse($value['customerOrder']['travel_date']);
    								$fecha->format("F"); // Ingl茅s.
    								$mes = $fecha->formatLocalized('%B');// mes en idioma espa帽ol

    				                $collection->push([
    				                    'Folio'    	  => $value['id'],
    				                    'Agente'	    => isset($value['user']) ? $value['user']['name']:null,
    				                    'Fecha'  	    => $value['created_at'],
    				                    'Mes'		      => $value['customerOrder']['travel_month'],
    				                    'Cliente' 	  => $value['customerOrder']['customer']['full_name'],
    				                    'Destinos'	  => isset($destinations) ?  implode(', ', $destinations->pluck('name')->toArray()):null,
                                'Utilidad'    => $value['markup'],
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
                                'Fecha de solicitud de cotizaci贸n' => "{$value['quote']['customerOrder']['created_at']}",
    				                    'Fecha Venta'  	  => $value['created_at'],
    				                    'Cliente' 	  => $value['quote']['customerOrder']['customer']['full_name'],
    				                    'Fecha de viaje' => "{$value['quote']['travel_date']}",
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
