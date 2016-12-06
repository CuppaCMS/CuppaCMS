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
        public $browser;
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
            if($this->configuration->smtp) $this->mail->configure( @$this->configuration->email_outgoing, @$this->configuration->email_password, @$this->configuration->email_host, @$this->configuration->email_port, @$this->configuration->smtp_security);
            $this->user = UserManager::getInstance();
            $this->view = View::getInstance();
            $this->menu = MenuManager::getInstance();
            $this->paginator = new Paginator();
            $this->permissions = Permissions::getInstance();
            $this->country->set("", false, $this->configuration, true);
            $this->browser = $this->utils->getBrowser();
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
        // return the current URL in the browser
            public function url($return_string = false){
                $url = trim(@$_SERVER["QUERY_STRING"]);
                if(!$url) $url = trim(@$_SERVER["PATH_INFO"]);
                $url = str_replace("path=", "", $url);
                $array = explode("/",$url);
                function filter($item){ if(trim($item)) return $item; }
                $array = @array_filter($array,filter);
                if(!count($array)) return null;
                if($return_string) return join("/", $array);
                return $array;
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
                $value = json_decode(htmlspecialchars_decode($value));
                return $value;
            }
        // Base64 Endoce
            function base64Encode($value){
                $value = htmlspecialchars_decode($value);
                $value = base64_encode($value);
                return $value;
            }
        // Encript / Decript
        // keys = 'C7FFgigeyQSmvWcSMiLAnce4Tl4KGX6j' (256)
            function encrypt($string, $key = ""){
                if(!$string) return false;
                if(!$key) $key = $this->configuration->global_encode_salt;
                $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
                $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
                $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_CBC, $iv);
                return trim(base64_encode($crypttext));
            }
            function decrypt($string, $key = ""){
                if(!$string) return false;
                if(!$key) $key = $this->configuration->global_encode_salt;
                $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
                $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
                $crypttext = base64_decode($string);
                $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_CBC, $iv);
                return trim($decrypttext);
            }
        /* includeInstance
                $tag = template // template, xmp (old browsers compatibility)
        */
            private $template_added =  array();
            function includeInstance($path, $add_cover_template = true){
                if(@$this->template_added[$path]) return;
                $tag = ($this->browser->name == "Internet Explorer") ? "xmp" : "template";
                if($add_cover_template){
                    echo '<div id="template_'.$this->utils->getFriendlyUrl($path).'" style="display:none;"><'.$tag.'>';
                    include $path;
                    echo '</'.$tag.'></div>';
                }else{ include $path; }
                $this->template_added[$path] = "1";
            }
            function instanceInclude($path, $add_cover_template = true){ $this->includeInstance($path, $add_cover_template); }
            function instance($path, $add_cover_template = true){ $this->includeInstance($path, $add_cover_template); }
            function import($path, $add_cover_template = true){ $this->includeInstance($path, $add_cover_template); }
            
        /* instanceCreate, create automatically the instance and keep the template
            $data = 'folder/file.php'
            or 
            $data = new stdClass();
                $data->url = 'folder/file.php';
                $data->data = new stdClass;
                $data->class = 'string';
                $data->template = 'The class of the current class of file';
        */
            function instanceCreate($data = null, $keep_template = true){
                $data = (object) $data;
                if(is_string($data)) $data = (object) array('url'=>$data);
                if(!$data) $data =  new stdClass;
                if(!@$data->unique) $data->unique = $this->utils->getUniqueString("instance");
                if(@$data->template) $data->instance = $data->template;
                else $data->instance = $this->file->getDescription($data->url)->name;
                $file = file_get_contents($data->url);
                $file = str_replace($data->instance, $data->unique, $file);
                if(@$data->class){ $file = preg_replace('/'.$data->unique.'/', $data->unique." ".$data->class,$file, 1); }
                $this->echoString($file);
                
                echo "<script class='inst_script_tmp'>  {$data->unique}.instance_unique = '{$data->unique}'; 
                                {$data->unique}.instance_name = '{$data->instance}';
                                {$data->unique}.html = $('.{$data->unique}').get(0);    
                                {$data->unique}.html.script = {$data->unique}; 
                                {$data->unique}.state = new cuppa.state( {$data->unique}.html ); 
                                try{ {$data->unique}.constructor(".json_encode(@$data->data)."); }catch(err){}
                                try{ {$data->unique}.{$data->unique}(".json_encode(@$data->data)."); }catch(err){}
                                $('.inst_script_tmp').remove(); 
                     </script>";
                if($keep_template){ $this->instanceInclude($data->url); }
                return $data->unique;
            }
        // includeJS
            public function includeJS($path){ echo "<script>"; include $path; echo "</script>"; }
            public function importJS($path){ $this->includeJS($path); }
        // includeCSS
            public function includeCSS($path, $root_resources = "css/"){
                if(!$root_resources){
                    echo "<style>"; include $path; echo "</style>";
                }else{
                    $file = file_get_contents($path);
                    $search = array("url(");
                    $replace = array("url(css/");
                    $file = str_replace($search, $replace, $file);
                    echo "<style>"; echo $file; echo "</style>";
                }
            }
            public function importCSS($path, $root_resources = "css/"){ $this->includeCSS($path, $root_resources); }
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
        // object
            public function object(){
                $args = func_get_args();
                return $args;
            }
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
        /* curl */
            function curl($str){
                $data = explode("\\", $str);
                $url = trim(substr($data[0], strpos($data[0],"http"))); array_shift($data);
                $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL, $url);
                        curl_setopt($curl, CURLOPT_HEADER, 0);
                        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
                        curl_setopt($curl, CURLOPT_POST, 1);
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
                                $row = explode("=", $row);
                                $key = @$row[0]; $value = @$row[1];
                                $data_array[$key] = $value;
                            }
                        }else if(strpos($row, "-H") !== false ){
                           $row = trim(str_replace("-H", "",$row));
                           array_push($array_header, $row);
                        }else if(strpos($row, "-X") !== false ){
                           $row = trim(str_replace("-X", "",$row));
                           curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $row);
                        }
                    }
                    // Add Header
                        curl_setopt($curl, CURLOPT_HTTPHEADER, $array_header);
                    // Add Data
                        if(count($data_array) <= 1) $data_array = @$data_array[0];
                        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data_array));
                //--
                $res = curl_exec($curl); curl_close($curl);
                return @$res;
            }
    }
?>