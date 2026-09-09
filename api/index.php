<?php

// Ensure writable directories exist in Vercel serverless environment (/tmp)
$directories = [
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
