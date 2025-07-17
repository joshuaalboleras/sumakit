<?php
require_once '../../configuration/config.php'; // Adjust path as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $house_number = isset($_POST['house_number']) ? intval($_POST['house_number']) : null;
    $family_name = isset($_POST['family_name']) ? trim($_POST['family_name']) : null;
    $street_name = isset($_POST['street_name']) ? trim($_POST['street_name']) : null;
    $barangay_id = isset($_POST['barangay_id']) ? intval($_POST['barangay_id']) : null;
    $municipal_id = isset($_POST['municipal_id']) ? intval($_POST['municipal_id']) : null;
    $province_id = isset($_POST['province_id']) ? intval($_POST['province_id']) : null;
    $geojson = isset($_POST['geojson']) ? trim($_POST['geojson']) : null;
    $building_type = isset($_POST['building_type']) ? trim($_POST['building_type']) : null;
    $status = isset($_POST['status']) ? trim($_POST['status']) : null;
    $no_floors = isset($_POST['no_floors']) ? intval($_POST['no_floors']) : null;
    $year_built = isset($_POST['year_built']) ? $_POST['year_built'] : null;

    // Basic validation
    if (
        !$house_number || !$family_name || !$street_name || !$barangay_id ||
        !$municipal_id || !$province_id || !$geojson ||
        !$building_type || !$status || !$no_floors || !$year_built
    ) {
        die('All fields are required.');
    }

    try {
        // Prepare and execute insert
        $stmt = $conn->prepare("INSERT INTO houses (house_number, street_name, barangay_id, municipal_id, province_id, geojson, building_type, status, no_floors, year_built) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $house_number,
            $street_name,
            $barangay_id,
            $municipal_id,
            $province_id,
            $geojson,
            $building_type,
            $status,
            $no_floors,
            $year_built
        ]);
        // Get the last inserted house ID
        $house_id = $conn->lastInsertId();

        // Insert household members if provided
        if (!empty($_POST['house_holdmember']) && is_array($_POST['house_holdmember'])) {
            $names = $_POST['house_holdmember'];
            $birthdates = isset($_POST['ages']) ? $_POST['ages'] : [];
            $suffixes = isset($_POST['suffixes']) ? $_POST['suffixes'] : [];
            $relationships = isset($_POST['relationships']) ? $_POST['relationships'] : [];
            $occupations = isset($_POST['occupations']) ? $_POST['occupations'] : [];

            $count = count($names);
            for ($i = 0; $i < $count; $i++) {
                $name = isset($names[$i]) ? trim($names[$i]) : null;
                $birthdate = isset($birthdates[$i]) ? $_POST['ages'][$i] : null;
                $suffix = isset($suffixes[$i]) ? trim($suffixes[$i]) : null;
                $relationship = isset($relationships[$i]) ? trim($relationships[$i]) : null;
                $occupation = isset($occupations[$i]) ? trim($occupations[$i]) : null;

                if ($name && $birthdate && $relationship) { // Only insert if required fields are present
                    $stmt = $conn->prepare("INSERT INTO household (house_id, family_name, name, suffix, birthdate, occupation, relationship) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([
                        $house_id,
                        $family_name,
                        $name,
                        $suffix,
                        $birthdate,
                        $occupation,
                        $relationship
                    ]);
                }
            }
        }
        // Redirect or show success
        header('Location: ../../barangayofficial/index.php?success=1');
        exit;
    } catch (PDOException $e) {
        // Handle error
        die("Database error: " . $e->getMessage());
    }

    dump($_POST);
} else {
    die('Invalid request.');
} 