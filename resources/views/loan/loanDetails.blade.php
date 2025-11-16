@extends('layouts.app')


@section('breadcrumb')
    Loan
@endsection

@section('page-title')
    New Loan
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container-fluid py-4">

    <div class="container">

            <div class="col-lg-12 mt-lg-0 mt-4">
                <!-- Card Profile -->
                <div class="card card-body" id="profile">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-sm-auto col-4">

                            <div class="avatar avatar-xl position-relative">
                                @if($loan->customer->photo && file_exists(public_path('storage/' . $loan->customer->photo)))
                                    <img src="{{ asset('storage/' . $loan->customer->photo) }}"
                                         alt="{{ $loan->customer->full_name }}"
                                         class="w-100 border-radius-lg shadow-sm">
                                @else
                                    <div class="border-radius-lg shadow-sm d-flex align-items-center justify-content-center bg-info text-white w-100 h-100"
                                         style="font-size: 1.5rem;">
                                        {{ strtoupper(substr($loan->customer->full_name, 0, 1)) }}
                                        @if(strpos($loan->customer->full_name, ' ') !== false)
                                            {{ strtoupper(substr($loan->customer->full_name, strpos($loan->customer->full_name, ' ') + 1, 1)) }}
                                        @endif
                                    </div>
                                @endif
                            </div>

                        </div>
                        <div class="col-sm-auto col-8 my-auto">
                            <div class="h-100">
                                <h5 class="mb-1 font-weight-bolder">
                                    {{ $loan->customer->full_name }}
                                </h5>
                                <p class="mb-0 font-weight-bold text-sm">
                                    {{ $loan->customer->customer_no }}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-auto ms-sm-auto mt-sm-0 mt-3 d-flex">
                            <a href="{{ route('customer.show', $loan->customer->id) }}"
                                class="btn btn-primary px-4 py-2 rounded-pill shadow-sm hover-shadow-lg transition-all d-flex align-items-center">
                                View
                             </a>
                        </div>
                    </div>

                </div>
            </div>

            <br />
            @livewire('loanDetails.loan-details',['loanId' => $loan->id])
    </div>


@endsection
