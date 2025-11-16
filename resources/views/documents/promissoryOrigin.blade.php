@extends('layouts.app')

@section('breadcrumb')
    Documents
@endsection

@section('page-title')
   Promissory Documents
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')

   <div class="container-fluid py-4">
        @livewire('documents.promissory-origin')

    </div>
@endsection


