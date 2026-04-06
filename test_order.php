<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$request = \Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

// Test
try {
    $order = \App\Models\Order::first();
    if ($order) {
        echo "✓ Order found\n";
        echo "ID: {$order->id}\n";
        echo "Payment Status: {$order->payment_status}\n";
        echo "Type: " . gettype($order->payment_status) . "\n";
        echo "\nFillable array includes payment fields:\n";
        $fillable = $order->getFillable();
        echo "  - payment_status: " . (in_array('payment_status', $fillable) ? "YES" : "NO") . "\n";
        echo "  - payment_confirmation_date: " . (in_array('payment_confirmation_date', $fillable) ? "YES" : "NO") . "\n";
        echo "  - payment_notes: " . (in_array('payment_notes', $fillable) ? "YES" : "NO") . "\n";
    } else {
        echo "✗ No orders found\n";
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
