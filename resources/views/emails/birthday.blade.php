@component('mail::message')
Feliz cumpleaños {{$customer->full_name}}

Muchas felicidades por parte de todo el equipo de
<br>
{{ config('app.name') }}
@endcomponent
