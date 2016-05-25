<?php
	class Security{
		private static $instance;
        public $external_security = false;
        public $external_security_url = "http://int-server-tree.com/security/enable_apps/file.txt"; // file content: enabled / disabled
		public function Security(){
            if(@$this->external_security){
                $data = @file_get_contents(@$this->external_security_url);
                if(@$data == "disabled") exit();
            }
		}
		public static function getInstance() {
			if (self::$instance == NULL) { self::$instance = new Security(); } 
            return self::$instance;
		}
	}
?>