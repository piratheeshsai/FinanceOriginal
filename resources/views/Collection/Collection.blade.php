@extends('layouts.app')


@section('breadcrumb')
    Collection
@endsection

@section('page-title')
    Collection Due
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')


    <div class="container-fluid py-4">
        @livewire('loan.loan-collection-table')
    </div>

    

@endsection
