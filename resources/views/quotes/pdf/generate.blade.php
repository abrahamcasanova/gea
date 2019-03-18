<html lang="es">
<head>
    <style type="text/css">
        @page { margin: 65px 2px}
        header { position: fixed; margin-bottom:1px; top: -65px; left: 5px; right: 0px; height: 53px; }
        footer { text-align: center !important; position: fixed; bottom: -70px; left: 0px; right: 0px; height: 25px; }
        @font-face {
          font-family: 'Helvetica' !important;
          font-weight: normal;
          font-size: 11px !important;
        }
        pre{
          font-family: Helvetica !important;
          overflow: auto;
          width: auto;
          padding: -2px;
          background-color: #fff !important;
        }
        .fix{
            font-family: Helvetica !important;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" />
</head>
<body>
  <header style=""><img width="80" src="{{ public_path()}}/img/logo_dark.png"></header>
  <footer>
      Asesor: {{$user->name}} Oficina: {{$user->phone}} Celular: {{$user->cellphone}} #CreamosMomentos
  </footer>
  <main http-equiv="Content-Type" content="text/html;charset=utf-8">
      <div class="container">
        <div class="row" style="margin-top:-20px !important;">
          <div class="col-sm-2">
              <span><strong>Folio:</strong>{{$quote->id}}</span>
          </div>
          <div class="col-sm-offset-5 col-sm-5">
              <span><strong>Fecha de la propuesta:</strong> {{$quote->proposal_date}}</span>
          </div>
          <div class="col-sm-6">
                <span><strong>Cliente:</strong> {{$quote->customerOrder->customer->full_name}}</span>
            </div>
        </div>
        <div class="row">
          <div class="form-group col-sm-4">
              <span><strong>Fecha de viaje:</strong> {{$quote->travel_date}}</span>
          </div>
          <div class="form-group col-sm-2">
              <span><strong>Adultos:</strong> {{$quote->number_adults}}</span>
          </div>
          <div class="form-group col-sm-2">
              <span><strong>Menores:</strong> {{$quote->number_childs}}</span>
          </div>
          <div class="form-group col-sm-2">
              <span><strong>Destino:</strong> {{$quote->customerOrder->travel_destination}}</span>
          </div>
        </div>
        <div class="row" style="margin-top: -10px;">
            <div class="form-group col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <center>
                            <div class="panel-heading"> 
                                <h5 class="panel-title"><label class="fix">VIAJE DE VACACIONES</label></h5> 
                            </div>                        
                        </center>
                    </div>
                    <div class="panel-body">
                      <div class="table-responsive">
                        <table class="table">
                          <thead>
                            <tr>
                              <th class="">
                                <a>Nombre</a>
                              </th>
                              <th>
                                <a>Categoria</a>
                              </th>
                              <th>
                                <a>Precio</a>
                              </th>
                              <th>
                                <a>Nota</a>
                              </th>
                              <th>
                                <a>Foto</a>
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                              @if(isset($quote->quoteDetails))
                                  @foreach ($quote->quoteDetails as $quoteDetail)
                                      <tr>
                                          <td class="">{{ $quoteDetail->product->name  }}</td>
                                          <td class="">
                                              @for ($i = 0; $i < 5; $i++)
                                                  @if($i < $quoteDetail->product->category)
                                                      <i class="glyphicon glyphicon-star" style="color:yellow"></i>
                                                  @else
                                                      <i class="glyphicon glyphicon-star-empty"></i>
                                                  @endif
                                              @endfor
                                          </td>
                                          <td class="">$ {{ number_format($quoteDetail->price,2) }}</td>
                                          <td class="">{{ $quoteDetail->product->description }}</td>
                                          <td>
                                            <div class="media">
                                              <div class="avatar_product float-left mr-3">
                                                  <img style='width:75px' src="../storage/app/public/product_img/{{$quoteDetail->product->url_image}}">
                                              </div>
                                            </div>
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
            <div class="fix form-group col-md-12" style="margin-top:-55px;">
                <div class="panel panel-default">
                    <div class="panel-heading"> <h5 class="panel-title"><label class="fix">PAQUETE INCLUYE</label></h5> </div>
                    <div class="panel-body">
                        <pre class="fix"> {!! $quote->include !!}
                        </pre>
                    </div>
                </div>
            </div>
            <div class="fix form-group col-md-12" style="margin-top:-55px;">
                <div class="panel panel-success">
                    <div class="panel-heading"> <h5 class="panel-title"><label class="fix">POLITICAS DE RESERVACIÓN</label></h5> </div>
                    <div class="panel-body">
                        <pre class="fix"> {!! $quote->policy !!}
                        </pre>
                    </div>
                </div>
            </div>
            <div class="fix form-group col-md-12" style="margin-top:-55px;">
                <div class="panel panel-info">
                    <div class="panel-heading"> <h5 class="panel-title"><label class="fix">FORMAS DE PAGO</label></h5> </div>
                    <div class="panel-body">
                        <pre class="fix"> {!! $quote->payment !!}
                        </pre>
                    </div>
                </div>
            </div>
        </div>
      </div>
  </main>
</body>
</html>
