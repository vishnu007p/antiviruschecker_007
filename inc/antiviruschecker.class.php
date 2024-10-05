<?php

//include "../../../inc/includes.php";

// if(!defined('GLPI_ROOT')){
//     die('No access found');
// }

class PluginaAntiviruscheckerAntiviruschecker{


    private $apiUrl = 'https://apne1-1001.sentinelone.net/web/api/v2.1/agents?limit=100';

    //Change the api token generated here. private $token = 'Your api token here';

    private $token = '#';  // Ensure this is stored securely


    // private $token;
    // public function __construct(){
    //     global $DB;
    
    //     // Retrieve the token from the database
    //     $query = "SELECT token FROM glpi_plugin_antiviruschecker_config WHERE id = 1 LIMIT 1";
    //     $result = $DB->query($query);
    
    //     if ($result && $DB->numrows($result) > 0) {
    //         $row = $DB->fetch_assoc($result);
    //         $this->token = $row['token'];
    //     } else {
    //         // Handle error if no token is found or database issue
    //         error_log("Error: API token not found in the database.");
    //     }
    // }

    public function fetch_display_data(){
        $hasNextPage = true;
        $nextCursor = '';
        $onemonthago = strtotime('-2 week');

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
