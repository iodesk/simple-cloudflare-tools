<?php
session_start();

require_once('../api/CloudflareAPI.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account = $_POST['account'] ?? '';
    $zoneId = $_POST['zone_id'] ?? '';
    $dnsRecordId = $_POST['dns_record_id'] ?? '';

    if (empty($account) || empty($zoneId)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input.']);
        exit;
    }

    if (isset($_POST['dns_record_id']) && empty($dnsRecordId)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid DNS record ID.']);
        exit;
    }

    $cloudflare = new CloudflareAPI($account);

    if (isset($_POST['dns_record_ids'])) {
        // Bulk deletion
        $dnsRecordIds = explode(',', $_POST['dns_record_ids']); 

        if (!empty($dnsRecordIds)) {
            $errors = [];
            foreach ($dnsRecordIds as $dnsRecordId) {
                $response = $cloudflare->deleteDNSRecord($zoneId, $dnsRecordId);
                if (!$response['success']) {
                    $errors[] = $response['errors'][0]['message'];
                }
            }

            if (empty($errors)) {
                echo json_encode(["message" => "All selected DNS records deleted successfully."]);
            } else {
                echo json_encode(["error" => "Errors: " . implode(", ", $errors)]);
            }
        } else {
            echo json_encode(["error" => "Invalid DNS record IDs."]);
        }
    } else {
        // Single record deletion
        if (empty($dnsRecordId)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid DNS record ID.']);
            exit;
        }

        $response = $cloudflare->deleteDNSRecord($zoneId, $dnsRecordId);

        if ($response['success']) {
            echo json_encode(["message" => "DNS Record deleted successfully."]);
        } else {
            echo json_encode(["error" => "Error: " . $response['errors'][0]['message']]);
        }
    }
    exit;
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed.']);
}
?>
