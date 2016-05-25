<?php
    @set_time_limit(0);
    @ini_set('max_execution_time', 9000);
    @ini_set('memory_limit', "256M"); 
    @ini_set('memory_limit', '-1');
    
	@session_start();
    require("../classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load();
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- Aplication icons -->
            <meta name="mobile-web-app-capable" content="yes" />
            <meta name="apple-mobile-web-app-capable" content="yes" />
            <meta name="apple-mobile-web-app-status-bar-style" content="black" />
            <link rel="shortcut icon" sizes="196x196" href="../templates/default/images/app_icon_196.png"  />                
            <link rel="shortcut icon" sizes="128x128" href="../templates/default/images/app_icon_128.png" />
            <link rel="shortcut icon" sizes="24x24" href="../templates/default/images/icon.png" />
            <link rel="apple-touch-icon" sizes="128x128" href="../templates/default/images/app_icon_128.png" />
            <link rel="apple-touch-icon-precomposed" sizes="128x128" href="../templates/default/images/app_icon_128.png" />
            <link rel="apple-touch-startup-image" href="../templates/default/images/app_icon_196.png" />
        <!-- -->
        <title><?php echo @$language->cuppa_title ?></title>
    	<!-- Principals Packages  -->
            <link href="../templates/default/css/template.css" rel="stylesheet" type="text/css" />
            <link href="../js/cuppa/cuppa.min.css" rel="stylesheet" type="text/css" />
            <script src="../js/jquery/jquery.js" type="text/javascript"></script>
            <script src="../js/jquery/jquery-ui.js" type="text/javascript"></script>
            <script src="../js/cuppa/extras.js" type="text/javascript"></script>
            <script src="../js/cuppa/cuppa.min.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="frame" style="overflow: hidden; margin: 20px; padding: 10px 20px;">
            <h1>Installation</h1>
        	<?php 
    			if(!isset($_REQUEST["view"]) || @$_REQUEST["view"] == "check_info"){
    				require("html/steps/check_info.php");
    			}else if(@$_REQUEST["view"] == "data_base_configuration"){
    				require("html/steps/data_base_configuration.php");
    			}else if(@$_REQUEST["view"] == "installation_finished"){
    				require("html/steps/installation_finished.php");
    			}else if(@$_REQUEST["view"] == "delete_installation_folder"){
    				require("html/steps/delete_installation_folder.php");
    			}
    		?>
        </div>
    </body>
</html>