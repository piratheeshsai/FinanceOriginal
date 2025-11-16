@extends('layouts.app')

@section('breadcrumb')
    Documents
@endsection

@section('page-title')
   Voucher
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')

   <div class="container-fluid py-4">

        @livewire('documents.Voucher')

    </div>
@endsection


