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
                info.editor = jQuery('#editor').val();
                info.width = jQuery('#width').val();
                info.height = jQuery('#height').val();
                info.styles_css = jQuery('#styles_css').val();
                info.template_list = jQuery('#template_list').val();
                info.folder = jQuery('#folder').val();
                info.base_64_encode = jQuery("#base_64_encode").prop("checked");
                info.editor_mode = jQuery('#editor_mode').val();
                info.maxlength =jQuery('#maxlength').val();
                info.tooltip = jQuery('#text_form #tooltip').val();
            // Json / Base64 encode
                info = jQuery.toJSON(info);
                info = jQuery.base64Encode(info);
            // add Info
                var field_name = "#<?php echo $_REQUEST["field"] ?>" + "_config";
                jQuery(field_name).attr("value", info);
                CloseDefaultAlert();
    	}
    // Set Default Info
    	function SetDefault(){
    		var field_name = "#<?php echo @$_POST["field"] ?>" + "_config";
            // Decode Info
                var info = cuppa.jsonDecode(jQuery(field_name).val());
                if(!info) return;
            // Set Info
                jQuery('#editor').val(info.editor);
        		if(info.width) jQuery("#width").val(info.width);
        		if(info.height)jQuery("#height").val(info.height);
                jQuery("#styles_css").val(info.styles_css);
                jQuery('#template_list').val(info.template_list);
                jQuery('#folder').val(info.folder);
                jQuery('#editor_mode').val(info.editor_mode);
                jQuery("#base_64_encode").prop("checked", info.base_64_encode );
                jQuery("#maxlength").val(info.maxlength);
                jQuery('#text_form #tooltip').val(info.tooltip);
    	}; SetDefault();
    cuppa.tooltip();
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
            <table>
                <tr>
                    <td><label for="editor"><?php echo @$language->editor ?></label></td>
                    <td>
                        <select id="editor" name="editor">
                            <option value="none"><?php echo @$language->none ?></option>
                            <option value="tinymce">tinymce</option>
                            <option value="ace">ace</option>
                            <option value="jsoneditor">jsoneditor</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="editor"><?php echo @$language->default_dimentions ?></label></td>
                    <td>
                        <input style="width:70px" type="text" id="width" name="width" value="821px" /> x <input style="width:70px" type="text" id="height" name="height" value="217px" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="editor"><?php echo @$language->style_css_path ?></label>
                        <img class="tooltip" style="margin-left: 3px" src="templates/default/images/template/icon_help_12.png" title="<?php echo @$language->style_css_tooltip ?>" />
                    </td>
                    <td>
                        <input type="text" id="styles_css" name="styles_css" value="" /> 
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="editor"><?php echo @$language->template_list_path ?></label>
                        <img class="tooltip" style="margin-left: 3px" src="templates/default/images/template/icon_help_12.png" title="<?php echo @$language->template_list_path_tooltip ?>" />
                    </td>
                    <td>
                        <input type="text" id="template_list" name="template_list" value="" /> 
                    </td>
                </tr>
                <tr>
                    <td><label for="folder"><?php echo @$language->folder_path ?></label></td>
                    <td><input id="folder" name="folder" class="required" value="<?php echo @$configuration->upload_default_path ?>" /></td>
                </tr>
                <tr>
                    <td>
                        <span><?php echo @$language->mode ?></span>
                        <img class="tooltip" style="margin-left: 3px" src="templates/default/images/template/icon_help_12.png" title="<?php echo @$language->editor_mode_tooltip ?>" />
                    </td>
                    <td>
                        <select id="editor_mode" name="mode">
                            <option value="html">html</option>
                            <option value="css">css</option>
                            <option value="javascript">javascript</option>
                            <option value="php">php</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="base_64_encode"><?php echo @$language->base_64_encode ?></label></td>
                    <td><input type="checkbox" id="base_64_encode" name="base_64_encode" /></td>
                </tr>
                <tr>
                    <td><?php echo @$language->maxlength ?></td>
                    <td><input type="text" class="maxlength" id="maxlength" name="maxlength" value="" /></td>
                </tr>
                <tr>
                    <td><?php echo @$language->tooltip ?></td>
                    <td>
                        <input type="text" class="tooltip" id="tooltip" name="tooltip" value="" /> 
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><input class='button_form' type='button' value='<?php echo @$language->accept ?>' onclick="GetInfo()"/></td>
                </tr>
            </table>
        </div>
    </form>
</div>