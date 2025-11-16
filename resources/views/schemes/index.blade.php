@extends('layouts.app')



@section('breadcrumb')
    Loan
@endsection

@section('page-title')
  Scheme
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="container-fluid py-4">

        @livewire('scheme-List')

    </div>
    </div>
    <!-- First Modal: Input Payment Amount -->


    <!-- Payment Input Modal -->
    <div class="modal fade" id="paymentInputModal" tabindex="-1" aria-labelledby="paymentInputModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentInputModalLabel">Enter Payment Amount</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="paymentAmountInput" class="form-label">Payment Amount:</label>
                    <input type="number" id="paymentAmountInput" class="form-control" placeholder="Enter amount"
                        min="0">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="calculateBtn">Check Payment</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="dueDetailsModal" tabindex="-1" aria-labelledby="dueDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dueDetailsModalLabel">Due Payment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Due Amount</th>
                                <th>Interest Amount</th>
                                <th>Total Due</th>
                            </tr>
                        </thead>
                        <tbody id="dueDetailsTable">
                            <!-- Content will be dynamically inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('closeModal', () => {
                var myModalEl = document.getElementById('loanSchemeModal');
                var modal = bootstrap.Modal.getInstance(myModalEl);
                modal.hide();
            });
        });
    </script>

@endsection
