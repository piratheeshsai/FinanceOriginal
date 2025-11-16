@extends('layouts.app')


@section('breadcrumb')
    Customers
@endsection

@section('page-title')
    Customer Table
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container-fluid py-4">
        @if (session('success'))
            <script>
                Swal.fire({
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                Swal.fire({
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonText: 'Try Again'
                });
            </script>
        @endif
        <style>
            /* Hide the default arrow */
            .custom-select {
                -webkit-appearance: none;
                /* Chrome, Safari, Edge */
                -moz-appearance: none;
                /* Firefox */
                appearance: none;
                background: white;
                /* Ensures a clean background */
                padding-right: 2rem;
                /* Create space for the custom arrow */
                border: 1px solid #ced4da;
                border-radius: 0.25rem;
            }

            /* Add a custom arrow using a pseudo-element */
            .custom-select::after {
                content: '\25BC';
                /* Unicode for down arrow */
                font-size: 0.8rem;
                position: absolute;
                right: 1rem;
                /* Position the arrow */
                top: 50%;
                transform: translateY(-50%);
                pointer-events: none;
                /* Prevents arrow interaction */
                color: #666;
            }
        </style>



        @livewire('customer-table')




        <script>
            var win = navigator.platform.indexOf('Win') > -1;
            if (win && document.querySelector('#sidenav-scrollbar')) {
                var options = {
                    damping: '0.5'
                }
                Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
            }


            $(document).ready(function() {
                $('#per_page').select2({
                    theme: 'bootstrap-5', // Optional Bootstrap 5 theme
                    width: 'auto', // To make it adapt the size
                });
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const deleteButtons = document.querySelectorAll('.delete-button');

                deleteButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const formId = `deleteForm-${this.getAttribute('data-id')}`;
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "This action cannot be undone!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById(formId).submit();
                            }
                        });
                    });
                });
            });
        </script>
    @endsection
