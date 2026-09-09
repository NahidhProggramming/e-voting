@extends('layouts.app')

@section('title', 'Terima Kasih - Suara Berhasil Direkam')

@section('styles')
<style>
    .thank-you-container {
        max-width: 600px;
        margin: 5rem auto;
        text-align: center;
        background: #ffffff;
        padding: 4rem 3rem;
        border-radius: var(--ev-border-radius);
        box-shadow: var(--ev-card-shadow);
        position: relative;
        overflow: hidden;
    }

    .thank-you-container::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--ev-primary) 0%, #198754 100%);
    }

    .success-icon-wrapper {
        width: 100px;
        height: 100px;
        background-color: var(--ev-primary-light);
        color: var(--ev-primary);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        margin-bottom: 2rem;
        animation: scaleIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    .thank-you-title {
        font-weight: 800;
        color: var(--ev-dark);
        margin-bottom: 1rem;
        font-size: 2rem;
    }

    .thank-you-message {
        color: var(--ev-muted);
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 3rem;
    }

    /* Circular Countdown Ring */
    .countdown-circle {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto 2rem;
    }

    .countdown-circle svg {
        width: 120px;
        height: 120px;
        transform: rotate(-90deg);
    }

    .countdown-circle circle {
        fill: none;
        stroke-width: 8;
    }

    .countdown-circle circle.bg {
        stroke: #f1f3f5;
    }

    .countdown-circle circle.bar {
        stroke: var(--ev-primary);
        stroke-dasharray: 351.85; /* 2 * pi * r (r=56) */
        stroke-dashoffset: 0;
        transition: stroke-dashoffset 1s linear;
    }

    .countdown-number {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--ev-primary);
    }

    .countdown-text {
        font-size: 0.9rem;
        color: var(--ev-muted);
        font-weight: 600;
    }

    @keyframes scaleIn {
        from {
            transform: scale(0);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="thank-you-container">
        <!-- Success Icon -->
        <div class="success-icon-wrapper">
            <i class="bi bi-check-circle-fill"></i>
        </div>

        <h2 class="thank-you-title">Suara Anda Berhasil Direkam</h2>
        <p class="thank-you-message">Terima kasih atas partisipasi Anda dalam Pemilihan Ketua dan Wakil Ketua OSIM Madrasah Diniyah Darul Lughah Wal Karomah. Suara Anda menentukan masa depan organisasi.</p>

        <!-- Countdown Timer -->
        <div class="countdown-circle">
            <svg>
                <circle class="bg" cx="60" cy="60" r="56" />
                <circle class="bar" id="progressBar" cx="60" cy="60" r="56" />
            </svg>
            <div class="countdown-number" id="countdownTimer">{{ $remaining }}</div>
        </div>

        <p class="countdown-text">Kembali ke halaman voting secara otomatis...</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let remainingSeconds = {{ $remaining }};
    const totalSeconds = 20;
    const countdownTimer = document.getElementById('countdownTimer');
    const progressBar = document.getElementById('progressBar');
    
    // Circumference of circle = 2 * pi * r = 2 * 3.14159 * 56 = 351.85
    const circumference = 351.85;

    function updateTimer() {
        // Update number text
        countdownTimer.textContent = remainingSeconds;

        // Update progress bar stroke-dashoffset
        const offset = circumference - ((remainingSeconds / totalSeconds) * circumference);
        progressBar.style.strokeDashoffset = offset;

        if (remainingSeconds <= 0) {
            window.location.href = "{{ route('voting.index') }}";
        } else {
            remainingSeconds--;
            setTimeout(updateTimer, 1000);
        }
    }

    // Initialize timer
    updateTimer();

    // Prevent going back
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
</script>
@endsection
