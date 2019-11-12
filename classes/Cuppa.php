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
        public $global;
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
        public function __construct(){
            @include_once realpath(__DIR__ . '/..')."/Configuration.php";
            @date_default_timezone_set(date_default_timezone_get());
            $this->global = new stdClass();
            $this->security = Security::getInstance();
            $this->configuration = new Configuration();
            $this->dataBase = $this->db = DataBase::getInstance($this->configuration->db_name, $this->configuration->db_host, $this->configuration->db_user, $this->configuration->db_password);
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
            //++ redirect not-www/www
                if(@$this->configuration->redirect_to == "www"){
                    if (substr($_SERVER['HTTP_HOST'], 0, 4) !== 'www.') {
                        $protocol = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
                        $url = $protocol.'www.'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                        header('Location: '.$url); exit;
                    }
                }
                if(@$this->configuration->redirect_to == "not_www"){
                    if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
                        $protocol = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
                        $url = $protocol.str_replace('www.',"", $_SERVER['HTTP_HOST']).$_SERVER['REQUEST_URI'];
                        header('Location: '.$url); exit;
                    }
                }
            //--
            //++ validate SSL active
                if(@$this->configuration->ssl){
                    if(!isset($_SERVER['HTTPS']) && @$_SERVER['HTTPS'] == "" && @$_SERVER['SERVER_PORT'] != 443) {
                        $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                        header("Location: $redirect");
                    }
                }
            //--
            // autoLogout (if inactivity > $configuration->auto_logout_time)
                $this->user->autoLogout();
        }
        public static function getInstance(){
			if (self::$instance == NULL) { self::$instance = new Cuppa(); }
			return self::$instance;
		}
        // Define access paths
            public function setPath($name = "administrator", $path = null, $removeParams = true){
                if(!$path){
                    if(@$_SERVER["SCRIPT_NAME"]) $path = "http://".@$_SERVER["HTTP_HOST"].@$_SERVER["SCRIPT_NAME"];
                    else if(@$_SERVER["REQUEST_URI"]) $path = "http://".@$_SERVER["HTTP_HOST"].@$_SERVER["REQUEST_URI"];
                    else $path = "http://".@$_SERVER["HTTP_HOST"].@$_SERVER["PHP_SELF"];
                    if($this->configuration->ssl){$path =  str_replace("http", "https", $path); }
                    $path = $this->utils->cutText("?", $path, 9999,"", true);
                    $path = $this->utils->cutText("/", $path, 9999, "/", true);
                    if(!$removeParams){ $path .= "?".$_SERVER['QUERY_STRING']; }
                }
                if($name == "administrator") $path = str_replace("administrator/administrator","administrator",$path);
                $this->setCookie($name."_path", $path);
                return $path;              
            }
        // Get Document path by name
            public function setDocumentPath($name = "administrator", $document_path = "", $include_root = false){
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
        // public function basePath
            public function base($name = "app"){
                $base = realpath(__DIR__ . '/../..')."/";
                if($name == "administrator") $base = realpath(__DIR__ . '/..')."/";
                $base = str_replace("\\", "/", $base);
                return $base;
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
                $value = json_encode($value, JSON_PRETTY_PRINT);
                if($base64_encode) $value = base64_encode($value);
                return $value;
            }
        // JSON Decode
            function jsonDecode($value, $base64_decode = true){
                if($base64_decode) $value = @base64_decode($value);
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
            function encrypt($string, $key = "", $base64Encode = false, $jsonEncode = false){
                if(!$string) return false;
                if(!$key) $key = $this->configuration->global_encode_salt;
                if($jsonEncode) $string = json_encode($string);
                $encrypt_method = "AES-256-CBC";
                $iv = substr(hash('sha256', $key), 0, 16);
                $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
                $output = trim($output);
                if($base64Encode) $output = base64_encode($output);
                return $output;
            }
            function decrypt($string, $key = "", $base64Decode = false, $jsonDecode = false){
                if(!$string) return false;
                if(!$key) $key = $this->configuration->global_encode_salt;
                if($base64Decode) $string = base64_decode($string);
                $encrypt_method = "AES-256-CBC";
                $iv = substr(hash('sha256', $key), 0, 16);
                $output = openssl_decrypt($string, $encrypt_method, $key, 0, $iv);
                $output = trim($output);
                if($jsonDecode) $output = json_decode($output);
                return $output;
            }
        /* includeInstance
                $tag = template // template, xmp (old browsers compatibility)
        */
            private $template_added =  array();
            function includeInstance($path, $documentPath = "", $add_cover_template = true){
                if(@$this->template_added[$path]) return;
                if(!file_exists($documentPath.$path)){ echo "instance no reach: ".$documentPath.$path."<br />"; return; }
                $tag = ($this->browser->name == "Internet Explorer") ? "xmp" : "template";
                if($add_cover_template){
                    echo '<div id="template_'.strtolower($this->utils->getFriendlyUrl($path)).'" style="display:none;"><'.$tag.'>';
                    include $documentPath.$path;
                    echo '</'.$tag.'></div>';
                }else{ 
                    echo '<div id="template_'.$this->utils->getFriendlyUrl($path).'" style="display:none;">';
                    include $documentPath.$path;
                    echo '</div>';
                }
                $this->template_added[$path] = "1";
            }
            function instanceInclude($path, $documentPath = "", $add_cover_template = true){ $this->includeInstance($path, $documentPath, $add_cover_template); }
            function import($path, $documentPath = "", $add_cover_template = true){ $this->includeInstance($path, $documentPath, $add_cover_template); }
            
        /* instanceCreate, create automatically the instance and keep the template
            $opts = 'folder/file.php'
            or 
                $opts = new stdClass();
                $opts->url = 'folder/file.php';
                $opts->data = new stdClass;
                $opts->class = 'string';
                $opts->template = 'The class of the current class of file';
                $opts->no_data = false;
        */
            function instance($opts = null, $documentPath = "", $keep_template = true){
                if(is_string($opts)) $opts = (object) array('url'=>$opts);
                $opts = (object) $opts;
                if(!$opts) $opts =  new stdClass;
                if(!@$opts->unique) $opts->unique = $this->utils->getUniqueString("instance");
                if(@$opts->template) $opts->instance = $opts->template;
                else $opts->instance = $this->file->getDescription($opts->url)->name;
                $file = file_get_contents($documentPath.$opts->url);
                $file = $this->utils->replace($file, $opts->instance, $opts->unique, true);
                $file = $this->utils->replace($file, $opts->instance, $opts->unique, true, ["<script>", "</script>"]);
                $file = $this->utils->replace($file, $opts->instance, $opts->unique, false, ["<style>", "</style>"]);
                if(@$opts->class){ $file = preg_replace('/'.$opts->unique.'/', $opts->unique." ".$opts->class, $file, 1); }
                    ob_start();
                    $_POST = (array) @$opts->data;
                    //$data = $opts->data; extract((array) $opts->data);
                    eval(" ?>".$file."<?php ");
                    $file = ob_get_clean();
                echo $file;
                echo "<script class='inst_script_tmp'> {$opts->unique}.instance_unique = '{$opts->unique}'; ";
                    echo "{$opts->unique}.instance_name = '{$opts->instance}'; ";
                    echo "{$opts->unique}.html = $('.{$opts->unique}').get(0); ";    
                    echo "{$opts->unique}.html.script = {$opts->unique}; ";
                    echo "{$opts->unique}.state = new cuppa.state( {$opts->unique}.html ); ";
                    echo "{$opts->unique}.nodes = {$opts->unique}.node  = new cuppa.nodes( {$opts->unique}.html ); ";
                    echo "{$opts->unique}.global = cuppa.global; ";

                    if(@$opts->no_data){
                        echo "try{ {$opts->unique}.constructor(); }catch(err){} ";
                        echo "try{ {$opts->unique}.{$opts->unique}(); }catch(err){} ";
                    }else{
                        echo "try{ {$opts->unique}.constructor(".json_encode(@$opts->data)."); }catch(err){} ";
                        echo "try{ {$opts->unique}.{$opts->unique}(".json_encode(@$opts->data)."); }catch(err){} ";    
                    }
                    echo "$('.inst_script_tmp').remove(); ";
                echo "</script>";
                if($keep_template){ $this->instanceInclude($opts->url, $documentPath); }
                return $opts->unique;
            }
        /* process php
            script = url, string with php code;
            $data = new stdClass();
        */
            public function processPHP($script, $data){
                ob_start();
                if(!file_exists($script)) eval(" ?>".$file."<?php ");
                else include $script;
                $file = ob_get_clean();
                return $file;
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
        /* content
            key: id or name
        */
            public function content($key, $return_column = "content", $return_all = false){
                $current_language = $this->language->current();
                $current_country = $this->country->current();
                $cond = " enabled = 1 ";
                $cond .= " AND (language = '' OR language = '".@$current_language."') ";
                $cond .= " AND (countries = '' OR countries LIKE '%\"".$current_country."\"%' ) ";
                $cond .= " AND countries_not NOT LIKE '%\"".$current_country."\"%' ";
                $cond .= " AND region = '' ";
                $cond .= " AND ( show_from <= '". date('Y-m-d') ."' OR show_from = '0000-00-00' ) ";
                $cond .= " AND ( show_to >= '". date('Y-m-d') ."' OR show_to = '0000-00-00' ) ";
                $cond .= " AND ( id = '".$key."' OR name = '".$key."' ) ";
                $data = $this->dataBase->getRow("ex_content", $cond, true);
                if($return_all) return @$data;
                else return @$data->{$return_column};
            }
        // check ssl activate
            public function ssl() {
                $secure = 1;
                // if origen has http check a valid https
                    if(@substr($_SERVER["HTTP_REFERER"], 0, 4) == "http") $secure = (substr($_SERVER["HTTP_REFERER"], 0, 5) == "https");
                // check server if origen is right
                    if($secure) $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
                return $secure;
            }
            public function isSecure(){ return $this->ssl(); }
        // baseURL
            public function baseURL($name = "web"){
                if($this->configuration->base_url) return @$this->configuration->base_url;
                return $this->getPath($name);
            }
        // log
            public function log(){
                foreach (func_get_args() as $data) {
                    echo "<script>
                                try{
                                    console.log(JSON.parse('{$this->jsonEncode($data, false)}'));
                                }catch(err){ }
                         </script>";
                }
            }
        // selected
            public function selected($value1 = null, $value2 = null, $default = null, $selectedStr="selected", $unselectedStr=""){
                if(is_array($value2) && count($value2)){
                    for($i = 0 ; $i < count($value2); $i++){
                        if($value1 == $value2[$i]) return $selectedStr;
                    }
                }else if($value1 == $value2){
                    return $selectedStr;
                }
                if(!$value1 && $default){
                    if($value2 == $default) return $selectedStr;
                }

                return $unselectedStr;
            }
        // pre
            public function pre(){
                foreach (func_get_args() as $data) {
                    echo "<pre style='z-index: 9999; background: #000; color:#FFF; padding: 10px; font-size: 12px;'>"; print_r($data); echo "</pre>";
                }
            }
        // error
            public function error($code, $message){
                $error = new stdClass();
                    $error->error = $code;
                    $error->error_message = $message;
                return $error;
            }
        /* curl
            $data_format = form
            example:
                https://url.com/data \
                       -u user:pass \
                       -H header_value=value \
                       -d variable=data \
                       -d variable2=data \
                       -X DELETE \
        */
            function curl($str, $data_format = 'form'){
                $data = explode("\\", $str);
                $url = trim(substr($data[0], strpos($data[0],"http"))); array_shift($data);
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_TIMEOUT, 10);
                $array_header = array();
                $data_array = array();
                //++ Analyze
                for($i = 0; $i < count($data); $i++){
                    $row = trim($data[$i]." ");
                    $hint = trim(strstr($row,' ', true));
                    $row = trim(strstr($row,' ', false));
                    if($hint == "-u"){                // user:password
                        $row = str_replace(array("'", '"'), array("", ""), $row);
                        curl_setopt($curl, CURLOPT_USERPWD, $row);
                    }else if($hint == "-d"){        // post data
                        if(strpos($row, "]=") !==  false ){
                            $row = str_replace(array('"', "'"), array("", ""), $row);
                            $row = explode("=", $row);
                            $key = @$row[0]; $value = @$row[1];
                            $data_array[$key] = $value;
                        }else{
                            $row2 = explode("=", $row);
                            if(count($row2) > 1){
                                $key = @$row2[0]; $value = @$row2[1];
                                $data_array[$key] = $value;
                            }else{
                                array_push($data_array, $row);
                            }
                        }
                    }else if($hint == "-H"){
                        array_push($array_header, $row);
                    }else if($hint == "-X"){
                        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $row);
                    }
                }
                // Add Header
                curl_setopt($curl, CURLOPT_HTTPHEADER, $array_header);
                // Add Data
                if(count($data_array) <= 1) $data_array = @$data_array[0];
                if($data_format == "form"){
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data_array));
                }else{
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_array);
                }
                //--
                $res = curl_exec($curl); curl_close($curl);
                return @$res;
            }
    }
?>