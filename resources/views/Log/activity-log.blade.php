@extends('layouts.app')

@section('breadcrumb')
    Reports
@endsection



@section('page-title')
Activity

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('content')
    <div class="container-fluid py-4">





        @livewire('log.unified-activity-log')

    </div>
@endsection



