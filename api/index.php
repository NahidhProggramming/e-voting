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

// Forward Vercel requests to Laravel public/index.php
require __DIR__ . '/../public/index.php';
