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


Estimado {{$customerOrder->customer->full_name}}

Hemos recibido su solicitud de cotización y le estaremos dando seguimiento a la brevedad.

Si tenemos alguna duda lo estaremos contactando, en caso de no recibir su cotización en un lapso de 24 horas favor de contactarnos.
<br>

@if(isset(auth()->user))
{{ auth()->user()->name }} *Telefono:* {{ auth()->user()->phone }} *Celular:* {{ auth()->user()->cellphone }} **#CreamosMomentos**
@else
*Oficinas:* 9-23-26-31 **#CreamosMomentos**
@endif

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
