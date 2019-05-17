<?php
	// Check if the directory installation exist
        //if(is_dir('installation')) header ("Location: installation/");
    // Import GlobalClass
        require("classes/Cuppa.php");
        $cuppa = Cuppa::getInstance();
        $language = $cuppa->language->load();
        if(@$_POST["language"]){ $cuppa->language->set($_POST["language"], true); }
	// Validate secure login
		if($cuppa->configuration->secure_login && !$cuppa->getSessionVar("secure")){
			if($cuppa->configuration->secure_login_value != @$_REQUEST["secure"]){ header ("Location: " . $cuppa->configuration->secure_login_redirect);
            }else{ $cuppa->setSessionVar("secure", 1); header ("Location: " . $cuppa->getPath()); }
        }
        $cuppa->setPath();
	// Validate user
		if(@$_POST["task"] == "login"){ $cuppa->user->setSession(); header("Location: ".$cuppa->getPath()); }
		else if(@$_REQUEST["task"] == "logout"){ $cuppa->user->destroy($cuppa->getPath()); }
    // Load Template
        require("templates/".$cuppa->configuration->administrator_template."/index.php");
?>