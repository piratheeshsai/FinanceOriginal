@extends('layouts.app')


@section('breadcrumb')
    Customer
@endsection

@section('page-title')
   Detail
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')

    <div class="container-fluid py-4">

        @livewire('customer-details', ['customerId' => $customer->id])

        

    </div>

@endsection
