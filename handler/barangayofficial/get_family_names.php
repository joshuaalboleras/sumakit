<?php
include '../../configuration/config.php';
header('Content-Type: application/json');
if (!isset($_GET['barangay_id']) || !is_numeric($_GET['barangay_id'])) {
    echo json_encode([]);
    exit;
}
$barangay_id = (int)$_GET['barangay_id'];
// Get all households in the barangay
$stmt = $conn->prepare('SELECT id, family_name FROM household WHERE house_id IN (SELECT id FROM houses WHERE barangay_id = ?) ORDER BY family_name ASC');
$stmt->execute([$barangay_id]);
$households = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group by family_name, and for each, collect members
$families = [];
foreach ($households as $row) {
    $family_name = $row['family_name'];
    $household_id = $row['id'];
    if (!isset($families[$family_name])) {
        $families[$family_name] = [
            'family_name' => $family_name,
            'household_id' => $household_id, // Use the first household_id found for this family_name
            'members' => []
        ];
    }
    // Add this member to the family
    $families[$family_name]['members'][] = [
        'id' => $row['id'],
        // Fetch name, suffix, etc for this member
        'name' => isset($row['name']) ? $row['name'] : '',
        'suffix' => isset($row['suffix']) ? $row['suffix'] : ''
    ];
}
// If members are not included in the first query, fetch them now
if (empty($households) || !isset($households[0]['name'])) {
    // Need to fetch all members for each family
    foreach ($families as $family_name => &$family) {
        $stmt = $conn->prepare('SELECT id, name, suffix FROM household WHERE family_name = ? AND house_id IN (SELECT id FROM houses WHERE barangay_id = ?)');
        $stmt->execute([$family_name, $barangay_id]);
        $family['members'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    unset($family);
}
// Return as array
echo json_encode(array_values($families)); 