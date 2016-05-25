<?php 
    include_once(realpath(__DIR__ . '/..')."/classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $params = $cuppa->utils->getUrlVars(@$_POST["params"]);
?>
<style>
    .new_content{ position: fixed; }
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
        top: 4px; right: 4px;
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
        padding: 10px;
        overflow: auto;
    }
</style>
<script>
    alert_lightbox = {}
    //++ reload
        alert_lightbox.reload = function(data){
            cuppa.blockadeScreen();
            menu.showCharger();
            if(data == undefined) data = {}
            data.params = cuppa.jsonDecode("<?php echo $cuppa->jsonEncode(@$cuppa->POST("params")) ?>");
            jQuery.ajax({url:"<?php echo @$cuppa->POST("url"); ?>", type:"POST", data:data, success:Ajax_Result});
            function Ajax_Result(result){
                cuppa.blockadeScreen(false);
                menu.showCharger(false);
                $(".alert_lightbox .content_alert_config").html(result);
            }
        }
    //--
    //++ close
    	alert_lightbox.close = function(){
    		cuppa.setContent({'load':false, duration:0.2});
            cuppa.blockade({'load':false, duration:0.2, delay:0.1});
    	}
    //--
    //++ resize
        alert_lightbox.resize = function(){
            if("<?php echo @$params["width"] ?>"){ $(".alert_config_field").width("<?php echo @$params["width"] ?>"); }
            if("<?php echo @$params["height"] ?>"){ $(".alert_config_field").height("<?php echo @$params["height"] ?>"); }
            
            var dimentions = cuppa.dimentions(".alert_config_field");
            $(".alert_config_field").css("margin-left", dimentions.width2*-0.5 );
            $(".alert_config_field").css("margin-top", dimentions.height2*-0.5 );
            $(".content_alert_config").height( dimentions.height2-57);
        }; 
    //--
    //++ end
        alert_lightbox.end = function(){
            cuppa.removeEventGroup("alert_lightbox");
        }; cuppa.addRemoveListener(".alert_lightbox", alert_lightbox.end);
    //--
    //++ init
        alert_lightbox.init = function(){
            cuppa.addEventListener("resize", alert_lightbox.resize, window, "alert_lightbox"); alert_lightbox.resize();
        }; cuppa.addEventListener("ready",  alert_lightbox.init, document, "alert_lightbox");
    //--
</script>
<div class="alert_config_field alert_lightbox" id="alert">
    <div class="alert_config_top">
        <?php echo @$cuppa->POST("title"); ?>
        <div class="btnClose_alert" id="btnClose_alert" onclick="alert_lightbox.close()"></div>
    </div>
    <div id="content_alert_config" class="content_alert_config">
        <?php include $cuppa->getDocumentPath().@$cuppa->POST("url"); ?>
    </div>
</div>