<?php
    // set notificationToken (OneSignal)
    function setNotificationToken($id, $token){
         $cuppa = Cuppa::getInstance();
         $data = new stdClass();
         $data->device_token = "'".$token."'";
         return $cuppa->db->update("cu_users", $data, "id = ".$id);
    }
    /* send notification */
        function notification($user, $message, $metadata = ""){
            $cuppa = Cuppa::getInstance();
            $user = $cuppa->db->getRow("cu_users", "id = $user", true);
            $data = new stdClass();
                $data->user = $user->id;
                $data->message = $message;
                $data->metadata = $metadata;
                $data = $cuppa->db->ajust($data, true, true, "metadata");
                $data->date = "NOW()";
            $cuppa->db->insert("tu_notifications", $data);
            //++ send notification
                $fields = array(
                    'app_id' => '8919d208-dd4b-4934-ba63-f654d964d434',
                    'include_player_ids' => array($user->device_token),
                    'data' => array("data" => "data1"),
                    'contents' => array("en" => $message)
                );
                $fields = json_encode($fields);
                $curl = 'https://onesignal.com/api/v1/notifications \
                            -H Content-Type: application/json; charset=utf-8 \
                            -H Authorization: Basic YmUxOWRiY2YtNTM4OC00ZWU2LWJhMGMtNTIzOWEyOGFjNzAw \
                            -d '.$fields.'  
                        ';
                $cuppa->curl($curl, "text");
            //--      
            return 1;
        }
?>