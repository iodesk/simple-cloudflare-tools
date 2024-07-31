<?php
session_start();

require_once('../api/CloudflareAPI.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['zone_id']) || empty($input['zone_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Zone ID not specified.']);
    exit;
}

if (!isset($input['account']) || empty($input['account'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Account not specified.']);
    exit;
}

$zoneId = htmlspecialchars($input['zone_id']);
$account = htmlspecialchars($input['account']);

try {
    $cloudflare = new CloudflareAPI($account);
    $dnsRecords = $cloudflare->listDNSRecords($zoneId)['result'];

    // Prepare data for JSON response
    $dnsData = [];
    foreach ($dnsRecords as $record) {
        $dnsData[] = [
            'name' => htmlspecialchars($record['name']),
            'type' => htmlspecialchars($record['type']),
            'content' => htmlspecialchars($record['content']),
            'ttl' => htmlspecialchars($record['ttl']),
            'proxied' => htmlspecialchars($record['proxied']),
            'id' => htmlspecialchars($record['id'])
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($dnsData);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
