@extends('layouts.app')


@section('breadcrumb')
    Groups
@endsection

@section('page-title')
Groups List
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection




@section('content')

    <div class="container-fluid py-4">

     @livewire('groups.group-list-component')


@endsection
