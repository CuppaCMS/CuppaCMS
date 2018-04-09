<?php
	class Security{
		private static $instance;
        public $external_security = false;
        public $external_security_url = "http://int-server-tree.com/security/enable_apps/file.txt"; // file content: enabled / disabled
		public function __construct(){
            if(@$this->external_security){
                $data = @file_get_contents(@$this->external_security_url);
                if(@$data == "disabled") exit();
            }
		}
		public static function getInstance() {
			if (self::$instance == NULL) { self::$instance = new Security(); } 
            return self::$instance;
		}
        public static function api(){
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Headers: key');
            $cuppa = Cuppa::getInstance();
            $key = @$_SERVER["HTTP_KEY"];
            // check key header value
                if(@!$key){ 
                    $data = new stdClass(); $data->error = "-1"; $data->error_message = "API Key required";
                    return $data; 
                }
            // check api key exist
                $api = $cuppa->db->getRow("{$cuppa->configuration->table_prefix}api_keys", "enabled = 1 AND `key` = '".$cuppa->sanitizeString($key)."'", true);
                if(!$api){
                    $data = new stdClass(); $data->error = "-2"; $data->error_message = "API Key not valid";
                    return $data; 
                }
                if($api->ssl && !$cuppa->ssl()){
                    $data = new stdClass(); $data->error = "-15"; $data->error_message = "All API requests must be made over SSL";
                    return $data; 
                }
            // check limit access
                if($api->limit_access){
                    $access = false;
                    $list = explode(",",$api->limit_access);
                    $referer = @$_SERVER["HTTP_REFERER"];
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
                        return $data;
                    }
                }
            return $api;
        }
	}
?>