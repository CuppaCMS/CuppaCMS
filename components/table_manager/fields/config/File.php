<?php         
    @session_start();
    include_once("../classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load();
    $configuration = $cuppa->configuration;
?>
<script type="text/javascript">
    // Get Info
    	function GetInfo(){
            var info = {};
    		var folder = jQuery('#folder').val();
    		if(!folder){ alert("Please, write a folder path");  return;	}
            // Get data
                info.folder = folder;
                info.unique_name = (jQuery("#unique_name").attr("checked")) ? 1 : 0;
                info.show_image = (jQuery("#show_image").attr("checked")) ? 1 : 0;
                info.dimention_priority = jQuery('#dimention_priority').val();
                info.dimention_image = jQuery('#dimention_image').val();
                info.download_enabled = (jQuery("#download_enabled").attr("checked")) ? 1 : 0;
            // Extra data
                info.tooltip = jQuery('.panel_extra_params #tooltip').val();
                info.width = jQuery('.panel_extra_params #width').val();
                info.resize = jQuery('.panel_extra_params #resize').prop("checked");
                info.max_width = jQuery('.panel_extra_params #max_width').val();
                info.max_height = jQuery('.panel_extra_params #max_height').val();
                info.crop = jQuery('.panel_extra_params #crop').prop("checked");
             // Encode info
                info = jQuery.base64Encode(jQuery.toJSON(info));
            // add Info
        		var field_name = "#" + "<?php echo $_REQUEST["field"] ?>" + "_config";
        		jQuery(field_name).attr("value", info);
        		CloseDefaultAlert();
    	}
	// Set Default Info
    	function SetDefault(){
    		var defaultFolder = '<?php echo $configuration->upload_default_path ?>';
    		var field_name = "#" + "<?php echo $_REQUEST["field"] ?>" + "_config";
             // Decode Info
                var info = cuppa.jsonDecode(jQuery(field_name).val());
            // Set Info
        		if(!info){ jQuery("#folder").attr("value", defaultFolder); return};
        		jQuery("#folder").attr("value", info.folder);
                jQuery('#unique_name').prop("checked", info.unique_name );
                if(info.show_image != undefined) jQuery("#show_image").prop("checked", info.show_image);
                jQuery("#dimention_priority").val(info.dimention_priority);
                jQuery("#dimention_image").val(info.dimention_image);
                if(info.download_enabled != undefined) jQuery("#download_enabled").prop("checked", info.download_enabled);
             // Extra params
                jQuery('.panel_extra_params #tooltip').val(info.tooltip);
                jQuery('.panel_extra_params #width').val(info.width);
                jQuery(".panel_extra_params #resize").prop("checked", info.resize);
                jQuery('.panel_extra_params #max_width').val(info.max_width);
                jQuery('.panel_extra_params #max_height').val(info.max_height);
                jQuery(".panel_extra_params #crop").prop("checked", info.crop);
                
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
                    <td><label for="folder"><?php echo @$language->folder_path ?>: </label></td>
                    <td><input id="folder" name="folder" class="required" /></td>
                </tr>
                <tr>
    				<td>Auto generate unique name:</td>
                    <td><input type="checkbox" id="unique_name" name="unique_name" /></td>
    			</tr>
    			<tr>
    				<td><?php echo @$language->show_image_in_list ?>:</td>
                    <td><input type="checkbox" id="show_image" name="show_image" checked="checked" /></td>
    			</tr>
    			<tr>
    				<td><?php echo @$language->ajust_priority ?></td>
                    <td>
                        <select id="dimention_priority" name="dimention_priority">
                            <option value="width"><?php echo @$language->width ?></option>
                            <option value="height" selected="selected" ><?php echo @$language->height ?></option>
                        </select>
                        <?php echo @$language->dimention ?>
                        <input style="width: 50px;" type="text" id="dimention_image" name="dimention_image" value="50" />
                    </td>
    			</tr>
                <tr>
    				<td>Enabled download:</td>
                    <td><input type="checkbox" id="download_enabled" name="download_enabled" checked="checked" /></td>
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
                        <td><?php echo @$language->tooltip ?></td>
                        <td>
                            <input type="text" class="tooltip" id="tooltip" name="tooltip" value="" /> 
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo @$language->width ?></td>
                        <td><input type="text" class="width" id="width" name="width" value="" /></td>
                    </tr>
                    <tr>
                        <td><?php echo @$language->resize ?></td>
                        <td>
                            <input type="checkbox" class="resize" name="resize" id="resize" />
                            <span> <?php echo @$language->maximum_dimensions ?> </span>
                            <input type="text" class="max_width" id="max_width" name="max_width" value="" style="width: 40px;" />
                            <span> x </span>
                            <input type="text" class="max_height" id="max_height" name="max_height" value="" style="width: 40px;"  />
                            <span><?php echo @$language->crop ?></span>
                            <input type="checkbox" class="crop" name="crop" id="crop" />
                        </td>
                    </tr>
                </table>
            </div>
        <!-- -->
        
    </form>
</div>