@extends('layouts.app')

@section('content')
<suppliers-index></suppliers-index>
@endsection

@push('scripts')
    <script>
        console.log('aqui')
    </script>
@endpush
