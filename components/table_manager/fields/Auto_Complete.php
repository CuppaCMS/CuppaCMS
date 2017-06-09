 <?php 
 	/**
    * Example Array:    {"data":["as3","as2"],"extraParams":{"language_reference":true,"multi_values":true}}
    * Example Table:    {"data":{"table_name":"cu_permissions","column":"id", "where_column":""},"extraParams":{"multi_values":1, "language_reference":1}}            
	*/
    
	class Auto_Complete {
		private $name;
		private $value;
        private $config;
        private $required;
		private $errorMessage;
        private $extraParams;
		private $include_clear_item;
        private $language = null;
        private $cuppa = null;
		public $urlConfig = "Auto_Complete.php";
		
		public function GetItem($name = "select", $value = "", $config = NULL, $required = false, $errorMessage = "", $extraParams = ""){
			$this->cuppa = Cuppa::getInstance();
            $language = $this->cuppa->language->load();
            $this->name = $name;
			$this->value = $value;
			$this->config = json_decode($config);
            $this->required = $required;
            $this->errorMessage = ($errorMessage) ? $errorMessage : @$language->this_field_is_required;
            $this->extraParams = $extraParams;
            
			if(is_array($this->config->data)){
				return $this->ArrayType($this->config);
			}else{
				return $this->TableType($this->config);
			}
		}
		private function ArrayType($config){
              $this->language = LanguageManager::getInstance()->load();
              $field = "<input ".@$extraParams." id='".@$this->name."' name='".@$this->name."' value='".@$this->value."' ";
              $field .= " class='text_field "; 
              if($this->required) $field .= " required ";
              $field .= " ' ";
              $field.= " title='".$this->errorMessage."' ";
              //++ Style
                $field .= " style=' ";
                        if(trim(@$config->extraParams->width)) $field .= " width:".@$config->extraParams->width.";";
                $field .= " ' ";
              //--
		      $field.= " /> ";
              //++ Create array with info
                  $data = $config->data;
                  if(@$config->extraParams->language_reference){
                    $data = array();
                    for($i = 0; $i < count($config->data); $i++){
                        array_push($data, $this->cuppa->language->getValue($config->data[$i], $this->language));
                    }
                  }
              //--
              $field.= "<script>cuppa.autoComplete('#".$this->name."','".json_encode($data)."','".@$config->extraParams->multi_values."')</script>";
		      return $field;
		}
		private function TableType($config){
            $this->language = LanguageManager::getInstance()->load();
            $field = "<input ".@$extraParams." id='".$this->name."' name='".$this->name."' value='".$this->value."' ";
            $field .= " class='text_field "; 
            if($this->required) $field .= " required ";
            $field .= " ' ";
            $field.= " title='".$this->errorMessage."' ";
            //++ Style
                $field .= " style=' ";
                        if(trim(@$config->extraParams->width)) $field .= " width:".@$config->extraParams->width.";";
                $field .= " ' ";
            //--
            $field.= " /> ";
            //++ Get Data
                $db = DataBase::getInstance();
                $table_data = $db->getList($config->data->table_name, @$config->data->where_column, "", $config->data->column . " ASC", true);
                $data = array();
                for($i = 0; $i < count($table_data); $i++){
                    $column = $config->data->column;
                    array_push($data, $table_data[$i]->{$column});
                }
                if(@$config->extraParams->language_reference){
                    $config->data = $data;
                    $data = array(); 
                    for($i = 0; $i < count($config->data); $i++){
                        array_push($data, $this->cuppa->language->getValue($config->data[$i], $this->language));
                    }
                }
            //--
            $field.= "<script>cuppa.autoComplete('#".$this->name."','".json_encode($data)."','".@$config->extraParams->multi_values."')</script>";
            return $field;
		}
	}
?>