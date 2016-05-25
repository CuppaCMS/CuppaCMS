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
    		var radioSelected = jQuery('input[name=typeSelect]:checked', '#text_form').val();
        		if(radioSelected == "normal"){
        			info.type = "normal";
        		}else if(radioSelected == "datePicker"){
        			var config = jQuery('#config').val();
                    info.type = "datePicker";
                    info.config = config;
        		}
            // Extra params
                info.tooltip = jQuery('.panel_extra_params #tooltip').val();
                info.width = jQuery('#width').val();
            var jsonScript = jQuery.toJSON(info);
            // Base64 encode
                jsonScript = jQuery.base64Encode(jsonScript);
            // add Info
        		var field_name = "#" + "<?php echo $_REQUEST["field"] ?>" + "_config";
        		jQuery(field_name).attr("value", jsonScript);
        		CloseDefaultAlert();
    	}
	// Set Default Info
	   function SetDefault(){
    		jQuery("#datePicker").attr("checked", "checked");
    		var field_name = "#" + "<?php echo $_REQUEST["field"] ?>" + "_config";
            // Decode Info
              var defaultInfo = cuppa.jsonDecode(jQuery(field_name).val());
            // Set Info
        		if(!defaultInfo) return;
        		if(defaultInfo.type == "datePicker"){
        			jQuery("#datePicker").prop("checked", true);
                    jQuery("#config").val(defaultInfo.config);
        		}
          // Extra params
                jQuery('.panel_extra_params #tooltip').val(defaultInfo.tooltip);
                jQuery('#width').val(defaultInfo.width);
    	}; SetDefault();
        
     cuppa.selectStyle(".alert_config_field select");
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
            <table style="width:100%">
                <tr>
                    <td style="width: 50px;"><input type="radio" name="typeSelect" id="datePicker" value="datePicker" /></td>
                    <td style="width: 180px;"><label for="datePicker"><?php echo @$language->date_picker_type ?>: </label></td>
                    <td>
                        <select id="config" name="config">
                            <option value="simple"><?php echo @$language->simple ?></option>
                            <option value="auto_today_selected" selected="selected"><?php echo @$language->auto_today_selected ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
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
                        <td><?php echo @$language->tooltip ?></td>
                        <td>
                            <input type="text" class="tooltip" id="tooltip" name="tooltip" value="" /> 
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo @$language->width ?></td>
                        <td><input type="text" class="width" id="width" name="width" value="" /></td>
                    </tr>
                </table>
            </div>
        <!-- -->
    </form>
</div>