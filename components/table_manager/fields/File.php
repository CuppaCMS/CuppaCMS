<?php 
	/**
    * config = {"folder":"media/userImages","show_image":1,"dimention_priority":"height","dimention_image":"50","download_enabled":1,"disable":false,"tooltip":"","tooltip_lang_reference":false,"width":""}
    * Example1: $file = new File(); echo $file->GetItem("file_field"); 
	*/
	
	class File{
		private $config;
		private $errorMessage;
        private $cuppa = null;
		public $urlConfig = "File.php";
        
        public function GetItem($name, $value = "", $config = NULL, $required = false, $errorMessage = "", $eventsString = ""){
            $this->cuppa = Cuppa::getInstance();
            $language = $this->cuppa->language->load();
            $path_resource = @$_SESSION["cuSession"]->paths->administrator->path;
            if(!$config) $config = '{"folder":"media/upload_files"}';
			$configuration = new Configuration();
			if(!$value) $value = "";
			$this->errorMessage = ($errorMessage) ? $errorMessage : @$language->this_field_is_required;
			$this->config = json_decode($config);
			$field = "<div class='file_field' style='position:relative;'>";
			$field .= "<input $eventsString id='".$name."' name='".$name."' value='$value' ";
            //++ Class
    			$field .= " class='text_field file_input"; 
                if($required) $field .= " required ";
                if(@$this->config->disable)	$field .= " readonly ";
    			$field .= " ' ";
            //--
			$field .= " title='$this->errorMessage' ";
            if(@$this->config->disable)	$field .= " readonly='readonly' ";
            //++ Style
                $field .= " style=' position:relative; vertical-align:middle; ";
                        if(trim(@$this->config->width)) $field .= " width:".@$this->config->width.";";
                $field .= " ' ";
            //--            
			$field .= " /> ";
            
            $php_path = '';
            $cuppa = Cuppa::getInstance();
            if($cuppa) $php_path = @$cuppa->getPath()."js/jquery_file_upload/server/php/";
            if(@$this->config->resize){
                $max_width = @$this->config->max_width;
                $max_height = @$this->config->max_height;
            }
            $field .= "<script>cuppa.fileUpload('#".$name."', '".@$this->config->folder."', '".$php_path."', '".@$this->config->unique_name."', '".@$max_width."', '".@$max_height."', '".@$this->config->crop."')</script>";
            $field .= "</div>";
			return $field;
		}
	}
?>