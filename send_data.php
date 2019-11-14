<?php

$send_object = new SEND_DATA();
$send_object->postData();

class SEND_DATA {
    public $total_power_consumption_value = 5487.458;
    public $current_power_value = 589.75;
    public $last_update = "14.11.2019 14:55:11";

    public function postData() {
        $data = array(
            'token' => "DSJICX3KD98DGFLMY3K5",
            'powerConsumptionCount' => $this->total_power_consumption_value,
            'currentPowerValue' => $this->current_power_value,
            'dateTime' => $this->last_update,
        );
        
        $payload = json_encode($data);

        
        // Prepare new cURL resource
        $ch = curl_init('http://localhost:8080/api/v1/measurement/data');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        
        // Set HTTP Header for POST request 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload))
        );
        
        // Submit the POST request
        $result = curl_exec($ch);
        
        print_r($result);
       // exit();

        // Close cURL session handle
        curl_close($ch);

    }
}