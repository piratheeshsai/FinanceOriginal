@extends('layouts.app')

@section('breadcrumb')
    Reports
@endsection



@section('page-title')
  Loan report

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('content')
    <div class="container-fluid py-4">




        @livewire('reports.loan-report')


    </div>
@endsection
