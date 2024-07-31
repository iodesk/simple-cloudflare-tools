<?php
require_once('../api/CloudflareAPI.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account = $_POST['account']; 
    $domain = $_POST['domain'];
    $type = $_POST['type'];

    $cloudflare = new CloudflareAPI($account);

    $data = [
        'name' => $domain,
        'type' => $type
    ];

    $response = $cloudflare->addZone($data);

    if ($response['success']) {
        echo json_encode(["message" => "Domain Zone added successfully."]);
    } else {
        echo json_encode(["message" => "Error: " . $response['errors'][0]['message']]);
    }
    exit;
}
?>
