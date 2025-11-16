@extends('layouts.app')

@section('breadcrumb')
    Accounting
@endsection



@section('page-title')
Transaction
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('content')

    <div class="container-fluid py-4">


        @livewire('account.cash-transfer')



        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


    </div>

@endsection
