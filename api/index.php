<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: key');
    include_once "../classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $header = getallheaders();
    $key = @$header["Key"];
    // check key header value
        if(@!$key){ 
            $data = new stdClass(); $data->error = "-1"; $data->error_message = "API Key required";
            echo json_encode($data, JSON_PRETTY_PRINT); 
            exit();
        }
    // check api key exist
        $api = $cuppa->db->getRow("cu_api_keys", "enabled = 1 AND `key` = '".$cuppa->sanitizeString($key)."'", true);
        if(!$api){
            $data = new stdClass(); $data->error = "-2"; $data->error_message = "API Key not valid";
            echo json_encode($data, JSON_PRETTY_PRINT); 
            exit();
        }
        if($api->ssl && !$cuppa->ssl()){
            $data = new stdClass(); $data->error = "-15"; $data->error_message = "All API requests must be made over SSL";
            echo json_encode($data, JSON_PRETTY_PRINT); 
            exit();
        }
    // check limit access
        if($api->limit_access){
            $access = false;
            $list = explode(",",$api->limit_access);
            $referer = $header["Referer"];
            // domain
                for($i = 0; $i < count($list); $i++){
                    $pos = strpos($referer, $list[$i]);
                    if($pos !== false){ $access = true; break; }
                }
            // ips
                if(!$access){
                    $ip = $cuppa->ip();
                    $pos = @strpos($api->limit_access, $ip);
                    if($pos !== false) $access = true;
                }
            if(!$access){
                $data = new stdClass(); $data->error = "-3"; $data->error_message = "This API Key is only accesible from some valid IPs or Domains";
                echo json_encode($data, JSON_PRETTY_PRINT); 
                exit();
            }
        }
        
    // permissions
        $table =  $cuppa->POST("table");
        $method = $cuppa->POST("method");
        $sql = strtolower($cuppa->POST("sql"));
        if($sql){
            if(!$api->sql_queries){
                $data = new stdClass(); $data->error = "-10"; $data->error_message = "This API Key don't accept SQL Queries";
                echo json_encode($data, JSON_PRETTY_PRINT); 
                exit();
            }
            if(    strpos($sql, "drop") !== false || strpos($sql, "insert") !== false 
                || strpos($sql, "update") !== false || strpos($sql, "delete") !== false
                || strpos($sql, "alter") !== false || strpos($sql, "create") !== false
            ){
                $data = new stdClass(); $data->error = "-11"; $data->error_message = "SQL Query not allowed";
                echo json_encode($data, JSON_PRETTY_PRINT); 
                exit();
            }
            
            $result = $cuppa->db->sql($sql, true);
            echo json_encode($result, JSON_PRETTY_PRINT);
        }else{
            if(@!$table){
                $data = new stdClass(); $data->error = "-4"; $data->error_message = "Please provide the table name";
                echo json_encode($data, JSON_PRETTY_PRINT); 
                exit();
            }
            if(@!$method){
                $data = new stdClass(); $data->error = "-5"; $data->error_message = "Please provide the method (insert, consult, edit, delete)";
                echo json_encode($data, JSON_PRETTY_PRINT); 
                exit();
            }
            $insert = $cuppa->permissions->getValueApiKey("6", $table, "13"); 
            $consult = $cuppa->permissions->getValueApiKey("6", $table, "16");
            $edit = $cuppa->permissions->getValueApiKey("6", $table, "17");
            $delete = $cuppa->permissions->getValueApiKey("6", $table, "18");
            if(@! "{$$method}"){
                $data = new stdClass(); $data->error = "-6"; $data->error_message = "This API Key does not have access to ".$method." on ".$table;
                echo json_encode($data, JSON_PRETTY_PRINT); 
                exit();
            }
        // execute
            switch ($method):
                case "insert":
                    $data = json_decode($_POST["data"]);
                    $data = $cuppa->db->ajust($data);
                    $result = $cuppa->db->insert($table, $data, $cuppa->POST("return_row"), true);
                    break;
                case "consult":
                    $result = $cuppa->db->getList($table, $cuppa->POST("condition"),$cuppa->POST("limit"), $cuppa->POST("order"), true);
                    break;
                case "edit":
                    $data = json_decode($_POST["data"]);
                    $data = $cuppa->db->ajust($data);
                    $result = $cuppa->db->update($table, $data, $cuppa->POST("condition"), true);
                    break;
                case "delete":
                    $result = $cuppa->db->delete($table, $cuppa->POST("condition"));
                    echo "i is not equal to 0, 1 or 2";
            endswitch;
            echo json_encode($result, JSON_PRETTY_PRINT);
        }
?>