<?php
    /* Include framework from Administrarot/Web
        include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["administrator_document_path"]."classes/Cuppa.php";
        include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["web_document_path"]."administrator/classes/Cuppa.php";
        $cuppa = Cuppa::getInstance();
    */
    @include_once("Security.php");
    @include_once("DataBase.php");
    @include_once("UserManager.php");
    @include_once("MenuManager.php");
    @include_once("LanguageManager.php");
    @include_once("CountryManager.php");
    @include_once("View.php");
    @include_once("Utils.php");
    @include_once("FileManager.php");
    @include_once("ImageManager.php");
    @include_once("SendMail.php");
    @include_once("Paginator.php");
    @include_once("Permissions.php");
    class Cuppa{
        private static $instance;
        public $configuration;
        public $dataBase; public $db;
        public $security;
        public $user;
        public $view;
        public $menu;
        public $language;
        public $country;
        public $utils;
        public $file;
        public $image;
        public $mail;
        public $paginator;
        public $permissions;
        public function Cuppa(){
            @include_once realpath(__DIR__ . '/..')."/Configuration.php";
            $this->security = Security::getInstance();
            $this->configuration = new Configuration();
            $this->dataBase = $this->db = DataBase::getInstance($this->configuration->db, $this->configuration->host, $this->configuration->user, $this->configuration->password);
            $this->language = LanguageManager::getInstance();
            $this->country = CountryManager::getInstance();
            $this->utils = Utils::getInstance();
            $this->file = FileManager::getInstance();
            $this->image = ImageManager::getInstance();
            $this->mail = new SendMail(); $this->mail->configure();
            $this->user = UserManager::getInstance();
            $this->view = View::getInstance();
            $this->menu = MenuManager::getInstance();
            $this->paginator = new Paginator();
            $this->permissions = Permissions::getInstance();
            $this->country->set("", false, $this->configuration, true);
            //++ validate SSL active
                if(@$this->configuration->ssl){
                    if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == ""){
                        $redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                        header("Location: $redirect");
                    }
                }
            //--
        }
        public static function getInstance(){
			if (self::$instance == NULL) { self::$instance = new Cuppa(); }
			return self::$instance;
		}
        // Define access paths
            public function setPath($path = null, $name = "administrator"){
                if(!$path){
                    if(@$_SERVER["SCRIPT_NAME"]) $path = "http://".@$_SERVER["HTTP_HOST"].@$_SERVER["SCRIPT_NAME"];
                    else if(@$_SERVER["REQUEST_URI"]) $path = "http://".@$_SERVER["HTTP_HOST"].@$_SERVER["REQUEST_URI"];
                    else $path = "http://".@$_SERVER["HTTP_HOST"].@$_SERVER["PHP_SELF"];
                    if($this->configuration->ssl){$path =  str_replace("http", "https", $path); }
                    $path = $this->utils->cutText("?", $path, 9999,"", true);
                    $path = $this->utils->cutText("/", $path, 9999, "/", true);
                }
                if($name == "administrator") $path = str_replace("administrator/administrator","administrator",$path);
                $this->setCookie($name."_path", $path);
                return $path;              
            }
        // Get Document path by name
            public function setDocumentPath($document_path = "", $name = "administrator", $include_root = false){
                if(!$document_path){
                    if(@$_SERVER["SCRIPT_NAME"]){
                        if($include_root) $document_path = $this->documentRoot() . @$_SERVER["SCRIPT_NAME"];
                        else $document_path = @$_SERVER["SCRIPT_NAME"];
                    }else if(@$_SERVER["REQUEST_URI"]){
                        if($include_root) $document_path = $this->documentRoot() . @$_SERVER["REQUEST_URI"];
                        else $document_path = @$_SERVER["REQUEST_URI"];
                    }else{
                        if($include_root) $document_path = $this->documentRoot() . @$_SERVER["PHP_SELF"];
                        else $document_path = @$_SERVER["PHP_SELF"];
                    }
                }
                $document_path = $this->utils->cutText("?", $document_path, 9999, "", true);
                $document_path = $this->utils->cutText("/", $document_path, 9999, "/", true);
                $document_path = str_replace("\\", "/", $document_path);
                $this->setCookie($name."_document_path", $document_path);
                return $document_path;
            }
        // Get Document root
            public function documentRoot(){
                return @$_SERVER['DOCUMENT_ROOT'];
            }
        // Get Path by name
            public function getPath($name = "administrator"){
                return $this->getCookie($name."_path");
            }
        // Get Document path by name
            public function getDocumentPath($name = "administrator", $include_root = true){
                $path = @$this->getCookie($name."_document_path");
                if($include_root) $path = $this->documentRoot().$path;
                return $path;
            }
        // Set session var
            public function setSessionVar($name, $value){
                @session_start();
                if(!@$_SESSION['cuSession']) @$_SESSION['cuSession'] = new stdClass();
                $_SESSION['cuSession']->{$name} = $value;
            }
        // Get session var
            public function getSessionVar($name){
                @session_start();
                return @$_SESSION['cuSession']->{$name};
            }
        // echo String
            public function echoString($string, $special = true, $document_root = ""){
                if($special){
                    echo $this->utils->echoSpecialString($string, $document_root);
                }else{
                    echo htmlspecialchars(trim($string));
                }
            }
        // santize String
            public function sanitizeString($string){ return $this->utils->sanitizeString($string); }
        // get
            public function GET($string){
                return $this->sanitizeString(@$_GET[$string]);
            }
        // post
            public function POST($string){
                return $this->sanitizeString(@$_POST[$string]);
            }
        // request
            public function REQUEST($string){
                return $this->sanitizeString(@$_REQUEST[$string]);
            }
        // JSON Endoce
            function jsonEncode($value, $base64_encode = true){
                $value = json_encode($value);
                if($base64_encode) $value = base64_encode($value);
                return $value;
            }
        // JSON Decode
            function jsonDecode($value, $base64_decode = true){
                if($base64_decode) $value = base64_decode($value);
                $value = json_decode($value);
                return $value;
            }
        // Encript / Decript
            function encrypt($string, $key = "", $key2 = ""){
                if(!$string) return false;
                if(!$key) $key = $this->configuration->global_encode_salt;
                if(!$key2) $key2 = $this->security->sessionKey;
                $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_ECB, $key2);
                return trim(base64_encode($crypttext));
            }
            function decrypt($string, $key = "", $key2 = ""){
                if(!$string) return false;
                if(!$key) $key = $this->configuration->global_encode_salt;
                if(!$key2) $key2 = $this->security->sessionKey;
                $crypttext = base64_decode($string);
                $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_ECB, $key2);
                return trim($decrypttext);
            }
        /* instance, 
            $data = 'folder/file.php'
            or 
            $data = new stdClass;
            $data->url = 'folder/file.php';
            $data->data = new stdClass;
            $data->class = 'string';
            $data->instance = 'The class of the current class of file';
        */
            function instance($data = null){
                if(is_string($data)) $data = (object) array(['url'=>$data]);
                if(!$data) $data =  new stdClass;
                if(!@$data->unique) $data->unique = $this->utils->getUniqueString("instance");
                if(!@$data->instance) $data->instance = $this->file->getDescription($data->url)->name;
                $file = file_get_contents($data->url);
                $file = str_replace($data->instance, $data->unique, $file);
                if(@$data->class){ $file = preg_replace('/'.$data->unique.'/', $data->unique." ".$data->class,$file, 1); }
                $this->echoString($file);
                echo "<script> try{ {$data->unique}.constructor(".json_encode(@$data->data)."); }catch(err){} </script>";
                echo "<script> try{ {$data->unique}.{$data->unique}(".json_encode(@$data->data)."); }catch(err){} </script>";
                return $data->unique;
            }
        // Encript / Decript ID's
            // Example: $cuppa->encryptIds(987654);
            // To Decript: $cuppa->encryptIds('encripted', true);
            public function encryptId($in, $to_num = false, $base64 = false, $pad_up = false, $pass_key = null){
                if(!$pass_key) $pass_key = $this->security->getToken();
                if($to_num && $base64) $in = base64_decode($in);
            	$out   =   ''; $index = 'abcdefghijklmnopqrstuvwxyz0123456789'; $base = strlen($index);
            	if ($pass_key !== null) {
            		for ($n = 0; $n < strlen($index); $n++) { $i[] = substr($index, $n, 1); }
            		$pass_hash = hash('sha256',$pass_key);
            		$pass_hash = (strlen($pass_hash) < strlen($index) ? hash('sha512', $pass_key) : $pass_hash);
            		for ($n = 0; $n < strlen($index); $n++) { $p[] =  substr($pass_hash, $n, 1); }
            		array_multisort($p, SORT_DESC, $i);
            		$index = implode($i);
            	}
            	if ($to_num) {
            	   $len = strlen($in) - 1;
            	   for ($t = $len; $t >= 0; $t--) {
            			$bcp = bcpow($base, $len - $t);
            			$out = $out + strpos($index, substr($in, $t, 1)) * $bcp;
           		   }
            	   if (is_numeric($pad_up)) {
            	   	   $pad_up--;
            	       	if ($pad_up > 0) { $out -= pow($base, $pad_up); }
           		   }
            	} else {
            		if (is_numeric($pad_up)) {
            			$pad_up--;
            			if ($pad_up > 0) { $in += pow($base, $pad_up); }
            		}
            		for ($t = ($in != 0 ? floor(log($in, $base)) : 0); $t >= 0; $t--) {
            			$bcp = bcpow($base, $t);
            			$a   = floor($in / $bcp) % $base;
            			$out = $out . substr($index, $a, 1);
            			$in  = $in - ($a * $bcp);
            		}
                    if($base64) $out =  base64_encode($out);
            	}
            	return $out;
            }
        // Get adminisrtator directory
            public function administratorFolder(){
                $dir = array_values(array_filter(explode("/",str_replace(array("\\", "classes"), array("/", ""), __DIR__))));
                $dir = array_pop($dir);
                return $dir;
            }
        // Cookie
            public function setCookie($name, $value = null, $expire_time = 0, $httponly = false, $secure = false){ $this->utils->setCookie($name, $value, $expire_time = 0, $httponly = false, $secure = false); }
            public function getCookie($name){ return $this->utils->getCookie($name); }
        // ip
            public function ip(){ return $this->utils->ip(); }
        // language value
            public function langValueRich($label, $return_all = false, $return_column = "content"){
                return $this->language->getValueRich($label, $return_all, $return_column);
            }
            public function langValue($key = "", $language_file_reference = "administrator", $convert_to_url_friendy = false){
                return $this->language->getValue($key, $language_file_reference, $convert_to_url_friendy);
            }
            public function langLoad($file, $language){
                return $this->language->load($file, $language);
            }
            public function langCurrent(){
                return $this->language->current();
            }
        // Curl
            public function curl($str){
                $data = explode("\\", $str);
                $url = trim(substr($data[0], strpos($data[0],"http"))); array_shift($data);
                $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL, $url);
                        curl_setopt($curl, CURLOPT_HEADER, false);
                        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
                
                $array_header = array();
                $data_array = array();
                //++ Analyze
                    for($i = 0; $i < count($data); $i++){
                        $row = trim($data[$i]);
                        if(strpos($row,"-u") !== false){                // user:password
                            $row = trim(str_replace("-u", "",$row));
                            $row = str_replace(array("'", '"'), array("", ""), $row);
                            curl_setopt($curl, CURLOPT_USERPWD, $row);
                        }else if(strpos($row, "-d") !== false ){        // post data
                            $row = trim(str_replace("-d", "",$row));
                            if(strpos($row, "]=") !==  false ){
                                $row = str_replace(array('"', "'"), array("", ""), $row);
                                $row = explode("=", $row);
                                $key = @$row[0]; $value = @$row[1];
                                $data_array[$key] = $value;
                            }else{
                                array_push($data_array, $row);
                            }
                        }else if(strpos($row, "-H") !== false ){
                           $row = trim(str_replace("-H", "",$row));
                           array_push($array_header, $row);
                        }
                    }
                    // Add Header
                        curl_setopt($curl, CURLOPT_HTTPHEADER, $array_header);
                    // Add Data
                        if(count($data_array) < 2) $data_array = @$data_array[0];
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_array);
                //--
                
                $res = curl_exec($curl); curl_close($curl);
                print_r($res);
            }
        
    }
?>