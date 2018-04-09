<?php
    include_once "../classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $api_check = $cuppa->security->api();
    if(@$api_check->error){ echo json_encode($api_check, JSON_PRETTY_PRINT); exit(); }
    // permissions
        $table =  $cuppa->POST("table");
        $method = $cuppa->POST("method");
        $sql = strtolower($cuppa->POST("sql"));
        if($sql){
            if(!$api_check->sql_queries){
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