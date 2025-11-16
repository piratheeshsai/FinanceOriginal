@extends('layouts.app')

@section('breadcrumb')
    Accounting
@endsection



@section('page-title')
Daily Cash Summary
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('content')

    <div class="container-fluid py-4">


        @livewire('account.daily-cash-summary')


    </div>

@endsection
