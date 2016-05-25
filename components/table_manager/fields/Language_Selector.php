 <?php 
 	/**
    * Example: config: {"remove_option_all":false, "add_init_option":false, "init_value":"", "init_label":"Select"}
	*/
    
	class Language_Selector {
        private $config = null;
        private $language = null;
        private $cuppa = null;
        public $urlConfig = "Language_Selector.php";
		public function GetItem($name, $value = "", $config = NULL, $required = false, $errorMessage = "", $eventsString = "", $language_file = null){
		      $this->cuppa = Cuppa::getInstance();
              $this->config = json_decode($config);
		      $this->language = LanguageManager::getInstance()->load();              
              if($language_file) $this->language = $language_file;
              $languages = Cuppa::getInstance()->language->getLanguageFiles();
              
              $field =  "<select name='".$name."' id='".$name."' ";
              if(@$this->config->multiSelect){
                  $field .= "  size='".$this->config->height."' ";
                  $field .= "  multiple='multiple' ";
              }
              //++ Style
                $field .= " style=' min-width: 150px; ";
                        if(trim(@$this->config->width)) $field .= " width:".@$this->config->width.";";
                $field .= " ' ";
              //--
              $field.= " > ";
              //++ All
                  $add_all = true;
                  if(@$this->config->remove_option_all){ $add_all = false; }
                  if($add_all && !@$this->config->multiSelect){
                      $field .= "<option value=''>".$this->cuppa->language->getValue("All", $this->language)."</option>";
                  }
              //--
              //++ Init value
                if(@$this->config->add_init_option){
                    $field .= "<option value='".@$this->config->init_value."'>".@$this->config->init_label."</option>";
                }
              //--
              //++ Languages
                forEach($languages as $lang){
                    $label = $this->cuppa->language->getValue($lang, $this->language);
                    if(strpos($value, $lang) === false){
                        $field .= "<option value='".$lang."' >".$label."</option>";
                    }else{
                        $field .= "<option value='".$lang."' selected='selected'>".$label."</option>";
                    }
                }
              //--
              $field .= "</select>";
              return $field;
		}
	}
?>