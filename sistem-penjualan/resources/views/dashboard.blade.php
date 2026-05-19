@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid px-4">
    <div class="dashboard-hero mb-3">
        <div class="dashboard-hero-overlay text-center text-white">
            <div>
                <h1>Selamat Datang, {{ Auth::user()->name }}</h1>
                <p class="mb-1">Role: {{ in_array(Auth::user()->role, ['admin','owner']) ? 'Owner' : 'Karyawan' }}</p>
                <p>SpeedShop2 siap bantu kelola penjualan, stok, dan laporan lebih cepat setiap hari.</p>
            </div>
        </div>
    </div>

    <!-- CHART -->
    <div class="row">
        <!-- Monthly -->
        <div class="col-md-6 mb-4">
            <div class="card custom-card">
                <div class="card-body">
                    <h6 class="card-title">
                        Penjualan per Bulan ({{ date('Y') }})
                    </h6>
                    <canvas id="monthlyChart" height="180"></canvas>
                </div>
            </div>
        </div>

        <!-- Overall -->
        <div class="col-md-6 mb-4">
            <div class="card custom-card">
                <div class="card-body">
                    <h6 class="card-title">
                        Total Penjualan
                    </h6>

                    <div class="total-badge">
                        Rp {{ number_format($totalSales, 0, ',', '.') }}
                    </div>

                    <canvas id="overallChart" height="180"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // BAR CHART
    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            datasets: [{
                data: @json($monthlySales),
                backgroundColor: 'rgba(60,130,246,0.7)',
                borderRadius: 6
            }]
        },
        options: {
            plugins: { legend: { display: false }},
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: val => 'Rp ' + val.toLocaleString('id-ID')
                    }
                }
            }
        }
    });

    // LINE CHART
    new Chart(document.getElementById('overallChart'), {
        type: 'line',
        data: {
            labels: @json($salesDates),
            datasets: [{
                data: @json($salesAmounts),
                borderColor: '#22c55e',
                backgroundColor: 'rgba(34,197,94,0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 2
            }]
        },
        options: {
            plugins: { legend: { display: false }},
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: val => 'Rp ' + val.toLocaleString('id-ID')
                    }
                }
            }
        }
    });
</script>

<!-- STYLE -->
<style>
/* HERO */
.dashboard-hero {
    padding: 1.4rem;
    border-radius: 14px;
    background: linear-gradient(135deg, #0a2a5e, #0f4d8b);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}

.dashboard-hero-overlay {
    min-height: 110px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.dashboard-hero h1 {
    font-size: 1.6rem;
    font-weight: 600;
    margin-bottom: 4px;
}

.dashboard-hero p {
    font-size: 0.9rem;
    opacity: 0.85;
    margin: 0;
}

/* CARD */
.custom-card {
    border: none;
    border-radius: 14px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}

/* TITLE */
.card-title {
    font-weight: 600;
    margin-bottom: 10px;
}

/* BADGE TOTAL */
.total-badge {
    display: inline-block;
    background: #22c55e;
    color: white;
    padding: 6px 14px;
    border-radius: 8px;
    font-weight: 600;
    margin-bottom: 10px;
    font-size: 0.9rem;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .dashboard-hero {
        padding: 1.2rem;
    }

    .dashboard-hero h1 {
        font-size: 1.4rem;
    }
}
</style>

@endsection