@extends('layouts.app')


@section('breadcrumb')
    Users
@endsection

@section('page-title')
    Settings
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container-fluid py-4">



        @livewire('settings.company-settings')



    </div>

    @endsection

