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

Estimado/a {{ $customer->full_name}} espero que te encuentres muy bien, el motivo de mi correo es para proporcionarte tu comprobante de pago.

Por favor, verifica que todo estén correcto y cualquier duda estaremos para servirte.

Importe: $ {{number_format($payment->price,2,'.',',')}} {{$payment->sale->quote->currency}} 

Fecha de pago: {{$payment->created_at}}

Folio de venta: {{$payment->sale->id}} 

Sin más por el momento me despido, bonito día.

De igual manera ponemos a tu disposición el siguiente link para el seguimiento de sus pagos y fechas limites de pago, los datos necesarios para ver su información son su **correo electronico** y el **folio**

New Sun Travel Connect [Entrar](http://appnovasolutions.com/travel/customers/login).


En caso de cancelaciones, imputable al CLIENTE, genera un cargo del 15% del total del hospedaje .
(este cargo puede variar dependiendo del tipo de promoción adquirida).

25% del total del servicio de 20 a 16 días antes de la fecha de pago.

50% del total del servicio de 15 a 10 días antes de la fecha de pago.

75% del total del servicio 9 días antes de la fecha de pago.

100% Si se cancela un día antes o la fecha de pago.

En caso de que el servicio aplique reembolso, este se entregará 15 días hábiles después de su
cancelación.

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
