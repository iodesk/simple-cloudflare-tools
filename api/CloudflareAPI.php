<?php
require_once '../accounts.php';

class CloudflareAPI {
    private $apiToken;
    private $email;
    private $apiUrl = "https://api.cloudflare.com/client/v4";

    public function __construct($account) {
        $config = require '../accounts.php';
        if (isset($config['cloudflare'][$account])) {
            $this->apiToken = $config['cloudflare'][$account]['apiToken'];
            $this->email = $config['cloudflare'][$account]['email'];
        } else {
            throw new Exception("Cloudflare account not found in configuration.");
        }
    }

    private function request($endpoint, $method = 'GET', $data = []) {
        $url = $this->apiUrl . $endpoint;
        $headers = [         
            "Authorization: Bearer {$this->apiToken}",
            "Content-Type: application/json"
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }

    public function listZones() {
        //$endpoint = "/zones?per_page=50&order=status&direction=desc&match=all";
        $endpoint = "/zones";
        return $this->request($endpoint);
    }

    public function listDNSRecords($zoneId) {
        $endpoint = "/zones/{$zoneId}/dns_records";
        return $this->request($endpoint);
    }

    public function addDNSRecord($zoneId, $data) {
        return $this->request("/zones/{$zoneId}/dns_records", 'POST', $data);
    }

    public function updateDNSRecord($zoneId, $dnsRecordId, $data) {
        return $this->request("/zones/{$zoneId}/dns_records/{$dnsRecordId}", 'PUT', $data);
    }

    public function deleteDNSRecord($zoneId, $dnsRecordId) {
        return $this->request("/zones/{$zoneId}/dns_records/{$dnsRecordId}", 'DELETE');
    }
    
    public function getDNSRecord($zoneId, $dnsRecordId) {
        return $this->request("/zones/{$zoneId}/dns_records/{$dnsRecordId}");
    }
  
    public function addZone($data) {
        return $this->request("/zones", 'POST', $data);
    }
  
    public function deleteZone($zoneId) {
        return $this->request("/zones/{$zoneId}", 'DELETE');
    }
}
?>
