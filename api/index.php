<?php

// Set environment flag for Vercel Serverless
$_ENV['VERCEL'] = '1';
putenv('VERCEL=1');

// Ensure writable directories exist in Vercel serverless environment (/tmp)
$directories = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/cache',
    '/tmp/storage/logs',
    '/tmp/views',
    '/tmp/sessions',
    '/tmp/cache',
    '/tmp/logs'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Auto-run migration if tables don't exist yet
if (isset($_GET['migrate']) && $_GET['migrate'] === 'secret123') {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $status = $kernel->call('migrate:fresh', ['--seed' => true, '--force' => true]);
    echo "<h1>Migration & Seeding Status:</h1><pre>" . $kernel->output() . "</pre>";
    exit;
}

// Forward Vercel requests to Laravel public/index.php
require __DIR__ . '/../public/index.php';
