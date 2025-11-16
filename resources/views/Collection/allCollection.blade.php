@extends('layouts.app')


@section('breadcrumb')
    Collection
@endsection

@section('page-title')
    Collected List
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')


    <div class="container-fluid py-4">
        @livewire('loan-details.all-collections')

    </div>


@endsection
