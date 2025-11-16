// dashboard-charts.js

// Chart instances storage
let barChart = null;
let lineChart = null;
let accountsChart = null;
let weeklyChart = null;

// Initialize everything when Livewire is ready
document.addEventListener('livewire:init', () => {
    initializeCharts();
    setupLivewireListeners();
});

function initializeCharts() {
    // Initial load from Livewire component properties
    Livewire.first().then(component => {
        const data = {
            monthlyTotals: component.get('monthlyTotals'),
            monthlyLoanDistribution: component.get('monthlyLoanDistribution'),
            monthlyRevenue: component.get('monthlyRevenue'),
            monthlyExpense: component.get('monthlyExpense'),
            monthlyProfit: component.get('monthlyProfit'),
            weeklyUserCollections: component.get('weeklyUserCollections')
        };
        createAllCharts(data);
    });
}

function setupLivewireListeners() {
    Livewire.on('branchUpdated', data => {
        console.log('Received update data:', data);

        // Destroy old charts
        destroyAllCharts();

        // Small delay for DOM stability
        setTimeout(() => {
            createAllCharts(data);
        }, 50);
    });
}

function createAllCharts(data) {
    try {
        validateChartData(data);
        initBarChart(data.monthlyTotals);
        initLineChart(data.monthlyTotals, data.monthlyLoanDistribution);
        initAccountsChart(data.monthlyRevenue, data.monthlyExpense, data.monthlyProfit);
        initWeeklyChart(data.weeklyUserCollections);
    } catch (error) {
        console.error('Chart creation failed:', error);
        // Handle error (e.g., show user message)
    }
}

function destroyAllCharts() {
    [barChart, lineChart, accountsChart, weeklyChart].forEach(chart => {
        if (chart) {
            chart.destroy();
            chart = null;
        }
    });
}

function validateChartData(data) {
    const requiredFields = [
        'monthlyTotals', 'monthlyLoanDistribution',
        'monthlyRevenue', 'monthlyExpense', 'monthlyProfit',
        'weeklyUserCollections'
    ];

    requiredFields.forEach(field => {
        if (!data[field] || !Array.isArray(data[field])) {
            throw new Error(`Invalid data for ${field}`);
        }
    });
}

// Chart initialization functions
function initBarChart(monthlyTotals) {
    const ctx = document.getElementById('monthlyCollectionChart');
    if (!ctx) return;

    if (barChart) barChart.destroy();

    barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: "Collections",
                tension: 0.4,
                borderWidth: 0,
                borderRadius: 4,
                borderSkipped: false,
                backgroundColor: "#fff",
                data: monthlyTotals,
                maxBarThickness: 9
            }]
        },
        options: getBarChartOptions()
    });
}

function initLineChart(collections, distributions) {
    const ctx = document.getElementById('chart-line')?.getContext('2d');
    if (!ctx) return;

    if (lineChart) lineChart.destroy();

    lineChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: "Loan Collection",
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 2,
                borderColor: "#cb0c9f",
                backgroundColor: createGradient(ctx, ['rgba(203,12,159,0.2)', 'rgba(72,72,176,0.0)', 'rgba(203,12,159,0)']),
                fill: true,
                data: collections,
                maxBarThickness: 6
            }, {
                label: "Loan Distribution",
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 2,
                borderColor: "#3A416F",
                backgroundColor: createGradient(ctx, ['rgba(20,23,39,0.2)', 'rgba(72,72,176,0.0)', 'rgba(20,23,39,0)']),
                fill: true,
                data: distributions,
                maxBarThickness: 6
            }]
        },
        options: getLineChartOptions(collections, distributions)
    });
}

function initAccountsChart(revenue, expense, profit) {
    const ctx = document.getElementById('chart-line-accounts')?.getContext('2d');
    if (!ctx) return;

    if (accountsChart) accountsChart.destroy();

    accountsChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: "Revenue",
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 2,
                borderColor: "#3A416F",
                backgroundColor: createGradient(ctx, ['rgba(203,12,23,0.2)', 'rgba(72,72,176,0.0)', 'rgba(203,23,159,0)']),
                fill: true,
                data: revenue,
                maxBarThickness: 6
            }, {
                label: "Expense",
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 2,
                borderColor: "#cb0c9f",
                backgroundColor: createGradient(ctx, ['rgba(255,69,58,0.3)', 'rgba(255,99,71,0.1)', 'rgba(255,130,97,0)']),
                fill: true,
                data: expense,
                maxBarThickness: 6
            }, {
                label: "Profit",
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 2,
                borderColor: "#17c1e8",
                backgroundColor: createGradient(ctx, ['rgba(30,144,255,0.3)', 'rgba(70,130,180,0.1)', 'rgba(100,149,237,0)']),
                fill: true,
                data: profit,
                maxBarThickness: 6
            }]
        },
        options: getAccountsChartOptions(revenue, expense, profit)
    });
}

function initWeeklyChart(weeklyData) {
    const ctx = document.getElementById('chart-cons-week');
    if (!ctx) return;

    if (weeklyChart) weeklyChart.destroy();

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
            }]
        },
        options: getWeeklyChartOptions(weeklyData)
    });
}

// Configuration helpers
function getBarChartOptions() {
    return {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                grid: { display: false, drawBorder: false },
                ticks: { color: '#fff' }
            },
            x: {
                grid: { display: false, drawBorder: false },
                ticks: { color: '#fff' }
            }
        },
        plugins: { legend: { display: false } }
    };
}

function getLineChartOptions(collections, distributions) {
    const maxValue = Math.max(
        Math.max(...collections.filter(Number.isFinite)),
        Math.max(...distributions.filter(Number.isFinite))
    );

    return {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: true, position: 'top' },
            title: { display: true, text: 'Loan Overview (Collection vs Distribution)' }
        },
        interaction: { intersect: false, mode: 'index' },
        scales: {
            y: {
                beginAtZero: true,
                suggestedMax: maxValue * 1.2,
                grid: { drawBorder: false, display: true, drawOnChartArea: true, borderDash: [5, 5] },
                ticks: {
                    padding: 10,
                    color: '#b2b9bf',
                    font: { size: 11, family: "Open Sans" },
                    callback: value => formatLargeNumbers(value)
                }
            },
            x: {
                grid: { display: false },
                ticks: {
                    padding: 20,
                    color: '#b2b9bf',
                    font: { size: 11, family: "Open Sans" }
                }
            }
        },
        animation: false
    };
}

// Reusable utility functions
function createGradient(ctx, colors) {
    const gradient = ctx.createLinearGradient(0, 230, 0, 50);
    gradient.addColorStop(1, colors[0]);
    gradient.addColorStop(0.2, colors[1]);
    gradient.addColorStop(0, colors[2]);
    return gradient;
}

function formatLargeNumbers(value) {
    if (value >= 1e6) return `${(value/1e6).toFixed(1)}M`;
    if (value >= 1e3) return `${(value/1e3).toFixed(1)}K`;
    return value;
}
