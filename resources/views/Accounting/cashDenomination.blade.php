@extends('layouts.app')

@section('breadcrumb')
    Accounting
@endsection



@section('page-title')
CashDenomination

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('content')

    <div class="container-fluid py-4">

        {{-- <h1>Accounting</h1> --}}
        @livewire('account.cash-denomination')


    </div>

@endsection
