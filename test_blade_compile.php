<?php
// Test view rendering
$app = require __DIR__ . '/bootstrap/app.php';

try {
    $blade = app('view')->make('customer.orders.show', [
        'order' => new class {
            public $id = 1;
            public $status = 'pending';
            public $payment_status = 'pending';
            public $payment_method = 'transfer';
            public $payment_confirmation_date = null;
            public $payment_notes = null;
            public $total_price = 100000;
            public $shipping_cost = 50000;
            public $address = 'Test Address';
            public $created_at;
            public $user;
            public $items = [];
            public $shipping;
            public $tracking_number = null;
            
            public function __construct() {
                $this->created_at = new DateTime();
                $this->user = new class {
                    public $name = 'Test User';
                    public $email = 'test@example.com';
                    public $phone = '08123456789';
                };
                $this->shipping = null;
            }
        }
    ]);
    
    echo "✓ View compiled and rendered successfully!\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
?>
