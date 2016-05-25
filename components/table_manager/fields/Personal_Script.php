<?php 
	class Personal_Script{
		private $required;
		private $config;
		private $errorMessage;
		public $urlConfig = "Personal_Script.php";
		
		public function GetItem($name = "input", $value = "", $config = NULL, $required = false, $errorMessage = "", $eventsString = ""){
			$this->required = $required;
			$this->config = json_decode($config);
			$this->errorMessage = $errorMessage; if(!$errorMessage) $this->errorMessage = " ";
			$field = "<input $eventsString id='".$name."' name='".$name."' ";
            if($value) $field .= "value='$value'"; else  $field .= "value='". @eval('return '.@$this->config->script.';') ."'";
			$field .= " class='text_field "; 
			if($required) $field .= " required ";
            if(!$this->config->editable) $field .= " readonly ";
			$field .= " ' ";
			$field.= " title='$this->errorMessage' ";
            if(!$this->config->editable) $field .= " readonly='readonly' ";
            //++ Style
                $field .= " style=' ";
                        if(trim(@$this->config->width)) $field .= " width:".@$this->config->width.";";
                $field .= " ' ";
            //--
			$field.= " /> ";
			return $field;
		}
	}
?>