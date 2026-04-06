<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use App\Services\LateFeeService;

// Test calculation
$result = LateFeeService::calculate(24, 25000, '2026-02-09', '2026-03-10');
echo "Test 1 (29 hari terlambat): " . $result . " (expected: 25000*29 = 725000)\n";

$result2 = LateFeeService::calculate(24, 5000, '2026-03-10 10:00', '2026-03-11 10:00');
echo "Test 2 (1 hari terlambat): " . $result2 . " (expected: 5000*1 = 5000)\n";

$result3 = LateFeeService::calculate(24, 5000, '2026-03-10 10:00', '2026-03-10 20:00');
echo "Test 3 (tidak terlambat): " . $result3 . " (expected: 0)\n";
