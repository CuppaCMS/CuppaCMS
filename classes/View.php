<?php
    class View{
        private static $instance;
        public function View(){ }
        public static function getInstance() {
			if (self::$instance == NULL) { self::$instance = new View(); } 
			return self::$instance;
		}
        /* Get a view 
            $value = id, name
        */
            function get($value = null){
                if($value == null) $value = 1;
                $cuppa = Cuppa::getInstance();
                $view = $cuppa->db->getRow("cu_views", "id = ".$value, true);
                if(!$view) $view = $cuppa->db->getRow("cu_views", "name = '".$value."'", true);
                if($view->code) $cuppa->echoString($view->code);
                else include $view->file_path;
            }
    }
?>