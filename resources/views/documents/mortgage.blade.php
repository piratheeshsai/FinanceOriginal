@extends('layouts.app')

@section('breadcrumb')
    Documents
@endsection

@section('page-title')
   Agreement Document
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')

   <div class="container-fluid py-4">
        @livewire('documents.mortgage')

    </div>
@endsection


