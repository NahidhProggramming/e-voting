@extends('layouts.app')

@section('title', 'Dashboard Administrator - E-Voting OSIM')

@section('styles')
<style>
    .admin-navbar {
        background-color: var(--ev-primary);
        color: #ffffff;
        padding: 1rem 0;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(15, 81, 50, 0.15);
    }
    
    .admin-navbar-brand {
        font-weight: 800;
        font-size: 1.3rem;
        color: #ffffff;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .admin-nav-link {
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .admin-nav-link:hover {
        color: #ffffff;
    }

    .stat-card {
        padding: 1.5rem;
        border-radius: var(--ev-border-radius);
        background: #ffffff;
        box-shadow: var(--ev-card-shadow);
        border: 1px solid rgba(0, 0, 0, 0.04);
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .stat-info {
        flex-grow: 1;
    }

    .stat-title {
        font-size: 0.85rem;
        color: var(--ev-muted);
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--ev-dark);
        line-height: 1.2;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.85rem;
        display: inline-block;
    }

    .status-badge-open {
        background-color: var(--ev-primary-light);
        color: var(--ev-primary);
    }

    .status-badge-closed {
        background-color: #f8d7da;
        color: #842029;
    }
</style>
@endsection

@section('content')
<!-- Custom Navbar -->
<nav class="admin-navbar">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.dashboard') }}" class="admin-navbar-brand">
            <i class="bi bi-shield-lock-fill"></i> Admin Panel OSIM
        </a>
        <div class="d-flex align-items-center gap-4">
            <a href="{{ route('voting.index') }}" target="_blank" class="admin-nav-link">
                <i class="bi bi-box-arrow-up-right me-1"></i> Buka Bilik Suara
            </a>
            <a href="{{ route('admin.candidates.index') }}" class="admin-nav-link">
                <i class="bi bi-people-fill me-1"></i> Kelola Kandidat
            </a>
            <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm fw-bold px-3" style="border-radius: 8px;">
                    <i class="bi bi-box-arrow-right me-1"></i> Keluar
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container">
    <!-- Success alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: 10px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Dashboard Stats Row -->
    <div class="row g-4 mb-4">
        <!-- Status Pemilihan -->
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-primary-custom text-white">
                    <i class="bi bi-power"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-title">Status Pemilihan</div>
                    <div class="mt-1 mb-2">
                        @if($votingStatus === 'open')
                            <span class="status-badge status-badge-open"><i class="bi bi-unlock-fill me-1"></i> Dibuka</span>
                        @else
                            <span class="status-badge status-badge-closed"><i class="bi bi-lock-fill me-1"></i> Ditutup</span>
                        @endif
                    </div>
                    <form action="{{ route('admin.toggle-status') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn {{ $votingStatus === 'open' ? 'btn-danger' : 'btn-success' }} btn-sm w-100 fw-bold py-2" style="border-radius: 8px;">
                            <i class="bi {{ $votingStatus === 'open' ? 'bi-lock-fill' : 'bi-unlock-fill' }} me-1"></i>
                            {{ $votingStatus === 'open' ? 'Tutup Sesi Pemilihan' : 'Buka Sesi Pemilihan' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Total Suara Masuk -->
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-success text-white" style="background-color: #198754 !important;">
                    <i class="bi bi-envelope-paper-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-title">Total Suara Masuk</div>
                    <div class="stat-value" id="totalVotesText">{{ $totalVotes }}</div>
                    <div class="text-muted" style="font-size: 0.8rem; margin-top: 5px;">
                        <i class="bi bi-clock-history"></i> Suara terupdate real-time
                    </div>
                </div>
            </div>
        </div>

        <!-- Kandidat Terdaftar -->
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-secondary text-dark" style="background-color: var(--ev-primary-light) !important; color: var(--ev-primary) !important;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-title">Kandidat Paslon</div>
                    <div class="stat-value">{{ $candidates->count() }}</div>
                    <div class="text-muted" style="font-size: 0.8rem; margin-top: 5px;">
                        <a href="{{ route('admin.candidates.index') }}" class="text-primary-custom fw-bold text-decoration-none">
                            Kelola Kandidat <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4 mb-5">
        <!-- Results Column / Chart -->
        <div class="col-lg-8">
            <div class="card card-custom h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-1 text-dark">Grafik Hasil Suara</h4>
                        @if($votingStatus === 'open')
                            <small class="text-warning fw-semibold"><i class="bi bi-exclamation-triangle"></i> Pratinjau Real-Time (Sesi Voting Masih Dibuka)</small>
                        @else
                            <small class="text-success fw-semibold"><i class="bi bi-check-circle"></i> Hasil Akhir Resmi (Sesi Voting Ditutup)</small>
                        @endif
                    </div>
                    <!-- Export Actions -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.export.pdf') }}" class="btn btn-outline-danger btn-sm fw-bold" style="border-radius: 8px;">
                            <i class="bi bi-file-pdf-fill me-1"></i> PDF
                        </a>
                        <a href="{{ route('admin.export.excel') }}" class="btn btn-outline-success btn-sm fw-bold" style="border-radius: 8px;">
                            <i class="bi bi-file-earmark-spreadsheet-fill me-1"></i> Excel
                        </a>
                    </div>
                </div>

                <!-- Chart canvas container -->
                <div id="chartContainer" style="position: relative; height: 320px; width: 100%; display: {{ $totalVotes > 0 ? 'block' : 'none' }};">
                    <canvas id="votesChart"></canvas>
                </div>
                <!-- Empty state placeholder -->
                <div id="noVotesPlaceholder" class="text-center py-5 my-auto text-muted" style="display: {{ $totalVotes > 0 ? 'none' : 'block' }};">
                    <i class="bi bi-bar-chart-line display-1"></i>
                    <h5 class="mt-3">Belum Ada Suara Masuk</h5>
                    <p>Buka sesi pemilihan dan biarkan siswa memberikan hak suaranya.</p>
                </div>
            </div>
        </div>

        <!-- Reset & Candidates Quick View -->
        <div class="col-lg-4">
            <!-- Reset Card -->
            <div class="card card-custom mb-4 p-4 border-danger" style="border-left: 5px solid #dc3545 !important;">
                <h5 class="fw-bold text-danger mb-2"><i class="bi bi-trash3-fill me-1"></i> Reset Pemilihan</h5>
                <p class="text-muted" style="font-size: 0.85rem;">Menghapus seluruh data suara masuk. Data kandidat tetap utuh. Aksi ini tidak dapat dibatalkan.</p>
                <button type="button" class="btn btn-danger w-100 fw-bold py-2 mt-2" onclick="confirmReset()" style="border-radius: 8px;">
                    <i class="bi bi-trash3 me-1"></i> Reset Seluruh Suara
                </button>
                <form id="resetForm" action="{{ route('admin.reset-votes') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>

            <!-- Candidates Vote Table Quick View -->
            <div class="card card-custom p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-list-ol me-1"></i> Detail Perolehan</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Pasangan Calon</th>
                                <th class="text-center">Suara</th>
                                <th class="text-end">%</th>
                            </tr>
                        </thead>
                        <tbody id="votesTableBody">
                            @forelse($candidates as $cand)
                                @php
                                    $pct = $totalVotes > 0 ? ($cand->votes_count / $totalVotes) * 100 : 0;
                                @endphp
                                <tr>
                                    <td class="fw-bold">{{ $cand->candidate_number }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $cand->chairman_name }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">& {{ $cand->vice_chairman_name }}</div>
                                    </td>
                                    <td class="text-center fw-bold">{{ $cand->votes_count }}</td>
                                    <td class="text-end fw-semibold text-primary-custom">{{ round($pct, 1) }}%</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Tidak ada kandidat terdaftar</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let myChart = null;
    const backgroundColors = [
        '#0f5132', // Deep green
        '#198754', // Medium green
        '#20c997', // Mint green
        '#146c43', // Forest green
        '#d1e7dd'  // Soft mint
    ];

    function initChart(labels, data, totalVotes) {
        const ctx = document.getElementById('votesChart').getContext('2d');
        myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Perolehan Suara',
                    data: data,
                    backgroundColor: backgroundColors.slice(0, data.length),
                    borderRadius: 8,
                    borderWidth: 0,
                    barThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const val = context.raw;
                                const pct = totalVotes > 0 ? ((val / totalVotes) * 100).toFixed(1) : 0;
                                return ` ${val} Suara (${pct}%)`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                family: "'Plus Jakarta Sans', sans-serif"
                            }
                        },
                        grid: {
                            drawBorder: false,
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                family: "'Plus Jakarta Sans', sans-serif",
                                weight: 'bold'
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Initial chart build if we already have votes on page load
        @if($totalVotes > 0)
            const labels = [];
            const data = [];
            @foreach($candidates as $cand)
                labels.push("Paslon {{ $cand->candidate_number }} ({{ $cand->chairman_name }})");
                data.push({{ $cand->votes_count }});
            @endforeach
            initChart(labels, data, {{ $totalVotes }});
        @endif

        // Start polling for real-time results
        startRealtimePolling();
    });

    function startRealtimePolling() {
        setInterval(async () => {
            try {
                const res = await fetch("{{ route('admin.realtime-results') }}", {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (!res.ok) return;
                const data = await res.json();

                // Update Total Votes Text
                document.getElementById('totalVotesText').textContent = data.totalVotes;

                // Update Candidates table
                const tableBody = document.getElementById('votesTableBody');
                if (data.candidates.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">Tidak ada kandidat terdaftar</td>
                        </tr>
                    `;
                } else {
                    let html = '';
                    data.candidates.forEach(cand => {
                        html += `
                            <tr>
                                <td class="fw-bold">${cand.candidate_number}</td>
                                <td>
                                    <div class="fw-bold">${escapeHtml(cand.chairman_name)}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">& ${escapeHtml(cand.vice_chairman_name)}</div>
                                </td>
                                <td class="text-center fw-bold">${cand.votes_count}</td>
                                <td class="text-end fw-semibold text-primary-custom">${cand.percentage}%</td>
                            </tr>
                        `;
                    });
                    tableBody.innerHTML = html;
                }

                // Update or Initialize Chart
                const chartContainer = document.getElementById('chartContainer');
                const noVotesPlaceholder = document.getElementById('noVotesPlaceholder');

                if (data.totalVotes > 0) {
                    chartContainer.style.display = 'block';
                    noVotesPlaceholder.style.display = 'none';

                    const labels = data.candidates.map(c => `Paslon ${c.candidate_number} (${c.chairman_name})`);
                    const votes = data.candidates.map(c => c.votes_count);

                    if (myChart) {
                        myChart.data.labels = labels;
                        myChart.data.datasets[0].data = votes;
                        // Update the tooltip callback closure reference for new totalVotes
                        myChart.options.plugins.tooltip.callbacks.label = function(context) {
                            const val = context.raw;
                            const pct = ((val / data.totalVotes) * 100).toFixed(1);
                            return ` ${val} Suara (${pct}%)`;
                        };
                        myChart.update();
                    } else {
                        initChart(labels, votes, data.totalVotes);
                    }
                } else {
                    chartContainer.style.display = 'none';
                    noVotesPlaceholder.style.display = 'block';
                    if (myChart) {
                        myChart.destroy();
                        myChart = null;
                    }
                }
            } catch (err) {
                console.warn('Real-time polling error:', err);
            }
        }, 3000); // Poll every 3 seconds
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    }

    // Confirm Reset Action with SweetAlert2
    function confirmReset() {
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            html: 'Seluruh perolehan suara masuk akan <strong>DIHAPUS permanen</strong>!<br><span class="text-danger font-weight-bold">Aksi ini tidak dapat dibatalkan!</span>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Reset Sekarang',
            cancelButtonText: 'Batal',
            borderRadius: '16px'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('resetForm').submit();
            }
        });
    }
</script>
@endsection
