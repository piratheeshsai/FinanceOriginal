@extends('layouts.app')

@section('breadcrumb')
    Reports
@endsection



@section('page-title')
  Balance Sheet

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('content')
    <div class="container-fluid py-4">





        @livewire('reports.balance-sheet')

    </div>
@endsection



