<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Vote;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VotingController extends Controller
{
    /**
     * Display the voting page (single-page, no reload).
     */
    public function index()
    {
        $votingStatus = Setting::get('voting_status', 'closed');
        $candidates = [];

        if ($votingStatus === 'open') {
            try {
                $candidates = Candidate::orderBy('candidate_number', 'asc')->get();
            } catch (\Throwable $e) {
                $candidates = [];
            }
        }

        return view('voting', compact('candidates', 'votingStatus'));
    }

    /**
     * Store the cast vote via AJAX. Returns JSON.
     */
    public function vote(Request $request)
    {
        // Only accept AJAX requests
        if (!$request->ajax() && !$request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Invalid request.'], 400);
        }

        $votingStatus = Setting::get('voting_status', 'closed');
        if ($votingStatus !== 'open') {
            return response()->json([
                'success' => false,
                'message' => 'Sesi pemilihan saat ini sedang ditutup.'
            ], 403);
        }

        // Prevent voting if session lock is still active (10 seconds)
        if (session()->has('voted_at')) {
            $votedAt = session('voted_at');
            $elapsed = time() - $votedAt;
            if ($elapsed < 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memberikan suara. Silakan tunggu.',
                    'remaining' => 10 - $elapsed
                ], 429);
            }
        }

        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
        ]);

        // Wrap the vote creation in a database transaction for consistency
        DB::transaction(function () use ($request) {
            Vote::create([
                'candidate_id' => $request->candidate_id,
            ]);
        });

        // Set session lock for 10 seconds
        session(['voted_at' => time()]);

        return response()->json([
            'success' => true,
            'message' => 'Suara Anda berhasil direkam.',
            'countdown' => 10
        ]);
    }

    /**
     * Thank you page fallback (kept for direct URL access).
     */
    public function thankYou()
    {
        return redirect()->route('voting.index');
    }

    /**
     * API: Check voting status (polled by voter booths every few seconds).
     */
    public function checkStatus()
    {
        $status = Setting::get('voting_status', 'closed');

        return response()->json([
            'status' => $status,
        ]);
    }

    /**
     * API: Get all candidates data (called once when booth detects status = open).
     */
    public function getCandidates()
    {
        $candidates = Candidate::orderBy('candidate_number', 'asc')->get();

        $data = $candidates->map(function ($c) {
            return [
                'id' => $c->id,
                'candidate_number' => $c->candidate_number,
                'chairman_name' => $c->chairman_name,
                'vice_chairman_name' => $c->vice_chairman_name,
                'vision' => $c->vision,
                'mission' => $c->mission,
                'photo_url' => asset('storage/' . $c->photo),
            ];
        });

        return response()->json([
            'candidates' => $data,
        ]);
    }
}
