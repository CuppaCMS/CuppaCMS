<?php
	/**
    * [value_1_1], value_[user_group]_[permission_id]
    * [default_1_1], default_[user_group]_[permission_id]
	*/
	class Permissions{
        private static $instance;
		public function __construct(){ }
        public static function getInstance() {
			if (self::$instance == NULL) { self::$instance = new Permissions(); } 
			return self::$instance;
		}
        public function getList($group = "ungroup", $reference = "", $title = "Permissions"){
            $cuppa = Cuppa::getInstance();
            require($cuppa->getDocumentPath()."components/permissions/list_permissions.php");
        }
        public function getListApiKey($group = "ungroup", $reference = "", $title = "Permissions"){
            $cuppa = Cuppa::getInstance();
            require($cuppa->getDocumentPath()."components/permissions/list_permissions_api_key.php");
        }
        public function getPermissions($group = "ungroup"){
            $cuppa = Cuppa::getInstance();
            if(!is_numeric($group)) $group = $cuppa->dataBase->getColumn($cuppa->configuration->table_prefix."permissions_group","id", "name = '{$group}'");
            $result = $cuppa->dataBase->getList($cuppa->configuration->table_prefix."permissions", "`group` = $group", "", "name ASC", true);
            return @$result;
        }
        public  function setValue($group = "ungroup", $reference, $data){
            if(!is_numeric($group)) $group = $cuppa->dataBase->getColumn($cuppa->configuration->table_prefix."permissions_group","id", "name = '{$group}'");
            //TODO implement this method
            return 1;
        }
        /* $group: id or string, permission: id or string
                    
        */
            public function getValue($group = "ungroup", $reference = "", $permission = "", $return_real_value = false){
                $cuppa = Cuppa::getInstance();
                if(!is_numeric($group)){
                    $group = $cuppa->dataBase->getColumnValueOnTable($cuppa->configuration->table_prefix."permissions_group", "name", $group, "id");
                }
                if(!is_numeric($permission)){
                    $permission = $cuppa->dataBase->getColumnValueOnTable($cuppa->configuration->table_prefix."permissions", "name", $permission, "id", "`group` = '".$group."'");
                }
                $permission_data = $cuppa->dataBase->getRow($cuppa->configuration->table_prefix."permissions_data", "`group` = '".$group."' AND reference = '".$reference."'", true);
                if($permission_data){
                    $permission_data = $cuppa->utils->jsonDecode($permission_data->data);
                    $user_group_id = $cuppa->user->getVar("user_group_id");
                    $key = "value_".$user_group_id."_".$permission;
                    $value = @$permission_data->{$key};
                    if($value){
                        $result = $cuppa->dataBase->getColumnValueOnTable($cuppa->configuration->table_prefix."permissions_values", "id", $value, "value");
                        if(strtolower($result) == "true"){ $result = 1; }else if(strtolower($result) == "false"){ $result = 0; }
                        return $result; 
                    }else{
                        return 1;
                    }
                }else{
                    if($return_real_value) return "";
                    return 1;
                }
    		}
        // get value by ApyKey
            public function getValueApiKey($group = "ungroup", $reference = "", $permission = "", $return_real_value = false){
                $cuppa = Cuppa::getInstance();
                if(!is_numeric($group)){
                    $group = $cuppa->dataBase->getColumnValueOnTable($cuppa->configuration->table_prefix."permissions_group", "name", $group, "id");
                }
                if(!is_numeric($permission)){
                    $permission = $cuppa->dataBase->getColumnValueOnTable($cuppa->configuration->table_prefix."permissions", "name", $permission, "id", "`group` = '".$group."'");
                }
                $permission_data = $cuppa->dataBase->getRow($cuppa->configuration->table_prefix."permissions_data", "`group` = '".$group."' AND reference = '".$reference."'", true);
                if($permission_data){
                    $permission_data = $cuppa->utils->jsonDecode($permission_data->data);
                    $user_group_id = $cuppa->db->getColumn("{$cuppa->configuration->table_prefix}api_keys", "id", "`key` = '".@$_SERVER["HTTP_KEY"]."'");
                    $key = "value_".$user_group_id."_".$permission;
                    $value = @$permission_data->{$key};
                    if($value){
                        $result = $cuppa->dataBase->getColumnValueOnTable($cuppa->configuration->table_prefix."permissions_values", "id", $value, "value");
                        if(strtolower($result) == "true"){ $result = 1; }else if(strtolower($result) == "false"){ $result = 0; }
                        return $result; 
                    }else{
                        return 0;
                    }
                }else{
                    if($return_real_value) return "";
                    return 0;
                }
    		}
        // $group: id or string, permission: id or string
            public function getDefault($group = "ungroup", $reference = "", $permission = ""){
                $cuppa = Cuppa::getInstance();
                if(!is_numeric($group)){
                    $group = $cuppa->dataBase->getColumnValueOnTable($cuppa->configuration->table_prefix."permissions_group", "name", $group, "id");
                }
                if(!is_numeric($permission)){
                    $permission = $cuppa->dataBase->getColumnValueOnTable($cuppa->configuration->table_prefix."permissions", "name", $permission, "id", "`group` = '".$group."'");
                }
                $permission_data = $cuppa->dataBase->getRow($cuppa->configuration->table_prefix."permissions_data", "`group` = '".$group."' AND reference = '".$reference."'", true);
                if($permission_data){
                    $permission_data = $cuppa->utils->jsonDecode($permission_data->data);
                    $user_group_id = $cuppa->user->getVar("user_group_id");
                    $key = "default_".$user_group_id."_".$permission;
                    return @$permission_data->{$key};
                }else{
                    return "";
                }
    		}
    }
?>