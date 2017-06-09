<?php 
	class Text{
		private $required;
		private $config;
		private $errorMessage;
        private $cuppa = null;
		public $urlConfig = "Text.php";
        public $urlChangePassword = "Text_Change_Password.php";
        public $button_lang_reference = true;
        public $button_label = "Change password";
		
		public function GetItem($name = "input", $value = "", $config = NULL, $required = false, $errorMessage = "", $extraParams = ""){
			$this->cuppa = Cuppa::getInstance();
            $language = $this->cuppa->language->load();
            $this->required = $required;
			$this->config = json_decode($config);
			$this->errorMessage = ($errorMessage) ? $errorMessage : @$language->this_field_is_required;
            if(@$this->config->type == "password" && @$this->config->new != 1 ){
                if(@$this->button_lang_reference){
                    $language = LanguageManager::getInstance()->load();
                    $this->button_label = $this->cuppa->language->getValue("change_password", @$language);
                }
                $field = "<input type='button' class='password_button button_form' value='".$this->button_label."' onclick='stage.loadConfigAlert(\"".$name."\", \"".$this->urlChangePassword."\", \"".base64_encode($config)."\" )' ";
                //++ Style
                    $field .= " style=' ";
                            if(trim(@$this->config->width)) $field .= " width:".@$this->config->width.";";
                    $field .= " ' ";
                //--
                $field.= " /> ";
            }else if(@$this->config->type == "password" && @$this->config->new == 1){
                $field = "<input ".$extraParams." id='".$name."' name='".$name."' value='$value' ";
                $field.= " type='password' ";
                //++ Classes
        			$field .= " class='text_field ";
                    if($required) $field .= " required ";
        			$field .= " ' ";
                //--
                $field.= " /> ";
            }else{
                $field = "<input ".$extraParams." id='".$name."' name='".$name."' ";
                if(@$value === null || @$value === ""){
                    $field.= " value='". $this->cuppa->utils->evalString(@$this->config->default)."' ";
                }else{
                    $value = str_replace("'","&#039;", $value);
                    $field.= " value='".$value."' ";
                }
    			$field.= " type='text' ";
                //++ Classes
        			$field .= " class='text_field ";
        				if(@$this->config->type == "email"){ 
        				    if($required) $field .= " email ";
                        }
                        if(@$this->config->type == "number"){
                            if(@$this->config->number_fortmat != "money"){
                                 $field .= " number ";
                            }
                        }
                        if(@$this->config->disable)	$field .= " readonly ";
        				if($required) $field .= " required ";
        			$field .= " ' ";
                //--
    			$field.= " title='$this->errorMessage' ";
                if(@$this->config->disable)	$field .= " readonly='readonly' ";
                if(isset($this->config->maxlength))  $field.= " maxlength='".$this->config->maxlength."' ";
                //++ Style
                    $field .= " style=' ";
                            if(trim(@$this->config->width)) $field .= " width:".@$this->config->width.";";
                    $field .= " ' ";
                //--
   	            $field.= " /> ";
                if(@$this->config->alias_from){
                    $sql = "SELECT ".$this->config->field." FROM ".$this->config->table;
                    $invalid_alias = $this->cuppa->dataBase->sql($sql, false);
                    if(is_array($invalid_alias)){
                         $invalid_alias_list = array();
                        for($i = 0; $i < count($invalid_alias); $i++){
                            if(@$value != $invalid_alias[$i][$this->config->field]){
                                array_push($invalid_alias_list, $invalid_alias[$i][$this->config->field]);
                            }
                        }
                    }
                    $invalid_alias_list = @join(",", @$invalid_alias_list);
                    $field .= " <script>cuppa.alias('".$name."', '".@$this->config->alias_from."', '".@$invalid_alias_list."')</script>";
                    $field .= " <script>cuppa.inputFilter('#".$name."', 'a-z0-9-')</script>";
                };
                if(@$this->config->type == "number" && @$this->config->number_fortmat != "money"){
                    $field .= "<script>cuppa.inputFilter('#".$name."', '0-9.-')</script>";
                }else if(@$this->config->type == "number" && @$this->config->number_fortmat == "money" ){
                    $field .= "<script> cuppa.moneyInput('#".$name."')  </script>";
                }else if(@$this->config->type == "tags"){
                    $field .= "<script> cuppa.tags('#".$name."'); </script>";
                }
            }
			return $field;
		}
	}
?>