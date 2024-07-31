<?php
require_once('../api/CloudflareAPI.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account = $_POST['account'];
    $zoneId = $_POST['zone_id'];
    $data = [
        "type" => $_POST['type'],
        "name" => $_POST['name'],
        "content" => $_POST['content'],
        "ttl" => (int)$_POST['ttl'],
        "proxied" => filter_var($_POST['proxied'], FILTER_VALIDATE_BOOLEAN)
    ];

    $cloudflare = new CloudflareAPI($account);

    $response = $cloudflare->addDNSRecord($zoneId, $data);

    if ($response['success']) {
        echo json_encode(["message" => "DNS Record added successfully."]);
    } else {
        echo json_encode(["message" => "Error: " . $response['errors'][0]['message']]);
    }
    exit;
}
?>