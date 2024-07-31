<?php
require_once('../api/CloudflareAPI.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['zone_id']) || !isset($_POST['account'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request.']);
        exit;
    }

    $zoneId = htmlspecialchars($_POST['zone_id']);
    $account = htmlspecialchars($_POST['account']);

    $_SESSION['account'] = $account; // Set session account for consistency

    $cloudflare = new CloudflareAPI($account);
    $response = $cloudflare->deleteZone($zoneId);

    if (isset($response['success']) && $response['success']) {
        echo json_encode(["message" => "Domain Zone deleted successfully."]);
    } else {
        echo json_encode(["message" => "Error: " . $response['errors'][0]['message']]);
    }
    exit;
}
?>
