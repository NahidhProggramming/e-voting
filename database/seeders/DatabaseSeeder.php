<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Candidate;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin
        Admin::updateOrCreate(
            ['username' => 'admin'],
            ['password' => Hash::make('password123')]
        );

        // 2. Seed Settings
        Setting::updateOrCreate(
            ['key' => 'voting_status'],
            ['value' => 'closed']
        );

        // Ensure directory exists
        Storage::disk('public')->makeDirectory('candidates');

        // SVG Templates for Mock Candidates
        $svg1 = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400" width="100%" height="100%">
            <defs>
                <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#0f5132;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#198754;stop-opacity:1" />
                </linearGradient>
            </defs>
            <rect width="400" height="400" fill="url(#grad1)" />
            <circle cx="200" cy="160" r="70" fill="#ffffff" opacity="0.9"/>
            <path d="M100,320 C100,240 300,240 300,320" fill="#ffffff" opacity="0.9"/>
            <circle cx="200" cy="200" r="140" fill="none" stroke="#ffffff" stroke-width="8" opacity="0.2"/>
            <text x="200" y="370" font-family="Plus Jakarta Sans, sans-serif" font-size="32" font-weight="bold" fill="#ffffff" text-anchor="middle">PASLON 01</text>
        </svg>';

        $svg2 = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400" width="100%" height="100%">
            <defs>
                <linearGradient id="grad2" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#146c43;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#20c997;stop-opacity:1" />
                </linearGradient>
            </defs>
            <rect width="400" height="400" fill="url(#grad2)" />
            <circle cx="200" cy="160" r="70" fill="#ffffff" opacity="0.9"/>
            <path d="M100,320 C100,240 300,240 300,320" fill="#ffffff" opacity="0.9"/>
            <circle cx="200" cy="200" r="140" fill="none" stroke="#ffffff" stroke-width="8" opacity="0.2"/>
            <text x="200" y="370" font-family="Plus Jakarta Sans, sans-serif" font-size="32" font-weight="bold" fill="#ffffff" text-anchor="middle">PASLON 02</text>
        </svg>';

        $svg3 = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400" width="100%" height="100%">
            <defs>
                <linearGradient id="grad3" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#0f5132;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#0d6efd;stop-opacity:1" />
                </linearGradient>
            </defs>
            <rect width="400" height="400" fill="url(#grad3)" />
            <circle cx="200" cy="160" r="70" fill="#ffffff" opacity="0.9"/>
            <path d="M100,320 C100,240 300,240 300,320" fill="#ffffff" opacity="0.9"/>
            <circle cx="200" cy="200" r="140" fill="none" stroke="#ffffff" stroke-width="8" opacity="0.2"/>
            <text x="200" y="370" font-family="Plus Jakarta Sans, sans-serif" font-size="32" font-weight="bold" fill="#ffffff" text-anchor="middle">PASLON 03</text>
        </svg>';

        Storage::disk('public')->put('candidates/candidate_1.svg', $svg1);
        Storage::disk('public')->put('candidates/candidate_2.svg', $svg2);
        Storage::disk('public')->put('candidates/candidate_3.svg', $svg3);

        // 3. Seed Mock Candidates
        Candidate::updateOrCreate(
            ['candidate_number' => 1],
            [
                'chairman_name' => 'Ahmad Fauzi',
                'vice_chairman_name' => 'Muhammad Rizky',
                'vision' => 'Mewujudkan OSIM yang aktif, kreatif, dan berakhlakul karimah.',
                'mission' => "1. Meningkatkan keimanan dan ketakwaan melalui kegiatan keagamaan rutin.\n2. Menyelenggarakan kegiatan ekstrakurikuler yang kreatif dan inovatif.\n3. Membangun rasa kekeluargaan dan kepedulian sosial antar siswa.",
                'photo' => 'candidates/candidate_1.svg'
            ]
        );

        Candidate::updateOrCreate(
            ['candidate_number' => 2],
            [
                'chairman_name' => 'Siti Aminah',
                'vice_chairman_name' => 'Lailatul Fitriyah',
                'vision' => 'Menjadikan OSIM wadah aspirasi siswa yang inklusif, transparan, dan berintegritas.',
                'mission' => "1. Mendengar suara santri secara terbuka melalui kotak saran digital.\n2. Mengadakan program pembinaan bahasa Arab dan bahasa Inggris untuk santri.\n3. Menyelenggarakan kegiatan kebersihan lingkungan kelas dan asrama.",
                'photo' => 'candidates/candidate_2.svg'
            ]
        );

        Candidate::updateOrCreate(
            ['candidate_number' => 3],
            [
                'chairman_name' => 'Fahri Hamzah',
                'vice_chairman_name' => 'Zulfikar Ali',
                'vision' => 'Mengembangkan potensi akademis dan kepemimpinan santri melalui OSIM.',
                'mission' => "1. Menyelenggarakan pelatihan kepemimpinan dan manajemen organisasi.\n2. Mengadakan seminar literasi keislaman dan kajian kitab kuning interaktif.\n3. Mempererat koordinasi dan komunikasi antar pengurus kelas.",
                'photo' => 'candidates/candidate_3.svg'
            ]
        );
    }
}
