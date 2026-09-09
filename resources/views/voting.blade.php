<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pemilihan Ketua & Wakil Ketua OSIM</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --ev-primary: #0f5132;
            --ev-primary-hover: #146c43;
            --ev-primary-light: #d1e7dd;
            --ev-dark: #212529;
            --ev-muted: #6c757d;
            --ev-font: 'Plus Jakarta Sans', sans-serif;
        }

        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            margin: 0; padding: 0;
            font-family: var(--ev-font);
            height: 100vh;
            overflow: hidden;
            background: linear-gradient(135deg, #f4f6f9 0%, #e8f5e9 50%, #f4f6f9 100%);
        }

        .voting-page {
            height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 1rem 2rem;
        }

        /* ===== HEADER ===== */
        .voting-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            padding: 0.6rem 0;
            flex-shrink: 0;
        }

        .header-logo {
            width: 52px; height: 52px;
            filter: drop-shadow(0 4px 8px rgba(15, 81, 50, 0.15));
            flex-shrink: 0;
        }

        .header-text h1 {
            font-weight: 800;
            color: var(--ev-primary);
            font-size: 1.35rem;
            margin: 0;
            letter-spacing: -0.3px;
        }

        .header-text p {
            color: var(--ev-muted);
            font-size: 0.8rem;
            font-weight: 500;
            margin: 0;
        }

        /* ===== CANDIDATES GRID ===== */
        .candidates-area {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 0;
            padding: 0.5rem 0;
        }

        .candidates-row {
            display: flex;
            gap: 1.25rem;
            justify-content: center;
            align-items: stretch;
            width: 100%;
            max-width: 1200px;
            height: 100%;
            max-height: 520px;
        }

        .candidate-col {
            flex: 1;
            max-width: 320px;
            min-width: 0;
            display: flex;
        }

        .candidate-card {
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            width: 100%;
            opacity: 0;
            transform: translateY(20px);
            animation: cardFadeIn 0.5s ease forwards;
        }

        .candidate-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 35px rgba(15, 81, 50, 0.12);
            border-color: var(--ev-primary-light);
        }

        .candidate-number-badge {
            position: absolute;
            top: 10px; left: 10px;
            z-index: 10;
            background-color: var(--ev-primary);
            color: white;
            font-size: 1.15rem;
            font-weight: 800;
            width: 38px; height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 10px rgba(15, 81, 50, 0.4);
            border: 2px solid #ffffff;
        }

        .candidate-img-wrap {
            position: relative;
            width: 100%;
            padding-top: 75%;
            background: #f8f9fa;
            overflow: hidden;
            flex-shrink: 0;
        }

        .candidate-img-wrap img {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .candidate-card:hover .candidate-img-wrap img {
            transform: scale(1.05);
        }

        .candidate-body {
            padding: 0.9rem 1rem;
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
        }

        .role-label {
            font-size: 0.65rem;
            color: var(--ev-muted);
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 1px;
        }

        .name-text {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--ev-dark);
            line-height: 1.2;
            margin-bottom: 0;
        }

        .name-divider {
            height: 1px;
            background: rgba(0, 0, 0, 0.06);
            margin: 6px 0;
        }

        .vision-text {
            color: var(--ev-muted);
            font-size: 0.78rem;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin: 6px 0;
            flex-shrink: 1;
        }

        .card-actions {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 5px;
            padding-top: 6px;
        }

        .btn-detail {
            background: #f8f9fa;
            border: 1px solid rgba(0,0,0,0.08);
            color: var(--ev-dark);
            font-weight: 600;
            font-size: 0.78rem;
            padding: 6px 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-detail:hover {
            background: var(--ev-primary-light);
            border-color: var(--ev-primary-light);
        }

        .btn-pilih {
            background: var(--ev-primary);
            border: none;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 9px 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-pilih:hover {
            background: var(--ev-primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15, 81, 50, 0.25);
        }

        .btn-pilih:disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        /* ===== FOOTER BAR ===== */
        .voting-footer {
            text-align: center;
            padding: 0.4rem 0;
            font-size: 0.72rem;
            color: var(--ev-muted);
            flex-shrink: 0;
        }

        /* ===== CLOSED STATE ===== */
        .closed-box {
            text-align: center;
            background: #fff;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            border-top: 5px solid var(--ev-primary);
            max-width: 480px;
            animation: fadeIn 0.5s ease;
        }

        .closed-box i { font-size: 3rem; color: var(--ev-primary); margin-bottom: 1rem; }

        /* Pulse animation for the waiting indicator */
        .pulse-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: var(--ev-primary);
            animation: pulse 1.5s ease-in-out infinite;
            margin: 0 3px;
        }
        .pulse-dot:nth-child(2) { animation-delay: 0.3s; }
        .pulse-dot:nth-child(3) { animation-delay: 0.6s; }

        @keyframes pulse {
            0%, 100% { opacity: 0.3; transform: scale(0.8); }
            50% { opacity: 1; transform: scale(1.2); }
        }

        /* ===== THANK YOU OVERLAY ===== */
        #thankYouOverlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(244, 246, 249, 0.97);
            backdrop-filter: blur(8px);
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.4s ease;
        }
        #thankYouOverlay.active { display: flex; }

        .ty-box {
            max-width: 460px; width: 90%;
            text-align: center;
            background: #fff;
            padding: 2.5rem 2rem;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
        }
        .ty-box::before {
            content: "";
            position: absolute; top: 0; left: 0; right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--ev-primary), #198754);
        }

        .ty-icon {
            width: 80px; height: 80px;
            background: var(--ev-primary-light);
            color: var(--ev-primary);
            border-radius: 50%;
            display: inline-flex;
            align-items: center; justify-content: center;
            font-size: 2.8rem;
            margin-bottom: 1.2rem;
            animation: scaleIn 0.5s cubic-bezier(0.175,0.885,0.32,1.275);
        }

        .countdown-ring {
            position: relative;
            width: 90px; height: 90px;
            margin: 0 auto 1rem;
        }
        .countdown-ring svg { width: 90px; height: 90px; transform: rotate(-90deg); }
        .countdown-ring circle { fill: none; stroke-width: 7; }
        .countdown-ring .bg-ring { stroke: #f1f3f5; }
        .countdown-ring .bar-ring {
            stroke: var(--ev-primary);
            stroke-dasharray: 251.33; /* 2*pi*40 */
            stroke-dashoffset: 0;
            transition: stroke-dashoffset 1s linear;
        }
        .countdown-num {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%,-50%);
            font-size: 1.8rem; font-weight: 800;
            color: var(--ev-primary);
        }

        @keyframes fadeIn { from { opacity:0 } to { opacity:1 } }
        @keyframes scaleIn { from { transform:scale(0);opacity:0 } to { transform:scale(1);opacity:1 } }
        @keyframes cardFadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Modal adjustments */
        .modal-content { border-radius: 14px !important; }
        .modal-header.bg-ev { background-color: var(--ev-primary); color: #fff; border-top-left-radius: 13px !important; border-top-right-radius: 13px !important; }

        /* ===== RESPONSIVE MEDIA QUERIES ===== */
        @media (max-width: 991.98px) {
            html, body {
                height: auto;
                overflow-y: auto;
            }

            .voting-page {
                height: auto;
                min-height: 100vh;
                padding: 1.5rem 1rem;
            }

            .voting-header {
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
                padding-bottom: 1.5rem;
            }

            .voting-header img {
                width: 60px;
                height: 60px;
            }

            .header-text h1 {
                font-size: 1.25rem;
            }

            .header-text p {
                font-size: 0.75rem;
            }

            .candidates-area {
                align-items: flex-start;
                padding: 1rem 0;
            }

            .candidates-row {
                flex-direction: column;
                align-items: center;
                height: auto;
                max-height: none;
                gap: 1.5rem;
            }

            .candidate-col {
                max-width: 100%;
                width: 100%;
            }

            .candidate-card {
                max-width: 360px;
                margin: 0 auto;
            }

            .candidate-img-wrap {
                padding-top: 85%;
            }

            .name-text {
                font-size: 1rem;
            }

            .vision-text {
                font-size: 0.8rem;
                -webkit-line-clamp: 3;
            }
        }
    </style>
</head>
<body>
    <div class="voting-page">
        <!-- HEADER -->
        <div class="voting-header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
            <div class="header-text">
                <h1>Pemilihan Ketua dan Wakil Ketua OSIM</h1>
                <p>Madrasah Diniyah Darul Lughah Wal Karomah</p>
            </div>
        </div>

        <!-- MAIN AREA -->
        <div class="candidates-area" id="mainArea">
            <!-- Initial: Waiting / Closed state -->
            <div class="closed-box" id="closedBox">
                <i class="bi bi-lock-fill d-block"></i>
                <h3 class="fw-bold mb-2">Pemilihan Belum Dibuka</h3>
                <p class="text-muted mb-3">Sesi pemungutan suara saat ini sedang ditutup. Pemilihan hanya dapat dilakukan ketika panitia membuka sesi voting.</p>
                <div class="mt-2">
                    <span class="pulse-dot"></span>
                    <span class="pulse-dot"></span>
                    <span class="pulse-dot"></span>
                </div>
                <p class="text-muted mt-2 mb-0" style="font-size: 0.75rem;">Menunggu panitia membuka sesi...</p>
            </div>

            <!-- Candidates rendered here by JavaScript -->
            <div class="candidates-row" id="candidatesRow" style="display: none;"></div>
        </div>

        <!-- FOOTER -->
        <div class="voting-footer">
            &copy; {{ date('Y') }} Madrasah Diniyah Darul Lughah Wal Karomah
        </div>
    </div>

    <!-- THANK YOU OVERLAY -->
    <div id="thankYouOverlay">
        <div class="ty-box">
            <div class="ty-icon"><i class="bi bi-check-circle-fill"></i></div>
            <h3 style="font-weight:800;color:var(--ev-dark);margin-bottom:0.5rem">Suara Anda Berhasil Direkam</h3>
            <p style="color:var(--ev-muted);font-size:0.9rem;line-height:1.6;margin-bottom:1.5rem">
                Terima kasih atas partisipasi Anda dalam Pemilihan Ketua dan Wakil Ketua OSIM.
            </p>
            <div class="countdown-ring">
                <svg><circle class="bg-ring" cx="45" cy="45" r="40"/><circle class="bar-ring" id="progressBar" cx="45" cy="45" r="40"/></svg>
                <div class="countdown-num" id="countdownTimer">10</div>
            </div>
            <p style="font-size:0.8rem;color:var(--ev-muted);font-weight:600">Kembali ke halaman voting otomatis...</p>
        </div>
    </div>

    <!-- Modal container for dynamically created modals -->
    <div id="modalsContainer"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            'use strict';

            // ===== CONFIGURATION =====
            const POLL_INTERVAL = 3000;   // 3 seconds
            const COUNTDOWN = 10;         // seconds after voting
            const CIRC = 2 * Math.PI * 40; // 251.33

            // ===== STATE =====
            let currentStatus = 'closed';
            let candidates = [];
            let isVoting = false;  // true when thank-you overlay is active
            let pollTimer = null;
            let countdownInterval = null;

            // ===== DOM ELEMENTS =====
            const closedBox = document.getElementById('closedBox');
            const candidatesRow = document.getElementById('candidatesRow');
            const mainArea = document.getElementById('mainArea');
            const thankYouOverlay = document.getElementById('thankYouOverlay');
            const countdownTimer = document.getElementById('countdownTimer');
            const progressBar = document.getElementById('progressBar');
            const modalsContainer = document.getElementById('modalsContainer');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            // ===== POLLING =====
            function startPolling() {
                pollStatus(); // immediate first call
                pollTimer = setInterval(pollStatus, POLL_INTERVAL);
            }

            async function pollStatus() {
                if (isVoting) return; // don't poll while showing thank-you

                try {
                    const baseUrl = window.location.origin + window.location.pathname.replace(/\/$/, '');
                    const res = await fetch(baseUrl + '/api/voting-status', {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await res.json();

                    if (data.status !== currentStatus) {
                        currentStatus = data.status;
                        handleStatusChange(data.status);
                    }
                } catch (err) {
                    console.warn('Polling error:', err);
                }
            }

            async function handleStatusChange(status) {
                if (status === 'open') {
                    await loadCandidates();
                    showVotingUI();
                } else {
                    showClosedUI();
                }
            }

            // ===== LOAD CANDIDATES =====
            async function loadCandidates() {
                try {
                    const baseUrl = window.location.origin + window.location.pathname.replace(/\/$/, '');
                    const res = await fetch(baseUrl + '/api/candidates', {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    candidates = data.candidates || [];
                } catch (err) {
                    console.error('Failed to load candidates:', err);
                    candidates = [];
                }
            }

            // ===== UI TRANSITIONS =====
            function showVotingUI() {
                closedBox.style.display = 'none';
                candidatesRow.style.display = 'flex';
                renderCandidates();
            }

            function showClosedUI() {
                candidatesRow.style.display = 'none';
                candidatesRow.innerHTML = '';
                modalsContainer.innerHTML = '';
                closedBox.style.display = '';
                // Re-trigger animation
                closedBox.style.animation = 'none';
                closedBox.offsetHeight; // force reflow
                closedBox.style.animation = 'fadeIn 0.5s ease';
            }

            // ===== RENDER CANDIDATES =====
            function renderCandidates() {
                candidatesRow.innerHTML = '';
                modalsContainer.innerHTML = '';

                if (candidates.length === 0) {
                    candidatesRow.innerHTML = `
                        <div class="text-center text-muted">
                            <i class="bi bi-people-fill" style="font-size:3rem"></i>
                            <h4 class="mt-2">Belum Ada Kandidat</h4>
                        </div>
                    `;
                    return;
                }

                candidates.forEach((c, index) => {
                    const numStr = String(c.candidate_number).padStart(2, '0');
                    const modalId = `modal_${c.id}`;

                    // Card
                    const col = document.createElement('div');
                    col.className = 'candidate-col';
                    col.innerHTML = `
                        <div class="candidate-card" style="animation-delay: ${index * 0.1}s">
                            <div class="candidate-img-wrap">
                                <div class="candidate-number-badge">${numStr}</div>
                                <img src="${escapeHtml(c.photo_url)}" alt="Paslon ${c.candidate_number}">
                            </div>
                            <div class="candidate-body">
                                <div class="role-label">Ketua</div>
                                <p class="name-text">${escapeHtml(c.chairman_name)}</p>
                                <div class="name-divider"></div>
                                <div class="role-label">Wakil Ketua</div>
                                <p class="name-text">${escapeHtml(c.vice_chairman_name)}</p>
                                <p class="vision-text"><strong>Visi:</strong> ${escapeHtml(c.vision)}</p>
                                <div class="card-actions">
                                    <button class="btn-detail" data-bs-toggle="modal" data-bs-target="#${modalId}">
                                        <i class="bi bi-info-circle me-1"></i> Visi & Misi
                                    </button>
                                    <button class="btn-pilih btn-vote"
                                        data-id="${c.id}"
                                        data-number="${c.candidate_number}"
                                        data-names="${escapeAttr(c.chairman_name)} &amp; ${escapeAttr(c.vice_chairman_name)}">
                                        <i class="bi bi-check-circle me-1"></i> PILIH PASLON
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    candidatesRow.appendChild(col);

                    // Modal
                    const modalDiv = document.createElement('div');
                    modalDiv.innerHTML = `
                        <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header bg-ev">
                                        <h5 class="modal-title fw-bold">Visi & Misi Paslon ${numStr}</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="text-center mb-3">
                                            <h5 class="fw-bold mb-1" style="color:var(--ev-primary)">${escapeHtml(c.chairman_name)}</h5>
                                            <p class="text-muted mb-1">&amp;</p>
                                            <h5 class="fw-bold" style="color:var(--ev-primary)">${escapeHtml(c.vice_chairman_name)}</h5>
                                        </div>
                                        <hr>
                                        <h6 class="fw-bold"><i class="bi bi-eye me-1" style="color:var(--ev-primary)"></i>Visi</h6>
                                        <p class="text-muted bg-light p-3 rounded" style="white-space:pre-line;line-height:1.6">${escapeHtml(c.vision)}</p>
                                        <h6 class="fw-bold"><i class="bi bi-list-task me-1" style="color:var(--ev-primary)"></i>Misi</h6>
                                        <p class="text-muted bg-light p-3 rounded" style="white-space:pre-line;line-height:1.6">${escapeHtml(c.mission)}</p>
                                    </div>
                                    <div class="modal-footer border-0 bg-light">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button class="btn-pilih btn-vote" data-bs-dismiss="modal"
                                            data-id="${c.id}"
                                            data-number="${c.candidate_number}"
                                            data-names="${escapeAttr(c.chairman_name)} &amp; ${escapeAttr(c.vice_chairman_name)}"
                                            style="font-size:0.85rem;padding:8px 20px;">
                                            Pilih Paslon Ini
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    modalsContainer.appendChild(modalDiv.firstElementChild);
                });

                // Bind vote buttons
                bindVoteButtons();
            }

            // ===== VOTE HANDLING =====
            function bindVoteButtons() {
                document.querySelectorAll('.btn-vote').forEach(btn => {
                    btn.addEventListener('click', function () {
                        if (isVoting) return;
                        const { id, number, names } = this.dataset;
                        Swal.fire({
                            title: 'Konfirmasi Pilihan',
                            html: `Apakah Anda yakin memilih pasangan calon nomor <strong style="color:#0f5132">${number}</strong>?<br><b>${names}</b><br><br><small class="text-danger">Pilihan tidak dapat dibatalkan.</small>`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#0f5132',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Ya, Saya Yakin',
                            cancelButtonText: 'Batal',
                        }).then(r => { if (r.isConfirmed) submitVote(id); });
                    });
                });
            }

            async function submitVote(candidateId) {
                setAllButtons(true);

                try {
                    const baseUrl = window.location.origin + window.location.pathname.replace(/\/$/, '');
                    const res = await fetch(baseUrl + '/vote', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ candidate_id: candidateId })
                    });
                    const data = await res.json();

                    if (data.success) {
                        showThankYou();
                    } else {
                        Swal.fire('Kesalahan', data.message, 'error');
                        setAllButtons(false);
                    }
                } catch (err) {
                    Swal.fire('Kesalahan', 'Koneksi gagal. Periksa jaringan Anda.', 'error');
                    setAllButtons(false);
                }
            }

            function setAllButtons(disabled) {
                document.querySelectorAll('.btn-vote').forEach(b => {
                    b.disabled = disabled;
                    b.style.opacity = disabled ? '0.5' : '1';
                });
            }

            // ===== THANK YOU OVERLAY =====
            function showThankYou() {
                isVoting = true;
                thankYouOverlay.classList.add('active');
                let remaining = COUNTDOWN;
                countdownTimer.textContent = remaining;
                progressBar.style.strokeDashoffset = '0';

                countdownInterval = setInterval(() => {
                    remaining--;
                    countdownTimer.textContent = remaining;
                    progressBar.style.strokeDashoffset = CIRC - ((remaining / COUNTDOWN) * CIRC);

                    if (remaining <= 0) {
                        clearInterval(countdownInterval);
                        hideThankYou();
                    }
                }, 1000);
            }

            function hideThankYou() {
                thankYouOverlay.classList.remove('active');
                isVoting = false;
                setAllButtons(false);
                progressBar.style.strokeDashoffset = '0';
                countdownTimer.textContent = COUNTDOWN;

                // After thank-you, re-check status immediately
                // If still open, re-render candidates fresh (reset UI for next voter)
                pollStatus();
            }

            // ===== HELPERS =====
            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text || '';
                return div.innerHTML;
            }

            function escapeAttr(text) {
                return (text || '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#39;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            }

            // ===== INIT =====
            document.addEventListener('DOMContentLoaded', () => {
                startPolling();
            });
        })();
    </script>
</body>
</html>
