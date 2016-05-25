<?php 
	/**
	*/
	
	class Id{
		public function GetItem($name, $value = "0", $config = NULL, $required = false, $errorMessage = "", $eventsString = ""){
			if(!$value) $value = "0";
			return "<input type='text' id='$name' name='$name' value='$value' readonly='readonly' class='text_field readonly' $eventsString/>";
		}
	}
?>