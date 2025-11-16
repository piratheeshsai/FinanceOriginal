<div>
    <!-- Header with branch selector -->
    <div class="d-flex justify-content-between align-items-center mb-4 px-3 py-2 bg-light rounded-3 shadow-sm">
        <!-- Left side title -->
        <h5 class="mb-0 text-muted fw-semibold">
            <i class="fas fa-chart-line me-2"></i>Dashboard Overview
        </h5>

        <!-- Right side branch info -->
        <div class="d-flex align-items-center gap-3">
            @if (auth()->user()->hasPermissionTo('view all branches'))
                <div class="position-relative" style="min-width: 200px;">
                    <select wire:model.live="selectedBranch"
                        class="form-select form-select-sm border-0 bg-white shadow-sm py-2 ps-3 pe-5 rounded-pill hide-native-arrow">
                        <option value="all">All Branches</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    <span class="position-absolute end-0 top-50 translate-middle-y me-3">
                        <i class="fas fa-chevron-down text-secondary fs-12"></i>
                    </span>
                </div>
            @else
                <div class="d-flex align-items-center gap-2 bg-white px-3 py-2 rounded-pill shadow-sm">
                    <i class="fas fa-map-marker-alt text-primary"></i>
                    <span class="text-muted small">Current Branch:</span>
                    <span class="fw-semibold text-truncate" style="max-width: 150px;">
                        {{ auth()->user()->branch->name ?? 'N/A' }}
                    </span>
                </div>
            @endif
        </div>
    </div>

    <!-- First row - Key metrics cards -->
    <div class="row g-3">
        <!-- Today's Collection -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Today's Collection</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ number_format($todayCollection['amount'], 0) }}
                                    @if($todayCollection['growth'] > 0)
                                    <span class="text-success text-sm font-weight-bolder">+{{ $todayCollection['growth'] }}%</span>
                                    @else
                                    <span class="text-danger text-sm font-weight-bolder">{{ $todayCollection['growth'] }}%</span>
                                    @endif
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fa-solid fa-coins"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Principal Collected -->
        @can('View Accounts cards')


        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Principal Collected</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ number_format($todayPrincipalCollected['amount'], 0) }}
                                    @if($todayPrincipalCollected['growth'] > 0)
                                    <span class="text-success text-sm font-weight-bolder">+{{ $todayPrincipalCollected['growth'] }}%</span>
                                    @else
                                    <span class="text-danger text-sm font-weight-bolder">{{ $todayPrincipalCollected['growth'] }}%</span>
                                    @endif
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fa-solid fa-wallet text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Interest Collected -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Interest Collected</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ number_format($todayInterestCollected['amount'], 0) }}
                                    @if($todayInterestCollected['growth'] > 0)
                                    <span class="text-success text-sm font-weight-bolder">+{{ $todayInterestCollected['growth'] }}%</span>
                                    @else
                                    <span class="text-danger text-sm font-weight-bolder">{{ $todayInterestCollected['growth'] }}%</span>
                                    @endif
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fa-solid fa-percentage text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Cash in Hand -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Cash in Hand</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ number_format($cashInHand['amount'], 0) }}
                                    @if($cashInHand['growth'] > 0)
                                    <span class="text-success text-sm font-weight-bolder">+{{ $cashInHand['growth'] }}%</span>
                                    @else
                                    <span class="text-danger text-sm font-weight-bolder">{{ $cashInHand['growth'] }}%</span>
                                    @endif
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fa-solid fa-money-bill-wave text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endcan
    {{-- </div>

    <!-- Second row - Additional metrics -->

    <div class="row g-3 mt-1"> --}}
        <!-- Total Customers -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Customers</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ number_format($totalCustomers['count'], 0) }}
                                    @if($totalCustomers['growth'] > 0)
                                    <span class="text-success text-sm font-weight-bolder">+{{ $totalCustomers['growth'] }}%</span>
                                    @else
                                    <span class="text-danger text-sm font-weight-bolder">{{ $totalCustomers['growth'] }}%</span>
                                    @endif
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fa-solid fa-users text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Active Loans -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Active Loans</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ number_format($totalActiveLoans['count'], 0) }}
                                    @if($totalActiveLoans['growth'] > 0)
                                    <span class="text-success text-sm font-weight-bolder">+{{ $totalActiveLoans['growth'] }}%</span>
                                    @else
                                    <span class="text-danger text-sm font-weight-bolder">{{ $totalActiveLoans['growth'] }}%</span>
                                    @endif
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fa-solid fa-file-invoice-dollar text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

         <!-- Today Pending -->
         <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Today Pending</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ number_format($todayPendingCollection['amount'], 0) }}
                                    @if($todayPendingCollection['growth'] > 0)
                                    <span class="text-success text-sm font-weight-bolder">+{{ $todayPendingCollection['growth'] }}%</span>
                                    @else
                                    <span class="text-danger text-sm font-weight-bolder">{{ $todayPendingCollection['growth'] }}%</span>
                                    @endif
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fa-solid fa-clock text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Total User -->
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total User</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ number_format($totalUsers['count'], 0) }}
                                    @if($totalUsers['growth'] > 0)
                                    <span class="text-success text-sm font-weight-bolder">+{{ $totalUsers['growth'] }}%</span>
                                    @else
                                    <span class="text-danger text-sm font-weight-bolder">{{ $totalUsers['growth'] }}%</span>
                                    @endif
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fa-solid fa-user-shield text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="row g-3 mt-3">

        <div class="col-lg-8">
            @can('Monthly Collection Chart')

            <div class="card mb-3">
                <div class="card-body p-3">
                    <div class="bg-gradient-dark border-radius-lg py-3 pe-1 mb-3">
                        <div wire:ignore>
                            <canvas id="monthlyCollectionChart"></canvas>
                        </div>
                    </div>
                    <h6 class="ms-2 mt-4 mb-0">Active Users</h6>
                    <p class="text-sm ms-2">
                        (<span class="font-weight-bolder">+{{ $activeUsersCount }}</span>) Last 1 Minute
                    </p>
                </div>
            </div>
            @endcan


            <div class="card mb-3">
                <div class="card-header pb-0">
                    <h6>Loan Overview</h6>
                    <p class="text-sm">
                        <i class="fa fa-arrow-up text-success"></i>
                        <span class="font-weight-bold">Monthly comparison of loan distribution and collection</span>
                    </p>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="chart-line" class="chart-canvas" height="300" wire:ignore></canvas>
                    </div>
                </div>
            </div>

            @can('Account Overview Graph')
            <div class="card mb-3">
                    <div class="card mb-3">
                        <div class="card-header pb-0">
                            <h6>Accounts Overview</h6>
                            <p class="text-sm">
                                <i class="fa fa-arrow-up text-success"></i>
                                <span class="font-weight-bold">Branch Revenue Expense Profit</span>
                            </p>
                        </div>
                        <div class="card-body p-3">
                            <div class="chart">
                                <canvas id="chart-line-accounts" class="chart-canvas" height="300" wire:ignore></canvas>
                            </div>
                        </div>
                    </div>
            </div>
            @endcan
        </div>


        <div class="col-lg-4">

            <div class="card mb-3">
                <div class="card-header pb-0">
                    <h6>Last Activities</h6>
                    <p class="text-sm">
                        <i class="fa fa-arrow-up text-success" aria-hidden="true"></i>
                        <span class="font-weight-bold">Latest activities</span> from your branch
                    </p>
                </div>

                {{-- style="max-height: 400px; overflow-y: auto;" --}}
                <div class="card-body p-3" >
                    <div class="timeline timeline-one-side" wire:poll.40s="refreshActivities">
                        @forelse($recentActivities as $activity)
                            <div class="timeline-block mb-3">
                                <span class="timeline-step">
                                    <i class="fa {{ $activity['icon'] }} text-{{ $activity['color'] }} text-gradient"></i>
                                </span>
                                <div class="timeline-content">
                                    <h6 class="text-dark text-sm font-weight-bold mb-0">{{ $activity['model'] }}: {{ $activity['details'] }}</h6>
                                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $activity['created_at_diff'] }} ({{ $activity['created_at'] }})</p>
                                </div>
                            </div>
                        @empty
                            <div class="timeline-block mb-3">
                                <span class="timeline-step">
                                    <i class="fa fa-info-circle text-info text-gradient"></i>
                                </span>
                                <div class="timeline-content">
                                    <h6 class="text-dark text-sm font-weight-bold mb-0">No recent activities</h6>
                                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Try changing branch or check back later</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Weekly Collections Chart -->
            <div class="card mt-4">
                <div class="card-body p-3">
                    <h6>Your Weekly Collections</h6>
                    <div class="chart pt-3">
                        <canvas id="chart-cons-week" class="chart-canvas" height="200" wire:ignore></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fourth row - Accounts Overview Chart -->



        <style>
            /* Custom select styling */
            .custom-branch-select {
                border: 1px solid #e0e0e0;
                transition: all 0.3s ease;
                appearance: none;
            }

            .custom-branch-select:focus {
                border-color: #0d6efd;
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            }

            /* Branch display styling */
            .branch-pill {
                background: rgba(13, 110, 253, 0.1);
                border-radius: 20px;
                padding: 8px 15px;


            }

            /* Hide native dropdown arrow in all browsers */
            .hide-native-arrow {
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                background-image: none;
                padding-right: 1.5rem;
                /* Space for our custom arrow */
            }

            /* Firefox specific fixes */
            @-moz-document url-prefix() {
                .hide-native-arrow {
                    text-indent: -2px;
                    text-overflow: '';
                    padding-right: 25px;
                    /* Extra padding for Firefox */
                    width: calc(100% + 2px);
                    /* Fix width overflow */
                }

                /* Target specifically Windows Firefox */
                _:-moz-tree-row(hover),
                .hide-native-arrow {
                    padding-right: 35px;
                }
            }

            /* Edge specific fix */
            @supports (-ms-ime-align:auto) {
                .hide-native-arrow {
                    &::-ms-expand {
                        display: none;
                    }
                }
            }

            /* Hide native dropdown arrow */
        </style>
    </div>





    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script>
 document.addEventListener('livewire:init', () => {
    // Monthly Collection Chart (Bar chart)
    const ctxBarElement = document.getElementById('monthlyCollectionChart');
    let barChart = null;

    if (ctxBarElement) {
        const ctxBar = ctxBarElement.getContext('2d');

        function initBarChart(data) {
            if (barChart) barChart.destroy();

            barChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                    ],
                    datasets: [{
                        label: "Collections",
                        tension: 0.4,
                        borderWidth: 0,
                        borderRadius: 4,
                        borderSkipped: false,
                        backgroundColor: "#fff",
                        data: data,
                        maxBarThickness: 9
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: false,
                                drawBorder: false,
                            },
                            ticks: {
                                color: '#fff'
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false,
                            },
                            ticks: {
                                color: '#fff'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        // Initial render
        initBarChart(@json($this->monthlyTotals));

        // Update chart when branch selection changes
        Livewire.on('branchUpdated', () => {
            initBarChart(@this.monthlyTotals);
        });
    }

    // Loan Overview Chart (Line chart)
    const chartLineElement = document.getElementById("chart-line");
    let lineChart = null;

    if (chartLineElement) {
        const ctxLine = chartLineElement.getContext("2d");

        function initLineChart(collectionData, distributionData) {
            // IMPORTANT FIX: Completely destroy the previous chart instance
            if (lineChart) {
                lineChart.destroy();
                lineChart = null;
            }

            // Find the maximum value to set appropriate scale
            // Filter out any NaN or null values before calculating max
            const collectionValues = collectionData.filter(val => !isNaN(val) && val !== null);
            const distributionValues = distributionData.filter(val => !isNaN(val) && val !== null);

            // Handle empty arrays
            const maxCollection = collectionValues.length > 0 ? Math.max(...collectionValues) : 0;
            const maxDistribution = distributionValues.length > 0 ? Math.max(...distributionValues) : 0;

            // Use the higher of the two maximums
            const maxValue = Math.max(maxCollection, maxDistribution);

            // Add 20% padding to the max value to avoid chart touching the top
            // If maxValue is 0, set a default minimum scale
            const yAxisMax = maxValue > 0 ? maxValue * 1.2 : 1000;

            var gradientStroke1 = ctxLine.createLinearGradient(0, 230, 0, 50);
            gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
            gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
            gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)');

            var gradientStroke2 = ctxLine.createLinearGradient(0, 230, 0, 50);
            gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
            gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
            gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)');

            // Create a new chart instance
            lineChart = new Chart(ctxLine, {
                type: "line",
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct",
                        "Nov", "Dec"
                    ],
                    datasets: [{
                            label: "Loan Collection",
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 2,
                            borderColor: "#cb0c9f",
                            backgroundColor: gradientStroke1,
                            fill: true,
                            data: collectionData,
                            maxBarThickness: 6
                        },
                        {
                            label: "Loan Distribution",
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 2,
                            borderColor: "#3A416F",
                            backgroundColor: gradientStroke2,
                            fill: true,
                            data: distributionData,
                            maxBarThickness: 6
                        }
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Loan Overview (Collection vs Distribution)'
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: yAxisMax,
                            grid: {
                                drawBorder: false,
                                display: true,
                                drawOnChartArea: true,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: true,
                                padding: 10,
                                color: '#b2b9bf',
                                font: {
                                    size: 11,
                                    family: "Open Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                },
                                // Format large numbers for better readability
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return (value / 1000000).toFixed(1) + 'M';
                                    } else if (value >= 1000) {
                                        return (value / 1000).toFixed(1) + 'K';
                                    }
                                    return value;
                                }
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: true,
                                color: '#b2b9bf',
                                padding: 20,
                                font: {
                                    size: 11,
                                    family: "Open Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                },
                            }
                        },
                    },
                    // Disable animations for more reliable updates
                    animation: false
                },
            });
        }

        // Initial render
        initLineChart(@json($this->monthlyTotals), @json($this->monthlyLoanDistribution));

        // Update chart when branch selection changes
        Livewire.on('branchUpdated', () => {
            initLineChart(@this.monthlyTotals, @this.monthlyLoanDistribution);
        });
    }

    // Accounts Overview Chart (Line chart)
    const accountsChartElement = document.getElementById("chart-line-accounts");
    let accountsLineChart = null;

    if (accountsChartElement) {
        const ctxAccountsLine = accountsChartElement.getContext("2d");

        function initAccountsLineChart(revenueData, expenseData, profitData) {
            // Destroy previous chart if it exists
            if (accountsLineChart) {
                accountsLineChart.destroy();
                accountsLineChart = null;
            }

            // Find maximum value for scale
            const revenueValues = revenueData.filter(val => !isNaN(val) && val !== null);
            const expenseValues = expenseData.filter(val => !isNaN(val) && val !== null);
            const profitValues = profitData.filter(val => !isNaN(val) && val !== null);

            const maxRevenue = revenueValues.length > 0 ? Math.max(...revenueValues) : 0;
            const maxExpense = expenseValues.length > 0 ? Math.max(...expenseValues) : 0;
            const maxProfit = profitValues.length > 0 ? Math.max(...profitValues) : 0;

            const maxValue = Math.max(maxRevenue, maxExpense, maxProfit);
            const yAxisMax = maxValue > 0 ? maxValue * 1.2 : 1000;

            var gradientStroke1 = ctxAccountsLine.createLinearGradient(0, 230, 0, 50);
            gradientStroke1.addColorStop(1, 'rgba(203,12,23,0.2)');  // Vibrant Emerald Green (30% opacity)
            gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)'); // Light Green (10% opacity)
            gradientStroke1.addColorStop(0, 'rgba(203,23,159,0)');  // Fully Transparent


            var gradientStroke2 = ctxAccountsLine.createLinearGradient(0, 230, 0, 50);
            gradientStroke2.addColorStop(1, 'rgba(255, 69, 58, 0.3)');  // Fiery Red (30% opacity)
            gradientStroke2.addColorStop(0.5, 'rgba(255, 99, 71, 0.1)'); // Warm Sunset (10% opacity)
            gradientStroke2.addColorStop(0, 'rgba(255, 130, 97, 0)');  // Fully Transparent
            ;

            var gradientStroke3 = ctxAccountsLine.createLinearGradient(0, 230, 0, 50);
            gradientStroke3.addColorStop(1, 'rgba(30, 144, 255, 0.3)');  // Deep Ocean Blue (30% opacity)
            gradientStroke3.addColorStop(0.5, 'rgba(70, 130, 180, 0.1)'); // Steel Blue (10% opacity)
            gradientStroke3.addColorStop(0, 'rgba(100, 149, 237, 0)');  // Fully Transparent


            // Create a new chart instance
            accountsLineChart = new Chart(ctxAccountsLine, {
                type: "line",
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [
                        {
                            label: "Revenue",
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 2,
                            borderColor: "#3A416F",
                            backgroundColor: gradientStroke1,
                            fill: true,
                            data: revenueData,
                            maxBarThickness: 6
                        },
                        {
                            label: "Expense",
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 2,
                            borderColor: "#cb0c9f",
                            backgroundColor: gradientStroke2,
                            fill: true,
                            data: expenseData,
                            maxBarThickness: 6
                        },
                        {
                            label: "Profit",
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 2,
                            borderColor: "#17c1e8",
                            backgroundColor: gradientStroke3,
                            fill: true,
                            data: profitData,
                            maxBarThickness: 6
                        }
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Branch Financial Overview'
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: yAxisMax,
                            grid: {
                                drawBorder: false,
                                display: true,
                                drawOnChartArea: true,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: true,
                                padding: 10,
                                color: '#b2b9bf',
                                font: {
                                    size: 11,
                                    family: "Open Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                },
                                // Format large numbers for better readability
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return (value / 1000000).toFixed(1) + 'M';
                                    } else if (value >= 1000) {
                                        return (value / 1000).toFixed(1) + 'K';
                                    }
                                    return value;
                                }
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: true,
                                color: '#b2b9bf',
                                padding: 20,
                                font: {
                                    size: 11,
                                    family: "Open Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                },
                            }
                        },
                    },
                    // Disable animations for more reliable updates
                    animation: false
                },
            });
        }

        // Initial render
        initAccountsLineChart(@json($this->monthlyRevenue), @json($this->monthlyExpense), @json($this->monthlyProfit));

        // Update chart when branch selection changes
        Livewire.on('branchUpdated', () => {
            initAccountsLineChart(@this.monthlyRevenue, @this.monthlyExpense, @this.monthlyProfit);
        });
    }

    // Weekly Collections Chart
    const weeklyChartElement = document.getElementById("chart-cons-week");
    let weeklyChart = null;

    if (weeklyChartElement) {
        const ctx = weeklyChartElement.getContext("2d");

        // Get the weekly collection data
        const weeklyData = @json($weeklyUserCollections);

        // Find the maximum value in the collection data
        const weeklyValues = weeklyData.filter(val => !isNaN(val) && val !== null);
        const maxWeeklyValue = weeklyValues.length > 0 ? Math.max(...weeklyValues) : 0;

        // Add 20% padding to the max value to give some space at the top of the chart
        const yAxisMaxWeekly = maxWeeklyValue > 0 ? maxWeeklyValue * 1.2 : 1000;

        weeklyChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                datasets: [{
                    label: "Amount",
                    tension: 0.4,
                    borderWidth: 0,
                    borderRadius: 4,
                    borderSkipped: false,
                    backgroundColor: "#3A416F",
                    data: weeklyData,
                    maxBarThickness: 6
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: yAxisMaxWeekly, // Dynamic max value based on data
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#9ca2b7',
                            // Optional: Format large numbers for better readability
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return (value / 1000000).toFixed(1) + 'M';
                                } else if (value >= 1000) {
                                    return (value / 1000).toFixed(1) + 'K';
                                }
                                return value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#9ca2b7'
                        }
                    },
                },
            },
        });

        // Update chart when branch is changed
        Livewire.on('branchUpdated', function() {
            // Update data
            weeklyChart.data.datasets[0].data = @this.weeklyUserCollections;

            // Recalculate max value when data changes
            const newWeeklyData = @this.weeklyUserCollections;
            const newWeeklyValues = newWeeklyData.filter(val => !isNaN(val) && val !== null);
            const newMaxWeeklyValue = newWeeklyValues.length > 0 ? Math.max(...newWeeklyValues) : 0;
            const newYAxisMaxWeekly = newMaxWeeklyValue > 0 ? newMaxWeeklyValue * 1.2 : 1000;

            // Update the y-axis scale
            weeklyChart.options.scales.y.suggestedMax = newYAxisMaxWeekly;

            // Apply changes
            weeklyChart.update();
        });
    }
});

    </script>

