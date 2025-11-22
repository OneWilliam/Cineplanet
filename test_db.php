<?php
// Database test script to verify authentication

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? 'localhost';
$dbname = $_ENV['DB_NAME'] ?? 'cineplanet';
$username = $_ENV['DB_USER'] ?? 'root';
$password = $_ENV['DB_PASS'] ?? '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Database connection successful!\n";
    
    // Check if users table exists and has data
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM usuarios");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Users in database: " . $result['count'] . "\n";
    
    // Check if roles table exists and has data
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM roles");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Roles in database: " . $result['count'] . "\n";
    
    // Check roles content
    $stmt = $pdo->query("SELECT * FROM roles");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Roles:\n";
    foreach ($roles as $role) {
        echo "  ID: " . $role['id_rol'] . ", Name: " . $role['nombre'] . "\n";
    }
    
    // If no users exist, suggest creating a test user
    if ($result['count'] == 0) {
        echo "\nNo users found. You may want to create a test user.\n";
    }
    
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
}