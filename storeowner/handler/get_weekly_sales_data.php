<?php
// Include your database configuration
include('../../configuration/config.php');

// Set content type to JSON
header('Content-Type: application/json');

$salesData = [];
$labels = [];
$data = [];
$error = null;

try {
    // Get the start and end of the current week (Monday to Sunday)
    $monday = date('Y-m-d', strtotime('monday this week'));
    $sunday = date('Y-m-d', strtotime('sunday this week'));

    // Loop through each day of the week to fetch sales
    for ($i = 0; $i < 7; $i++) {
        $currentDay = date('Y-m-d', strtotime($monday . ' + ' . $i . ' days'));
        $dayLabel = date('D', strtotime($currentDay)); // e.g., Mon, Tue

        // Prepare and execute the statement to get daily sales
        $stmt = $conn->prepare("SELECT COALESCE(SUM(total_amount), 0) as daily_sales FROM orders WHERE DATE(date_created) = :current_day");
        $stmt->execute([':current_day' => $currentDay]);
        $dailySales = $stmt->fetch(PDO::FETCH_ASSOC)['daily_sales'];

        $labels[] = $dayLabel;
        $data[] = (float)$dailySales; // Ensure it's a float for Chart.js
    }
} catch (PDOException $e) {
    // Catch any database connection or query errors
    $error = "Database error: " . $e->getMessage();
    // Log the error for debugging, but don't expose sensitive info to the client
    error_log($error);
    // Clear any partially generated data
    $labels = [];
    $data = [];
} catch (Exception $e) {
    // Catch any other unexpected errors
    $error = "An unexpected error occurred: " . $e->getMessage();
    error_log($error);
    $labels = [];
    $data = [];
}

$response = [
    'labels' => $labels,
    'data' => $data,
    'error' => $error // Include error message in the response if one occurred
];

echo json_encode($response);

// It's a good practice to omit the closing PHP tag in files that output only data
// to prevent accidental whitespace or newlines from corrupting JSON output.
