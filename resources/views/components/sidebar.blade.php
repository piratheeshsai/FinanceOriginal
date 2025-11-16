<aside
    class="sidenav navbar bg-mask-secondary navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>

        <!-- Company Logo (Much Larger) -->
        <div class="text-center py-2"
            style="min-height: 80px; width: 100%; max-width: 100%; display: flex; align-items: center; justify-content: center; overflow: visible;">
            @if ($company && $company->logo)
                <img src="{{ Storage::url('logos/' . $company->logo) }}" alt="Company Logo"
                    style="height: 70px; width: auto; max-width: none; object-fit: contain;">
            @else
                <img src="../assets/img/logo-ct.png" alt="Default Logo"
                    style="height: 70px; width: auto; max-width: none; object-fit: contain;">
            @endif
        </div>

        <!-- Company Name (Below Logo) -->
        {{-- <div class="text-center mb-3">
            <h5 class="m-0 font-weight-bold">{{ $company->name }}</h5>
        </div> --}}
    </div>

    <hr class="horizontal dark mt-1">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100 h-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>shop </title>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                    <g transform="translate(1716.000000, 291.000000)">
                                        <g transform="translate(0.000000, 148.000000)">
                                            <path class="color-background opacity-6"
                                                d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z">
                                            </path>
                                            <path class="color-background"
                                                d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z">
                                            </path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>


            @can('branch View')
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#branchesExample"
                        class="nav-link {{ request()->routeIs('branches.index') ? 'active' : '' }}"
                        aria-controls="branchesExample" role="button"
                        aria-expanded="{{ request()->routeIs('branches.index') ? 'true' : 'false' }}">
                        <div
                            class="icon icon-sm shadow-sm border-radius-md bg-white text-center d-flex align-items-center justify-content-center me-2">
                            {{-- <i class="ni ni-archive-2" aria-hidden="true"></i> --}}
                            <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>credit-card</title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g transform="translate(453.000000, 454.000000)">
                                                <path class="color-background opacity-6"
                                                    d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z">
                                                </path>
                                                <path class="color-background"
                                                    d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z">
                                                </path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1">Branches</span>
                    </a>
                    <div class="collapse {{ request()->routeIs('branches.index') ? 'show' : '' }}" id="branchesExample">
                        <ul class="nav ms-4 ps-3">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('branches.index') ? 'active' : '' }}"
                                    href="{{ route('branches.index') }}">
                                    <span class="sidenav-mini-icon">A</span>
                                    <span class="sidenav-normal">Manage Branches</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan

            <li class="nav-item">
                @php
                    $userRoutes = ['users.index', 'users.create', 'users.edit', 'role.index', 'assign.index'];
                    $isUserActive = request()->routeIs($userRoutes);
                @endphp
                <a data-bs-toggle="collapse" href="#usersExample" class="nav-link {{ $isUserActive ? 'active' : '' }}"
                    aria-controls="usersExample" role="button" aria-expanded="{{ $isUserActive ? 'true' : 'false' }}">
                    <div
                        class="icon icon-sm shadow-sm border-radius-md bg-white text-center d-flex align-items-center justify-content-center me-2">
                        <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>User Management</title>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                    <g transform="translate(1716.000000, 291.000000)">
                                        <g transform="translate(453.000000, 454.000000)">
                                            <!-- User icon -->
                                            <path class="color-background"
                                                d="M21.5 12a6 6 0 1 1 0-12 6 6 0 0 1 0 12zm0 3c-5.598 0-10.5 2.686-10.5 6.75V30h21v-8.25c0-4.064-4.902-6.75-10.5-6.75z" />
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Users</span>
                </a>

                <div class="collapse {{ $isUserActive ? 'show' : '' }}" id="usersExample">

                    <ul class="nav ms-4 ps-3">

                        @can('user View')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}"
                                    href="{{ route('users.index') }}">
                                    <span class="sidenav-mini-icon">A</span>
                                    <span class="sidenav-normal">Users</span>
                                </a>
                            </li>
                        @endcan

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('role.index') ? 'active' : '' }}"
                                href="{{ route('role.index') }}">
                                <span class="sidenav-mini-icon">R</span>
                                <span class="sidenav-normal">Permission</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('assign.index') ? 'active' : '' }}"
                                href="{{ route('assign.index') }}">
                                <span class="sidenav-mini-icon">AS</span>
                                <span class="sidenav-normal">User Role</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>


            <li class="nav-item">
                @php
                    $masterRoutes = ['customer.create', 'customer.index', 'loan-schemes.index', 'groups.index'];
                    $isMasterActive = request()->routeIs($masterRoutes);
                @endphp
                <a data-bs-toggle="collapse" href="#customerExample"
                    class="nav-link {{ $isMasterActive ? 'active' : '' }}" aria-controls="customerExample"
                    role="button" aria-expanded="{{ $isMasterActive ? 'true' : 'false' }}">
                    <div
                        class="icon icon-sm shadow-sm border-radius-md bg-white text-center d-flex align-items-center justify-content-center me-2">
                        <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>Master List</title>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF"
                                    fill-rule="nonzero">
                                    <g transform="translate(1716.000000, 291.000000)">
                                        <g transform="translate(453.000000, 454.000000)">
                                            <!-- Top bar -->
                                            <path class="color-background opacity-6"
                                                d="M0,6 L43,6 L43,0 L0,0 L0,6 Z M7,4.5 C7,3.67157288 6.32842712,3 5.5,3 C4.67157288,3 4,3.67157288 4,4.5 C4,5.32842712 4.67157288,6 5.5,6 C6.32842712,6 7,5.32842712 7,4.5 Z">
                                            </path>
                                            <!-- Middle and bottom bars -->
                                            <path class="color-background"
                                                d="M0,21 L43,21 L43,15 L0,15 L0,21 Z M7,19.5 C7,18.6715729 6.32842712,18 5.5,18 C4.67157288,18 4,18.6715729 4,19.5 C4,20.3284271 4.67157288,21 5.5,21 C6.32842712,21 7,20.3284271 7,19.5 Z M0,36 L43,36 L43,30 L0,30 L0,36 Z M7,34.5 C7,33.6715729 6.32842712,33 5.5,33 C4.67157288,33 4,33.6715729 4,34.5 C4,35.3284271 4.67157288,36 5.5,36 C6.32842712,36 7,35.3284271 7,34.5 Z">
                                            </path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Master</span>
                </a>
                <div class="collapse {{ $isMasterActive ? 'show' : '' }}" id="customerExample">
                    <ul class="nav ms-4 ps-3">

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('customer.index') ? 'active' : '' }}"
                                href="{{ route('customer.index') }}">
                                <span class="sidenav-mini-icon">R</span>
                                <span class="sidenav-normal">Customers</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('customer.create') ? 'active' : '' }}"
                                href="{{ route('customer.create') }}">
                                <span class="sidenav-mini-icon">A.C</span>
                                <span class="sidenav-normal">New Customer</span>
                            </a>
                        </li>

                        @can('View Schemes')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('loan-schemes.index') ? 'active' : '' }}"
                                    href="{{ route('loan-schemes.index') }}">
                                    <span class="sidenav-mini-icon">S</span>
                                    <span class="sidenav-normal">Schemes</span>
                                </a>
                            </li>
                        @endcan
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('groups.index') ? 'active' : '' }}"
                                href="{{ route('groups.index') }}">
                                <span class="sidenav-mini-icon">G</span>
                                <span class="sidenav-normal">Groups</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- <li class="nav-item">
                @php
                    $loanRoutes = ['loan.index', 'loan.create', 'loan.approval'];
                    $isLoanActive = request()->routeIs($loanRoutes);
                @endphp
                <a data-bs-toggle="collapse" href="#Loan"
                    class="nav-link {{ $isLoanActive ? 'active' : '' }}"
                    aria-controls="Loan" role="button"
                    aria-expanded="{{ $isLoanActive ? 'true' : 'false' }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>Loan</title>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                    <g transform="translate(1716.000000, 291.000000)">
                                        <g transform="translate(453.000000, 454.000000)">
                                            <path class="color-background opacity-6"
                                                d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z">
                                            </path>
                                            <path class="color-background"
                                                d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z">
                                            </path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Loan</span>
                </a>

                <div class="collapse {{ $isLoanActive ? 'show' : '' }}" id="Loan">
                    <ul class="nav ms-4 ps-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('loan.index') ? 'active' : '' }}"
                               href="{{ route('loan.index') }}">
                                <span class="sidenav-mini-icon">L</span>
                                <span class="sidenav-normal">Loans</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('loan.create') ? 'active' : '' }}"
                               href="{{ route('loan.create') }}">
                                <span class="sidenav-mini-icon">N.L</span>
                                <span class="sidenav-normal">New Loan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('loan.approval') ? 'active' : '' }}"
                               href="{{ route('loan.approval') }}">
                                <span class="sidenav-mini-icon">L.P</span>
                                <span class="sidenav-normal">Loan Approval</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('collection.loanProgress') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('collection.loanProgress') }}">
                                <span class="sidenav-mini-icon">N.L</span>
                                <span class="sidenav-normal">Loan Progress</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li> --}}


            <li class="nav-item"> @php
                $loanRoutes = ['loan.index', 'loan.create', 'loan.approval', 'collection.loanProgress'];
                $isLoanActive = request()->routeIs($loanRoutes);
            @endphp

                <a data-bs-toggle="collapse" href="#Loan" class="nav-link {{ $isLoanActive ? 'active' : '' }}"
                    aria-controls="Loan" role="button" aria-expanded="{{ $isLoanActive ? 'true' : 'false' }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>Loan</title>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF"
                                    fill-rule="nonzero">
                                    <g transform="translate(1716.000000, 291.000000)">
                                        <g transform="translate(453.000000, 454.000000)">
                                            <path class="color-background opacity-6"
                                                d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z">
                                            </path>
                                            <path class="color-background"
                                                d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z">
                                            </path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Loan</span>
                </a>
                <div class="collapse {{ $isLoanActive ? 'show' : '' }}" id="Loan">
                    <ul class="nav ms-4 ps-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('loan.index') ? 'active' : '' }}"
                                href="{{ route('loan.index') }}">
                                <span class="sidenav-mini-icon">L</span>
                                <span class="sidenav-normal">Loans</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('loan.create') ? 'active' : '' }}"
                                href="{{ route('loan.create') }}">
                                <span class="sidenav-mini-icon">N.L</span>
                                <span class="sidenav-normal">New Loan</span>
                            </a>
                        </li>
                        @can('New Loan Approval')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('loan.approval') ? 'active' : '' }}"
                                    href="{{ route('loan.approval') }}">
                                    <span class="sidenav-mini-icon">L.P</span>
                                    <span class="sidenav-normal">Loan Approval</span>
                                </a>
                            </li>
                        @endcan
                        @can('View Loan Progress')
                            <li class="nav-item {{ request()->routeIs('collection.loanProgress') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('collection.loanProgress') }}">
                                    <span class="sidenav-mini-icon">N.L</span>
                                    <span class="sidenav-normal">Loan Progress</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>







            <li
                class="nav-item {{ request()->routeIs(['collection.index', 'collections.transfer', 'collections.all', 'collections.collectionTrApproval']) ? 'active' : '' }}">
                <a data-bs-toggle="collapse" href="#Collection"
                    class="nav-link {{ request()->routeIs(['collection.index', 'collections.transfer', 'collections.all', 'collections.collectionTrApproval']) ? 'active' : '' }}"
                    aria-controls="Collection">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 42 42" xmlns="http://www.w3.org/2000/svg">
                            <title>Collection</title>
                            <g fill="none" fill-rule="evenodd">
                                <g transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF"
                                    fill-rule="nonzero">
                                    <g transform="translate(1716.000000, 291.000000)">
                                        <g transform="translate(603.000000, 0.000000)">
                                            <path class="color-background"
                                                d="M22.7597,19.309 L38.8987,11.2395 C39.3927,10.9925 39.5929,10.3919 39.3459,9.89788 C39.2492,9.70436 39.0922,9.54744 38.8987,9.45068 L20.2742,0.137812 C19.9054,-0.04725 19.4696,-0.04725 19.0995,0.137812 L3.10117,8.13816 C2.60721,8.38518 2.40702,8.98586 2.65404,9.47983 C2.7508,9.67333 2.90771,9.83023 3.10122,9.92699 L21.8653,19.309 C22.1468,19.4498 22.4782,19.4498 22.7597,19.309 Z">
                                            </path>
                                            <path class="color-background opacity-6"
                                                d="M23.625,22.4292 L23.625,39.8805 C23.625,40.4328 24.0727,40.8805 24.625,40.8805 C24.7803,40.8805 24.9334,40.8444 25.0722,40.775 L41.2742,32.6734 C41.7191,32.4516 42,31.9974 42,31.5 L42,14.2417 C42,13.6894 41.5523,13.2417 41,13.2417 C40.8448,13.2417 40.6916,13.2778 40.5528,13.3472 L24.1778,21.5347 C23.839,21.7041 23.625,22.0504 23.625,22.4292 Z">
                                            </path>
                                            <path class="color-background opacity-6"
                                                d="M20.4472,21.5347 L1.44721,12.0347 C0.953235,11.7877 0.352562,11.988 0.105573,12.4819 C0.0361451,12.6208 0,12.7739 0,12.9292 L0,30.1875 C0,30.6849 0.280875,31.1391 0.725813,31.3622 L19.5528,40.7751 C20.0468,41.0221 20.6475,40.8218 20.8944,40.3278 C20.9639,40.189 21,40.0359 21,39.8806 L21,22.4292 C21,22.0504 20.786,21.7041 20.4472,21.5347 Z">
                                            </path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Collection</span>
                </a>
                <div class="collapse {{ request()->routeIs(['collection.*', 'collections.*']) ? 'show' : '' }}"
                    id="Collection">
                    <ul class="nav ms-4 ps-3">
                        <li class="nav-item {{ request()->routeIs('collection.index') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('collection.index') }}">
                                <span class="sidenav-mini-icon">C</span>
                                <span class="sidenav-normal">Today Dues</span>
                            </a>
                        </li>
                        {{-- <li class="nav-item {{ request()->routeIs('collection.loanProgress') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('collection.loanProgress') }}">
                                <span class="sidenav-mini-icon">N.L</span>
                                <span class="sidenav-normal">Loan Progress</span>
                            </a>
                        </li> --}}
                        <li class="nav-item {{ request()->routeIs('collections.transfer') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('collections.transfer') }}">
                                <span class="sidenav-mini-icon">T</span>
                                <span class="sidenav-normal">Collection Transfer</span>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->routeIs('collections.all') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('collections.all') }}">
                                <span class="sidenav-mini-icon">V</span>
                                <span class="sidenav-normal">View Collections</span>
                            </a>
                        </li>

                        @can('Collection Approval page')
                            <li
                                class="nav-item {{ request()->routeIs('collections.collectionTrApproval') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('collections.collectionTrApproval') }}">
                                    <span class="sidenav-mini-icon">A</span>
                                    <span class="sidenav-normal">Collection Approval</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>




            <li class="nav-item {{ request()->routeIs(['accounts.*', 'transaction.*']) ? 'active' : '' }}">
                <a data-bs-toggle="collapse" href="#Account"
                    class="nav-link {{ request()->routeIs(['accounts.*', 'transaction.*']) ? 'active' : '' }}"
                    aria-controls="Account">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>Accounting</title>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF"
                                    fill-rule="nonzero">
                                    <g transform="translate(1716.000000, 291.000000)">
                                        <g transform="translate(453.000000, 454.000000)">
                                            <path class="color-background opacity-6"
                                                d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z">
                                            </path>
                                            <path class="color-background"
                                                d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z">
                                            </path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Accounting</span>
                </a>

                <div class="collapse {{ request()->routeIs(['accounts.*', 'transaction.*']) ? 'show' : '' }}"
                    id="Account">
                    <ul class="nav ms-4 ps-3">

                          {{-- @can('Fund transfer') --}}
                            <li class="nav-item {{ request()->routeIs('transaction.index') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('general-ledger.index') }}">
                                    <span class="sidenav-mini-icon">L</span>
                                    <span class="sidenav-normal">General Ledger</span>
                                </a>
                            </li>
                        {{-- @endcan --}}

                        @can('Fund transfer')
                            <li class="nav-item {{ request()->routeIs('transaction.index') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('transaction.index') }}">
                                    <span class="sidenav-mini-icon">L</span>
                                    <span class="sidenav-normal">Transfers</span>
                                </a>
                            </li>
                        @endcan

                        @can('Daily cash summary')
                            <li class="nav-item {{ request()->routeIs('accounts.index') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('accounts.index') }}">
                                    <span class="sidenav-mini-icon">L</span>
                                    <span class="sidenav-normal">Daily Cash Summary</span>
                                </a>
                            </li>
                        @endcan

                        @can('cash summary Denomination')
                            <li class="nav-item {{ request()->routeIs('accounts.Denomination') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('accounts.Denomination') }}">
                                    <span class="sidenav-mini-icon">L</span>
                                    <span class="sidenav-normal">Cash Denomination</span>
                                </a>
                            </li>
                        @endcan

                        @can('cash summary Denomination')
                            <li class="nav-item {{ request()->routeIs('reports.balanceSheet') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('reports.balanceSheet') }}">
                                    <span class="sidenav-mini-icon">L</span>
                                    <span class="sidenav-normal">Cash Denomination</span>
                                </a>
                            </li>
                        @endcan


                        @can('Profit and Loss')
                            <li class="nav-item {{ request()->routeIs('accounts.ProfitLoss') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('accounts.ProfitLoss') }}">
                                    <span class="sidenav-mini-icon">L</span>
                                    <span class="sidenav-normal">Profit & Loss</span>
                                </a>
                            </li>
                        @endcan

                        @can('Petty cash')
                            <li class="nav-item {{ request()->routeIs('accounts.PettyCash') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('accounts.PettyCash') }}">
                                    <span class="sidenav-mini-icon">PC</span>
                                    <span class="sidenav-normal">Petty Cash</span>
                                </a>
                            </li>
                        @endcan

                        {{-- <li class="nav-item {{ request()->routeIs('settings.cities') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('settings.cities') }}">
                                <span class="sidenav-mini-icon">PC</span>
                                <span class="sidenav-normal">cities</span>
                            </a>
                        </li> --}}

                        @can('Payments')
                            <li class="nav-item {{ request()->routeIs('accounts.payments') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('accounts.payments') }}">
                                    <span class="sidenav-mini-icon">PC</span>
                                    <span class="sidenav-normal">Payments</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>




            <li
                class="nav-item {{ request()->routeIs(['reports.agreement', 'reports.mortgage', 'reports.promissory', 'agreement', 'mortgage', 'promissory', 'promissoryOrigin']) ? 'active' : '' }}">
                <a data-bs-toggle="collapse" href="#documentsExample"
                    class="nav-link {{ request()->routeIs(['reports.agreement', 'reports.mortgage', 'reports.promissory', 'agreement', 'mortgage', 'promissory', 'promissoryOrigin']) ? 'active' : '' }}"
                    aria-controls="documentsExample" role="button"
                    aria-expanded="{{ request()->routeIs(['reports.agreement', 'reports.mortgage', 'reports.promissory', 'agreement', 'mortgage', 'promissory', 'promissoryOrigin']) ? 'true' : 'false' }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 40 40" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>Documents</title>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF"
                                    fill-rule="nonzero">
                                    <g transform="translate(1716.000000, 291.000000)">
                                        <g transform="translate(453.000000, 454.000000)">
                                            <path class="color-background opacity-6"
                                                d="M6,0 L28,0 C30,0 32,2 32,4 L32,8 L6,8 C4,8 2,6 2,4 L2,2 C2,0.8954305 2.8954305,0 4,0 L6,0 Z">
                                            </path>
                                            <path class="color-background"
                                                d="M2,12 L2,32 C2,34.209139 3.790861,36 6,36 L28,36 C30.209139,36 32,34.209139 32,32 L32,12 L2,12 Z M8,20 L24,20 L24,22 L8,22 L8,20 Z M8,26 L24,26 L24,28 L8,28 L8,26 Z M8,32 L20,32 L20,34 L8,34 L8,32 Z">
                                            </path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Documents</span>
                </a>

                <div class="collapse {{ request()->routeIs(['reports.agreement', 'reports.mortgage', 'reports.promissory', 'agreement', 'mortgage', 'promissory', 'promissoryOrigin']) ? 'show' : '' }}"
                    id="documentsExample">
                    <ul class="nav ms-4 ps-3">
                        <li
                            class="nav-item {{ request()->routeIs(['reports.agreement', 'agreement']) ? 'active' : '' }}">
                            <a class="nav-link {{ request()->routeIs(['reports.agreement', 'agreement']) ? 'active' : '' }}"
                                href="{{ route('agreement') }}">
                                <span class="sidenav-mini-icon">A</span>
                                <span class="sidenav-normal">Offer Letter</span>
                            </a>
                        </li>

                        <li
                            class="nav-item {{ request()->routeIs(['reports.mortgage', 'mortgage']) ? 'active' : '' }}">
                            <a class="nav-link {{ request()->routeIs(['reports.mortgage', 'mortgage']) ? 'active' : '' }}"
                                href="{{ route('mortgage') }}">
                                <span class="sidenav-mini-icon">M</span>
                                <span class="sidenav-normal">Mortgage Bond</span>
                            </a>
                        </li>

                        <li
                            class="nav-item {{ request()->routeIs(['reports.promissory', 'promissory']) ? 'active' : '' }}">
                            <a class="nav-link {{ request()->routeIs(['reports.promissory', 'promissory']) ? 'active' : '' }}"
                                href="{{ route('promissory') }}">
                                <span class="sidenav-mini-icon">A</span>
                                <span class="sidenav-normal">Agreement Lending</span>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->routeIs('promissoryOrigin') ? 'active' : '' }}">
                            <a class="nav-link {{ request()->routeIs('promissoryOrigin') ? 'active' : '' }}"
                                href="{{ route('promissoryOrigin') }}">
                                <span class="sidenav-mini-icon">P</span>
                                <span class="sidenav-normal">Promissory Note</span>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->routeIs('voucherTamil') ? 'active' : '' }}">
                            <a class="nav-link {{ request()->routeIs('voucherTamil') ? 'active' : '' }}"
                                href="{{ route('voucherTamil') }}">
                                <span class="sidenav-mini-icon">R</span>
                                <span class="sidenav-normal">Receipt</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>


            <li class="nav-item {{ request()->routeIs(['reports.*', 'log.ActivityLog']) ? 'active' : '' }}">
                <a data-bs-toggle="collapse" href="#Reports"
                    class="nav-link {{ request()->routeIs(['reports.*', 'log.ActivityLog']) ? 'active' : '' }}"
                    aria-controls="Reports">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>Reports</title>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF"
                                    fill-rule="nonzero">
                                    <g transform="translate(1716.000000, 291.000000)">
                                        <g transform="translate(453.000000, 454.000000)">
                                            <path class="color-background opacity-6"
                                                d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z">
                                            </path>
                                            <path class="color-background"
                                                d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z">
                                            </path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Reports</span>
                </a>

                <div class="collapse {{ request()->routeIs(['reports.*', 'log.ActivityLog']) ? 'show' : '' }}"
                    id="Reports">
                    <ul class="nav ms-4 ps-3">

                        @can('Customer Reports')
                            <li class="nav-item {{ request()->routeIs('reports.customerList') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('reports.customerList') }}">
                                    <span class="sidenav-mini-icon">L</span>
                                    <span class="sidenav-normal">Customers Reports</span>
                                </a>
                            </li>
                        @endcan



                        <li class="nav-item {{ request()->routeIs('reports.balanceSheet') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('reports.balanceSheet') }}">
                                <span class="sidenav-mini-icon">L</span>
                                <span class="sidenav-normal">Balance Sheet</span>
                            </a>
                        </li>


                        @can('loan Reports')
                            <li class="nav-item {{ request()->routeIs('reports.loanReport') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('reports.loanReport') }}">
                                    <span class="sidenav-mini-icon">L</span>
                                    <span class="sidenav-normal">Loans Report</span>
                                </a>
                            </li>
                        @endcan
                        @can('collection Reports')
                            <li class="nav-item {{ request()->routeIs('reports.collectionReport') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('reports.collectionReport') }}">
                                    <span class="sidenav-mini-icon">L</span>
                                    <span class="sidenav-normal">Collections Report</span>
                                </a>
                            </li>
                        @endcan

                        @can('balance Sheet')
                            <li class="nav-item {{ request()->routeIs('reports.pendingCollection') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('reports.pendingCollection') }}">
                                    <span class="sidenav-mini-icon">PC</span>
                                    <span class="sidenav-normal">Collections Pending</span>
                                </a>
                            </li>
                        @endcan

                        @can('Balance Sheet')
                            <li class="nav-item {{ request()->routeIs('reports.balanceSheet') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('reports.balanceSheet') }}">
                                    <span class="sidenav-mini-icon">PC</span>
                                    <span class="sidenav-normal">Balance Sheet</span>
                                </a>
                            </li>
                        @endcan

                        @can('Overalls Report')
                            <li
                                class="nav-item {{ request()->routeIs('reports.branchFinancialReport') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('reports.branchFinancialReport') }}">
                                    <span class="sidenav-mini-icon">PC</span>
                                    <span class="sidenav-normal">Overalls Report</span>
                                </a>
                            </li>
                        @endcan
                        @can('Activity log')
                            <li class="nav-item {{ request()->routeIs('log.ActivityLog') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('log.ActivityLog') }}">
                                    <span class="sidenav-mini-icon">PC</span>
                                    <span class="sidenav-normal">Activity Log</span>
                                </a>
                            </li>
                        @endcan

                        @can('Trial Balance')
                            <li class="nav-item {{ request()->routeIs('reports.trialBalance') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('reports.trialBalance') }}">
                                    <span class="sidenav-mini-icon">PC</span>
                                    <span class="sidenav-normal">Trial Balance</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>





            <li class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}"
                    href="{{ route('settings.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 40 40" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>settings</title>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g transform="translate(-2020.000000, -442.000000)" fill="#FFFFFF"
                                    fill-rule="nonzero">
                                    <g transform="translate(1716.000000, 291.000000)">
                                        <g transform="translate(304.000000, 151.000000)">
                                            <polygon class="color-background opacity-6"
                                                points="18.0883333 15.7316667 11.1783333 8.82166667 13.3333333 6.66666667 6.66666667 0 0 6.66666667 6.66666667 13.3333333 8.82166667 11.1783333 15.315 17.6716667">
                                            </polygon>
                                            <path class="color-background opacity-6"
                                                d="M31.5666667,23.2333333 C31.0516667,23.2933333 30.53,23.3333333 30,23.3333333 C29.4916667,23.3333333 28.9866667,23.3033333 28.48,23.245 L22.4116667,30.7433333 L29.9416667,38.2733333 C32.2433333,40.575 35.9733333,40.575 38.275,38.2733333 L38.275,38.2733333 C40.5766667,35.9716667 40.5766667,32.2416667 38.275,29.94 L31.5666667,23.2333333 Z">
                                            </path>
                                            <path class="color-background"
                                                d="M33.785,11.285 L28.715,6.215 L34.0616667,0.868333333 C32.82,0.315 31.4483333,0 30,0 C24.4766667,0 20,4.47666667 20,10 C20,10.99 20.1483333,11.9433333 20.4166667,12.8466667 L2.435,27.3966667 C0.95,28.7083333 0.0633333333,30.595 0.00333333333,32.5733333 C-0.0583333333,34.5533333 0.71,36.4916667 2.11,37.89 C3.47,39.2516667 5.27833333,40 7.20166667,40 C9.26666667,40 11.2366667,39.1133333 12.6033333,37.565 L27.1533333,19.5833333 C28.0566667,19.8516667 29.01,20 30,20 C35.5233333,20 40,15.5233333 40,10 C40,8.55166667 39.685,7.18 39.1316667,5.93666667 L33.785,11.285 Z">
                                            </path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Options</span>
                </a>
            </li>


            <li class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                    href="{{ route('profile.show') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 40 40" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>Profile</title>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g transform="translate(-2020.000000, -442.000000)" fill="#FFFFFF"
                                    fill-rule="nonzero">
                                    <g transform="translate(1716.000000, 291.000000)">
                                        <g transform="translate(304.000000, 151.000000)">
                                            <polygon class="color-background opacity-6"
                                                points="18.0883333 15.7316667 11.1783333 8.82166667 13.3333333 6.66666667 6.66666667 0 0 6.66666667 6.66666667 13.3333333 8.82166667 11.1783333 15.315 17.6716667">
                                            </polygon>
                                            <path class="color-background opacity-6"
                                                d="M31.5666667,23.2333333 C31.0516667,23.2933333 30.53,23.3333333 30,23.3333333 C29.4916667,23.3333333 28.9866667,23.3033333 28.48,23.245 L22.4116667,30.7433333 L29.9416667,38.2733333 C32.2433333,40.575 35.9733333,40.575 38.275,38.2733333 L38.275,38.2733333 C40.5766667,35.9716667 40.5766667,32.2416667 38.275,29.94 L31.5666667,23.2333333 Z">
                                            </path>
                                            <path class="color-background"
                                                d="M33.785,11.285 L28.715,6.215 L34.0616667,0.868333333 C32.82,0.315 31.4483333,0 30,0 C24.4766667,0 20,4.47666667 20,10 C20,10.99 20.1483333,11.9433333 20.4166667,12.8466667 L2.435,27.3966667 C0.95,28.7083333 0.0633333333,30.595 0.00333333333,32.5733333 C-0.0583333333,34.5533333 0.71,36.4916667 2.11,37.89 C3.47,39.2516667 5.27833333,40 7.20166667,40 C9.26666667,40 11.2366667,39.1133333 12.6033333,37.565 L27.1533333,19.5833333 C28.0566667,19.8516667 29.01,20 30,20 C35.5233333,20 40,15.5233333 40,10 C40,8.55166667 39.685,7.18 39.1316667,5.93666667 L33.785,11.285 Z">
                                            </path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Profile</span>
                </a>
            </li>

            {{-- @endhasrole --}}

    </div>

    {{-- <div class="sidenav-footer mx-3 ">
        <div class="card card-background shadow-none card-background-mask-secondary" id="sidenavCard">
            <div class="full-background"
                style="background-image: url('../assets/img/curved-images/white-curved.jpeg')"></div>
            <div class="card-body text-start p-3 w-100">
                <div
                    class="icon icon-shape icon-sm bg-white shadow text-center mb-3 d-flex align-items-center justify-content-center border-radius-md">
                    <i class="ni ni-diamond text-dark text-gradient text-lg top-0" aria-hidden="true"
                        id="sidenavCardIcon"></i>
                </div>
                <div class="docs-info">
                    <h6 class="text-white up mb-0">Need help?</h6>
                    <p class="text-xs font-weight-bold">Please check our docs</p>
                    <a href="#" target="_blank" class="btn btn-white btn-sm w-100 mb-0">Documentation</a>
                </div>
            </div>
        </div>
        <a class="btn bg-gradient-primary mt-4 w-100" href="" type="button"></a>
    </div> --}}


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var collapseElementList = [].slice.call(document.querySelectorAll('.collapse'))
            var collapseList = collapseElementList.map(function(collapseEl) {
                return new bootstrap.Collapse(collapseEl, {
                    toggle: false // You can set it to false if you want to control toggle manually.
                });
            });

            // If you want to add custom behavior for the toggle on click
            const usersMenuLink = document.querySelector('a[href="#usersMenu"]');
            usersMenuLink.addEventListener('click', function(e) {
                const menu = document.getElementById('usersMenu');
                if (menu.classList.contains('show')) {
                    // If menu is already open, collapse it
                    new bootstrap.Collapse(menu, {
                        toggle: false
                    }).hide();
                } else {
                    // If menu is closed, open it
                    new bootstrap.Collapse(menu, {
                        toggle: true
                    }).show();
                }
            });
        });
    </script>
    <style>
        .nav-item.active {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 0.375rem;
        }

        .nav-item.active .nav-link {
            font-weight: 600;
        }
    </style>



</aside>
