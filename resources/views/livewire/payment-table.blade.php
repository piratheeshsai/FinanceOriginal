<div>
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Calculate Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" wire:model="amount" class="form-control" id="amount" required>
                    </div>
                    <button type="button" wire:click="calculateDue" class="btn btn-primary">Check</button>

                    @if($dueAmount)
                        <div class="mt-4">
                            <h5>Payment Details</h5>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Due Amount</th>
                                        <th scope="col">Due Interest</th>
                                        <th scope="col">Total Due</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ number_format($dueAmount, 2) }}</td>
                                        <td>{{ number_format($dueInterest, 2) }}</td>
                                        <td>{{ number_format($totalDue, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
