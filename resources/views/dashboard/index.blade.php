@extends('layouts.app')


@section('breadcrumb')
    Dashboard
@endsection

@section('page-title')
Dashboard
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('content')



<div class="container-fluid py-4">
    @livewire('dashboard.main-dashboard')

  
</div>




  </script>
@endsection
