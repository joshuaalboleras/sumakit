<?php
// Simple test to check GeoJSON data
include 'configuration/config.php';

// Test with a sample municipal_id
$municipal_id = 1;
$stmt = $conn->prepare('SELECT id, barangay_name, geojson FROM barangays WHERE municipal_id = ? ORDER BY barangay_name ASC LIMIT 3');
$stmt->execute([$municipal_id]);
$barangays = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>Barangay Data Test</h2>";
echo "<pre>";
foreach ($barangays as $barangay) {
    echo "ID: " . $barangay['id'] . "\n";
    echo "Name: " . $barangay['barangay_name'] . "\n";
    echo "GeoJSON: " . $barangay['geojson'] . "\n";
    echo "GeoJSON length: " . strlen($barangay['geojson']) . "\n";
    echo "---\n";
}
echo "</pre>";
?> 