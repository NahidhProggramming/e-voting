<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Vote;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $totalVotes = Vote::count();
        $candidates = Candidate::withCount('votes')->orderBy('candidate_number', 'asc')->get();
        $votingStatus = Setting::get('voting_status', 'closed');

        return view('admin.dashboard', compact('totalVotes', 'candidates', 'votingStatus'));
    }

    /**
     * Toggle the voting session status (open/closed).
     */
    public function toggleStatus(Request $request)
    {
        $currentStatus = Setting::get('voting_status', 'closed');
        $newStatus = $currentStatus === 'open' ? 'closed' : 'open';
        
        Setting::set('voting_status', $newStatus);

        $statusText = $newStatus === 'open' ? 'dibuka' : 'ditutup';
        return redirect()->route('admin.dashboard')
            ->with('success', "Masa pemungutan suara berhasil {$statusText}.");
    }

    /**
     * Reset the voting counts.
     */
    public function resetVotes()
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
            Vote::truncate();
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Vote::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Seluruh hasil pemilihan telah berhasil direset.');
    }

    /**
     * Export the results to Excel (CSV format formatted for MS Excel).
     */
    public function exportExcel()
    {
        $candidates = Candidate::withCount('votes')->orderBy('candidate_number', 'asc')->get();
        $totalVotes = Vote::count();

        $fileName = 'hasil_evoting_' . date('Ymd_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($candidates, $totalVotes) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM to make Excel open it with correct encoding
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header Row
            fputcsv($file, ['Nomor Urut', 'Calon Ketua', 'Calon Wakil Ketua', 'Jumlah Suara', 'Persentase']);

            // Data Rows
            foreach ($candidates as $cand) {
                $percentage = $totalVotes > 0 ? round(($cand->votes_count / $totalVotes) * 100, 2) : 0;
                fputcsv($file, [
                    $cand->candidate_number,
                    $cand->chairman_name,
                    $cand->vice_chairman_name,
                    $cand->votes_count,
                    $percentage . '%'
                ]);
            }

            // Summary Row
            fputcsv($file, ['', '', 'Total Suara Masuk', $totalVotes, '100%']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export the results to PDF.
     */
    public function exportPdf()
    {
        $candidates = Candidate::withCount('votes')->orderBy('candidate_number', 'asc')->get();
        $totalVotes = Vote::count();
        $date = date('d-m-Y H:i:s');

        // Build HTML content for PDF
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Laporan Hasil Pemilihan Ketua & Wakil Ketua OSIM</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    color: #222;
                    margin: 0;
                    padding: 0;
                }
                .header {
                    text-align: center;
                    border-bottom: 3px double #0f5132;
                    padding-bottom: 15px;
                    margin-bottom: 25px;
                }
                .school-name {
                    font-size: 20px;
                    font-weight: bold;
                    color: #0f5132;
                    text-transform: uppercase;
                    margin-bottom: 5px;
                }
                .school-sub {
                    font-size: 12px;
                    color: #555;
                    margin-bottom: 10px;
                }
                .doc-title {
                    font-size: 16px;
                    font-weight: bold;
                    margin-top: 10px;
                    color: #333;
                }
                .date-info {
                    font-size: 11px;
                    color: #666;
                    margin-top: 5px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 40px;
                }
                th {
                    background-color: #0f5132;
                    color: white;
                    font-weight: bold;
                    padding: 10px;
                    border: 1px solid #ddd;
                    font-size: 12px;
                    text-transform: uppercase;
                }
                td {
                    padding: 10px;
                    border: 1px solid #ddd;
                    font-size: 12px;
                }
                .text-center {
                    text-align: center;
                }
                .text-right {
                    text-align: right;
                }
                .total-row {
                    font-weight: bold;
                    background-color: #f1f3f5;
                }
                .signatures {
                    margin-top: 60px;
                    width: 100%;
                }
                .signature-box {
                    width: 40%;
                    float: left;
                    text-align: center;
                    font-size: 12px;
                }
                .signature-box-right {
                    width: 40%;
                    float: right;
                    text-align: center;
                    font-size: 12px;
                }
                .signature-space {
                    height: 70px;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="school-name">Madrasah Diniyah Darul Lughah Wal Karomah</div>
                <div class="school-sub">Kel. Sidomukti, Kec. Kraksaan, Kabupaten Probolinggo, Jawa Timur</div>
                <div class="doc-title">LAPORAN HASIL PEMILIHAN KETUA & WAKIL KETUA OSIM</div>
                <div class="date-info">Tanggal Cetak: ' . $date . '</div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 10%">No. Urut</th>
                        <th style="width: 35%">Calon Ketua</th>
                        <th style="width: 35%">Calon Wakil Ketua</th>
                        <th style="width: 10%">Suara</th>
                        <th style="width: 10%">Persentase</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($candidates as $cand) {
            $percentage = $totalVotes > 0 ? round(($cand->votes_count / $totalVotes) * 100, 2) : 0;
            $html .= '
                    <tr>
                        <td class="text-center" style="font-weight: bold;">' . $cand->candidate_number . '</td>
                        <td>' . htmlspecialchars($cand->chairman_name) . '</td>
                        <td>' . htmlspecialchars($cand->vice_chairman_name) . '</td>
                        <td class="text-center">' . $cand->votes_count . '</td>
                        <td class="text-center">' . $percentage . '%</td>
                    </tr>';
        }

        $html .= '
                    <tr class="total-row">
                        <td colspan="3" class="text-right">Total Suara Masuk</td>
                        <td class="text-center">' . $totalVotes . '</td>
                        <td class="text-center">100%</td>
                    </tr>
                </tbody>
            </table>

            <div class="signatures">
                <div class="signature-box">
                    <p>Mengetahui,</p>
                    <p style="font-weight:bold; margin-top: 5px;">Ketua Panitia Pemilihan</p>
                    <div class="signature-space"></div>
                    <p>( _______________________ )</p>
                </div>
                <div class="signature-box-right">
                    <p>Probolinggo, ' . date('d F Y') . '</p>
                    <p style="font-weight:bold; margin-top: 5px;">Saksi Utama</p>
                    <div class="signature-space"></div>
                    <p>( _______________________ )</p>
                </div>
                <div style="clear: both;"></div>
            </div>
        </body>
        </html>';

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('a4', 'portrait');
        $dompdf->render();

        return response($dompdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="laporan_evoting_' . date('Ymd_His') . '.pdf"');
    }

    /**
     * API: Get real-time vote count results for admin dashboard polling.
     */
    public function getRealtimeResults()
    {
        $totalVotes = Vote::count();
        $candidates = Candidate::withCount('votes')->orderBy('candidate_number', 'asc')->get();

        $data = $candidates->map(function ($c) use ($totalVotes) {
            $pct = $totalVotes > 0 ? ($c->votes_count / $totalVotes) * 100 : 0;
            return [
                'id' => $c->id,
                'candidate_number' => $c->candidate_number,
                'chairman_name' => $c->chairman_name,
                'vice_chairman_name' => $c->vice_chairman_name,
                'votes_count' => $c->votes_count,
                'percentage' => round($pct, 1)
            ];
        });

        return response()->json([
            'totalVotes' => $totalVotes,
            'candidates' => $data
        ]);
    }
}

