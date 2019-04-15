@extends('layouts.app')

@section('content')
<div>
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    @can('see-calendar')
        <v-layout wrap>
          <v-container>
            <v-layout justify-center>
                <calendar :user_id={{ auth()->user()->id }}></calendar>
            </v-layout>
          </v-container>
        </v-layout>
    @endcan 
    <div class="container">
        <div class="card-body">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif
            <div class="row justify-content-md-center">
                <div class="col-sm-6 col-xl-6 col-md-6">
                    @can('see-count-tracing')
                        <tracing-count></tracing-count>
                    @endcan
                </div>
                <div class="col-sm-6 col-xl-6 col-md-6">
                    @can('see-count-sales')
                        <sold-count></sold-count>
                    @endcan
                </div>
                <div class="col-sm-6 col-xl-6 col-md-6">
                    @can('see-count-quotes')
                        <quotes-count></quotes-count>
                    @endcan
                </div>
                <div class="col-sm-6 col-xl-6 col-md-6">
                    @can('see-count-quotes')
                        <quotes></quotes>
                    @endcan
                </div>
                <div class="col-sm-6 col-xl-6 col-md-6">
                    @can('see-top-products')
                        <top-products></top-products>
                    @endcan
                </div>
                <div class="col-sm-12 col-xl-12 col-md-12">
                    @can('see-tracing')
                    <prospectings-count></prospectings-count>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
