<?php 
    include_once(realpath(__DIR__ . '/..')."/classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $params = $cuppa->utils->getUrlVars(@$_POST["params"]);
?>
<style>
    .new_content{
        position: fixed;
    }
    .alert_config_field{
    	font-size:12px;
    	background:#FFF;
    	position:relative;
    	border-radius: 3px;
    	box-shadow: 0px 0px 5px rgba(0,0,0,0.2);
    	overflow:hidden;
    	position:fixed;
    	top:50%;
    	left:50%;
        width:600px;
    	height:434px;
    	margin-left:-300px;
    	margin-top:-217px;
    }
    .alert_config_top{
        position: relative;
        margin: 2px;
        margin-bottom: 0px;
        border: 1px solid #D2D2D2;
        background: #4489F8;
        overflow: auto;
        color:#FFF;
        font-size: 13px;
        padding: 7px 5px;
        box-shadow: 0 0 2px rgba(0, 0, 0, 0.1);
        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
    }
    .description_alert{
    	position:relative;
    	font-size:12px;
        text-shadow:0 1px #FFFFFF;
        font-weight: normal;
        padding: 5px 0px 5px 0px;
    }
    .btnClose_alert{
    	position:absolute;
        top: 4px; right: 2px;
    	width:22px;
    	height:22px;
    	cursor:pointer;
        background:url(js/cuppa/cuppa_images/close_white.png) no-repeat;
        background-position: center;
        background-size: 13px;
    }
    .content_alert_config{
    	position:relative;
    	clear:both;
        margin: 2px;
        margin-top: 0px;
        height: 397px;
        padding: 0px;
        overflow: auto;
    }
    .alert_iframe iframe{
        border: 0px;
        position: absolute;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        
        
    }
</style>
<script>
	alert_iframe = {}
    //++ close
    	alert_iframe.close = function(){
    		cuppa.setContent({'load':false, duration:0.2});
            cuppa.blockade({'load':false, duration:0.2, delay:0.1});
    	}
    //--
    //++ resize
        alert_iframe.resize = function(){
            if("<?php echo @$params["width"] ?>"){ $(".alert_config_field").width("<?php echo @$params["width"] ?>"); }
            if("<?php echo @$params["height"] ?>"){ $(".alert_config_field").height("<?php echo @$params["height"] ?>"); }
            
            var dimentions = cuppa.dimentions(".alert_config_field");
            $(".alert_config_field").css("margin-left", dimentions.width2*-0.5 );
            $(".alert_config_field").css("margin-top", dimentions.height2*-0.5 );
            $(".content_alert_config").height( dimentions.height2-38);
        }; 
    //--
    //++ end
        alert_iframe.end = function(){
            cuppa.removeEventGroup("alert_iframe");
        }; cuppa.addRemoveListener(".alert_iframe", alert_iframe.end);
    //--
    //++ init
        alert_iframe.init = function(){
            cuppa.addEventListener("resize", alert_iframe.resize, window, "alert_iframe"); alert_iframe.resize();
        }; cuppa.addEventListener("ready",  alert_iframe.init, document, "alert_iframe");
    //--
</script>
<div class="alert_config_field alert_iframe" id="alert">
    <div class="alert_config_top">
        <?php echo @$cuppa->POST("title"); ?>
        <div class="btnClose_alert" id="btnClose_alert" onclick="alert_iframe.close()"></div>
    </div>
    <div id="content_alert_config" class="content_alert_config">
        <iframe class="iframe" id="iframe" name="iframe" src="<?php echo @$cuppa->POST("url"); ?>"></iframe>
    </div>
</div>