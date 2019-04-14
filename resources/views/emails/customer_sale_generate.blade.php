<style>
    table{
        margin: 0 !important;
        width: auto !important;
    }

    .justify{
        text-align: justify !important;
        white-space: normal !important;
        -moz-text-align-last: center !important;
        text-align-last: center !important;
    }
</style>

@component('mail::layout')

@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        **{{ config('app.name') }}**
    @endcomponent
@endslot


Estimado {{ $customerSaleGenerate->quote->customerOrder->customer->full_name}}, gracias por elegir a new sun travel. 
A continuación le proporcionamos la siguiente información de su viaje:

*Destino(s)*: 
@foreach ($destinations as $destination)
    * {{ $destination->name }}
@endforeach

*Adultos:* {{$customerSaleGenerate->quote->number_adults}}

@if(isset($customerSaleGenerate->quote->number_childs))
*Menores:* {{ $customerSaleGenerate->quote->number_childs}}
@else
*Menores:* 0
@endif

_Servicios_
@if($customerSaleGenerate->saleDetail)
    @foreach ($customerSaleGenerate->saleDetail as $detail)
    <br>{{ $detail->product->name }}</br>
        {{ $detail->product->description }}
    @endforeach
@endif

*Tarifa:* {{ number_format($customerSaleGenerate->price,2,'.',',') }} {{ $customerSaleGenerate->quote->currency}}

*Fecha de pago (anticipo):*  {{ $customerSaleGenerate->date_advance }}

*Fecha limite de pago:*  {{ $customerSaleGenerate->date_payment_limit }}

*Folio:*  {{$customerSaleGenerate->id}}

De igual manera ponemos a tu disposición el siguiente link para el seguimiento de sus pagos y fechas limites de pago, los datos necesarios para ver su información son su **correo electronico** y el **folio**

New Sun Travel Connect [Entrar](http://appnovasolutions.com/travel/customers/login).

{{ auth()->user()->name }} *Telefono:* {{ auth()->user()->phone }} *Celular:* {{ auth()->user()->cellphone }} **#CreamosMomentos**

@slot('footer')
    @component('mail::footer')
        <div class="justify">
            <span style="color:#2e74b5;font-weight: bold;"> **  AVISO DE CONFIDENCIALIDAD: ** </span> Este correo electrónico, contiene información de carácter confidencial y/o
            privilegiada y/o archivos adjuntos y se envían a la atención única y exclusiva de la(s) persona(s) y/o
            entidad(es) a quien va dirigido. La copia, revisión, uso, revelación y/o distribución de dicha información
            confidencial sin la autorización por escrito de New Sun Travel está prohibido.
            Si usted no es el destinatario a quien se dirige el presente correo, favor de contactar al remitente
            respondiendo del presente correo y eliminar el correo original incluyendo sus archivos, así como cualesquiera
            copia del mismo. Mediante la recepción del presente correo usted reconoce y acepta que en caso de incumplimiento
            de su parte y/o de sus representantes a los términos antes mencionados, New Sun Travel tendrá derecho al reclamo por daños y perjuicios que esto le cause.
            <img src="{{ asset('/public/img/logo_dark.png') }}" style="display:block; margin-left: auto; margin-right: auto;" width="130" height="auto" data-auto-embed="attachment"/>
        </div>
    @endcomponent
@endslot
<span style="color:orange"> **{{ config('app.name') }}**</span>

@endcomponent
