<?php 
	/**
	*/
	
	class Date{
		private $required;
		private $config;
		private $errorMessage;
		private $eventsString;
		private $value;
		private $name;
		public $urlConfig = "Date.php";
		
		public function GetItem($name = "input", $value = "", $config = NULL, $required = false, $errorMessage = "", $eventsString = ""){
			$this->name = $name;
			$this->value = $value;
			$this->config = json_decode($config);
			$this->required = $required;
			$this->errorMessage = $errorMessage; if(!$errorMessage) $this->errorMessage = " ";
			$this->eventsString = $eventsString;
			if($this->config->type == "datePicker"){
				return $this->GetDataPiker();
			}else if($this->config->type == "normal"){
				
			}
		}
		private function GetDataPiker(){
			$field = "";
			$field.= "<input title='$this->errorMessage' $this->eventsString type='text' id='$this->name' name='$this->name' readonly='readonly' class='text_field readonly ";
			if($this->required) $field.= " required ";
			$field.= " ' ";
			if($this->config->config == "auto_today_selected" && !trim($this->value)) $field.= " value='".date("Y-m-d")."'  ";
			else $field.= " value='$this->value'  ";
            //++ Style
                $field .= " style=' ";
                        if(trim(@$this->config->width)) $field .= " width:".@$this->config->width.";";
                $field .= " ' ";
            //--
			$field.= " />";
            if(!@$this->config->disable) $field.= "<script>cuppa.date('#$this->name')</script>";
			return $field; 
		}
		private function GetDate(){
			$field.= "";
			return $field; 
		}
	}
?>