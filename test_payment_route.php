#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$router = $app->make('router');

echo "=== PAYMENT ROUTES CHECK ===\n\n";

// Get all routes
foreach ($router->getRoutes() as $route) {
    if (strpos($route->getName() ?? '', 'payment') !== false) {
        echo "✓ " . ($route->getName() ?? 'unnamed') . "\n";
        echo "  Method: " . implode('|', $route->methods) . "\n";
        echo "  URI: " . $route->uri . "\n";
        echo "  Controller: " . ($route->getControllerClass() ?? 'N/A') . "@" . ($route->getControllerMethod() ?? 'N/A') . "\n\n";
    }
}

// Test update method
echo "\n=== ORDER MODEL CHECK ===\n";
$order = \App\Models\Order::first();
if ($order) {
    echo "✓ Order found: ID " . $order->id . "\n";
    echo "  Current payment_status: " . $order->payment_status . "\n";
    echo "  Fillable fields:\n";
    foreach ($order->getFillable() as $field) {
        if (strpos($field, 'payment') !== false) {
            echo "    - $field\n";
        }
    }
    
    // Try to update
    echo "\n  Testing update...\n";
    $result = $order->update(['payment_status' => 'test_' . time()]);
    echo "  Update result: " . ($result ? "SUCCESS" : "FAILED") . "\n";
    echo "  New payment_status: " . $order->fresh()->payment_status . "\n";
} else {
    echo "✗ No orders found\n";
}
?>
