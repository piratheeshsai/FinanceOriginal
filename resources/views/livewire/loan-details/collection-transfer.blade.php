<div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-11">
        <!-- Page Title -->
        <div class="d-flex align-items-center mb-4">
          <i class="bi bi-arrow-left-right text-primary fs-3 me-3"></i>
          <h4 class="fw-bold m-0">Funds Transfer Management</h4>
        </div>

        <!-- Main Card -->
        <div class="card shadow border-0 rounded-4 overflow-hidden">
          <!-- Card Header with Progress Indicator -->
          <div class="card-header bg-white border-bottom p-0">
            <div class="d-flex">
              <div class="px-4 py-3 border-bottom border-primary border-3 text-primary fw-semibold">
                Transfer
              </div>

            </div>
          </div>

          <!-- Card Body -->
          <div class="card-body p-4 p-lg-5">
            <!-- Form Description -->
            <p class="text-muted mb-4">
              Transfer collected funds from field agents to the central cashier. All transfers are logged and require proper verification.
            </p>

            <!-- Form Content -->
            <div class="row g-4">
              <!-- Left Column -->
              <div class="col-md-6">
                <div class="card bg-light border-0 h-100">
                  <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Source Information</h6>

                    <div class="form-floating mb-4">
                      <select class="form-select border-0 bg-white shadow-sm" id="collector" wire:model="selectedCollector" wire:change="updateCollectedAmount">
                        @foreach($staffList as $id => $name)
                          <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                      </select>
                      <label for="collector" class="text-muted">Select Collector</label>
                    </div>

                    <div class="form-floating">
                      <input type="number" class="form-control border-0 bg-white shadow-sm" id="collectedAmount" wire:model="totalCollectedAmount" readonly>
                      <label for="collectedAmount" class="text-muted">Total Collected Amount</label>
                    </div>

                    <div class="alert alert-info mt-4 d-flex align-items-center small" role="alert">
                      <i class="bi bi-info-circle-fill fs-5 me-2"></i>
                      <div class= "text-white">
                        This amount represents all collections made by the selected field agent that haven't been transferred yet.
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Right Column -->
              <div class="col-md-6">
                <div class="card bg-light border-0 h-100">
                  <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Destination Details</h6>

                    <div class="form-floating mb-4">
                      <input type="text" class="form-control border-0 bg-white shadow-sm" id="TransferTo" value="{{ $toField }}" readonly>
                      <label for="TransferTo" class="text-muted">Transfer To</label>
                    </div>

                    <div class="form-floating mb-2">
                      <input type="text" class="form-control border-0 bg-white shadow-sm" id="Remark" wire:model="Remark">
                      <label for="Remark" class="text-muted">Transfer Remark/Reference</label>
                    </div>
                    @error('Remark')
                      <div class="text-danger small mt-1 mb-3">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                      </div>
                    @enderror

                    <div class="alert alert-warning mt-4 d-flex align-items-center small" role="alert">
                      <i class="bi bi-shield-exclamation fs-5 me-2"></i>
                      <div class= "text-white">
                        Please ensure all physical cash has been counted and verified before proceeding with the transfer.
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between mt-4 pt-2">
              <button type="button" class="btn btn-outline-secondary px-4">
                <i class="bi bi-arrow-left me-2"></i>Back
              </button>
              <button
                wire:click="transferToCashier"
                class="btn btn-primary px-4 py-2 d-flex align-items-center"
                wire:loading.attr="disabled"
                @if(!$totalCollectedAmount || $totalCollectedAmount <= 0) disabled @endif
              >
                <span wire:loading wire:target="transferToCashier" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                <span wire:loading wire:target="transferToCashier">Processing Transfer...</span>
                <span wire:loading.remove>Complete Transfer<i class="bi bi-arrow-right ms-2"></i></span>
              </button>
            </div>
          </div>


        </div>

        <!-- Recent Transfers Card -->
        {{-- <div class="card shadow-sm border-0 rounded-4 mt-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
              <h6 class="fw-bold m-0">Recent Transfers</h6>
              <a href="#" class="text-decoration-none small">View All</a>
            </div>
            <div class="card-body p-3 p-md-4">
              <!-- Card-based layout instead of table -->
              <div class="row g-3">
                <!-- Transfer 1 -->
                <div class="col-12">
                  <div class="card border-0 shadow-sm hover-shadow transition-all">
                    <div class="card-body p-0">
                      <div class="row g-0 align-items-center">
                        <!-- Avatar and Name -->
                        <div class="col-lg-3 col-md-4 p-3 border-end">
                          <div class="d-flex align-items-center">
                            <div class="avatar-circle bg-white text-dark me-3">
                              <span>JD</span>
                            </div>
                            <div>
                              <h6 class="mb-0 fw-semibold">John Doe</h6>
                              <small class="text-muted">Field Agent</small>
                            </div>
                          </div>
                        </div>

                        <!-- Transaction Details -->
                        <div class="col-lg-6 col-md-5 p-3">
                          <div class="row g-2">
                            <div class="col-sm-6">
                              <div class="d-flex align-items-center">
                                <i class="bi bi-calendar3 text-muted me-2"></i>
                                <div>
                                  <small class="text-muted d-block">Date</small>
                                  <span>Mar 12, 2025</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="d-flex align-items-center">
                                <i class="bi bi-cash-stack text-muted me-2"></i>
                                <div>
                                  <small class="text-muted d-block">Amount</small>
                                  <span class="fw-semibold">$2,500.00</span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-lg-3 col-md-3 bg-light p-3 d-flex flex-column align-items-end justify-content-between">
                          <span class="badge bg-dark text-success px-3 py-2 mb-2">
                            <i class="bi bi-check-circle-fill me-1"></i>Completed
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>


              </div>



            </div>
          </div>

          <style>
          /* Custom CSS for avatar circles and hover effects */
          .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
          }

          .hover-shadow {
            transition: all 0.2s ease;
          }

          .hover-shadow:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
          }

          .transition-all {
            transition: all 0.2s ease;
          }
          </style>

    </div> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  </div>

  <!-- Add Bootstrap Icons CSS -->

