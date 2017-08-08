<?php
    class View{
        private static $instance;
        public function __construct(){ }
        public static function getInstance() {
			if (self::$instance == NULL) { self::$instance = new View(); } 
			return self::$instance;
		}
        /* Get a view 
            $value = id, name
        */
            function get($value = null){
                $cuppa = Cuppa::getInstance();
                if($value) $view = $cuppa->db->getRow("cu_views", "id = ".$value, true);
                else $view = $cuppa->db->getRow("cu_views", "`default` = 1", true);
                if(!$view) return;
                if($view->code) $cuppa->echoString($view->code);
                else include $view->file_path;
            }
    }
?>