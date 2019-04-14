@extends('errors::illustrated-layout')

@section('code', '401')
@section('title', __('No autorizado'))

@section('image')
    <div style="background-image: url({{ asset('public/svg/403.svg') }});" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection

@section('message', __('Lo sentimos, no estás autorizado para acceder a esta página.'))
