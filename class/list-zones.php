<?php
session_start(); 

require_once('../api/CloudflareAPI.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['account']) || empty($input['account'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Account not specified.']);
    exit;
}

$account = htmlspecialchars($input['account']);

try {
    $cloudflare = new CloudflareAPI($account);
    $response = $cloudflare->listZones();

    $zones = [];
    foreach ($response['result'] as $zone) {
        $name_servers_html = implode('<br>', array_map('htmlspecialchars', $zone['name_servers']));
        $zones[] = [
            'id' => htmlspecialchars($zone['id']),
            'name' => htmlspecialchars($zone['name']),
            'registrar' => htmlspecialchars($zone['original_registrar']),
            'accname' => htmlspecialchars($zone['account']['name']),
            'name_servers' => $name_servers_html,
            'status' => htmlspecialchars($zone['status'])
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($zones);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
