<?php 
	/**
	*/
	
	class TextArea{
		private $required;
		private $config;
		private $errorMessage;
        private $cuppa = null;
		public $urlConfig = "TextArea.php";
		
		public function GetItem($name = "textArea", $value = "", $config = NULL, $required = false, $errorMessage = "", $eventsString = "", $font_list = ""){
            $this->cuppa = Cuppa::getInstance();
            $language = $this->cuppa->language->load();
            $this->required = $required;
			$this->config = json_decode($config);
            $this->errorMessage = ($errorMessage) ? $errorMessage : @$language->this_field_is_required;
            if($this->config->editor == "jsoneditor"){
                $field = "<textarea style='width:".$this->config->width."; height:".$this->config->height."' $eventsString id='".$name."' name='".$name."' >";
                $field.= $value;
                $field.= "</textarea>";
                echo "<script>cuppa.jsonEditor('#".$name."', '".@$this->config->base_64_encode."')</script>";
            }else if($this->config->editor == "tinymce"){
    			$field = "<textarea style='width:".$this->config->width."; height:".$this->config->height."' $eventsString id='".$name."' name='".$name."' ";
    			$field .= " class='text_field ";
                if($required) $field .= " required ";
    			$field .= " ' ";
    			$field.= " title='$this->errorMessage' ";
    			$field.= " style='width: 100%; height: 300px' ";
    			$field.= " >";
                if(@json_decode($value) && @json_encode(json_decode($value), JSON_PRETTY_PRINT)) $field.= json_encode(json_decode($value), JSON_PRETTY_PRINT);
    			else $field.= $value;
    			$field.= "</textarea>";
                echo "<script>cuppa.tinyMCE('#$name','".@$this->config->width."','".@$this->config->height."','".@$this->config->styles_css."','','".@$this->config->template_list."','".@$this->config->folder."', '".@$font_list."')</script>";
            }else if($this->config->editor == "ace"){
    			$field = "<textarea style='width:".$this->config->width."; height:".$this->config->height."' $eventsString id='".$name."' name='".$name."' ";
    			$field .= " class='text_field ace_editor_text_area";
                if($required) $field .= " required ";
    			$field .= " ' ";
    			$field.= " title='$this->errorMessage' ";
                if(@$this->config->maxlength) $field.= " maxlength='".@$this->config->maxlength."' ";
    			$field.= " >";
                if(@json_decode($value) && @json_encode(json_decode($value), JSON_PRETTY_PRINT)) $field.= json_encode(json_decode($value), JSON_PRETTY_PRINT);
    			else $field.= $value;
    			$field.= "</textarea>";
                echo "<script>cuppa.aceEditor('#".$name."', '".$this->config->width."','".@$this->config->height."', '".@$this->config->editor_mode."')</script>";
            }else{
    			$field = "<textarea style='width:".$this->config->width."; height:".$this->config->height."' $eventsString id='".$name."' name='".$name."' ";
    			$field .= " class='text_field ";
                if($required) $field .= " required ";
    			$field .= " ' ";
    			$field.= " title='$this->errorMessage' ";
                if(@$this->config->maxlength) $field.= " maxlength='".@$this->config->maxlength."' ";
    			$field.= " >";
                if(@json_decode($value) && @json_encode(json_decode($value), JSON_PRETTY_PRINT)) $field.= json_encode(json_decode($value), JSON_PRETTY_PRINT);
    			else $field.= $value;
    			$field.= "</textarea>";
            }
			return $field;
		}
	}
?>