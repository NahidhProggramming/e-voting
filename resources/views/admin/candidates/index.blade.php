@extends('layouts.app')

@section('title', 'Kelola Kandidat Paslon - E-Voting OSIM')

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

    .candidate-thumbnail {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid rgba(0,0,0,0.05);
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
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-link">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
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
    <!-- Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: 10px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 10px;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header Card -->
    <div class="card card-custom p-4 mb-4">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <div>
                <h3 class="fw-bold text-dark mb-1">Daftar Pasangan Calon</h3>
                <p class="text-muted mb-0">Kelola informasi kandidat yang akan tampil pada halaman voting bilik suara. (Maksimal 5 Pasangan)</p>
            </div>
            <div>
                @if($candidates->count() < 5)
                    <a href="{{ route('admin.candidates.create') }}" class="btn btn-primary-custom fw-bold py-2">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Kandidat
                    </a>
                @else
                    <button class="btn btn-secondary-custom fw-bold py-2 text-muted" disabled>
                        <i class="bi bi-info-circle me-1"></i> Batas Kandidat Terpenuhi
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Candidate List Table -->
    <div class="card card-custom p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 8%">No. Urut</th>
                        <th style="width: 10%">Foto</th>
                        <th style="width: 25%">Nama Ketua</th>
                        <th style="width: 25%">Nama Wakil Ketua</th>
                        <th style="width: 22%">Visi Singkat</th>
                        <th style="width: 10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($candidates as $candidate)
                        <tr>
                            <td class="fw-extrabold text-primary-custom text-center" style="font-size: 1.25rem;">
                                {{ sprintf('%02d', $candidate->candidate_number) }}
                            </td>
                            <td>
                                <img src="{{ asset('storage/' . $candidate->photo) }}" class="candidate-thumbnail" alt="Paslon {{ $candidate->candidate_number }}">
                            </td>
                            <td class="fw-bold text-dark">{{ $candidate->chairman_name }}</td>
                            <td class="fw-bold text-dark">{{ $candidate->vice_chairman_name }}</td>
                            <td>
                                <span class="d-inline-block text-truncate text-muted" style="max-width: 250px;" title="{{ $candidate->vision }}">
                                    {{ $candidate->vision }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group gap-2">
                                    <a href="{{ route('admin.candidates.edit', $candidate->id) }}" class="btn btn-sm btn-outline-primary border-0" style="border-radius: 6px;" title="Ubah">
                                        <i class="bi bi-pencil-square" style="font-size: 1.1rem;"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger border-0" style="border-radius: 6px;" onclick="confirmDelete({{ $candidate->id }}, '{{ $candidate->chairman_name }} & {{ $candidate->vice_chairman_name }}')" title="Hapus">
                                        <i class="bi bi-trash-fill" style="font-size: 1.1rem;"></i>
                                    </button>
                                    <form id="deleteForm{{ $candidate->id }}" action="{{ route('admin.candidates.destroy', $candidate->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-people display-3 mb-3 d-block text-muted"></i>
                                <h5>Belum Ada Data Kandidat</h5>
                                <p class="mb-0">Silakan tambahkan data pasangan calon baru untuk memulai.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmDelete(id, names) {
        Swal.fire({
            title: 'Hapus Kandidat?',
            html: `Apakah Anda yakin ingin menghapus pasangan calon:<br><strong class="text-danger">${names}</strong>?<br><br>Seluruh data perolehan suara paslon ini juga akan terhapus.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            borderRadius: '16px'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm' + id).submit();
            }
        });
    }
</script>
@endsection
