@extends('layouts.app')

@section('breadcrumb')
    Branches
@endsection

@section('page-title')
  loan Progress

@endsection


@section('content')

    <div class="container-fluid py-4">
        @livewire('loan.loan-progress')

    </div>
    
@endsection
