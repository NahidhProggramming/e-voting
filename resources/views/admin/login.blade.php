@extends('layouts.app')

@section('title', 'Login Administrator - E-Voting OSIM')

@section('styles')
<style>
    .login-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-card {
        width: 100%;
        max-width: 420px;
        background: #ffffff;
        border-radius: var(--ev-border-radius);
        box-shadow: var(--ev-card-shadow);
        border: 1px solid rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }

    .login-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--ev-primary) 0%, #198754 100%);
    }

    .login-header {
        text-align: center;
        padding: 2.5rem 2rem 1.5rem;
    }

    .login-logo {
        width: 65px;
        height: 65px;
        margin-bottom: 1rem;
        filter: drop-shadow(0 4px 10px rgba(15, 81, 50, 0.15));
    }

    .login-title {
        font-weight: 800;
        color: var(--ev-primary);
        font-size: 1.5rem;
        margin-bottom: 0.25rem;
    }

    .login-subtitle {
        color: var(--ev-muted);
        font-size: 0.85rem;
        font-weight: 500;
    }

    .login-body {
        padding: 0 2rem 2.5rem;
    }

    .form-group-custom {
        margin-bottom: 1.25rem;
    }

    .form-label-custom {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--ev-dark);
        margin-bottom: 6px;
        display: inline-block;
    }

    .input-icon-wrapper {
        position: relative;
    }

    .input-icon-wrapper i {
        position: absolute;
        top: 50%;
        left: 15px;
        transform: translateY(-50%);
        color: var(--ev-muted);
    }

    .input-icon-wrapper .form-control {
        padding-left: 45px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="login-wrapper">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <!-- SVG Logo -->
                <svg class="login-logo" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="login-logo-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#0f5132;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#198754;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <polygon points="50,5 95,25 95,75 50,95 5,75 5,25" fill="url(#login-logo-grad)" />
                    <polygon points="50,12 87,28 87,72 50,88 13,72 13,28" fill="#ffffff" />
                    <!-- Shield icon silhouette inside -->
                    <path d="M50,25 C60,25 65,30 65,42 C65,55 53,68 50,71 C47,68 35,55 35,42 C35,30 40,25 50,25 Z" fill="url(#login-logo-grad)" />
                </svg>
                <h2 class="login-title">Admin E-Voting</h2>
                <p class="login-subtitle">Silakan login untuk mengelola pemilihan</p>
            </div>

            <!-- Body -->
            <div class="login-body">
                <form action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf

                    <!-- Error Alert -->
                    @if($errors->any())
                        <div class="alert alert-danger p-2" style="font-size: 0.85rem; border-radius: 8px;">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Username -->
                    <div class="form-group-custom">
                        <label for="username" class="form-label-custom">Username</label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-person"></i>
                            <input type="text" name="username" id="username" class="form-control form-control-custom" placeholder="Masukkan username" value="{{ old('username') }}" required autofocus autocomplete="username">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="form-group-custom mb-4">
                        <label for="password" class="form-label-custom">Password</label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-shield-lock"></i>
                            <input type="password" name="password" id="password" class="form-control form-control-custom" placeholder="Masukkan password" required autocomplete="current-password">
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="form-check mb-4">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                        <label for="remember" class="form-check-label text-muted" style="font-size: 0.85rem;">Ingat Sesi Saya</label>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary-custom">
                            Masuk Dashboard
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
