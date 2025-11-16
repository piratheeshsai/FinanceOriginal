<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-touch-icon.png') }}?v=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}?v=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon-16x16.png') }}?v=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=1.0">
    <link rel="manifest" href="{{ asset('img/site.webmanifest') }}?v=1.0">






    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>





    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome (CSS) -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>



    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/fontawesome.min.css"
        integrity="sha384-NvKbDTEnL+A8F/AA5Tc5kmMLSJHUO868P+lDtTpJIeQdGYaUIuLr4lVGOEA1OcMy" crossorigin="anonymous">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Google Fonts (Open Sans) -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    @vite(['resources/css/app.css'])

    {{-- <link id="pagestyle" href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link id="pagestyle" href="{{ asset('css/choice.css') }}" rel="stylesheet"> --}}

    @livewireStyles



</head>
<style>
    .loader-container {
        position: fixed;
        inset: 0;
        background-color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    /* Dot Ring Loader */
    .dot-ring-loader {
        position: relative;
        width: 64px;
        height: 64px;
    }

    .dot-ring-loader::before,
    .dot-ring-loader::after {
        content: "";
        position: absolute;
        border: 6px dotted #3498db;
        /* Dot style */
        border-radius: 50%;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        animation: spin 1.2s linear infinite;
    }

    .dot-ring-loader::after {
        border-color: #2980b9 transparent transparent transparent;
        /* optional variation */
        animation-direction: reverse;
        opacity: 0.6;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }



    /* Badge styling */
    #notification-count {
        position: absolute;
        top: -5px;
        right: -10px;
        font-size: 0.75rem;
        padding: 3px 6px;
        border-radius: 50%;
    }


    .toast-success {
        background-color: #28a745 !important;
        color: white !important;
        border-radius: 4px;
        font-family: Arial, sans-serif;
    }


    #notification-bell {
        cursor: pointer;
        position: relative;
    }

    #notification-count {
        position: absolute;
        top: -5px;
        right: -5px;
        font-size: 12px;
        background-color: red;
        color: white;
        padding: 2px 5px;
        border-radius: 50%;
    }

    @media (max-width: 1200px) {
        table {
            font-size: 14px;
            /* Reduce font size */
        }

        th,
        td {
            padding: 6px;
            /* Reduce padding */
        }
    }

    @media (max-width: 768px) {
        table {
            font-size: 12px;
            /* Even smaller font size */
        }

        th,
        td {
            padding: 4px;
            /* Reduce padding for small screens */
        }
    }

    @media (max-width: 480px) {
        table {
            font-size: 8px;
            /* Significantly reduce font size for phones */
        }

        th,
        td {
            padding: 2px;
            /* Reduce padding even further */
        }
    }
</style>

<body class="g-sidenav-show  bg-gray-100">


    <!-- Sidebar -->
    <x-sidebar />

    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">


        <!-- Nav bar -->

        <x-navbar />


        @yield('content')

        <div id="loader" class="loader-container">
            <div class="dot-ring-loader"></div>
        </div>


        <!-- footer -->
        <x-footer />


    </main>


    @livewireScripts

    <script>
        document.getElementById('logoutButton').addEventListener('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out of your session.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#cb0c9f',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes, log me out!',
                reverseButtons: true,
                customClass: {
                    popup: 'small-swal-popup',
                    title: 'small-swal-title',
                    icon: 'small-swal-icon',
                    content: 'small-swal-text',
                    confirmButton: 'small-swal-button',
                    cancelButton: 'small-swal-button'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        });



        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }




        document.addEventListener('DOMContentLoaded', function() {
            // Initialize scrollbar on desktop
            if (navigator.platform.indexOf('Win') > -1 && document.querySelector('#sidenav-scrollbar')) {
                var options = {
                    damping: '0.5'
                }
                Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
            }

            // Make sure touch scrolling works for mobile
            const mainContent = document.querySelector('.main-content');
            if (mainContent && window.innerWidth < 768) {
                mainContent.style.overflowY = 'auto';
                mainContent.style.webkitOverflowScrolling = 'touch';
            }
        });

        Pusher.logToConsole = true;
        var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            authEndpoint: '/broadcasting/auth', // Ensure private channel authentication
            auth: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        });



        const currentUserId = {{ auth()->id() }};
        var channel = pusher.subscribe('private-approver-channel.' + currentUserId);

        channel.bind('Loan_Created', function(data) {
            console.log('Loan_Created event received', data);

            if (data && data.loan && data.loan.loan_number && data.loan.loan_type) {
                toastr.success('New Loan Created', 'Loan Number: ' + data.loan.loan_number + '<br>Type: ' + data
                    .loan.loan_type, {
                        timeOut: 0,
                        extendedTimeOut: 0,
                    });

                var notificationCount = parseInt($('#notification-count').text()) + 1;
                $('#notification-count').text(notificationCount);

                var notificationHtml = `
        <li class="dropdown-item">
            <a href="{{ route('collection.loanProgress') }}" class="text-decoration-none">
                Loan Number: ${data.loan.loan_number} - Type: ${data.loan.loan_type}
                <p class="text-xs text-secondary mb-0">
                    <i class="fa fa-clock me-1"></i> Just now
                </p>
            </a>
        </li>`;
                $('#notification-list').prepend(
                    notificationHtml); // Prepend to show the new notification at the top
                $('#notification-dropdown').show();
            }
        });




        $('#notification-bell').click(function() {
            $('#notification-dropdown').toggle();
        });



        window.addEventListener('DOMContentLoaded', (event) => {
            const currentUserId = {{ auth()->id() }};


            $(document).on('click', function(e) {
                if (!$(e.target).closest('.dropdown').length) {
                    $('.dropdown-menu').removeClass('show');
                }
            });





            $(document).ready(function() {
                const currentUserId = {{ auth()->id() }}; // Ensure user is authenticated and has ID

                $.ajax({
                    url: `/api/notifications/${currentUserId}`, // Modify with your API endpoint to fetch notifications
                    method: 'GET',
                    success: function(data) {
                        if (data.notifications.length > 0) {
                            data.notifications.forEach(notification => {
                                var notificationHtml = `
                  <li class="dropdown-item">
                        <a href="{{ route('loan.approval') }}" class="text-decoration-none">
                            <strong>${notification.type}</strong><br>  <!-- Display type with a line break -->
                            ${notification.message}  <!-- Display message below type -->
                            <p class="text-xs text-secondary mb-0">
                                <i class="fa fa-clock me-1"></i> ${notification.time_ago}
                            </p>
                        </a>
                    </li>`;
                                $('#notification-list').append(notificationHtml);
                            });

                            // Update notification count
                            $('#notification-count').text(data.notifications.length);
                            $('#notification-dropdown').show();
                        } else {
                            $('#notification-list').html(
                                '<li class="dropdown-item text-center">No notifications</li>'
                            );
                            $('#notification-count').text(0);
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching notifications:', error);
                    }
                });
            });


        });
        $(document).on('click', '#dropdownMenuButton', function() {
            // Reset notification count to 0 when the bell is clicked
            $('#notification-count').text('0'); // Reset the notification count


            $.ajax({
                url: '/api/notifications/read',
                method: 'POST',
                data: {
                    user_id: currentUserId, // Pass the current user ID
                    _token: '{{ csrf_token() }}' // CSRF token for security
                },
                success: function(response) {
                    console.log('Notifications marked as read');
                },
                error: function(error) {
                    console.error('Error marking notifications as read:', error);
                }
            });
        });



        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('loader');
            window.skipLoader = false; // Make it global so export handlers can access it

            document.addEventListener('livewire:init', () => {
                Livewire.hook('request', () => {
                    if (!window.skipLoader) {
                        loader.style.display = 'flex';
                    }
                });

                Livewire.hook('message.processed', () => {
                    loader.style.display = 'none';
                    window.skipLoader = false; // Reset flag
                });
            });

            window.addEventListener('load', function() {
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 300);
            });

            window.addEventListener('beforeunload', function() {
                if (!window.isDownloading) {
                    loader.style.display = 'flex';
                }
            });

            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    loader.style.display = 'none';
                }
            });
        });
    </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}


    <script src="{{ asset('js/plugins/perfect-scrollbar.min.js') }}"></script>



    <script src="{{ asset('js/dashboard.min.js') }}"></script>
    {{-- <script src="{{ asset('js/customer1.js') }}"></script> --}}

    {{-- <script src="{{ asset('js/sweet.js') }}"></script> --}}

    <!-- customer list fetch in loan form -->


    {{-- <script src="{{ asset('js/plugins/choices.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/plugins/chartjs.min.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> --}}



    <script src="{{ asset('js/check.js') }}"></script>
    <script src="{{ asset('js/sweet.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>

    <!--@vite(['resources/js/app.js'])-->
</body>

</html>
