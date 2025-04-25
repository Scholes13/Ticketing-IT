<?php
// Script to clean database for production use
// This will truncate tickets, comments, attachments, and job-related tables
// While preserving users, staff, categories, and other system settings

// Database connection parameters from .env file
$host = '127.0.0.1';
$dbname = 'wgtiket';
$username = 'root';
$password = '';

try {
    // Connect to database
    echo "Connecting to database...\n";
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Starting database cleanup...\n";
    
    // Disable foreign key checks
    echo "Disabling foreign key checks...\n";
    $db->exec('SET FOREIGN_KEY_CHECKS=0');
    
    // Tables to clean (in order)
    $tables = [
        'jobs',
        'job_batches',
        'failed_jobs',
        'attachments',
        'comments',
        'tickets'
    ];
    
    // Truncate each table
    foreach ($tables as $table) {
        echo "Attempting to truncate table: $table\n";
        try {
            // Check if table exists first
            $stmt = $db->query("SHOW TABLES LIKE '$table'");
            if ($stmt->rowCount() > 0) {
                $db->exec("TRUNCATE TABLE $table");
                echo "Successfully truncated table: $table\n";
            } else {
                echo "Table $table does not exist, skipping...\n";
            }
        } catch (Exception $e) {
            echo "Warning: Could not truncate $table: " . $e->getMessage() . "\n";
        }
    }
    
    // Re-enable foreign key checks
    echo "Re-enabling foreign key checks...\n";
    $db->exec('SET FOREIGN_KEY_CHECKS=1');
    
    echo "\nDatabase cleaned successfully!\n";
    echo "All tickets, comments, attachments, and job-related data have been removed.\n";
    echo "User accounts, staff, categories, and system settings have been preserved.\n";
    
} catch (Exception $e) {
    // In case of error
    echo "Error: " . $e->getMessage() . "\n";
    
    // Make sure foreign key checks are re-enabled
    try {
        $db->exec('SET FOREIGN_KEY_CHECKS=1');
        echo "Foreign key checks have been re-enabled.\n";
    } catch (Exception $e2) {
        echo "Could not re-enable foreign key checks: " . $e2->getMessage() . "\n";
    }
} 