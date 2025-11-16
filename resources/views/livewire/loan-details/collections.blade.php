


<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white">Collection Records</h5>
            </div>
            <div class="card-body px-3 pt-3 pb-2">
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 15%;">Date</th>
                                <th style="width: 20%;">Collected By</th>
                                <th style="width: 15%;">Method</th>
                                <th style="width: 15%;">Amount</th>
                                <th style="width: 15%;">Action</th>
                                <th style="width: 15%;">Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($collections as $collection)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-info text-white">
                                            {{ $collection->id }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-secondary text-sm font-weight-bold">
                                            {{ \Carbon\Carbon::parse($collection->collection_date)->format('m/d/Y') }}
                                        </span>
                                    </td>
                                    <td class="text-wrap">
                                        <strong>{{ $collection->collector->name }}</strong>
                                    </td>
                                    <td>{{ $collection->collection_method }}</td>
                                    <td class="text-success fw-bold">
                                        {{ number_format($collection->collected_amount, 2) }}
                                    </td>
                                    <td>
                                    <span class="text-danger cursor-pointer clickable-text"
                                    onclick="confirmAction({
                                      title: 'Delete Collection?',
                                      text: 'Are you sure you want to delete this collection and related invoices?',
                                      icon: 'warning',
                                      confirmButtonText: 'Yes, delete it!'
                                    }).then((result) => {
                                      if (result.isConfirmed) {
                                          Livewire.dispatch('deleteCollection', { collectionId: {{ $collection->id }} });
                                      }
                                    })">
                                  Delete
                              </span>
                            </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <!-- Invoice Button (Print POS Invoice) -->
                                            <button class="btn btn-white btn-sm px-2" wire:click="generateInvoice({{ $collection->id }}, 'pos')">
                                                <i class="fa-solid fa-file-invoice text-primary"></i>
                                            </button>

                                            <!-- PDF Button (Download A4 Invoice) -->
                                            <button class="btn btn-white btn-sm px-2" wire:click="generateInvoice({{ $collection->id }}, 'a4')">
                                                <i class="fa-solid fa-file-pdf text-danger"></i>
                                            </button>

                                            <!-- SMS Button -->
                                            <button class="btn btn-white btn-sm px-2">
                                                <i class="fa-solid fa-comment-sms text-success"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
