<?php

class SEND_DATA {
    public $token = "";
    public $type = "DWS7420";
    public $smartMeterId = "id-power-meter";
    public $total_power_consumption_value = 0.001;
    public $current_power_value = 0.01;
    public $url = "http://localhost:8080/api/v1/measurement/data";

    public function useParams($params=array()) {
        if(isset($params['token'])) { $this->token = $params['token']; }; 
        if(isset($params['type'])) { $this->type = $params['type']; }; 
        if(isset($params['smartMeterId'])) { $this->smartMeterId = $params['smartMeterId']; }; 
        if(isset($params['url'])) { $this->url = $params['url']; }; 
    }

    public function postData() {
        $data = array(
            'token' => $this->token,
            'type' => $this->type,
            'smartMeterId' => $this->smartMeterId,
            'powerConsumptionCount' => $this->total_power_consumption_value,
            'currentPowerValue' => $this->current_power_value,
            'lastUpdate' => date("d.m.Y H:i:s"),
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