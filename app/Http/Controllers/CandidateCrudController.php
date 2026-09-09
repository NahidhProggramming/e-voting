<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateCrudController extends Controller
{
    /**
     * Display a listing of the candidates.
     */
    public function index()
    {
        $candidates = Candidate::orderBy('candidate_number', 'asc')->get();
        return view('admin.candidates.index', compact('candidates'));
    }

    /**
     * Show the form for creating a new candidate.
     */
    public function create()
    {
        if (Candidate::count() >= 5) {
            return redirect()->route('admin.candidates.index')
                ->with('error', 'Gagal menambah kandidat. Jumlah maksimal kandidat adalah 5 pasang.');
        }
        return view('admin.candidates.form');
    }

    /**
     * Store a newly created candidate in storage.
     */
    public function store(Request $request)
    {
        if (Candidate::count() >= 5) {
            return redirect()->route('admin.candidates.index')
                ->with('error', 'Gagal menambah kandidat. Jumlah maksimal kandidat adalah 5 pasang.');
        }

        $request->validate([
            'candidate_number' => 'required|integer|unique:candidates,candidate_number',
            'chairman_name' => 'required|string|max:255',
            'vice_chairman_name' => 'required|string|max:255',
            'vision' => 'required|string',
            'mission' => 'required|string',
            'photo' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('candidates', 'public');
        }

        Candidate::create([
            'candidate_number' => $request->candidate_number,
            'chairman_name' => $request->chairman_name,
            'vice_chairman_name' => $request->vice_chairman_name,
            'vision' => $request->vision,
            'mission' => $request->mission,
            'photo' => $photoPath,
        ]);

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Kandidat berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified candidate.
     */
    public function edit(Candidate $candidate)
    {
        return view('admin.candidates.form', compact('candidate'));
    }

    /**
     * Update the specified candidate in storage.
     */
    public function update(Request $request, Candidate $candidate)
    {
        $request->validate([
            'candidate_number' => 'required|integer|unique:candidates,candidate_number,' . $candidate->id,
            'chairman_name' => 'required|string|max:255',
            'vice_chairman_name' => 'required|string|max:255',
            'vision' => 'required|string',
            'mission' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $data = [
            'candidate_number' => $request->candidate_number,
            'chairman_name' => $request->chairman_name,
            'vice_chairman_name' => $request->vice_chairman_name,
            'vision' => $request->vision,
            'mission' => $request->mission,
        ];

        if ($request->hasFile('photo')) {
            // Delete old photo if exists and is not a seeded SVG
            if ($candidate->photo && Storage::disk('public')->exists($candidate->photo) && !str_contains($candidate->photo, 'candidate_')) {
                Storage::disk('public')->delete($candidate->photo);
            }
            $data['photo'] = $request->file('photo')->store('candidates', 'public');
        }

        $candidate->update($data);

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Data kandidat berhasil diperbarui.');
    }

    /**
     * Remove the specified candidate from storage.
     */
    public function destroy(Candidate $candidate)
    {
        // Delete photo if exists and is not a seeded SVG
        if ($candidate->photo && Storage::disk('public')->exists($candidate->photo) && !str_contains($candidate->photo, 'candidate_')) {
            Storage::disk('public')->delete($candidate->photo);
        }

        $candidate->delete();

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Kandidat berhasil dihapus.');
    }
}
