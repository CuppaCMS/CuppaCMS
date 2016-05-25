<?php
	//++ Functions
		function getProducts(){
            @session_start();
            include_once($_SESSION["cuSession"]->paths->administrator->document_path."classes/Cuppa.php");
            $cuppa = Cuppa::getInstance();
            $token = $cuppa->security->token;
            $section = @$cuppa->POST("section"); 
            $condition = htmlspecialchars(base64_decode($_POST["condition"]), ENT_NOQUOTES);
            $limit = @$cuppa->POST("limit"); 
            $order = @$cuppa->POST("order");
            $include_filters = @@$cuppa->POST("include_filters");
            include_once("Shop.php");
            $result = Shop::getProducts($section, $condition, $limit, $order, $include_filters);
            echo $cuppa->utils->jsonEncode($result);
		}
	//--
	//++ Handler
		$nameFunction = htmlspecialchars($_POST["function"]);
		if($nameFunction == ""){ echo "function name no found"; exit();}
		$nameFunction();
	//--
?>