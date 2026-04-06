<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$response = $kernel->handle($request = Illuminate\Http\Request::capture());

// Test the view
try {
    $blade = app('view')->exists('customer.orders.show');
    if ($blade) {
        echo "✓ View exists\n";
    }
    
    // Try to compile it
    $compiled = app('view')->file(__DIR__ . '/resources/views/customer/orders/show.blade.php', [], true);
    echo "✓ View compiled successfully!\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "In file: " . $e->getFile() . " at line " . $e->getLine() . "\n";
}
?>
