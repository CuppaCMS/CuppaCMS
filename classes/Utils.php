<?php
	class Utils{
	    private static $instance;
		public function __construct(){ }
        public static function getInstance() {
			if (self::$instance == NULL) { self::$instance = new Utils(); } 
			return self::$instance;
		}
        /* send and load with CULR
                $data = object
        */
            public function sendAndLoad($url, $data = NULL, $method = "POST"){
                $data = (array) $data;
                $data = @http_build_query(@$data);
                $curl = curl_init();
                if($method == "GET") $url.="?".$data;
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                curl_setopt($curl, CURLOPT_COOKIESESSION, $data);
                
                $strCookie = 'PHPSESSID=' . @$_COOKIE['PHPSESSID'] . '; path=/';
                @session_write_close();
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt( $curl, CURLOPT_COOKIE, $strCookie ); 
                
                $result = curl_exec($curl);
                curl_close($curl);
                return $result;
            }
        /* ajax 
        */
            public function ajax($url, $data = null, $header = null){
                $postdata = http_build_query((array) $data);
                $opts = array('http' =>
                    array(
                        'method'  => 'POST',
                        'header'  => 'Content-type: application/x-www-form-urlencoded',
                        'content' => $postdata
                    )
                );
                $context  = stream_context_create($opts);
                @$response = file_get_contents($url, false, $context);
                $result = new stdClass();
                $result->body = $response;
                @$result->header = $http_response_header;
                return $result;
            }
        // Get inputs with the Request vars
    		public function getInputsWithRequestVars($type = ""){
                $inputs = "";
                $data = $_REQUEST;
                if($type == "post") $data = $_POST;  
                else if($type == "get") $data = $_GET;
                foreach ( $data as $key => $value ){
                    $inputs .= '<input type="hidden" name="'.$key.'" id="'.$key.'" value="'.$data[$key].'" />';
                }
                return $inputs;
    		}
        // Search in Array
            function searchInArray($array, $key, $value){
                if(is_object($array)) $array = (array) $array;
                $results = array();
                if (is_array($array)) {
                    if (isset($array[$key]) && $array[$key] == $value)
                        $results[] = $array;
                    foreach ($array as $subarray) $results = array_merge($results, $this->searchInArray($subarray, $key, $value));
                }
                return $results;
            }
        // filter
            function filter($array, $search){
                $result = null;
                foreach($array as $key=>$value){
                    if(strpos($value, $search) !== false){ 
                        $result = $value;
                        break; 
                    }
                }
                return $result;
            }
        // Cut string text
            function cutText($delimiter = " ", $text = "", $lenght = 200, $string_to_end = "", $delimiter_forced = false, $remove_tags = false){
                if($remove_tags) $text = strip_tags($text);
                if($delimiter_forced){
                    $text = substr($text, 0, $lenght);
                    if(strrpos($text, $delimiter) !== false) $text = trim(substr($text,0, strrpos($text, $delimiter)));
                    $text .= $string_to_end;
                }else if(strlen($text) > $lenght ){
                    $text = substr($text, 0, $lenght);
                    if(strrpos($text, $delimiter) !== false) $text = trim(substr($text,0, strrpos($text, $delimiter)));
                    $text .= $string_to_end;                    
                }
                return $text;
            }
        // ip
            public function ip(){
                $ip = "";
                if (!empty($_SERVER['HTTP_CLIENT_IP'])){ $ip = $_SERVER['HTTP_CLIENT_IP']; }
                else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; }
                else { $ip = $_SERVER['REMOTE_ADDR']; }
                if($ip == "::1") $ip = "";
                return $ip;
            }
        // Create Tree
            private $tmp_info;
            function tree($info, $column_real, $column_to_validate, $column_road_path = "alias", $on_fail_return_info = true, $init_id_reference = 0, $include_init_reference = false, $character_depth = "|&mdash;&nbsp;&nbsp;", $return_object = false){
                return $this->createTree($info, $column_real, $column_to_validate, $column_road_path, $on_fail_return_info, $init_id_reference, $include_init_reference, $character_depth, $return_object);
            }
            function createTree($info, $column_real, $column_to_validate, $column_road_path = "alias", $on_fail_return_info = true, $init_id_reference = 0, $include_init_reference = false, $character_depth = "|&mdash;&nbsp;&nbsp;", $return_object = false){
                if(!$info) return null;
                $this->tmp_info = array();
                if(!$init_id_reference){
                    for($i = 0; $i < count($info); $i++){
                        $info[$i] = (array) $info[$i];
                        if(!$info[$i][$column_to_validate]){
                            $row = (array) $info[$i];
                            $validation = count($this->searchInArray($this->tmp_info, $column_real, $row[$column_real]));
                            if(!$validation){
                                $row["level_tree"] = 0;
                                $row["road_path"] = @$row[$column_road_path];
                                array_push($this->tmp_info, $row);
                                $this->getSubTree($row, $column_real, $column_to_validate, $info, 1, $character_depth, $column_road_path, $row["road_path"]);
                            }
                        }
                    }
                }else{
                    for($i = 0; $i < count($info); $i++){
                        $info[$i] = (array) $info[$i];
                        if($info[$i]["id"] == $init_id_reference){
                            $row = (array) $info[$i];
                            $validation = count($this->searchInArray($this->tmp_info, $column_real, $row[$column_real]));
                            if(!$validation){
                                $backPath = $this->getBackPath($info, $column_real, $column_to_validate, $column_road_path = "alias", $init_id_reference);
                                $row["level_tree"] = 0;
                                $row["road_path"] = $backPath;
                                if($include_init_reference){
                                    $level_tree = 1;
                                    array_push($this->tmp_info, $row);
                                }else{
                                    $level_tree = 0;
                                }
                                $this->getSubTree($row, $column_real, $column_to_validate, $info, $level_tree, $character_depth, $column_road_path, $row["road_path"]);
                            }
                        }
                    }
                }
                if(!count($this->tmp_info)){
                    if($on_fail_return_info) return $info;
                    else return null; 
                }
                //++ convert to object
                    if($return_object){
                        for($i = 0; $i < count($this->tmp_info); $i++){
                            $this->tmp_info[$i] = (object) $this->tmp_info[$i];
                        }
                    }
                //--
                return $this->tmp_info;
            }
                private function getSubTree($row, $column_real, $column_to_validate, $info, $level_tree = 1, $character_depth = "|&mdash;&nbsp;&nbsp;", $column_road_path = "alias", $road_path = ""){
                    for($j = 0; $j < count($info); $j++){
                        $info[$j] = (array) $info[$j];
                        if($info[$j][$column_to_validate] == $row[$column_real]){
                            $row2 = (array) $info[$j];
                            $validation = count($this->searchInArray($this->tmp_info, $column_real, $row2[$column_real]));
                            if(!$validation){
                                $deep_string = "";
                                for($k = 0; $k < $level_tree; $k++){$deep_string .= $character_depth; }
                                $row2["level_tree"] = $level_tree;
                                $row2["deep_string"] = $deep_string;
                                $row2["road_path"] = $road_path."/".@$row2[$column_road_path];
                                array_push($this->tmp_info, $row2);
                                $this->getSubTree($row2, $column_real, $column_to_validate, $info, $level_tree + 1, $character_depth, $column_road_path, $row2["road_path"]);
                            }
                        }
                    }
                }
                public function getBackPath($info, $column_real, $column_to_validate, $column_road_path = "alias", $init_id_reference = 0, $path = "", $include_current_value = true){
                    for($m = 0; $m < count($info); $m++){
                        $info[$m] = (array) $info[$m];
                        if($info[$m][$column_real] == $init_id_reference){
                            if($include_current_value) $path = ($path) ? $info[$m][$column_road_path] ."/". $path : $info[$m][$column_road_path];
                            if($info[$m][$column_to_validate]){
                                $path = $this->getBackPath($info, $column_real, $column_to_validate, $column_road_path, $info[$m][$column_to_validate], $path, true);
                            }
                            break;
                        }
                    }
                    return $path;  
                }
        // Get Friendly URL
            function urlFrienly($string, $include_init_value = null) { return $this->getFriendlyUrl($string, $include_init_value); }
            function frienlyURL($string, $include_init_value = null) { return $this->getFriendlyUrl($string, $include_init_value); }
            function getFriendlyUrl($string, $include_init_value = null) {
                $end = preg_replace('/\s+/', ' ', $string);
                $end = trim($end);
                $end = str_replace(
                    array('á','à','ä','â','ª','Á','À','Â','Ä','ã','Ã','Å','å'),
                    array('a','a','a','a','a','a','a','a','a','a','a','a','a'),
                    $end
                );
                $end = str_replace(
                    array('é','è','ë','ê','É','È','Ê','Ë'),
                    array('e','e','e','e','e','e','e','e'),
                    $end
                );
                $end = str_replace(
                    array('í','ì','ï','î','Í','Ì','Ï','Î'),
                    array('i','i','i','i','i','i','i','i'),
                    $end
                );
                $end = str_replace(
                    array('ó','ò','ö','ô','Ó','Ò','Ö','Ô','Õ','õ'),
                    array('o','o','o','o','o','o','o','o','o','o'),
                    $end
                );
                $end = str_replace(
                    array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
                    array('u', 'u', 'u', 'u', 'u', 'u', 'u', 'u'),
                    $end
                );
                $end = str_replace(
                    array('ñ', 'Ñ', 'ç', 'Ç'),
                    array('n', 'n', 'c', 'c',),
                    $end
                );
                $end = str_replace(
                    array("\\", "¨", "º", "~",
                         "#", "@", "|", "!", "\"",
                         "·", "$", "%", "&", "/",
                         "(", ")", "?", "'", "¡",
                         "¿", "[", "^", "`", "]",
                         "+", "}", "{", "¨", "´",
                         ">", "< ", ";", ",", ":"),
                    '',
                    $end
                );
                $delete=array("’","!","¡","?","¿","‘","\"","$","(",")",":",";","\\","\$","%","@","#",",", "«", "»");
                $search=array(" ","á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","ü","à","è","ì","ò","ù","À","È","Ì","Ò","Ù","_",".");
                $sustitut=array("-","a","e","i","o","u","a","e","i","o","u","n","n","u","a","e","i","o","u","A","E","I","O","U","-","-");
                $end=str_replace($search,$sustitut,str_replace($delete,"",$end));
                //$end=strtolower(str_replace($search,$sustitut,str_replace($delete,"",$end)));
                $end = preg_replace('/\-+/', '-', $end);
                if($include_init_value) $end = $include_init_value."/".$end;
                return $end;
            }
        // Get a array with all values in the url, example: ?/news/18-title-article 
        // $language_reference = the file json loaded if you want try convert translate or convert the path
        // case = upper, lower, none
            function getUrlVars($path = null, $number_return = false, $language_reference_file = null, $convert_to_friendy_url = false, $case = "none"){
                if(is_array($path)) $path = join("/", $path);
                if($path === null) $path = trim(@$_SERVER["QUERY_STRING"]);
                if($path === null) $path = trim(@$_SERVER["PATH_INFO"]);
                $path = str_replace("path=", "", $path);
                $path = htmlspecialchars($path);
                $tmp_array = explode('/', $path);

                $last_element = array_pop($tmp_array);
                if(strpos($last_element,"?") !== false) $last_element = str_replace("?", "","&".$last_element);

                $get = "";
                if(strpos($last_element, "&") !== false){
                    $last_element = explode("&", $last_element);
                    $first_part = @array_shift($last_element);
                    if($first_part) array_push($tmp_array, $first_part);
                    $get = join("&", $last_element);
                }else{
                    array_push($tmp_array, $last_element);
                }
                $array = array();
                foreach($tmp_array as $index=>$row){
                    if($index == count($tmp_array)-1 && strpos($row, "=") !== false ){ $row = @explode("&", $row); $row = @$row[0]; }
                    if($row){
                        $number_value = $row;
                        if($number_return){
                            $number_value = (int) $row;
                            if($number_value) 
                                array_push($array, $number_value); 
                            else{
                                $row = explode(".",$row);
                                $row = @$row[0];
                                if(@$language_reference_file){
                                    $row_translated = @$this->getKeyFromValue($row, $language_reference_file);
                                    $row = (@$row_translated) ? @$row_translated : $row;
                                    if($convert_to_friendy_url) $row = $this->getFriendlyUrl($row);
                                }
                                if($case == "upper") @$row = @strtoupper($row);
                                if($case == "lower") @$row = @strtolower($row);
                            }
                        }else{
                            $row = explode(".",$row);
                            $row = @$row[0];
                            if(@$language_reference_file){
                                $row_translated = @$this->getKeyFromValue($row, $language_reference_file);
                                $row = (@$row_translated) ? @$row_translated : $row;
                                if($convert_to_friendy_url) $row = $this->getFriendlyUrl($row);
                            }
                            if($case == "upper") @$row = @strtoupper($row);
                            if($case == "lower") @$row = @strtolower($row);
                            array_push($array, @$row);
                        }
                    } 
                }
                //++ substract GET values
                    if($get){
                        $get = "&".$get; $get = str_replace("amp;", "", $get); $get = preg_replace('/&+/', '&', $get);
                        parse_str($get, $get);
                        $array = array_merge($array, $get);
                    }
                //--
                if(count($array)) return $array;
                else return null;
            }
        // pathData
            function pathData($url = ''){
                if(!$url) $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                //TODO Implement that method similar to JS
            }
        // Get data form url string ?value1=value&value2=value&value3=value
            function getVarsFromURLString($string, $return_object = false, $base64_decode = false){
                $data = "";
                if($base64_decode) $string = base64_decode($string);
                parse_str($string,$data);
                if($return_object) $data = (object) $data;
                return $data;
            }
        // Pass a string and key word and the function return part of string before or after the key word
        // $invert = true, return all before to the key word 
            function getStringPart($string, $word, $includeWord = false, $invert = false, $explode_delimiter = ""){
                if(is_array($string)) $string = join("/", $string);
                $new_string = $string;
                if($invert){
                    $new_string = @substr($string, @strpos($string, $word));
                    if(!$includeWord){ $new_string = preg_replace('/'.$word.'/', '', $new_string, 1); }
                }else{
                    $new_string = @substr($string,0, @strpos($string, $word) );
                    if($includeWord) $new_string .= $word;
                }
                if($explode_delimiter && $new_string){
                    $new_string = array_filter(explode($explode_delimiter, $new_string));
                    $new_string = array_values($new_string);
                }
                return $new_string;
            }
        // Get key from value
            function getKeyFromValue($value, $array, $forced_url_friendy_format = false){
                $array = (array) $array;
                $key = "";
                foreach ($array as $key_array => $value_array) {
                    if(strtolower($this->frienlyURL($value)) == strtolower( $this->frienlyURL( $value_array ) )){
                        $key = $key_array;
                        break;
                    }
                    //++ If not found, convert the value to url friendy and compare again
                        $value_array = $this->getFriendlyUrl($value_array);
                        if($value == $value_array){
                            $key = $key_array;
                            break;
                        }
                    //--
                }
                if($forced_url_friendy_format) $key = $this->getFriendlyUrl($key);
                return $key;
            }
        // Get value from key
            function getValueFromKey($key, $array, $forced_url_friendy_format = false){
                $array = (array) $array;
                $value = "";
                foreach ($array as $key_array => $value_array) {
                    if($key == $key_array){
                        $value = $value_array;
                        break;
                    }
                    //++ If not found, convert the value to url friendy and compare again
                        $key_array = $this->getFriendlyUrl($key_array);
                        if($key == $key_array){
                            $value = $value_array;
                            break;
                        }
                    //--
                    //++ If not found, convert the key to url friendy and compare again
                        $key = $this->getFriendlyUrl($key);
                        if($key == $key_array){
                            $value = $value_array;
                            break;
                        }
                    //--
                }
                if($forced_url_friendy_format) $value = $this->getFriendlyUrl($value);
                return $value;
            }
        // Eval data
            function evalString($string){
                @$string = eval("return $string;");
                return $string;
            }
        // Get a array with all var names inside of Object 
            function getObjectVars($object){
                $array = @get_object_vars($object);
                if(!$array) return null;
                $array_result = array();
                foreach ( $array as $key => $value ){ array_push($array_result, $key); }
                return $array_result;
            }
        // Encode to sha1Salt
            function sha1Salt($value, $salt = "M519geetUdWQHhU8g8vZL_CbIDxlca8m"){
                $value = sha1($value).sha1($salt.$value);
                return $value;
            }
        // Get aviable links from plain HTML string
           function getAviableLinks($string){
                $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
                if(preg_match_all("/$regexp/siU", $string, $matches, PREG_SET_ORDER)) {
                    return $matches;
                }else{
                    return "";
                }
            }
        // Generate random string
            function getRandomString($length = 10){
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randstring = '';
                for ($i = 0; $i < $length; $i++) { $randstring .= $characters[rand(0, strlen($characters)-1)]; }
                return $randstring;
            }
        // Generate unique string
            function getUniqueString($initial = ""){
                if($initial) $string = $initial;
                $string .= time().rand(1,9999);
                return $string;
            }
        // Echo Special String, print a string content, if there are code php inside labels like: {? ?} print it.
        //    - Labels allowed: {?, ?}, [?, ?], {?php, [?php, {php}, [php]
            function echoSpecialString($string, $document_root = ""){
                $cuppa = Cuppa::getInstance();
                $current_language = $cuppa->language->current();
                $current_country = $cuppa->country->current();
                $dir = array_values(array_filter(explode("/",str_replace(array("\\", "classes"), array("/", ""), __DIR__))));
                $dir = array_pop($dir);
                $string = str_replace("media/", $dir."/media/", $string);
                $string = str_replace(array("<?", "<?php","?>", "&lt;?php","&lt;?","?&gt;","{?", "?}", "[?", "?]", "{?php", "[?php", "{php}", "[php]"), "{?", $string);
                $string = str_replace(array("#lang#","#language#"), array($current_language, $current_language),$string);
                $string = str_replace("#country#", $current_country,$string);
                $string = str_replace(array("#web_path#","#administrator_path#"), array($cuppa->getDocumentPath("web"), $cuppa->getDocumentPath()),$string);
                $string = str_replace(array("#path_web#","#path_administrator#"), array($cuppa->getDocumentPath("web"), $cuppa->getDocumentPath()),$string);
                $string = str_replace(array("#web_url#","#url_web#"), array($cuppa->getPath("web"), $cuppa->getPath("web")),$string);
                $string = str_replace(array("#textarea#", "#textarea_end#", "#/textarea#"), array("<textarea", "></textarea>", "></textarea>"), $string);
                $data = explode("{?", $string);
                forEach($data as $i => $item){
                    if($i%2){
                        $item = str_replace("&nbsp;","", $item);
                        $item = str_replace("&gt;",">", $item);
                        $item = $item.";";
                        $item= str_replace(";;",";", $item);
                        eval(@$item);
                    }else{
                        echo $item;
                    }
                }
            }
        // Join Path array
            function joinPath($array, $remove_first_element = false, $remove_last_element = false, $string = "/"){
                $tmp_array = json_decode(json_encode($array));
                if($remove_first_element) array_shift($tmp_array);
                if($remove_last_element) array_pop($tmp_array); 
                return join($string, $tmp_array);
            }
        // JSON Endoce
            function jsonEncode($value, $base64_encode = true){
                $value = json_encode($value);
                if($base64_encode) $value = base64_encode($value);
                return $value;
            }
        // JSON Decode
            function jsonDecode($value, $base64_decode = true){
                if($base64_decode) $value = @base64_decode($value);
                $value = json_decode($value);
                return $value;
            }
        /* replace
                range = ['<style>','</style>']
        */
        function replace($string, $search, $replace, $first = false, $range = null, $first_range = false){
            if(!$string) return;
            if(@$range){
                $c_temp1 = explode($range[0], $string);
                if(count($c_temp1) <= 1 ) return $string;
                for($i = 0; $i < count($c_temp1); $i++){
                    $c_temp2 = explode($range[1], $c_temp1[$i]);
                    if(count($c_temp2) > 1){
                        if(@$first){ $pos = strpos($c_temp2[0], $search); if($pos) $c_temp2[0] = substr_replace($c_temp2[0], $replace, $pos, strlen($search));
                        }else{ $c_temp2[0] =  str_replace($search, $replace, $c_temp2[0]); }
                        $c_temp1[$i] = join($range[1], $c_temp2);
                        if($first_range) break;
                    }
                }
                $string = join($range[0], $c_temp1);
                //echo '<textarea style="width:100%; height:400px; background:#FFF !important;">'.$string.'</textarea>';
            }else{
                if(@$first){ $pos = strpos($string, $search); if($pos) $string = substr_replace($string, $replace, $pos, strlen($search));
                }else{ $string =  str_replace($search, $replace, $string); }
            }
            return $string;
        }
        // Get Browser inf
            function getBrowser(){ 
                $u_agent = @$_SERVER['HTTP_USER_AGENT']; 
                $bname = 'Unknown';
                $platform = 'Unknown';
                $version= "";
                if (preg_match('/linux/i', $u_agent)) {
                    $platform = 'linux';
                }
                elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
                    $platform = 'mac';
                }
                elseif (preg_match('/windows|win32/i', $u_agent)) {
                    $platform = 'windows';
                }
                if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
                { 
                    $bname = 'Internet Explorer'; 
                    $ub = "MSIE"; 
                } 
                elseif(preg_match('/Trident/i',$u_agent)) 
                { // this condition is for IE11
                    $bname = 'Internet Explorer'; 
                    $ub = "rv"; 
                } 
                elseif(preg_match('/Firefox/i',$u_agent)) 
                { 
                    $bname = 'Mozilla Firefox'; 
                    $ub = "Firefox"; 
                } 
                elseif(preg_match('/Chrome/i',$u_agent)) 
                { 
                    $bname = 'Google Chrome'; 
                    $ub = "Chrome"; 
                } 
                elseif(preg_match('/Safari/i',$u_agent)) 
                { 
                    $bname = 'Apple Safari'; 
                    $ub = "Safari"; 
                } 
                elseif(preg_match('/Opera/i',$u_agent)) 
                { 
                    $bname = 'Opera'; 
                    $ub = "Opera"; 
                } 
                elseif(preg_match('/Netscape/i',$u_agent)) 
                { 
                    $bname = 'Netscape'; 
                    $ub = "Netscape"; 
                } 
                $known = array('Version', @$ub, 'other');
                $pattern = '#(?<browser>' . join('|', $known) .
                 ')[/|: ]+(?<version>[0-9.|a-zA-Z.]*)#';
                if (!preg_match_all($pattern, $u_agent, $matches)) { }
                $i = count($matches['browser']);
                if ($i != 1) {
                    if (strripos($u_agent,"Version") < strripos($u_agent,@$ub)){
                        $version= $matches['version'][0];
                    }
                    else {
                        $version= @$matches['version'][1];
                    }
                }
                else {
                    $version= $matches['version'][0];
                }
                if ($version==null || $version=="") {$version="?";}
                $result = array(
                    'userAgent' => $u_agent,
                    'name'      => $bname,
                    'version'   => $version,
                    'platform'  => $platform,
                    'pattern'    => $pattern
                );
                return (object) $result;
            }
        // santize String
            public function sanitizeString($string){
                return htmlspecialchars(trim(@$string));
            }
        // Get SO info
            function getOS() {
                $useragent= strtolower($_SERVER['HTTP_USER_AGENT']);
                if (strpos($useragent, 'windows nt 5.1') !== FALSE){ return 'Windows XP';
                }elseif (strpos($useragent, 'windows nt 6.1') !== FALSE) { return 'Windows 7';
                }elseif (strpos($useragent, 'windows nt 6.0') !== FALSE) { return 'Windows Vista';
                }elseif (strpos($useragent, 'windows 98') !== FALSE) { return 'Windows 98';
                }elseif (strpos($useragent, 'windows nt 5.0') !== FALSE) { return 'Windows 2000';
                }elseif (strpos($useragent, 'windows nt 5.2') !== FALSE) { return 'Windows 2003 Server';
                }elseif (strpos($useragent, 'windows nt') !== FALSE) { return 'Windows NT';
                }elseif (strpos($useragent, 'win 9x 4.90') !== FALSE && strpos($useragent, 'win me')) { return 'Windows ME';
                }elseif (strpos($useragent, 'win ce') !== FALSE) { return 'Windows CE';
                }elseif (strpos($useragent, 'win 9x 4.90') !== FALSE) { return 'Windows ME';
                }elseif (strpos($useragent, 'windows phone') !== FALSE) { return 'Windows Phone';
                }elseif (strpos($useragent, 'iphone') !== FALSE) { return 'iPhone';
                }elseif (strpos($useragent, 'ipad') !== FALSE) { return 'iPad';
                }elseif (strpos($useragent, 'webos') !== FALSE) { return 'webOS';
                }elseif (strpos($useragent, 'symbian') !== FALSE) { return 'Symbian';
                }elseif (strpos($useragent, 'android') !== FALSE) { return 'Android';
                }elseif (strpos($useragent, 'blackberry') !== FALSE) { return 'Blackberry';
                }elseif (strpos($useragent, 'mac os x') !== FALSE) { return 'Mac OS X';
                }elseif (strpos($useragent, 'macintosh') !== FALSE) { return 'Macintosh';
                }elseif (strpos($useragent, 'linux') !== FALSE) { return 'Linux';
                }elseif (strpos($useragent, 'freebsd') !== FALSE) { return 'Free BSD';
                }elseif (strpos($useragent, 'symbian') !== FALSE) { return 'Symbian';
                }else { return 'Desconocido'; }
            }
        /* Cookie, 
            expire_time = 0, end when browser close
            expire_time = 10 // 10 days
        */
            function setCookie($name, $value = null, $expire_time = 0, $httponly = false, $secure = false){
                if(!$expire_time) $expire_time = 0;
                else $expire_time = time() + 86400*$expire_time;
                @setcookie(@$name, @$value, @$expire_time, '/', "", @$secure, @$httponly);  
                @$_COOKIE[$name] = $value;
            }
            function getCookie($name){
                return $this->sanitizeString(@$_COOKIE[$name]);
            }
        /**
         * Calculate the difference in months between two dates in String YYYY-MM-DD
         * @param String $date1
         * @param String $date2
         * @return int
         */
        public function dateDiffMonths($date1, $date2){
            $date1 = new DateTime($date1);
            $date2 = new DateTime($date2);
            $diff =  $date1->diff($date2);
            $months = $diff->y * 12 + $diff->m + $diff->d / 30;
            return $months;
        }
        /* return different between 2 date
            $returnType = seconds, minutes, all
        */
        public function  dateDiff($date1, $date2, $returnType = ''){
            $result = 0;
            $start_date = new DateTime($date1);
            $interval = $start_date->diff(new DateTime($date2));
            if($returnType == "minutes"){
                $result = $interval->days * 24 * 60;
                $result += $interval->h * 60;
                $result += $interval->i;
                $result += ".".$interval->s;
            }else if($returnType == "seconds"){
                $result = $interval->days * 24 * 60 * 60;
                $result += $interval->h * 60 * 60;
                $result += $interval->i * 60;
                $result += $interval->s;
            }else if($returnType == 'object'){
                return $interval;
            }else{
                $result = $interval->format('%H:%I:%S');
            }
            return $result;
        }
        // return
        public function dateByNumber($years = 0, $months = 0,$days = 0, $hours = 0, $minutes = 0, $seconds = 0, $returnString = false, $stringFormat = "%Y-%M-%D %H:%I:%S"){
            $date1 = new DateTime('2000-01-01');
            $date2 = new DateTime('2000-01-01');
            $date2->modify($years." years");
            $date2->modify($months." months");
            $date2->modify($days." days");
            $date2->modify($hours." hours");
            $date2->modify($minutes." minutes");
            $date2->modify($seconds." seconds");
            $diff = $date1->diff($date2);
            if($returnString) return $diff->format($stringFormat);
            return $diff;
        }
        public function getIndependentDates($startDate, $endDate, $format = "Y-m-d")
        {
            $begin = new DateTime($startDate);
            $end = new DateTime($endDate);
            $interval = new DateInterval('P1D'); // 1 Day
            $dateRange = new DatePeriod($begin, $interval, $end);
            $range = [];
            foreach ($dateRange as $date) {
                $range[] = $date->format($format);
            }
            array_push($range,$endDate);
            return $range;
        }

        public function percent($n, $min, $max, $invert = false){
            $percent = ($n-$min)/($max-$min);
            if($percent < 0) $percent = 0;
            else if($percent > 1) $percent = 1;
            if($invert) $percent = 1-$percent;
            return $percent;
        }
        /*
         * $from: ''Pacific/Nauru'
         * $to: ''America/Toronto'
         * */
        function convertTimeZone($time = '', $from = '', $to = '', $format= 'Y-m-d H:i:s'){
            if($from == $to) return $time;
            $date = null;
            try {
                $date = new DateTime($time, new DateTimeZone($from));
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
            $date->setTimezone(new DateTimeZone($to));
            $time = $date->format($format);
            return $time;
        }
	}
?>