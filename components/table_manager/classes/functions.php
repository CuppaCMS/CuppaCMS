<?php
	//++ Functions
		function saveTableManager(){
            include_once(realpath(__DIR__ . '/../../..')."/classes/Cuppa.php");
            $cuppa = Cuppa::getInstance(); $cuppa->user->valid("admin_login");
            $infoColumbs = $cuppa->dataBase->getColums($_POST["table_name"]);
            $data_to_save = array();
            for($i = 0; $i < count($infoColumbs); $i++){
            		$array = array();
            		$array["type"] = $cuppa->dataBase->escape($_POST[$infoColumbs[$i]."_field"]);
            		$array["label"] = $cuppa->dataBase->escape($_POST[$infoColumbs[$i]."_label"]);
            		if(isset($_POST[$infoColumbs[$i]."_showList"])) $array["showList"] = 1; else $array["showList"] = 0;
                    if(isset($_POST[$infoColumbs[$i]."_includeDownload"])) $array["includeDownload"] = 1; else $array["includeDownload"] = 0;
            		if(isset($_POST[$infoColumbs[$i]."_required"])) $array["required"] = 1; else $array["required"] = 0;
            		if(isset($_POST[$infoColumbs[$i]."_language_button"])) $array["language_button"] = 1; else $array["language_button"] = 0;
                    if(isset($_POST[$infoColumbs[$i]."_field_config"])) $array["config"] = $cuppa->jsonDecode($_POST[$infoColumbs[$i]."_field_config"]);
            	    $data_to_save[$infoColumbs[$i]] = $array;
            }
            // More info
                $data_to_save["show_list_like_tree"] = (@$_POST["show_list_like_tree"]) ? 1 : 0;
                $data_to_save["show_list_like_tree_column"] = @$_POST["show_list_like_tree_column"];
                $data_to_save["show_list_like_tree_validate"] = @$_POST["show_list_like_tree_validate"];
                $data_to_save["show_list_like_tree_indicator"] = @$_POST["show_list_like_tree_indicator"];
                $data_to_save["language_file_reference"] = @$_POST["language_file_reference"];
                $data_to_save["custom_table_name"] = @$_POST["custom_table_name"];
                $data_to_save["custom_table_name_language_reference"] = (@$_POST["custom_table_name_language_reference"]) ? 1 : 0;
                $data_to_save["order_by"] = @$_POST["order_by"];
                $data_to_save["order_by_order"] = @$_POST["order_by_order"];
                $data_to_save["link_indicator"] = @$_POST["link_indicator"];
                $data_to_save["list_limit"] = @$_POST["list_limit"];
                
            $data_to_save["primary_key"] = $_POST["primary_key"];
            $data_to_save["option_panel"] = $cuppa->jsonDecode($_POST["option_panel"]);
            $data_to_save["include_file"] = $_POST["include_file"];
            $data_to_save["tabs"] = $_POST["tabs"];
            $json = $cuppa->jsonEncode($data_to_save, false);
            $json = str_replace("'", "\'", $json);
            if(isset($_POST["id"])) $data["id"] = "'".$_POST["id"]."'"; else $data["id"] = "'0'";
            $data["table_name"] = "'".$_POST["table_name"]."'";
            $data["params"] = "'".$json."'";
            $result = $cuppa->dataBase->add($cuppa->configuration->table_prefix."tables", $data, true, true);
            echo (@$result->id) ? @$result->id : 0;
        }
        // Receive array of ids
        function deleteTableManager(){
            include_once(realpath(__DIR__ . '/../../..')."/classes/Cuppa.php");
            $cuppa = Cuppa::getInstance(); $cuppa->user->valid("admin_login");
            $_POST["ids"] = $cuppa->utils->jsonDecode($_POST["ids"]);
            for($i = 0; $i < count($_POST["ids"]); $i++){ 
                $info = $cuppa->dataBase->getRow($cuppa->configuration->table_prefix."tables", "id='".$_POST["ids"][$i]."'", true);
                $cuppa->dataBase->delete($cuppa->configuration->table_prefix."tables_log", "table_name='".$info->table_name."'");
                $cuppa->dataBase->delete($cuppa->configuration->table_prefix."tables", "id='".$info->id."'");
            }
            echo 1;
        }
        function saveAdminTable(){
            include_once(realpath(__DIR__ . '/../../..')."/classes/Cuppa.php");
            $cuppa = Cuppa::getInstance(); $cuppa->user->valid("admin_login");
            $db = $cuppa->dataBase;
            $configuration = $cuppa->configuration;
            $view = $cuppa->POST("view");
            $tableInfo = $db->getList($configuration->table_prefix."tables", "table_name = '".$view."'");
			$field_types = @json_decode(base64_decode($tableInfo[0]["params"]));
			if(@!$field_types) $field_types =  @json_decode($tableInfo[0]["params"]);
			$infoColumns = $db->getColums($view);
			$data_to_save = array();

            for($i = 0; $i < count($infoColumns); $i++){
                $field_type = $field_types->{$infoColumns[$i]};
                $configuration_params = $cuppa->utils->jsonDecode(@$field_type->config,true);
			    if(@$_REQUEST[$infoColumns[$i]."_field"] !== null){
				    //++ Password 
                        if(@$configuration_params->type == "password" && @$_REQUEST[$infoColumns[$i]."_field"]){
                            $encode = ($configuration_params->encode == "global_encode") ?  $configuration->global_encode : $configuration_params->encode;
                            if($encode == "md5") $_REQUEST[$infoColumns[$i]."_field"] = md5($cuppa->dataBase->escape( $_REQUEST[$infoColumns[$i]."_field"] ));
                            else if($encode == "sha1" ) $_REQUEST[$infoColumns[$i]."_field"] = sha1($cuppa->dataBase->escape( $_REQUEST[$infoColumns[$i]."_field"] ));
                            else if($encode == "sha1Salt" ) $_REQUEST[$infoColumns[$i]."_field"] = $cuppa->utils->sha1Salt($cuppa->dataBase->escape( $_REQUEST[$infoColumns[$i]."_field"] ), $configuration->global_encode_salt);
                        }
                    //--
				    if(@is_array(@$_REQUEST[$infoColumns[$i]."_field"]) || @is_object(@$_REQUEST[$infoColumns[$i]."_field"])){
                        @$_REQUEST[$infoColumns[$i]."_field"] = json_encode(@$_REQUEST[$infoColumns[$i]."_field"]);
                    }
                    $_REQUEST[$infoColumns[$i]."_field"] = str_replace("'","\'",@$_REQUEST[$infoColumns[$i]."_field"]);
                    $_REQUEST[$infoColumns[$i]."_field"] = str_replace("\\\\", "\\",@$_REQUEST[$infoColumns[$i]."_field"]);
                    //$_REQUEST[$infoColumns[$i]."_field"] = str_replace('\\"', '\\\\"',@$_REQUEST[$infoColumns[$i]."_field"]);
                    $_REQUEST[$infoColumns[$i]."_field"] = trim(@$_REQUEST[$infoColumns[$i]."_field"]);
                    $data_to_save[$infoColumns[$i]] = "'".$_REQUEST[$infoColumns[$i]."_field"] ."'";
                }
            }
            $result = $db->add($view, $data_to_save, true, true);
            //++ insert on log_table
                $primary_key = $field_types->primary_key;
                $data_to_save_table_log = new stdClass();
                $data_to_save_table_log->table_name = "'".@$view."'";
                $data_to_save_table_log->date_update = "NOW()";
                // New Register / update Reguster  
                    if(!$data_to_save[$primary_key] || $data_to_save[$primary_key] == "'0'"){
                        $data_to_save_table_log->user_id_creator = "'".@$cuppa->user->getVar("id")."'";
                        $data_to_save_table_log->user_id_update = "'".@$cuppa->user->getVar("id")."'";
                        $data_to_save_table_log->reference_id = "'".@$result->{$primary_key}."'";
                        $data_to_save_table_log->date_registered = "NOW()";
                    }else{
                        $data_to_save_table_log->user_id_update = "'".@$cuppa->user->getVar("id")."'";
                        $data_to_save_table_log->reference_id = @$data_to_save[$primary_key];
                    }
                $data_to_save_table_log->date_updating = "'0000-00-00 00:00:00'";
                if(!$db->add($configuration->table_prefix."tables_log", $data_to_save_table_log)){
                    $db->insert($configuration->table_prefix."tables_log", $data_to_save_table_log);
                }
            //--
            $pk = $field_types->primary_key;
            echo (@$result->{$pk}) ? @$result->{$pk} : 0;
        }
	//--
	//++ Handler
		$nameFunction = htmlspecialchars($_POST["function"]);
		if($nameFunction == ""){ echo "function name no found"; exit();}
		$nameFunction();
	//--
?>