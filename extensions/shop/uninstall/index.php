<?php
    include_once "../../../classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $administrator_path = $cuppa->utils->getStringPart(__DIR__, "administrator\\", true);
    $cuppa->setDocumentPath($administrator_path);
    $cuppa->init();
    $token = $cuppa->security->token;
    //++ Script -  create tables 
        $sql = file_get_contents('uninstall.sql');
    	$sql_array = explode(";", $sql);
    	for($i = 0; $i < count($sql_array); $i++){
    		if(trim($sql_array[$i])){
    			$result = $cuppa->dataBase->personalSql($sql_array[$i], $token);
                echo "<b>SQL:</b> ".$cuppa->utils->cutText("", $sql_array[$i], 50, "...");
                echo " | Result:</b> ".$result;
                echo "<br />";
            }
    	}
     //--
?>