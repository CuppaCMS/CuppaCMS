<?php 
    include_once(realpath(__DIR__ . '/..')."/classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load();
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
    	height:440px;
    	margin-left:-300px;
    	margin-top:-220px;
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
        height: 401px;
        padding: 10px;
        overflow: auto;
    }
</style>
<script>
	function CloseDefaultAlert(){
		cuppa.setContent({'load':false, duration:0.2});
        cuppa.blockade({'load':false, duration:0.2, delay:0.1});
	}
</script>
<div class="alert_config_field" id="alert">
    <div class="alert_config_top">
        <strong><?php echo $language->fields_configuration ?></strong>: <?php echo @$cuppa->POST("field"); ?>
        <div class="btnClose_alert" id="btnClose_alert" onclick="CloseDefaultAlert()"></div>
    </div>
    <div id="content_alert_config" class="content_alert_config">
        <?php include "../components/table_manager/fields/config/".@$cuppa->POST("urlConfig"); ?>
    </div>
</div>