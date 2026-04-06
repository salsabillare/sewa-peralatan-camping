<?php
// Simple database test
$host = 'localhost';
$db = 'ecommerce_bila';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    echo "✓ Database connected\n\n";
    
    // Check if payment_status column exists
    $result = $pdo->query("SHOW COLUMNS FROM orders LIKE 'payment_status'");
    $column = $result->fetch();
    
    if ($column) {
        echo "✓ payment_status column EXISTS\n";
        echo "  Type: " . $column['Type'] . "\n";
        echo "  Null: " . $column['Null'] . "\n";
        echo "  Default: " . ($column['Default'] ?? 'NULL') . "\n";
    } else {
        echo "✗ payment_status column NOT FOUND\n";
    }
    
    // Get first order
    $result = $pdo->query("SELECT id, payment_status FROM orders LIMIT 1");
    $order = $result->fetch();
    
    if ($order) {
        echo "\n✓ Sample Order:\n";
        echo "  ID: " . $order['id'] . "\n";
        echo "  Current payment_status: " . $order['payment_status'] . "\n";
        
        // Try update
        echo "\nTesting UPDATE...\n";
        $stmt = $pdo->prepare("UPDATE orders SET payment_status = 'confirmed', payment_confirmation_date = NOW() WHERE id = ?");
        $updated = $stmt->execute([$order['id']]);
        
        if ($updated) {
            echo "✓ UPDATE executed\n";
            
            // Verify
            $result = $pdo->query("SELECT payment_status FROM orders WHERE id = " . $order['id']);
            $newStatus = $result->fetch();
            echo "  New payment_status: " . $newStatus['payment_status'] . "\n";
        } else {
            echo "✗ UPDATE failed\n";
            echo "  Error: " . implode(", ", $stmt->errorInfo()) . "\n";
        }
    } else {
        echo "✗ No orders found\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
