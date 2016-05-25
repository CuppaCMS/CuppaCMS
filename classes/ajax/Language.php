<?php
    include_once("../Cuppa.php");
	//++ Functions
		function loadFile(){
            $cuppa = Cuppa::getInstance();
            $all_languages = $cuppa->language->getLanguageFiles($cuppa->getDocumentPath()."language/");
            $info = new stdClass();
            for($i = 0; $i < count($all_languages); $i++){
                $file_data = $cuppa->file->loadFile($cuppa->getDocumentPath()."language/".$all_languages[$i]."/".$_POST["file"]);
                $file = json_decode($file_data); 
                if(!$file) $file = json_decode(utf8_encode($file_data));
                $info->{$all_languages[$i]} = $file;
            }
            $file_data = $cuppa->file->loadFile($cuppa->getDocumentPath()."language/".$cuppa->language->getCurrentLanguage()."/".$_POST["file"]);
            $file = json_decode($file_data); if(!$file) $file = json_decode(utf8_encode($file_data));
            $info->labels = $cuppa->utils->getObjectVars($file);
            echo base64_encode(json_encode($info));
		}
        function saveFile(){
             $cuppa = Cuppa::getInstance();
             $info = $cuppa->jsonDecode($cuppa->POST("info"));
             $file = $cuppa->POST("file");
             $result = $cuppa->language->saveFile($file, $info);
             echo $result;
        }
        function createFile(){
            $cuppa = Cuppa::getInstance();
            $result = $cuppa->language->createFile($cuppa->POST("file"));
            echo $result;
        }
        function deleteFile(){
            $cuppa = Cuppa::getInstance();
            $result = $cuppa->language->deleteFile($cuppa->POST("file"));
            echo $result;
        }
        function createReference(){
            $cuppa = Cuppa::getInstance();
            $result = $cuppa->language->createReference($cuppa->POST("reference"), $cuppa->POST("base"));
            echo $result;
        }
        function deleteReference(){
            $cuppa = Cuppa::getInstance();
            $result = $cuppa->language->deleteReference($cuppa->POST("reference"));
            echo $result;
        }
	//--
	//++ Handler
		$nameFunction = $_POST["function"];
		if($nameFunction == ""){ echo "function name no found"; exit();}
		$nameFunction();
	//--
?>