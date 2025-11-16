@extends('layouts.app')


@section('breadcrumb')
    Loan
@endsection

@section('page-title')
   Loan List
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')


<div class="container-fluid py-4">

    @if (session('status'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('status') }}'
        });
    </script>
@elseif (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}'
        });
    </script>
@endif


@livewire('loan.loan-list')


</div>
@endsection
