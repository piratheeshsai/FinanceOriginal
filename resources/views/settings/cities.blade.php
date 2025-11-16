@extends('layouts.app')


@section('breadcrumb')
    Settings
@endsection

@section('page-title')
    Cities management
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container-fluid py-4">



        @livewire('settings.cities')



    </div>

    @endsection
