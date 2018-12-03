<?php
    include_once("../Cuppa.php");
	//++ Functions
		function changePassword(){
            $cuppa = Cuppa::getInstance();
            //++ Verify data
                //++ Encode types
                    if(@$_POST["current_password"]){
                        if($_POST["current_password_encode"] == "md5" && $_POST["current_password"]) $_POST["current_password"] = md5($cuppa->dataBase->scape($_POST["current_password"]));
                        else if($_POST["current_password_encode"] == "sha1" && $_POST["current_password"]) $_POST["current_password"] = sha1($cuppa->dataBase->scape($_POST["current_password"]));
                        else if($_POST["current_password_encode"] == "sha1Salt" && $_POST["current_password"]) $_POST["current_password"] = $cuppa->utils->sha1Salt($cuppa->dataBase->scape($_POST["current_password"]), $cuppa->configuration->global_encode_salt);
                    }
                //--
                $condition = $_POST["primary_key_field"]."='".$_POST["id"]."' AND ( ".$_POST["column"]."='".$_POST["current_password"]."'";
                if(!@$_POST["current_password"]) $condition .= " OR ".$_POST["column"]." IS NULL ";
                $condition.= " ) ";
                $result = $cuppa->dataBase->getRow($_POST["table"], $condition, true);
                if(!$result){ echo "-1"; return; }
            //--
            //++ update Info
                $data = new stdClass();
                $data->{$_POST["column"]} = $_POST["new_password"];
                    if($_POST["new_password_encode"] == "global_encode") $_POST["new_password_encode"] = $cuppa->configuration->global_encode;
                    if($_POST["new_password_encode"] == "md5" && $data->{$_POST["column"]}) $data->{$_POST["column"]} = md5($cuppa->dataBase->scape($data->{$_POST["column"]}));
                    else if($_POST["new_password_encode"]  == "sha1" && $data->{$_POST["column"]}) $data->{$_POST["column"]} = sha1($cuppa->dataBase->scape($data->{$_POST["column"]}));
                    else if($_POST["new_password_encode"]  == "sha1Salt" && $data->{$_POST["column"]}) $data->{$_POST["column"]} = $cuppa->utils->sha1Salt($cuppa->dataBase->scape($data->{$_POST["column"]}), $cuppa->configuration->global_encode_salt);
                $data->{$_POST["column"]} = "'".$data->{$_POST["column"]}."'";
                $result = $cuppa->dataBase->update($_POST["table"], $data, $_POST["primary_key_field"]."='".$_POST["id"]."'");
                echo $result;
            //--
		}
        // Updating user row reference on log_table
            function updateUserTableLog(){
                if(!@$_POST["reference_id"]) echo "1";
                $cuppa = Cuppa::getInstance();
                $data = new stdClass();
                    $data->user_id_updating = $cuppa->user->getVar("id");
                    $data->date_updating = date('Y-m-d H:i:s');
                    $data->table_name = $_POST["table_name"];
                    $data->reference_id = $_POST["reference_id"];
                    $data = $cuppa->dataBase->ajust($data, true);
                $result = $cuppa->dataBase->add($cuppa->configuration->table_prefix."tables_log", $data);
                echo $result;
            }
        // Update permissions files
            function updatePermisionFiles(){
                $cuppa = Cuppa::getInstance();
                $data = new stdClass();
                $data->group = @$_POST["group"];
                $data->reference = @$_POST["reference"];
                $data->data = @$_POST["data"];
                $data = $cuppa->dataBase->ajust($data);
                $result = $cuppa->dataBase->add2($cuppa->configuration->table_prefix."permissions_data", $data);
                echo $result;
            }
        // Load Select info
            function loadSelectInfo(){
                $cuppa = Cuppa::getInstance();
                $language = $cuppa->language->load();
                if( !@$_POST["compare_column_value"] || !@$_POST["compare_column"] ){
                    $data = array();
                    $object = new stdClass();
                    $object->{$_POST["value"]} = "";
                    $object->{$_POST["label"]} = $language->no_result;
                    array_push($data, $object);
                    echo $cuppa->utils->jsonEncode($data);
                    exit("");
                }
                //++ Create condition
                    $condition = (@$_POST["condition"]) ? $_POST["condition"]." AND " : "";
                    $condition .= "`".@$_POST["compare_column"]."` = '".@$_POST["compare_column_value"]."'";
                //--
                $data = $cuppa->dataBase->getList(@$_POST["table"], $condition, "", "", true);
                if(is_array($data)){
                    if($cuppa->POST("nested_column")){
                        $data = $cuppa->utils->tree($data, $cuppa->POST("nested_column"), $cuppa->POST("parent_column"), "alias", true, 0, false, "|&mdash;&nbsp;&nbsp;", true);
                    }
                    $object = new stdClass();
                    $object->{$_POST["value"]} = "";
                    $object->{$_POST["label"]} = $language->select;
                    array_unshift($data, $object);
                    for($i = 0; $i < count($data); $i++){
                        $label = $_POST["label"];
                        $data[$i]->{$label} = @$data[$i]->deep_string." ".$cuppa->language->getValue($data[$i]->{$label}, $language);
                    }
                }else{
                    $data = array();
                    $object = new stdClass();
                    $object->{$_POST["value"]} = "";
                    $object->{$_POST["label"]} = $language->no_result;
                    array_push($data, $object);
                }
                echo $cuppa->jsonEncode($data);
            }
            function loadSelectInfo2(){
                $cuppa = Cuppa::getInstance();
                $language = $cuppa->language->load();
                if( !@$_POST["compare_column_value"] || !@$_POST["compare_column"] ){
                    exit("");
                }
                //++ Create condition
                    $condition = (@$_POST["condition"]) ? $_POST["condition"]." AND " : "";
                    $condition .= "`".@$_POST["compare_column"]."` = '".@$_POST["compare_column_value"]."'";
                //--
                $data = $cuppa->dataBase->getList(@$_POST["table"], $condition, "", "", true);
                if(is_array($data)){
                    if($cuppa->POST("nested_column")){
                        $data = $cuppa->utils->tree($data, $cuppa->POST("nested_column"), $cuppa->POST("parent_column"), "alias", true, 0, false, "|&mdash;&nbsp;&nbsp;", true);
                    }
                    for($i = 0; $i < count($data); $i++){
                        $label = $_POST["label"];
                        $data[$i]->{$label} = @$data[$i]->deep_string." ".$cuppa->language->getValue($data[$i]->{$label}, $language);
                    }
                }
                echo $cuppa->jsonEncode($data);
            }
        function setSessionValue(){
            @session_start();
            $cuppa = Cuppa::getInstance();
            echo $cuppa->setSessionVar($_POST["name"], $_POST["value"]);
        }
        function getSessionValue(){
            @session_start();
            $cuppa = Cuppa::getInstance();
            echo $cuppa->getSessionVar($_POST["name"]);
        }
        function saveConfigData(){
            @session_start();
            $cuppa = Cuppa::getInstance();
            //++ save on file
                $file = $cuppa->jsonDecode($cuppa->POST("file"));
                    $first_values = new stdClass();
                    $first_values->host = $file->host; unset($file->host);
                    $first_values->db = $file->db; unset($file->db);
                    $first_values->user = $file->user; unset($file->user);
                    $first_values->password = $file->password; unset($file->password);
                $file = array_merge((array) $first_values, (array) $file);
                
                $contentToSave = "<?php \n	class Configuration{";
                foreach($file as $key=>$value){
                  $contentToSave .= "\n";
    		      $contentToSave .= '		public $'.$key.' = "'.$value.'";';
                }
               	$contentToSave .= "\n	} \n?>";
                $fp = fopen($cuppa->getDocumentPath()."Configuration.php","w");
        		fwrite($fp, $contentToSave); 
        		fclose($fp);
            //--
            echo 1;
        }
        function getMenu(){
            @session_start();
            $cuppa = Cuppa::getInstance();
            $menu = $cuppa->menu->getList($cuppa->POST("menu"));
            echo $cuppa->jsonEncode($menu);
        }
    //--
    //++ user
        function login(){
            @session_start();
            $cuppa = Cuppa::getInstance();
            echo $cuppa->user->login("site_login", $cuppa->POST("user"), $cuppa->POST("password"));
        }
        function restore(){
            $cuppa = Cuppa::getInstance();
            $language = $cuppa->language->load();
            $data = $cuppa->jsonDecode($cuppa->POST("info"));
            $data_update = array("restore_password"=>1);
            $user = $cuppa->db->update("{$cuppa->configuration->table_prefix}users", $data_update, "email = '".$data->email."' AND enabled = 1", true, true);
            if($user){
                $body = "The next link is valid to restore password one time, please click it to set your new password <br />#link#";
                $body = str_replace("#link#", $cuppa->getPath()."?restore=".$cuppa->encrypt($user->id,"", true), $body);
                echo $cuppa->mail->send($cuppa->langValue("email_from", $language), @$cuppa->configuration->email_outgoing, $cuppa->langValue("Restore password", $language), $user->email, $body);
            }
        }
        function restore2(){
            $cuppa = Cuppa::getInstance();
            $data = $cuppa->jsonDecode($cuppa->POST("info"));
            $ref = $cuppa->decrypt(@$data->ref);
            $password = $data->password;
            if(!$ref || !$password) exit("-1");            
            $data_to_save = array(  "password"=>"'".$cuppa->utils->sha1Salt($password, $cuppa->configuration->global_encode_salt)."'","restore_password"=>0 );
            echo $cuppa->db->update("{$cuppa->configuration->table_prefix}users", $data_to_save, "id = '".$ref."'");
        }
	//--
	//++ Handler
		$nameFunction = $_POST["function"];
		if($nameFunction == ""){ echo "function name no found"; exit();}
		$nameFunction();
	//--
?>