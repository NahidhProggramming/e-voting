<?php

// Ensure compiled views directory exists in Vercel serverless environment (/tmp)
$viewsPath = '/tmp/views';
if (!is_dir($viewsPath)) {
    @mkdir($viewsPath, 0755, true);
}

// Forward Vercel requests to Laravel public/index.php
require __DIR__ . '/../public/index.php';
