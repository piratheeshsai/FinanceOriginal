@extends('layouts.app')

@section('breadcrumb')
    Accounting
@endsection



@section('page-title')
Suppliers
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('content')

    <div class="container-fluid py-4">


        @livewire('account.payment-supplier')


    </div>

@endsection
