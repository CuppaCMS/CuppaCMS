<?php
    @session_start();
    include_once("../classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load();
?>
<script type="text/javascript">
    // Get Info
    	function GetInfo(){
    		var info = {};
                info.script = jQuery("#script").val();
                info.editable = (jQuery("#editable").attr("checked")) ? 1 : 0;
            // Extra data
                info.width = jQuery('#width').val();
                info.tooltip = jQuery('.panel_extra_params #tooltip').val();
            // Base64 encode
                var jsonScript = jQuery.toJSON(info);
                jsonScript = jQuery.base64Encode(jsonScript);
            // add Info
        		var field_name = "#" + "<?php echo $_REQUEST["field"] ?>" + "_config";
        		jQuery(field_name).attr("value", jsonScript);
        		CloseDefaultAlert();
    	}
	// Set Default Info
    	function SetDefault(){
    		var field_name = "#" + "<?php echo $_REQUEST["field"] ?>" + "_config";
            // Decode Info
              var defaultInfo = cuppa.jsonDecode(jQuery(field_name).val());
            // Set Info
                if(!defaultInfo) return;
                jQuery("#script").val(defaultInfo.script);
                if(defaultInfo.editable) jQuery("#editable").attr("checked", "checked");
             // Extra params
                jQuery('#width').val(defaultInfo.width);
                jQuery('.panel_extra_params #tooltip').val(defaultInfo.tooltip);
    	}; SetDefault();
</script>
<style>
	.alert_config_field td{
		padding:3px;
		vertical-align:central;
		text-align:left;
	}
</style>
<div class="text">
    <form id="text_form" name="text_form">
        <div class="section" style="margin: 0px 0px 10px;"><div></div><span><?php echo @$language->general_configuration ?></span></div>
        <div style="padding-left: 20px;">
            <table>
                <tr>
                    <td style="width: 140px;"><?php echo @$language->personal_script ?>:</td>
                    <td>
                        <input id="script" name="script" value="" />
                        &nbsp;<?php echo @$language->editable ?>:
                        <input type="checkbox" id="editable" name="editable" value="1" />
                    </td>
                </tr>
            </table>
            <br />
            <table>
                <tr>
                    <td style="vertical-align: text-top; color: #025A8D; width: 140px;"><b><?php echo @$language->examples ?>:</b></td>
                    <td style="color: #025A8D;">
                        <?php echo @$language->examples_text ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input class='button_form' type='button' value='<?php echo @$language->accept ?>' onclick="GetInfo()"/>
                    </td>
                </tr>
            </table>
        </div>
        <!-- Extra Params -->
            <div class="section" style="margin: 10px 0px;"><div></div><span><?php echo @$language->extra_params ?></span></div>
            <div style="padding-left: 20px;">
                <table class="panel_extra_params" >
                    <tr>
                        <td><?php echo @$language->width ?></td>
                        <td><input type="text" class="width" id="width" name="width" value="" /></td>
                    </tr>
                    <tr>
                        <td><?php echo @$language->tooltip ?></td>
                        <td>
                            <input type="text" class="tooltip" id="tooltip" name="tooltip" value="" /> 
                        </td>
                    </tr>
                </table>
            </div>
        <!-- -->
    </form>
</div>