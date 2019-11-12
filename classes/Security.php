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
                if(@!$key){ return $cuppa->error("api_key_required",  "Header API Key required"); }
            // check api key exist
                $api = $cuppa->db->getRow("{$cuppa->configuration->table_prefix}api_keys", "enabled = 1 AND `key` = '".$cuppa->sanitizeString($key)."'", true);
                if(!$api){ return $cuppa->error("invalid_api_key",  "API Key not valid"); }
                if($api->ssl && !$cuppa->ssl()){ return $cuppa->error("ssl_request_required",  "All API requests must be made over SSL"); }
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
                    if(!$access){ return $cuppa->error("invalid_ip",  "This API Key is only accessible from some valid IPs or Domains"); }
                }
            return $api;
        }
	}
?>