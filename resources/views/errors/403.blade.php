@extends('errors::illustrated-layout')

@section('code', '403')
@section('title', __('Prohibido'))

@section('image')
    <div style="background-image: url({{ asset('public/svg/403.svg') }});" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
    </div>
@endsection

@section('message', __($exception->getMessage() ?: 'Lo sentimos, está prohibido acceder a esta página.'))
