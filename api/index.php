<?php
    include_once "classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    
    $api_check = $cuppa->security->api();
    if(@$api_check->error){ echo json_encode($api_check, JSON_PRETTY_PRINT); exit(); }

    // Handler
		$nameFunction = htmlspecialchars(@$_POST["function"]); unset($_POST["function"]);
        if(!$nameFunction) $nameFunction = htmlspecialchars(@$_POST["action"]); unset($_POST["action"]);
        $action =  explode("/",$nameFunction);
        $nameFunction = $action;
        if(count($nameFunction) > 1){
            $rest = $nameFunction; $nameFunction = array_pop($rest);
            $path = __DIR__."/".join("/", $rest);
            if(file_exists($path) && is_file($path)){
                @include_once($path);
            }else if(file_exists($path.".php") && is_file($path.".php")) {
                $path = $path.".php";
                @include_once($path);
            }else if(file_exists($path."/index.php") && is_file($path."/index.php") ) {
                $path = $path."/index.php";
                @include_once($path);
            }else{
                array_shift($rest);
                $path = __DIR__."/".join("/", $rest).".php";
                @include_once($path);
            }
        }else{ $nameFunction = $nameFunction[0]; }

        if($nameFunction){
            $params = array();
            forEach($_POST as $key => $item){ array_push($params, $item); unset($_POST[$key]); }
            $base64 = false; if(isset($_SERVER["HTTP_BASE64"])){ $base64 = $_SERVER["HTTP_BASE64"] === 'true' ? true : false; }
            $params = join("','", $params);
            $eval = $nameFunction."('".$params."')";
            $result = null;
            try { $result = eval('return '.$eval.";"); } catch (Error $e) { }

            if(!$result && count($action) > 1){
                $eval = $action[count($action)-2]."::".$nameFunction."('".$params."')";
                try { $result = eval('return '.$eval.";"); } catch (Error $e) { }
            }
            if(!$result && count($action) > 1){
                $eval = $action[0]."\\".$action[count($action)-2]."::".$nameFunction."('".$params."')";
                try { $result = eval('return '.$eval.";"); } catch (Error $e) { }
            }
            echo $cuppa->jsonEncode($result, $base64);
        }