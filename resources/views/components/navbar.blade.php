<nav class="navbar bg-white navbar-main navbar-expand-lg mt-3 px-0 mx-4 shadow-lg border-radius-xl" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <!-- Left side - Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm">
                    <a class="opacity-5 text-dark" href="javascript:;">Pages</a>
                </li>
                <li class="breadcrumb-item text-sm text-dark active" aria-current="page">
                    @yield('breadcrumb')
                </li>
            </ol>
            <h6 class="font-weight-bolder mb-0">@yield('page-title')</h6>
        </nav>

        <!-- Right side - Icons and user menu - Moved outside collapse -->
        <div class="d-flex align-items-center">
            <ul class="navbar-nav ms-auto d-flex flex-row gap-3"> <!-- Added gap-3 for spacing -->
                <!-- User profile -->
                <li class="nav-item d-flex align-items-center">
                    <a href="{{ route('profile.show') }}" class="nav-link text-body font-weight-bold px-0">
                        <i class="fa fa-user"></i>
                        <span class="d-none d-md-inline ms-1">{{ Auth::check() ? Auth::user()->name : 'Guest' }}</span>
                    </a>
                </li>

                <!-- Mobile sidebar toggle -->
                <li class="nav-item d-xl-none d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>

                <!-- Notifications dropdown -->
                <li class="nav-item dropdown d-flex align-items-center position-relative">
                    <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fa fa-bell cursor-pointer"></i>
                        <span id="notification-count" class="badge bg-danger">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown px-2 py-3" aria-labelledby="dropdownMenuButton"
                        id="notification-list">
                        <!-- Dynamically loaded notifications will go here -->
                    </ul>
                </li>

                <!-- Logout button -->
                <li class="nav-item d-flex align-items-center">
                    <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                        @csrf
                        <button type="button" id="logoutButton" class="nav-link text-body font-weight-bold px-0"
                            style="border: none; background: none;">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
