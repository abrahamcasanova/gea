<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>COMPROBANTE DE PAGO FOLIO {{$payment->id}}</title>

    <style type="text/css">
        @page {
            margin: 0px;
        }

        body {
            margin: 0px;
        }

        * {
            font-family: Verdana, Arial, sans-serif;
        }

        a {
            color: #000000;
            text-decoration: none;
        }

        table {
            font-size: x-small;
        }

        .textUppercase{
            text-transform: uppercase;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .invoice table {
            margin: 15px;
        }

        .invoice h4 {
            margin-left: 15px;
        }

        .information {
            background-color: #FFFFFF;
            color: #000000;
        }

        .information .logo {
            margin: 5px;
        }

        .information table {
            padding: 5px;
        }
    </style>

</head>
<body>

<div class="information">
    <table width="100%">
        <tr>
            <td align="left" style="width: 40%;">
                <h4 class="textUppercase">{{$payment->sale->quote->customerOrder->customer->full_name}}</h4>
                <h4>Moneda: {{$payment->sale->quote->currency}}</h4>
                <h4>Destinos: {{ implode(',', $destinations->pluck('name')->toArray()) }}</h4>
                <h4>Fecha de viaje: {{ $payment->sale->quote->customerOrder->travel_date }} AL {{ $payment->sale->quote->customerOrder->travel_end_date }}
                </h4>
            </td>
            <td align="center">
                <img src="{{ public_path()}}/img/logo_dark.png" alt="Logo" width="120" class="logo"/>
            </td>
            <td align="right" style="width: 50%;">
                <strong>FOLIO: {{$payment->id}}</strong>
                <h4>FECHA: {{date('Y-m-d H:i:s')}}</h4>
                <h4>FECHA LIMITE DE PAGO: {{ $payment->sale->date_payment_limit }}</h4>
                <h4>New Sun travel</h4>
                <pre>
                    https://newsuntravel.com.mx
                </pre>
            </td>
        </tr>

    </table>
</div>


<br/>

<div class="invoice">
    <h4>Detalle</h4>
    <table width="100%">
        <thead>
        <tr>
            <th>Folio</th>
            <th>Forma de pago</th>
            @if($payment->authorization_number)
                <th>No. Autorización</th>    
            @endif
            @if($payment->percentage)
                <th>Porcentaje</th>
            @endif
            @if($payment->deposit_date)
                    <th>Fecha Deposito</th>
                @endif 
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{$payment->id}}</td>
            <td>{{$payment->type_of_payment}}</td>
            @if($payment->authorization_number)
                <td>{{$payment->authorization_number}}</td>
            @endif
            @if($payment->percentage)
                <td>{{$payment->percentage}}%</td>
            @endif
            @if($payment->deposit_date)
                <td>{{$payment->deposit_date}}</td>
            @endif
            <td align="left">$ {{ number_format($payment->price,2,'.',',') }}</td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="2"></td>
            <td align="left">Total</td>
            <td align="left" class="gray">$ {{ number_format($payment->price,2,'.',',') }}</td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td align="left">Saldo</td>
            <td align="left" class="gray">$ {{ number_format($balance,2,'.',',') }}</td>
        </tr>
        <tr style="margin-top: 25px;">
            <td colspan="3">
                <div class="form-group col-sm-12">
                  <span><strong>Comentario:</strong> {{$payment->comments}}</span>
                </div>
            </td>
        </tr>
        </tfoot>
    </table>
</div>
<div class="information" style="position: absolute; bottom: 0;">
    <table width="100%">
        <tr>
            <td colspan="2" align="justify" style="width: 100%">
                <span>
                    En caso de cancelaciones, imputable al CLIENTE, genera un cargo del 15% del total del hospedaje .(este cargo puede variar dependiendo del tipo de promoción adquirida).25% del total del servicio de 20 a 16 días antes de la fecha de pago.50% del total del servicio de 15 a 10 días antes de la fecha de pago.75% del total del servicio 9 días antes de la fecha de pago.100% Si se cancela un día antes o la fecha de pago.En caso de que el servicio aplique reembolso, este se entregará 15 días hábiles después de su cancelación.
                </span>
            </td>
        </tr>
        <tr>
            <td align="left" style="width: 50%;">
                &copy; {{ date('Y') }} - All rights reserved.
            </td>
            <td align="right" style="width: 50%;">
                Asesor: {{$user->name}} Oficina: {{$user->phone}} Celular: {{$user->cellphone}}
                #CreamosMomentos
            </td>
        </tr>

    </table>
</div>
</body>
</html>