<?php
	//++ Functions
        function register(){
            @session_start();
            include_once($_SESSION["cuSession"]->paths->administrator->document_path."classes/Cuppa.php");
            $cuppa = Cuppa::getInstance();
            $token = $cuppa->security->token;
            $data2 = $data = $cuppa->utils->jsonDecode($_POST["info"], true);
            $data->password = $cuppa->utils->sha1Salt($data->password, $cuppa->configuration->global_encode_salt);
            $data = $cuppa->dataBase->ajustObjectToSave($data, true);
            //++ more params
                $data->user_group_id = "3";
                $data->enabled = "1";
                $data->date_registered = "NOW()";
            //--
            $result = $cuppa->dataBase->insert($cuppa->configuration->table_prefix."users", $data, $token, true, true);
            if($result){
                echo $cuppa->user->createUserSessionById("site_login", @$result->id);
            }else{
                $result = $cuppa->dataBase->getRow($cuppa->configuration->table_prefix."users", "username = '".$data2->username."'", $token, true);
                if($result) echo "-1";  // username exist
                else echo "-2";         // email exist
            }
        }
        function update(){
            @session_start();
            include_once($_SESSION["cuSession"]->paths->administrator->document_path."classes/Cuppa.php");
            $cuppa = Cuppa::getInstance();
            $token = $cuppa->security->token;
            $data2 = $data = $cuppa->utils->jsonDecode($_POST["info"], true);
            $data = $cuppa->dataBase->ajustObjectToSave($data, true);
            unset($data->password); unset($data->current_password);
            //++ update info
                $user = $cuppa->dataBase->update($cuppa->configuration->table_prefix."users", $data, "id = ".$cuppa->user->getVar("id"), $token, true, true);
            //--
            //++ update password
                if($data2->password ){
                    $data2->password = $cuppa->utils->sha1Salt($data2->password, $cuppa->configuration->global_encode_salt);
                    $data2->current_password = $cuppa->utils->sha1Salt($data2->current_password, $cuppa->configuration->global_encode_salt);
                    if($user->password == $data2->current_password){
                        $data_to_save = new stdClass();
                        $data_to_save->password = "'".$data2->password."'";
                        echo $cuppa->dataBase->update($cuppa->configuration->table_prefix."users", $data_to_save, "id = ".$cuppa->user->getVar("id"), $token);
                    }else{
                        exit("-1");     // invalid current_password
                    }
                }else{
                     exit("1");
                }
            //--
        }
        function logout(){
            @session_start();
            include_once($_SESSION["cuSession"]->paths->administrator->document_path."classes/Cuppa.php");
            $cuppa = Cuppa::getInstance();
            $token = $cuppa->security->token;
            $cuppa->user->destroyUserSession("");
            exit("1");
        }
	//--
	//++ Handler
		$nameFunction = $_POST["function"];
		if($nameFunction == ""){ echo "function name no found"; exit();}
		$nameFunction();
	//--
?>