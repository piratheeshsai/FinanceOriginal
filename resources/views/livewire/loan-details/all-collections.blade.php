<div class="container-fluid py-2">
    <div class="row g-2">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <!-- Streamlined Header -->
          <div class="card-header bg-primary py-2 d-flex justify-content-between align-items-center border-0">
            <h6 class="mb-0 text-white fs-6">
              <i class="bi bi-cash-stack me-1"></i>Loan Collections
            </h6>
            <div class="d-flex align-items-center">
              <div class="dropdown">
                <button class="btn btn-sm btn-light dropdown-toggle py-1 px-2" type="button" data-bs-toggle="dropdown">
                  <i class="bi bi-download me-1 small"></i><span class="small">Export</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                  <li><a class="dropdown-item small py-1" wire:click="exportExcel"><i class="bi bi-file-earmark-excel text-success me-1"></i>Excel</a></li>
                  <li><a class="dropdown-item small py-1" wire:click="exportPDF"><i class="bi bi-file-pdf text-danger me-1"></i>PDF</a></li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Compact Filters -->
          <div class="card-body bg-light border-bottom py-2">
            <div class="row g-2">
              <div class="col-md-3">
                <select wire:model.live="loan_type" class="form-select form-select-sm">
                  <option value="">All Loan Types</option>
                  <option value="individual">Individual Loans</option>
                  <option value="group">Group Loans</option>
                </select>
              </div>
              <div class="col-md-5">
                <div class="input-group input-group-sm">
                  <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                  <input type="text" class="form-control form-control-sm border-start-0" placeholder="Search by loan number, customer, or staff" wire:model.live.debounce.500ms="search">
                </div>
              </div>
              <div class="col-md-2">
                <input type="date" class="form-control form-control-sm" wire:model.live="from_date" wire:change="updateDateFilter" placeholder="From Date">
              </div>
              <div class="col-md-2">
                <input type="date" class="form-control form-control-sm" wire:model.live="to_date" wire:change="updateDateFilter" placeholder="To Date">
              </div>
            </div>
          </div>

          <!-- Table with smaller text -->
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover table-sm align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th class="small py-2" >
                      Date
                    </th>
                    <th class="small py-2" >
                      Name
                    </th>
                    <th class="small py-2">
                      Loan
                    </th>
                    <th class="small py-2">Staff</th>
                    <th class="small py-2">Status</th>
                    <th class="small py-2">Method</th>
                    <th class="small py-2" >
                      Amount 
                    </th>
                    <th class="small py-2 text-center">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($collections as $collection)
                    <tr wire:key="{{ $collection->id }}">
                      <td class="small">{{ $collection->collection_date->format('m/d/Y') }}</td>
                      <td class="small">{{ $collection->loan->customer->full_name }}</td>
                      <td class="small text-primary">{{ $collection->loan->loan_number }}</td>
                      <td class="small">{{ $collection->staff->name }}</td>
                      <td>
                        @php
                          $status = optional($collection->staffCollectionStatus)->status;
                          $statusColors = [
                            'Pending' => 'bg-warning',
                            'Waiting to Accept' => 'bg-primary',
                            'Transferred' => 'bg-success',
                          ];
                        @endphp
                        @if($status)
                          <span class="badge {{ $statusColors[$status] ?? 'bg-secondary' }} small">{{ $status }}</span>
                        @else
                          <span class="badge bg-secondary small">No Status</span>
                        @endif
                      </td>
                      <td><span class="badge bg-info text-dark small">{{ $collection->collection_method }}</span></td>
                      <td class="small text-end text-success fw-bold">{{ number_format($collection->collected_amount, 2) }}</td>
                      <td class="text-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <button class="btn btn-outline-primary btn-sm py-1 px-2" wire:click="generateInvoice({{ $collection->id }}, 'pos')" title="POS Invoice">
                                <i class="bi bi-receipt small"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm py-1 px-2" wire:click="generateInvoice({{ $collection->id }}, 'a4')" title="PDF Invoice">
                                <i class="bi bi-file-pdf small"></i>
                            </button>

                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="8" class="text-center py-3">
                        <div class="alert alert-info py-2 px-3 mb-0 small">
                          <i class="bi bi-info-circle me-1"></i>No collections found.
                        </div>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- Compact Pagination -->
          <div class="card-footer bg-white py-2 d-flex justify-content-between align-items-center">
            <div class="text-muted small fs-7">
              Showing {{ $collections->firstItem() ?? 0 }} to {{ $collections->lastItem() ?? 0 }} of {{ $collections->total() ?? 0 }} entries
            </div>
            <div class="pagination pagination-sm">
              {{ $collections->links('livewire::bootstrap') }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <style>
      .table th {
        cursor: pointer;
        user-select: none;
        font-size: 0.8rem;
      }
      .table th:hover {
        background-color: rgba(0,0,0,0.05);
      }
      .fs-7 {
        font-size: 0.75rem;
      }
      .table td {
        padding-top: 0.3rem;
        padding-bottom: 0.3rem;
      }
      .form-select-sm, .form-control-sm {
        font-size: 0.8rem;
      }
      .badge {
        font-size: 0.7rem;
      }
      .btn-group-sm .btn {
        line-height: 1;
      }
    </style>
  </div>

  <script>
    document.addEventListener('livewire:initialized', () => {
      // Add wire:key attribute to prevent duplicate renders
      Livewire.hook('element.initialized', (el) => {
        if (!el.getAttribute('wire:key') && el.getAttribute('wire:id')) {
          el.setAttribute('wire:key', el.getAttribute('wire:id'));
        }
      });

      Livewire.on('open-invoice', (event) => {
        window.open(event.url, '_blank');
      });
    });
  </script>
