@extends('layouts.app')

@section('content')
    <payments-create :user_id={{ auth()->user()->id }} :username="'{{ auth()->user()->name }}'"></payments-create>
@endsection