<?php

class PluginaAntiviruscheckerAntiviruschecker{

    private $apiUrl = 'https://apne1-1001.sentinelone.net/web/api/v2.1/agents?limit=100';

    //Change the api token generated here. private $token = 'Your api token here';

    private $token = 'eyJraWQiOiJhcC1zb3V0aGVhc3QtMS1wcm9kLTAiLCJhbGciOiJFUzI1NiJ9.eyJzdWIiOiJzZXJ2aWNldXNlci05ZGIzZjEyZS01YTcxLTQ0MmUtYWEyNC0wMjI5YzZjNzUyNGFAbWdtdC0yMC5zZW50aW5lbG9uZS5uZXQiLCJpc3MiOiJhdXRobi1hcC1zb3V0aGVhc3QtMS1wcm9kIiwiZGVwbG95bWVudF9pZCI6IjIwIiwidHlwZSI6InVzZXIiLCJleHAiOjE3MzUxMTgwNjcsImlhdCI6MTcyNzI1NTg3NSwianRpIjoiYTcwY2EyMzYtZDU4OC00YWU5LWI3ZmMtMTFlMzFmZDE2NmRmIn0.7hXLdHyTgh2wlCvguLEqBTX31Poeh4w2kjUx-cos-YXpafp0vHPaTZvpIM6I6vp-vhLi9GIWMcdeJ5HFoDSlrA';  // Ensure this is stored securely
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function fetch_display_data(){
        $hasNextPage = true;
        $nextCursor = '';
        $onemonthago = strtotime('-1 day');

        while ($hasNextPage) {
            $ch = curl_init($this->apiUrl . $nextCursor);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->token,
                'Content-Type: application/json',
            ]);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                error_log('cURL Error: '. curl_error($ch));
                break;
            }

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($httpCode != 200) {
                error_log('HTTP error'. curl_error($ch));
                break;
            }

            //data storing
            $data=json_decode($response, true);

            if (isset($data['data']) && is_array($data['data'])){
                foreach($data["data"] as $device) {
                    if (isset($device["computerName"]) && isset($device["lastActiveDate"]) && isset($device["groupName"])){

                        $lastActive = strtotime($device["lastActiveDate"]);    
                        //checking for inactive for 1 month
                        $antivirusStatus=$lastActive>=$onemonthago?'Active':'Inactive';

                        $devices[] = [
                                'computerName' => htmlspecialchars($device['computerName']),
                                'antivirusStatus' => htmlspecialchars($antivirusStatus),
                                'lastActiveDate' => htmlspecialchars(date('Y-m-d H:i:s', $lastActive)),
                                'groupName' => htmlspecialchars($device['groupName']),
                             ];
                    }
                }

                usort($devices, function($a, $b) {
                    return strcmp($a['groupName'], $b['groupName']);
                });

                $nextCursor = isset($data['pagination']['nextCursor']) ? '&cursor=' . $data['pagination']['nextCursor'] : '';
                $hasNextPage = !empty($nextCursor);
            }else {
                error_log('Error in data extraction.');
                break;
            }

            curl_close($ch);
        }
        return $devices;
    }
}