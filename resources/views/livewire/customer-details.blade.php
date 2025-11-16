<div class="container py-4">
    <div class="row g-4">
      <!-- Sidebar -->
      <div class="col-lg-3">
        <div class="card shadow-sm sticky-top" style="top: 1rem;">
          <nav class="p-3">
            <ul class="nav flex-column">
              <li class="nav-item mb-2">
                <a class="nav-link d-flex align-items-center text-dark" href="#profile">
                  <i class="fa-solid fa-user me-2"></i>
                  <span>Profile</span>
                </a>
              </li>
              <li class="nav-item mb-2">
                <a class="nav-link d-flex align-items-center text-dark" href="#basic-info">
                  <i class="fa-solid fa-circle-info me-2"></i>
                  <span>Basic Info</span>
                </a>
              </li>
              <li class="nav-item mb-2">
                <a class="nav-link d-flex align-items-center text-dark" href="#address">
                  <i class="fa-solid fa-location-dot me-2"></i>
                  <span>Address</span>
                </a>
              </li>
              <li class="nav-item mb-2">
                <a class="nav-link d-flex align-items-center text-dark" href="#spouse">
                  <i class="fa-solid fa-user-group me-2"></i>
                  <span>Spouse Details</span>
                </a>
              </li>
              <li class="nav-item mb-2">
                <a class="nav-link d-flex align-items-center text-dark" href="#family">
                  <i class="fa-solid fa-people-roof me-2"></i>
                  <span>Family Details</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center text-dark" href="#documents">
                  <i class="fa-solid fa-book me-2"></i>
                  <span>Documents</span>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </div>

      <!-- Main Content -->
      <div class="col-lg-9">
        <!-- Profile Card -->
        <div class="card shadow-sm mb-4" id="profile">
          <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center">
              <div class="me-4 mb-3 mb-md-0">
                <div class="avatar rounded-circle overflow-hidden bg-light d-flex align-items-center justify-content-center" style="width: 90px; height: 90px;">
                    @if($customer->photo)
                      <img src="{{ asset('storage/' . $customer->photo) }}" alt="{{ $customer->full_name }}" class="img-fluid">
                    @else
                      <i class="fa-solid fa-user text-secondary" style="font-size: 45px;"></i>
                    @endif
                  </div>
              </div>
              <div class="me-auto">
                <h4 class="mb-1 fw-bold">{{ $customer->full_name }}</h4>
                <p class="text-muted mb-0">{{ $customer->customer_no }}</p>
              </div>

              @php
                  $loanApproval = $customer->loans->first() ? $customer->loans->first()->approval : null;
                  $statusClass = 'bg-secondary';
                  $statusText = 'Loan Inactive';

                  if($loanApproval) {
                      switch($loanApproval->status) {
                          case 'Active':
                              $statusClass = 'bg-success';
                              $statusText = 'Loan Active';
                              break;
                          case 'Pending':
                              $statusClass = 'bg-warning';
                              $statusText = 'Pending';
                              break;
                          case 'Rejected':
                              $statusClass = 'bg-danger';
                              $statusText = 'Rejected';
                              break;
                          case 'Completed':
                              $statusClass = 'bg-info';
                              $statusText = 'Completed';
                              break;
                      }
                  }
              @endphp

              <span class="badge {{ $statusClass }} py-2 px-3 fs-6">{{ $statusText }}</span>
            </div>
          </div>
        </div>

        <!-- Basic Info Card -->
        <div class="card shadow-sm mb-4" id="basic-info">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Basic Information</h5>
          </div>
          <div class="card-body p-4">
            <div class="row g-4">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="text-muted small">Full Name</label>
                  <p class="mb-0">{{ $customer->full_name }}</p>
                </div>
                <div class="mb-3">
                  <label class="text-muted small">Phone</label>
                  <p class="mb-0">{{ $customer->customer_phone }}</p>
                </div>
                <div class="mb-3">
                  <label class="text-muted small">Center</label>
                  <p class="mb-0">{{ $customer->center->name }}</p>
                </div>
                <div class="mb-3">
                  <label class="text-muted small">Type</label>
                  <p class="mb-0">
                    @foreach ($customer->types as $type)
                      {{ $type->name }}
                    @endforeach
                  </p>
                </div>
                <div>
                  <label class="text-muted small">Gender</label>
                  <p class="mb-0">{{ $customer->gender }}</p>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label class="text-muted small">NIC Number</label>
                  <p class="mb-0">{{ $customer->nic }}</p>
                </div>
                <div class="mb-3">
                  <label class="text-muted small">Home Phone</label>
                  <p class="mb-0">{{ $customer->home_phone }}</p>
                </div>
                <div class="mb-3">
                  <label class="text-muted small">Date of Birth</label>
                  <p class="mb-0">{{ $customer->date_of_birth }}</p>
                </div>
                <div class="mb-3">
                  <label class="text-muted small">Civil Status</label>
                  <p class="mb-0">{{ $customer->civil_status }}</p>
                </div>
                <div>
                  <label class="text-muted small">Occupation</label>
                  <p class="mb-0">{{ $customer->occupation }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Address Card -->
        <div class="card shadow-sm mb-4" id="address">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Address</h5>
          </div>
          <div class="card-body p-4">
            <div class="row g-4">
              <div class="col-md-6">
                <h6 class="fw-bold mb-3">Permanent Address</h6>
                <div class="p-3 border rounded bg-light">
                  {{ $customer->permanent_address }}, {{ $customer->permanent_city }}
                </div>
              </div>
              <div class="col-md-6">
                <h6 class="fw-bold mb-3">Living Address</h6>
                <div class="p-3 border rounded bg-light">
                  {{ $customer->living_address }}, {{ $customer->living_city }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Spouse Details Card -->
        <div class="card shadow-sm mb-4" id="spouse">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Spouse Details</h5>
          </div>
          <div class="card-body p-4">
            <div class="row g-4">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="text-muted small">Name</label>
                  <p class="mb-0">{{ $customer->spouse_name }}</p>
                </div>
                <div class="mb-3">
                  <label class="text-muted small">NIC</label>
                  <p class="mb-0">{{ $customer->spouse_nic }}</p>
                </div>
                <div>
                  <label class="text-muted small">Age</label>
                  <p class="mb-0">{{ $customer->spouse_age }}</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="text-muted small">Phone</label>
                  <p class="mb-0">{{ $customer->Spouse_phone }}</p>
                </div>
                <div>
                  <label class="text-muted small">Occupation</label>
                  <p class="mb-0">{{ $customer->spouse_occupation }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Family Details Card -->
        <div class="card shadow-sm mb-4" id="family">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Family Details</h5>
          </div>
          <div class="card-body p-4">
            <div class="row g-4">
              <div class="col-md-4">
                <div class="mb-3">
                  <label class="text-muted small">Family Members</label>
                  <p class="mb-0">{{ $customer->family_members }}</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="mb-3">
                  <label class="text-muted small">Family Income</label>
                  <p class="mb-0">{{ $customer->family_income }}</p>
                </div>
              </div>
              <div class="col-md-4">
                <div>
                  <label class="text-muted small">Income Earners</label>
                  <p class="mb-0">{{ $customer->income_earners }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Documents Card -->
        <div class="card shadow-sm" id="documents">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Documents</h5>
          </div>
          <div class="card-body p-4">
            <div class="row g-4">
              <div class="col-md-6">
                <div class="card h-100">
                  <div class="card-header bg-light py-3 text-center">
                    <h6 class="mb-0 fw-bold">NIC Copy</h6>
                  </div>
                  <div class="card-body p-0">
                    <a href="{{ asset('storage/' . $customer->nic_copy) }}" target="_blank" class="d-block">
                      <iframe src="{{ asset('storage/' . $customer->nic_copy) }}" class="w-100" style="height: 220px; border: none;"></iframe>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
