<?php
    class LanguageManager{
        private static $instance;
        private function __construct(){ }
        public static function getInstance() {
			if (self::$instance == NULL) { self::$instance = new LanguageManager(); } 
			return self::$instance;
		}
        // Laoad and add language
            public function load($file = "administrator", $language = null){
                $file = trim($file);
                if(!$language) $language = $this->getCurrentLanguage();
                $file_path =  realpath(__DIR__."/..")."/language/".$language."/";
                $data = @file_get_contents($file_path.$file.".json");
                $file = @json_decode($data);
                if(!$file) $file = json_decode(utf8_encode($data));
                if(!$file) return;
                return $file;
            }
        // Get all language reference, [es, en, ...]
            public function getLanguageFiles($path = ""){
                if(!$path) $path =  realpath(__DIR__."/..")."/language/";
        		$files = @scandir($path);
                $languages = array();
        		for($i = 0; $i < @count($files); $i++){
        			if($files[$i] != "." && $files[$i] != ".."){
        				$temp_name_file = explode(".", $files[$i]);
        				array_push($languages, $temp_name_file[0]);
        			}
        		}
                return $languages;
            }
            public function languages($path = ""){
                return $this->getLanguageFiles($path);
            }
            public function getLanguagesAvailable($path = ""){
                return $this->getLanguageFiles($path);
            }
        // Is Valid language
            public function validLanguage($value, $path = ""){
                $value = explode("-",$value); $value = @$value[0];
                $languages = $this->getLanguagesAvailable($path);
                $aviable = false;
                for($i = 0; $i < count($languages); $i++){
                    if($value == $languages[$i]){ $aviable = true; break; }
                }
                return $aviable;
            }
            public function valid($value, $path = ""){ return $this->validLanguage($value, $path); }
        // Get current language selected
            public function getCurrentLanguage(){
                $utils = Utils::getInstance();
                $language = $utils->getCookie("language");
                return $language;
            }
            public function current(){
                return $this->getCurrentLanguage();
            }
        // Create default language cookie variable
            public function setCurrentLanguage($language = "", $force = false, $config = null){
                $utils = Utils::getInstance();
                $lang = "";
                if($force && $this->valid($language)){ 
                    $lang = $language;
                }else if( $this->valid($this->getLanguagePath()) ){
                    $lang = $this->getLanguagePath();
                }else if($this->valid($utils->getCookie("language"))){
                    $lang = $utils->getCookie("language");
                }else if($this->valid($language)){
                    $lang = @$language;
                }else if($config){
                    $browserLang = strtolower(trim(substr(@$_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)));
                    if($this->valid($browserLang)){
                        $lang = $browserLang;
                    }else{
                        $lang = $config->language_default;
                    }
                }
                $utils->setCookie("language", $lang);
                return $lang;
            }
            public function set($language = "", $forced = false, $config = null){
                return $this->setCurrentLanguage($language, $forced, $config);
            }
        // Get Language Value
            public function getValue($key = "", $language_file_reference = "administrator", $convert_to_url_friendy = false){
                if(!$key) return '';
                $real_key = $key;
                if(is_string(@$language_file_reference) ){ $language_file_reference = $this->load($language_file_reference); }
                $cuppa = Cuppa::getInstance();
                $data = "";
                if(@$language_file_reference->{$key}){ $data = @$language_file_reference->{$key}; }
                if(!@$data){ $key = strtolower($key); $data = @$language_file_reference->{$key}; }
                if(!@$data){ $key = $cuppa->utils->getFriendlyUrl($key); $data = @$language_file_reference->{$key}; }
                if(!@$data){ $key = str_replace("-", "_", $key); $data = @$language_file_reference->{$key}; }
                if(!@$data){ $key = str_replace("_", " ", $key); $data = @$language_file_reference->{$key}; }
                if(!@$data){ $key = ucfirst($key); $data = @$language_file_reference->{$key}; }
                if(!@$data){ $key = ucwords($key); $data = @$language_file_reference->{$key}; }
                if(!@$data){ $key = strtoupper($key); $data = @$language_file_reference->{$key}; } 
                if($data && $convert_to_url_friendy){ $data = strtolower($cuppa->utils->getFriendlyUrl($data)); }
                if(@$data) return @$data;
                else if($convert_to_url_friendy) return $cuppa->utils->getFriendlyUrl($real_key);
                else return @$real_key;
            }
            public function value($key = "", $language_file_reference = "administrator", $convert_to_url_friendy = false){
                return $this->getValue($key, $language_file_reference, $convert_to_url_friendy);
            }
        // Get Language Values,
            public function getValues($array_values, $language_file_reference, $convert_to_url_friendy = false, $return_string = false){
                for($i = 0; $i < count($array_values); $i++){
                    $array_values[$i] = $this->getValue($array_values[$i], $language_file_reference, $convert_to_url_friendy);
                }
                if($return_string) $array_values = implode("/",$array_values);
                return $array_values;
            }
        // Get key
            public function key($value, $language_file_reference = "administrator", $convert_to_url_friendy = false){
                return $this->getKey($value, $language_file_reference, $convert_to_url_friendy);
            }
            public function getKey($value, $language_file_reference = "administrator", $convert_to_url_friendy = false){
               $cuppa = Cuppa::getInstance();
               if(is_string(@$language_file_reference) ){ $language_file_reference = $this->load($language_file_reference); }
               $key = $cuppa->utils->getKeyFromValue($value, $language_file_reference, true);
               if($key) return $key;
               return $value;
            }
        // Get keys values (transform multilanguage path to the origin path)
            public function getKeys($array, $language_file_reference = "administrator", $convert_to_url_friendy = false){
                if(is_string(@$language_file_reference) ){ $language_file_reference = $this->load($language_file_reference); }
                if(is_string(@$array) ){ $array = explode("/", $array); }
                $cuppa = Cuppa::getInstance();
                for($i = 0; $i < count($array); $i++){
                    $key = $cuppa->utils->getKeyFromValue($array[$i], $language_file_reference, true);
                    if($key) $array[$i] = $key; 
                }
                return $array;
            }
        // Get File info
            public function getFileInfo($file){
                if(!$file) return;
                $cuppa = Cuppa::getInstance();
                $available_languages = $cuppa->language->getLanguagesAvailable();
                $info = new stdClass();
                // Get Info
                    for($i = 0; $i < count($available_languages); $i++){
                        $language = $available_languages[$i];
                        $file_data = $cuppa->file->loadFile($cuppa->getDocumentPath()."language/".$available_languages[$i]."/".$file);
                        $file_data_decode = json_decode($file_data); if(!$file_data) $file_data_decode = json_decode(utf8_encode($file_data));
                        @$info->languages->{$language} = $file_data_decode;
                    }
                // Get Label Name
                    $file_data = $cuppa->file->loadFile($cuppa->getDocumentPath()."language/".$cuppa->language->getCurrentLanguage()."/".$file);
                    $file_data_decode = json_decode($file_data); if(!$file) $file_data_decode = json_decode(utf8_encode($file_data));
                    $info->labels = $cuppa->utils->getObjectVars($file_data_decode);
                if(@!$info->labels) return null;
                return $info;
            }
        // Get table value
            public function valueRich($label, $return_all = false, $return_column = "content"){
                return $this->getValueRich($label, $return_all, $return_column);
            }
            public function getValueRich($label, $return_all = false, $return_column = "content"){
                $cuppa = Cuppa::getInstance();
                $label = strtolower(str_replace(array("-", "_"), array(" ", " "), $label));
                $condition = "label = '".$label."'AND (language = '' OR language = '".$cuppa->language->current()."')";
                $data = $cuppa->dataBase->getRow($cuppa->configuration->table_prefix."language_rich_content", $condition, true);
                // Alternative seach
                    if(!$data){
                        $label = str_replace(" ","-", $label);
                        $condition = "label = '".$label."'AND (language = '' OR language = '".$cuppa->language->current()."')";
                        $data = $cuppa->dataBase->getRow($cuppa->configuration->table_prefix."language_rich_content", $condition, true);
                    }
                    if(!$data){
                        $label = str_replace("-","_", $label);
                        $condition = "label = '".$label."'AND (language = '' OR language = '".$cuppa->language->current()."')";
                        $data = $cuppa->dataBase->getRow($cuppa->configuration->table_prefix."language_rich_content", $condition, true);
                    }
                //--
                if(!$return_all) return @$data->{$return_column};  
                else return @$data;
            }
        // Translate path, $path = array or string
            function translatePath($path = null, $language, $frienly_url = false, $get_string = false, $invert = false){
                //++ get path
                    if($path === null){ 
                        $path = trim(@$_SERVER["QUERY_STRING"]);
                        if(!$path) $path = trim(@$_SERVER["PATH_INFO"]);
                        $path = str_replace("path=", "", $path);
                    }
                //--
                if(!$path) return "";
                if(is_string($language) ){ $language = $this->load($language); }
                if(is_string($path)){ $path = explode("/", $path); }
                
                if($invert){
                    for($i = 0; $i < count($path); $i++){
                        if($i == count($path)-1 && strpos($path[$i], "=") !== false ){
                            $path[$i] = @explode("&", $path[$i]); $path[$i] = @$path[$i][0];                     
                        }
                        $path[$i] = $this->key($path[$i], $language,$frienly_url);
                    }
                }else{
                    for($i = 0; $i < count($path); $i++){
                        if($i == 0 && strpos($path[$i], "=") !== false ){
                            $path[$i] = @explode("&", $path[$i]); $path[$i] = @$path[$i][0];
                        }
                        $path[$i] = $this->getValue($path[$i], $language, $frienly_url);
                    }
                }
                if($get_string) $path = join("/", $path);
                return $path;
            }
        // Save info
            public function saveFile($name, $info){
                $cuppa = Cuppa::getInstance();
                $available_languages = $this->getLanguagesAvailable();
                for($i = 0; $i < count($available_languages); $i++){
                    $current_language = $available_languages[$i];
                    $file_data = json_encode(@$info->{$current_language});
                    $path = $cuppa->getDocumentPath()."language/".$current_language."/";
                    if($file_data) $cuppa->file->createStringFile($file_data, $name, false, $path, "json" );
                }
                return 1;
            }
        // Create new file
            public function createFile($file){
                $cuppa = Cuppa::getInstance();
                $available_languages = $this->getLanguagesAvailable();
                for($i = 0; $i < count($available_languages); $i++){
                    $path = $cuppa->getDocumentPath()."language/".$available_languages[$i]."/";
                    $cuppa->file->createStringFile("{}", $file, false, $path, "json" );
                }
                return 1;
            }
        // Delete file
            public function deleteFile($file){
                $cuppa = Cuppa::getInstance();
                $available_languages = $this->getLanguagesAvailable();
                for($i = 0; $i < count($available_languages); $i++){
                  $path = $cuppa->getDocumentPath()."language/".$available_languages[$i]."/";
                  $cuppa->file->deleteFile($path.$file.".json");
                }
                return 1;
            }
        // Create reference
            public function createReference($reference, $base){
                $cuppa = Cuppa::getInstance();
                $from = $cuppa->getDocumentPath()."language/".$base;
                $to = $cuppa->getDocumentPath()."language/".$reference;
                $cuppa->file->copyFolder($from, $to);
                return 1;
            }
        // Delete reference
            public function deleteReference($reference){
                $cuppa = Cuppa::getInstance();
                $resource = $cuppa->getDocumentPath()."language/".$reference;
                $cuppa->file->deleteFolder($resource);
                return 1;
            }
        // Get language by path
            public function getLanguagePath($path = ""){
                $utils = Utils::getInstance();
                if(!@$path) $path = @$utils->getUrlVars(@$_REQUEST["path"]);
                $language = explode("-",@$path[0]); 
                $language = @$language[0];
                return $language;
            }
    }
?>