<?php
// get_address.php - PHP Proxy Script for Nominatim Reverse Geocoding

// 1. Set Header to return JSON
header('Content-Type: application/json');

// 2. Kuhanin ang Latitude at Longitude mula sa GET request
$lat = isset($_GET['lat']) ? filter_var($_GET['lat'], FILTER_VALIDATE_FLOAT) : null;
$lon = isset($_GET['lon']) ? filter_var($_GET['lon'], FILTER_VALIDATE_FLOAT) : null;

if (empty($lat) || empty($lon)) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Missing or invalid coordinates.']);
    exit;
}

// 3. Construct the Nominatim URL
$nominatim_url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lon}&zoom=18&addressdetails=1";

// 4. Set User-Agent and Context for the cURL/fetch request
// IMPORTANT: Ang User-Agent ay kailangan ng Nominatim Policy
$context = stream_context_create([
    'http' => [
        // Palitan ang URL na ito ng aktuwal na URL ng iyong website
        'header' => 'User-Agent: WalkBuddyTrackingApp/1.0 (https://yourwebsite.com)'
    ]
]);

// 5. Perform the Server-to-Server Request
$response = @file_get_contents($nominatim_url, false, $context);

// 6. Handle the response
if ($response === FALSE) {
    // Kung nag-fail ang koneksyon o request
    http_response_code(503); // Service Unavailable
    echo json_encode(['error' => 'Failed to fetch address from Nominatim API. Check server connection.']);
} else {
    // Ibalik ang raw response mula sa Nominatim
    echo $response;
}
?>