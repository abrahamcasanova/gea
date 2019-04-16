<html lang="es">
<head>
    <style type="text/css">
        @page { margin: 75px 2px}
        header { position: fixed; margin-bottom:1px; top: -65px; left: 5px; right: 0px; height: 53px; }
        footer { text-align: center !important; position: fixed; bottom: 0px left: 0px; right: 0px; height: 25px; }
        .page-break {
            page-break-after: always;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" />
</head>
<body>
  <header style="">
    <center>
      <img width="130" src="{{ public_path()}}/img/logo_dark.png">
    </center>
  </header>
  <footer>
      Asesor: {{$user->name}} Oficina: {{$user->phone}} Celular: {{$user->cellphone}} #CreamosMomentos
  </footer>
  <main http-equiv="Content-Type" content="text/html;charset=utf-8">
      <div class="container">
        <div class="row" style="margin-top: 0px;">
          <div class="col-sm-12">
              Gracias por reservar con New Sun Travel .
              <br>
              Este es tu comprobante de viaje. Deberás mostrarlo junto con una identificación oficial vigente para reclamar los servicios reservados.
          </div>
        </div>
        <div class="row">
          <div class="fix form-group col-md-12">
              <div class="panel panel-info">
                  <div class="panel-heading">
                    <span>
                      <h5 class="panel-title">
                        <i style='color:#2196f3;margin-top:5px;' class="fa-2x glyphicon glyphicon-exclamation-sign"></i>
                        <label class="fix">Atención a clientes New Sun Travel</label>
                      </h5> 
                    </span> 
                  </div>
                  <div class="panel-body">
                      <p>
                        <strong>Oficina:</strong> 999923-26-31
                        <br><strong>Emergencia:</strong> +52 (9992)451907
                      </p>
                  </div>
              </div>
          </div>
        </div>

        <div class="row" style="margin-top:-25px;">
          <div class="fix form-group col-md-12">
              <div class="panel panel-primary">
                  <div class="panel-heading">
                    <span>
                      <h5 class="panel-title">
                        <i style='color:#DDDDDD;margin-top:5px;' class="fa-2x glyphicon glyphicon-bed"></i>
                        <label class="fix text-capitalize">{{implode(', ', $details->pluck('product.name')->toArray())}}</label>
                      </h5> 
                    </span> 
                  </div>
                  <div class="panel-body">
                      <p>
                        <strong>Estancia:</strong> {{$sale->quote->customerOrder->travel_date}} al {{$sale->quote->customerOrder->travel_end_date}} 
                        <strong>Destino:</strong> {{ implode(',', $destinations->pluck('name')->toArray()) }}
                        <br>
                        <strong>Adultos:</strong> {{$sale->quote->number_adults}}
                        <strong>Niños:</strong> {{isset($sale->quote->number_childs) ? $sale->quote->number_childs:'Ninguno'}}<br>
                        <strong>Reservado para:</strong> {{$sale->quote->customerOrder->customer->full_name}}
                      </p>
                      <table class="table">
                          <thead>
                            <tr>
                              <th class="">
                                <a>Nombre</a>
                              </th>
                              <th class="">
                                <a>Descripción</a>
                              </th>
                              <th>
                                <a>Confirmación</a>
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                              @if(isset($details))
                                  @foreach ($details as $detail)
                                  <tr>
                                      <td class="">
                                        <div class="media">
                                          <div>{{$detail->product->name}}</div>
                                          <div class="medium">
                                            @if($detail->quote->quoteDetail)
                                              {{$detail->quote->quoteDetail->description}}
                                            @endif
                                          </div>
                                        </div>
                                      </td>
                                      <td class="">
                                        {{$detail->product->description}}
                                      </td>
                                      <td class="">
                                        {{$detail->confirmation}}
                                      </td>
                                  </tr>
                                  @endforeach
                              @endif
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
        </div>
        <div class="row" style="margin-top:-25px;">
          <div class="fix form-group col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading"> 
                  <h5 class="panel-title">
                    <label class="fix">Nota Addicional</label>
                  </h5> 
                </div>
                <div class="panel-body">
                    {{$sale->note}}
                </div>
            </div>
          </div>
        </div>
        <div class="row" style="margin-top:-25px;">
          <div class="fix form-group col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading"> 
                  <h5 class="panel-title">
                    <label class="fix">Tabla de penalización</label>
                  </h5> 
                </div>
                <div class="panel-body">
                  <table class="table">
                    <thead>
                      <tr>
                        <th class="">
                          <a>Fecha de cancelación</a>
                        </th>
                        <th>
                          <a>Penalidad</a>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="">
                          15 días o más antes de la fecha de llegada
                        </td>
                        <td class="">
                          15%
                        </td>
                      </tr>
                      <tr>
                        <td class="">
                          3 a 14 días antes de la fecha de llegada
                        </td>
                        <td class="">
                          2 noches
                        </td>
                      </tr>
                      <tr>
                        <td class="">
                          0 a 2 días antes de la fecha de llegada
                        </td>
                        <td class="">
                          100%
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div>
                    <strong>Nota:</strong>
                    En caso de que tengas que irte del hotel antes de que termine tu estancia (salida anticipada), o no te presentes al hotel (conocido como no show) se considera como cancelación y no aplican reembolsos. Si reduces el número de huéspedes una vez pagada la reservación, es decisión del hotel aplicar penalidades o reembolsos.<br>
                    <strong>Impuesto hoteles Caribe </strong> ***Por disposición del municipio local, el hotel cobrará un impuesto obligatorio entre $20.00 y $25.00 MXN por habitación, por noche, a pagar directamente en el establecimiento.
                    Aplica para Reservación de hotel Hora de entrada: 15:00 Hora de salida: 12:00
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
  </main>
</body>
</html>
