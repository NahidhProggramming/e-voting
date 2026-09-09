@extends('layouts.app')

@section('title', isset($candidate) ? 'Ubah Kandidat - E-Voting OSIM' : 'Tambah Kandidat - E-Voting OSIM')

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

    .form-card {
        background: #ffffff;
        border-radius: var(--ev-border-radius);
        box-shadow: var(--ev-card-shadow);
        border: 1px solid rgba(0,0,0,0.04);
        padding: 2.5rem;
    }

    .photo-preview-box {
        width: 150px;
        height: 150px;
        border-radius: 12px;
        background-color: #f8f9fa;
        border: 2px dashed rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .photo-preview-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
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
            <a href="{{ route('admin.candidates.index') }}" class="admin-nav-link">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center mb-5">
        <div class="col-lg-8">
            <div class="form-card">
                <h3 class="fw-bold text-dark mb-4">
                    {{ isset($candidate) ? 'Ubah Data Pasangan Calon' : 'Tambah Pasangan Calon Baru' }}
                </h3>
                <hr class="mb-4">

                <form action="{{ isset($candidate) ? route('admin.candidates.update', $candidate->id) : route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($candidate))
                        @method('PUT')
                    @endif

                    <!-- Validation Errors -->
                    @if($errors->any())
                        <div class="alert alert-danger p-3 mb-4" style="border-radius: 10px;">
                            <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill"></i> Mohon periksa kembali:</h6>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Candidate Number -->
                    <div class="mb-4">
                        <label for="candidate_number" class="form-label fw-bold">Nomor Urut Pasangan Calon</label>
                        <input type="number" name="candidate_number" id="candidate_number" class="form-control form-control-custom" placeholder="Contoh: 1" value="{{ old('candidate_number', $candidate->candidate_number ?? '') }}" required min="1" max="99">
                        <div class="form-text">Masukkan angka nomor urut kandidat. Nomor urut harus unik.</div>
                    </div>

                    <div class="row">
                        <!-- Chairman Name -->
                        <div class="col-md-6 mb-4">
                            <label for="chairman_name" class="form-label fw-bold">Nama Calon Ketua</label>
                            <input type="text" name="chairman_name" id="chairman_name" class="form-control form-control-custom" placeholder="Masukkan nama calon ketua" value="{{ old('chairman_name', $candidate->chairman_name ?? '') }}" required>
                        </div>

                        <!-- Vice Chairman Name -->
                        <div class="col-md-6 mb-4">
                            <label for="vice_chairman_name" class="form-label fw-bold">Nama Calon Wakil Ketua</label>
                            <input type="text" name="vice_chairman_name" id="vice_chairman_name" class="form-control form-control-custom" placeholder="Masukkan nama calon wakil ketua" value="{{ old('vice_chairman_name', $candidate->vice_chairman_name ?? '') }}" required>
                        </div>
                    </div>

                    <!-- Vision -->
                    <div class="mb-4">
                        <label for="vision" class="form-label fw-bold">Visi Singkat</label>
                        <textarea name="vision" id="vision" class="form-control form-control-custom" rows="3" placeholder="Masukkan visi singkat pasangan calon..." required>{{ old('vision', $candidate->vision ?? '') }}</textarea>
                    </div>

                    <!-- Mission -->
                    <div class="mb-4">
                        <label for="mission" class="form-label fw-bold">Misi Lengkap</label>
                        <textarea name="mission" id="mission" class="form-control form-control-custom" rows="5" placeholder="Masukkan misi lengkap pasangan calon (gunakan baris baru untuk setiap butir misi)..." required>{{ old('mission', $candidate->mission ?? '') }}</textarea>
                    </div>

                    <!-- Photo Upload -->
                    <div class="mb-4">
                        <label for="photo" class="form-label fw-bold">Foto Pasangan Calon</label>
                        
                        <div class="d-flex align-items-start gap-4">
                            <!-- Photo Preview Container -->
                            <div class="photo-preview-box">
                                @if(isset($candidate) && $candidate->photo)
                                    <img src="{{ asset('storage/' . $candidate->photo) }}" id="preview" class="photo-preview-img" alt="Pratinjau">
                                @else
                                    <img src="" id="preview" class="photo-preview-img d-none" alt="Pratinjau">
                                    <i class="bi bi-image text-muted" id="preview-icon" style="font-size: 2.5rem;"></i>
                                @endif
                            </div>
                            
                            <!-- File Input control -->
                            <div class="flex-grow-1">
                                <input type="file" name="photo" id="photo" class="form-control form-control-custom" accept="image/*" {{ isset($candidate) ? '' : 'required' }} onchange="previewImage(this)">
                                <div class="form-text mt-2">Format file: jpeg, png, jpg, svg. Ukuran maksimal 2MB.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <hr class="my-4">
                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('admin.candidates.index') }}" class="btn btn-secondary-custom">Batal</a>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="bi bi-save me-1"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Live preview of uploaded photo
    function previewImage(input) {
        const preview = document.getElementById('preview');
        const icon = document.getElementById('preview-icon');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                if (icon) {
                    icon.classList.add('d-none');
                }
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
