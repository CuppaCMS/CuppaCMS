 <?php 
 	/**
    * Example Array:    {"data":[["1","true"],["0","false"]],"extraParams":{"language_reference":0,"add_custom_item":0,"custom_data":"","custom_label":""}}
    * Example Table:    {"data":{"table_name":"cu_permissions","data_column":"id","label_column":"title", "where_column":""},"extraParams":{"language_reference":1,"add_custom_item":1,"custom_data":"0","custom_label":"unselect", "multiSelect":1,"listHeight":7}} 
    * 
	*/
	
	class Select_List {
		private $name;
		private $value;
		private $eventsString;
		private $config;
		private $required;
		private $errorMessage;
		private $include_clear_item;
        private $extraParams;
        private $language = null;
        private $language_translation = true;
        private $language_reference;
        private $cuppa = null;
		public $urlConfig = "Select_List.php";
		
		public function GetItem($name = "select", $value = "", $config = NULL, $required = false, $errorMessage = "", $eventsString = "", $include_clear_item = false, $language_translation = true, $language_reference = ""){
            $this->cuppa = Cuppa::getInstance();
            $language = $this->cuppa->language->load();
            $this->name = $name;
			$this->value = json_decode($value);
			$this->config = json_decode($config);
            $this->language_translation = $language_translation;
            $this->language_reference = $language_reference;
            if(isset($this->config->extraParams)){
                $this->extraParams = $this->config->extraParams;
            }
			$this->required = $required;
			$this->errorMessage = ($errorMessage) ? $errorMessage : @$language->this_field_is_required;
			$this->eventsString = $eventsString;
			$this->include_clear_item = $include_clear_item;
			if(is_array($this->config->data)){
				return $this->ArrayType($this->config->data);
			}else{
				return $this->TableType($this->config->data->table_name, $this->config->data->data_column, $this->config->data->label_column);
			}
		}
		private function ArrayType($config){
            if($this->language_translation){
                $this->language = $this->cuppa->language->load($this->language_reference);
            }
            $size = @$this->config->extraParams->listHeight;
			$field =  "<select name='$this->name' size='$size' id='$this->name' $this->eventsString ";
            // If Multiple
                if(@$this->config->extraParams->multiSelect) $field.= " multiple='multiple' ";
            // If Required
                if($this->required) $field.= " class='required' title='$this->errorMessage' ";
            //++ Style
                $field .= " style=' min-width: 150px; ";
                            if(trim(@$this->extraParams->width)) $field .= " width:".@$this->extraParams->width.";";
                $field .= " ' ";
            //--
			$field.= ">";
            // Include Clear item
                if($this->include_clear_item) $field .= "<option value=''></option>";
            // add Custom Item
                if(@$this->extraParams->add_custom_item){
                    $selectedItem = false;
                    for($j = 0; $j < count($this->value); $j++){
                       if($this->value[$j] == $this->extraParams->custom_data) $selectedItem = true;
                    }
                    if($selectedItem) $field .= "<option value='".$this->extraParams->custom_data."' selected='selected'>";
                    else $field .= "<option value='".$this->extraParams->custom_data."'>";
                    $field .= $this->cuppa->language->getValue($this->extraParams->custom_label, $this->language);
                    $field .= "</option>";
                }
            // Other info
    			for($i = 0; $i < count($config); $i++){
    			    $selectedItem = false;
                    for($j = 0; $j < count($this->value); $j++){
                       if($this->value[$j] == $config[$i][0]) $selectedItem = true;
                    }
                    // option
                        if($selectedItem) $field .= "<option value='".$config[$i][0]."' selected='selected'>";
    				    else $field .= "<option value='".$config[$i][0]."'>";
                    // label
                        if(@$this->extraParams->no_translate){
                            $field .= $config[$i][1];
                        }else{
                            $field .= $this->cuppa->language->getValue($config[$i][1], $this->language);
                        }
                    $field.= "</option>";
    			}
			$field .= "</select>";
            if(@$this->config->extraParams->mirrorList){
                echo "<script> cuppa.selectListMirror('#".$this->name."', '".json_encode(@$this->value)."') </script>";
            };
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
            if(@$this->config->data->parent_column && @$this->config->data->nested_column){
                $config = $db->getList($table_name, @$this->config->data->where_column);
                $config = Cuppa::getInstance()->utils->createTree($config, $this->config->data->nested_column, $this->config->data->parent_column, "alias", true, @$this->config->data->init_id);
                for($i = 0; $i < count($config); $i++){
                    $config[$i][$label_column] = @$config[$i]["deep_string"] . $config[$i][$label_column];
                }
            }else{
                $config = $db->getList($table_name, @$this->config->data->where_column, "", "`".$label_column."`" . " ASC");
            }
            $size = @$this->config->extraParams->listHeight;
			$field =  "<select name='$this->name' size='$size' id='$this->name' $this->eventsString ";
            // If Multiple
                if(@$this->config->extraParams->multiSelect) $field.= " multiple='multiple' ";
            // If Required
                if($this->required) $field.= " class='required' title='$this->errorMessage' ";
            //++ Style
                $field .= " style=' min-width: 150px; ";
                            if(trim(@$this->extraParams->width)) $field .= " width:".@$this->extraParams->width.";";
                $field .= " ' ";
            //--
            $field.= ">";
            // Include Clear item
                if($this->include_clear_item) $field .= "<option value=''></option>";
            // add Custom Item
                if(@$this->extraParams->add_custom_item){
                    $selectedItem = false;
                    for($j = 0; $j < count($this->value); $j++){
                       if($this->value[$j] == $this->extraParams->custom_data) $selectedItem = true;
                    }
                    if($selectedItem) $field .= "<option value='".$this->extraParams->custom_data."' selected='selected'>";
                    else $field .= "<option value='".$this->extraParams->custom_data."'>";
                    $field .= $this->cuppa->language->getValue($this->extraParams->custom_label, $this->language);
                    $field .= "</option>";
                }
            // Other info
    			if($config &&  (!@$this->config->data->dinamic_update_field || !@$this->config->data->dinamic_update_column) ){ 
    				for($i = 0; $i < count($config); $i++){
    				    $selectedItem = false;
                        for($j = 0; $j < count($this->value); $j++){
                           if($this->value[$j] == $config[$i][$data_column]) $selectedItem = true;
                        }
                        // option
    					   if($selectedItem) $field .= "<option value='".$config[$i][$data_column]."' selected='selected'>";
    					   else $field .= "<option value='".$config[$i][$data_column]."'>";
                        // label
                            if(@$this->extraParams->no_translate){
                                $field .= $config[$i][$label_column];
                            }else{
                                $label = $db->getConstraintValue($table_name, $label_column, @$config[$i][$label_column] );
                                $field .= $this->cuppa->language->getValue(@$label, $this->language);
                            }
                            //++ add more labels info
                                for($k = 0; $k < count($labels); $k++){
                                    $label = $db->getConstraintValue($table_name, $labels[$k], @$config[$i][trim($labels[$k])] );
                                    if($label) $field.= $label;
                                    else $field.= $labels[$k];
                                }
                            //--
                        $field.= "</option>";
    				}
    			}
			$field .= "</select>";
            if(@$this->config->data->dinamic_update_field && @$this->config->data->dinamic_update_column){
                echo "<script>cuppa.autoLoadSelect('".json_encode(@$this->value)."','#".$this->config->data->dinamic_update_field."_field','#".$this->name."','$table_name','$data_column','$label_column', '".@$this->config->data->where_column."', '".$this->config->data->dinamic_update_column."', false)</script>";
            }
            if(@$this->config->extraParams->mirrorList){
                echo "<script> cuppa.selectListMirror('#".$this->name."', '".json_encode(@$this->value)."') </script>";
            };
			return $field;
		}
	}
?>