@extends('layouts.app')

@section('content')
    <branches-index></branches-index>
@endsection

@push('scripts')
    <script>
        console.log('aqui')
    </script>
@endpush
