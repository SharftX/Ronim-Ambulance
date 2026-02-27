<?php
// Automatically switch between localhost and production
if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1') {
    // --- LOCALHOST SETTINGS (XAMPP / MAMP) ---
    $dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'ronim_ambulance';
} else {
    // --- PRODUCTION SETTINGS (HOSTINGER) ---
    // Update these with your Hostinger database details from hPanel
    $dbHost = 'localhost'; 
    $dbUser = 'u864739415_user'; // Replace with your Hostinger DB user
    $dbPass = 'Ronimweb@2026';   // Replace with your Hostinger DB password
    $dbName = 'u864739415_ronim_db';   // Replace with your Hostinger DB name
}

/**
 * Connect to the database
 * 
 * @return mysqli The database connection object
 */
function connectToDatabase() {
    global $dbHost, $dbUser, $dbPass, $dbName;
    
    // Create connection using mysqli
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    
    // Check for connection error
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}
?>
