<?php 
	/**
	*/
	
	class TextArea{
		private $required;
		private $config;
		private $errorMessage;
		public $urlConfig = "TextArea.php";
		
		public function GetItem($name = "textArea", $value = "", $config = NULL, $required = false, $errorMessage = "", $eventsString = "", $font_list = ""){
            $this->required = $required;
			$this->config = json_decode($config);
            if($this->config->editor == "jsoneditor"){
                $this->errorMessage = $errorMessage; if(!$errorMessage) $this->errorMessage = " ";
                $field = "<textarea style='width:".$this->config->width."; height:".$this->config->height."' $eventsString id='".$name."' name='".$name."' >";
                $field.= $value;
                $field.= "</textarea>";
                echo "<script>cuppa.jsonEditor('#".$name."', '".@$this->config->base_64_encode."')</script>";
            }else if($this->config->editor == "tinymce"){
                $this->errorMessage = $errorMessage; if(!$errorMessage) $this->errorMessage = " ";
    			$field = "<textarea style='width:".$this->config->width."; height:".$this->config->height."' $eventsString id='".$name."' name='".$name."' ";
    			$field .= " class='text_field ";
    			$field .= " ' ";
    			$field.= " title='$this->errorMessage' ";
    			$field.= " style='width: 100%; height: 300px' ";
    			$field.= " >";
                if(@json_decode($value) && @json_encode(json_decode($value), JSON_PRETTY_PRINT)) $field.= json_encode(json_decode($value), JSON_PRETTY_PRINT);
    			else $field.= $value;
    			$field.= "</textarea>";
                echo "<script>cuppa.tinyMCE('#$name','".@$this->config->width."','".@$this->config->height."','".@$this->config->styles_css."','','".@$this->config->template_list."','".@$this->config->folder."', '".@$font_list."')</script>";
            }else if($this->config->editor == "ace"){
                $this->errorMessage = $errorMessage; if(!$errorMessage) $this->errorMessage = " ";
    			$field = "<textarea style='width:".$this->config->width."; height:".$this->config->height."' $eventsString id='".$name."' name='".$name."' ";
    			$field .= " class='text_field ace_editor_text_area";
    			$field .= " ' ";
    			$field.= " title='$this->errorMessage' ";
                if(@$this->config->maxlength) $field.= " maxlength='".@$this->config->maxlength."' ";
    			$field.= " >";
                if(@json_decode($value) && @json_encode(json_decode($value), JSON_PRETTY_PRINT)) $field.= json_encode(json_decode($value), JSON_PRETTY_PRINT);
    			else $field.= $value;
    			$field.= "</textarea>";
                echo "<script>cuppa.aceEditor('#".$name."', '".$this->config->width."','".@$this->config->height."', '".@$this->config->editor_mode."')</script>";
            }else{
                $this->errorMessage = $errorMessage; if(!$errorMessage) $this->errorMessage = " ";
    			$field = "<textarea style='width:".$this->config->width."; height:".$this->config->height."' $eventsString id='".$name."' name='".$name."' ";
    			$field .= " class='text_field ";
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