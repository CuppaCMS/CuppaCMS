 <?php 
 	/**
    * Example Array:    {"data":[["1","true"],["0","false"]],"extraParams":{"language_reference":0,"add_custom_item":0,"custom_data":"","custom_label":""}}
    * Example Table:    {"data":{"table_name":"ex_projects","data_column":"id","label_column":"name","where_column":"","nested_column":"id","parent_column":"parent","init_id":"","dinamic_update_field":"","dinamic_update_column":""},"extraParams":{"add_custom_item":1,"custom_data":"0","custom_label":"No parent","no_translate":true,"width":""},"tooltip":""}
	*/
	
	class Select {
		private $name;
		private $value;
		private $eventsString;
		private $config;
        private $extraParams;
		private $required;
		private $errorMessage;
		private $include_clear_item;
        private $language = null;
        private $language_translation = true;
        private $language_reference;
        private $cuppa = null;
		public $urlConfig = "Select.php";
		
		public function GetItem($name = "select", $value = "", $config = NULL, $required = false, $errorMessage = "", $eventsString = "", $include_clear_item = "", $language_translation = true, $language_reference = ""){
			$this->cuppa = Cuppa::getInstance();
            $language = $this->cuppa->language->load();
            $this->name = $name;
			$this->value = $value;
            $this->language_translation = $language_translation;
            $this->language_reference = $language_reference;
			$this->config = json_decode($config);
            if(isset($this->config->extraParams)){
                $this->extraParams = @$this->config->extraParams;
                $this->config = @$this->config->data;
            }else if(@$this->config->data){
                $this->config = @$this->config->data;
            }
			$this->required = $required;
			$this->errorMessage = ($errorMessage) ? $errorMessage : @$language->this_field_is_required;
			$this->eventsString = $eventsString;
			$this->include_clear_item = $include_clear_item;
			if(is_array($this->config)){
				return $this->ArrayType($this->config);
			}else{
				return $this->TableType($this->config->table_name, $this->config->data_column, $this->config->label_column);
			}
		}
		private function ArrayType($config){
            if($this->language_translation){
                $this->language = $this->cuppa->language->load($this->language_reference);
            }
            // Create Select
                $field =  "<select name='$this->name' id='$this->name' $this->eventsString";
                if($this->required) $field.= " class='required' title='$this->errorMessage' ";
            //++ Style
                $field .= " style=' ";
                            if(trim(@$this->extraParams->width)) $field .= " width:".@$this->extraParams->width.";";
                $field .= " ' ";
            //--
                $field.= ">";
            // add Clear Item
                if($this->include_clear_item) $field .= "<option value='".$this->include_clear_item[0]."'>".$this->include_clear_item[1]."</option>";
            // add Custom Item
                if(@$this->extraParams->add_custom_item){
                    if($this->value == $this->extraParams->custom_data) $field .= "<option value='".$this->extraParams->custom_data."' selected='selected'>";
                    else $field .= "<option value='".$this->extraParams->custom_data."'>";
                    $field .= $this->cuppa->language->getValue($this->extraParams->custom_label, $this->language);
                    $field .= "</option>";
                }
            // add Other info
    			for($i = 0; $i < count($config); $i++){
    				if($this->value == $config[$i][0]) $field .= "<option value='".$config[$i][0]."' selected='selected'>";
                    else $field .= "<option value='".$config[$i][0]."'>";
                    if(@$this->extraParams->no_translate){
                        $field .= $config[$i][1];
                    }else{
                        $field .= $this->cuppa->language->getValue($config[$i][1], $this->language);
                    }
                    $field .= "</option>";
    			}
			$field .= "</select>";
			return $field;
		}
		private function TableType($table_name, $data_column, $label_column){
            $labels = explode(",", $label_column);
            $label_column = trim($labels[0]);
            array_shift($labels);
            if($this->language_translation){
                $this->language = $this->cuppa->language->load($this->language_reference);
            }          		  
			$db = DataBase::getInstance();
            if(@$this->config->parent_column && @$this->config->nested_column){
                $config = $db->getList($table_name, @$this->config->where_column);
                $config = Cuppa::getInstance()->utils->createTree($config, $this->config->nested_column, $this->config->parent_column, "alias", true, @$this->config->init_id );
                for($i = 0; $i < count($config); $i++){
                    $config[$i][$label_column] = @$config[$i]["deep_string"] . $config[$i][$label_column];
                }
            }else{
                $config = $db->getList($table_name, @$this->config->where_column, "", $label_column . " ASC");
            }
            // Create selected
                $field =  "<select name='$this->name' id='$this->name' $this->eventsString ";
                if($this->required) $field.= " class='required' title='$this->errorMessage' ";
            //++ Style
                $field .= " style=' ";
                            if(trim(@$this->extraParams->width)) $field .= " width:".@$this->extraParams->width.";";
                $field .= " ' ";
            //--
                $field.= ">";
			// add Clear Item
                if($this->include_clear_item) $field .= "<option value='".$this->include_clear_item[0]."'>".$this->include_clear_item[1]."</option>";
            // add Custom Item
                if(@$this->extraParams->add_custom_item){
                    if($this->value == $this->extraParams->custom_data) $field .= "<option value='".$this->extraParams->custom_data."' selected='selected'>";
                    else $field .= "<option value='".$this->extraParams->custom_data."'>";
                    $field .= $this->cuppa->language->getValue($this->extraParams->custom_label, $this->language);
                    $field .= "</option>";
                }
            // add other info  
                if(@$this->config->dinamic_update_field && @$this->config->dinamic_update_column){                              
    			      $field .= "<option value=''>";
                      $field .= @$this->language->select;
                      $field .= "</option>";
                }else if($config){
    				for($i = 0; $i < count($config); $i++){
                        if($this->value == $config[$i][$data_column]) $field .= "<option value='".$config[$i][$data_column]."' selected='selected'>";
                        else $field .= "<option value='".$config[$i][$data_column]."'>";
                            if(@$this->extraParams->no_translate){
                                $field .= $config[$i][$label_column];
                            }else{
                                $field .= $this->cuppa->language->getValue($config[$i][$label_column], $this->language);
                            }
                            //++ add more labels info
                                for($k = 0; $k < count($labels); $k++){
                                    if(@$config[$i][trim($labels[$k])]) $field.= $config[$i][trim($labels[$k])];
                                    else $field.= $labels[$k];
                                }
                            //--
                        $field .= "</option>";                                                                                                    
    				}
    			}
			$field .= "</select>";
            if(@$this->config->dinamic_update_field && @$this->config->dinamic_update_column){
                echo "<script>cuppa.autoLoadSelect('".@$this->value."','#".$this->config->dinamic_update_field."_field','#".$this->name."','$table_name','$data_column','$label_column', '".@$this->config->where_column."', '".$this->config->dinamic_update_column."')</script>";
            }
			return $field;
		}
	}
?>