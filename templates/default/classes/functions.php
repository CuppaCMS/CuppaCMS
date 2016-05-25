<?php
	//++ Functions
		function functionName(){
            @session_start();
            include_once($_SESSION["cuSession"]->paths->administrator->document_path."classes/Cuppa.php");
            $cuppa = Cuppa::getInstance();
		}
	//--
	//++ Handler
		$nameFunction = htmlspecialchars($_POST["function"]);
		if($nameFunction == ""){ echo "function name no found"; exit();}
		$nameFunction();
	//--
?>