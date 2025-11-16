@extends('layouts.app')

@section('breadcrumb')
    Accounting
@endsection



@section('page-title')
Payment Category
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('content')

    <div class="container-fluid py-4">


        @livewire('account.payment-category')


    </div>

@endsection
