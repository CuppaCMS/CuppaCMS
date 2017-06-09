<?php 
	/**
	*/
	
	class Radio{
		private $required;
		private $config;
		private $errorMessage;
        private $language;
        private $cuppa = null;
		public $urlConfig = "Radio.php";
        
		public function GetItem($name = "input", $value = "", $config = NULL, $required = false, $errorMessage = "", $eventsString = ""){
			$this->cuppa = Cuppa::getInstance();
            $this->language = $this->cuppa->language->load();
            $this->required = $required;
			$this->config = json_decode($config);            
			$this->errorMessage = ($errorMessage) ? $errorMessage : @$this->language->this_field_is_required;
			// Create Field
                $field = "";      
    			for($i = 0; $i < count($this->config->data); $i ++){
    				$ban = 0;
    				if(!$value && $i == 0){
    					$ban = 1;
    					$field .= '<input '.$eventsString.' type="radio" checked="checked" title="'.$this->errorMessage.'" name="'.$name.'" id="'.$name.'_'.$this->config->data[$i][0].'" value="'.$this->config->data[$i][0].'" ';
    				}else if($value == $this->config->data[$i][0]){
    					$ban = 2;
    					$field .= '<input '.$eventsString.' type="radio" checked="checked" title="'.$this->errorMessage.'" name="'.$name.'" id="'.$name.'_'.$this->config->data[$i][0].'" value="'.$this->config->data[$i][0].'" ';
    				}else{
    					$ban = 3;
    					$field .= '<input '.$eventsString.' type="radio" title="'.$this->errorMessage.'" name="'.$name.'" id="'.$name.'_'.$this->config->data[$i][0].'" value="'.$this->config->data[$i][0].'" ';
    				}
    				$field .= ' class="radio ';
    				if($this->required) $field .= ' required  ';
    				$field .= '" /> ';
                    $field .= '<span class="radio_text" >'.$this->cuppa->language->getValue($this->config->data[$i][1], $this->language).'</span>';
                    
    			}
			return $field;
		}
	}
?>