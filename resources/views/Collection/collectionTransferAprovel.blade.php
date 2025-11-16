@extends('layouts.app')


@section('breadcrumb')
    Collection
@endsection

@section('page-title')
    Transfer Approvel
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')

<div class="container-fluid py-4">
    @livewire('loan-details.collection-transfer-aprovel')

</div>


@endsection


















