<?php
session_start();

require_once('../api/CloudflareAPI.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account = $_POST['account'] ?? '';
    $zoneId = $_POST['zone_id'] ?? '';
    $dnsRecordId = $_POST['dns_record_id'] ?? '';

    if (empty($account) || empty($zoneId) || empty($dnsRecordId)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input.']);
        exit;
    }

    $cloudflare = new CloudflareAPI($account);

    if (isset($_POST['fetch'])) {
        $dnsRecords = $cloudflare->listDNSRecords($zoneId)['result'];
        foreach ($dnsRecords as $record) {
            if ($record['id'] == $dnsRecordId) {
                echo json_encode($record);
                exit;
            }
        }
        http_response_code(404);
        echo json_encode(['error' => 'Record not found.']);
    } elseif (isset($_POST['update_proxied'])) {
        // Ensure all required fields are present
        $proxied = filter_var($_POST['proxied'], FILTER_VALIDATE_BOOLEAN);
        $type = $_POST['type'] ?? '';
        $name = $_POST['name'] ?? '';
        $content = $_POST['content'] ?? '';
        $ttl = $_POST['ttl'] ?? '';

        if (!$type || !$name || !$content || !$ttl) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required DNS record fields.']);
            exit;
        }

        // Prepare data for updating
        $data = [
            "type" => $type,
            "name" => $name,
            "content" => $content,
            "ttl" => (int)$ttl,
            "proxied" => $proxied
        ];

        $response = $cloudflare->updateDNSRecord($zoneId, $dnsRecordId, $data);

        if ($response['success']) {
            echo json_encode(["message" => "Proxied status updated successfully."]);
        } else {
            echo json_encode(["message" => "Error: " . $response['errors'][0]['message']]);
        }
    } else {
        // Handle full updates from form
        $data = [
            "type" => $_POST['type'],
            "name" => $_POST['name'],
            "content" => $_POST['content'],
            "ttl" => (int)$_POST['ttl'],
            "proxied" => filter_var($_POST['proxied'], FILTER_VALIDATE_BOOLEAN)
        ];

        $response = $cloudflare->updateDNSRecord($zoneId, $dnsRecordId, $data);

        if ($response['success']) {
            echo json_encode(["message" => "DNS Record updated successfully."]);
        } else {
            echo json_encode(["message" => "Error: " . $response['errors'][0]['message']]);
        }
    }
    exit;
}
?>
