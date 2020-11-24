<?php

class SEND_DATA_POWMON {
    public $token = "<INSERT-VALID-TOKEN>";
    public $smartMeterId = "<INSERT-DEVICE-ID>";
    public $total_power_consumption_value = 0.001;
    public $url = "https://powermonitor-interface.herokuapp.com/api/v1/m2m/meter/data";

    public function postData() {
        $data = array(
            'deviceId' => $this->smartMeterId,
            'date' => date("Y-m-d\TH:i:s"),
            'val' => $this->total_power_consumption_value,
            'token' => $this->token
        );
        
        $payload = json_encode($data);

        // Prepare new cURL resource
        $ch = curl_init($this->url);
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

        // Close cURL session handle
        curl_close($ch);

    }
}