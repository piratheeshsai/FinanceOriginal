@extends('layouts.app')


@section('breadcrumb')
    Loan
@endsection

@section('page-title')
  Loan Approvel
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')


    <div class="container-fluid py-4">


        @livewire('loan.loan-approval')


    </div>






@endsection
