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
    		if(radioSelected == "text"){
    			info.type = radioSelected;
    		}else if(radioSelected == "password"){
                info.type = radioSelected;
                info.encode = jQuery('#encode').val();
    		}else if(radioSelected == "email"){
    			info.type = radioSelected;
    		}else if(radioSelected == "number"){
                info.type = radioSelected;
                info.number_fortmat = jQuery('#number_format').val();
    		}else if(radioSelected == "alias"){
                info.type = radioSelected;
                info.alias_from =jQuery('#alias_from').val();
    		}else if(radioSelected == "tags"){
                info.type = radioSelected;
    		}
            // Extra data
                info.maxlength =jQuery('#maxlength').val();
                info.tooltip = jQuery('.panel_extra_params #tooltip').val();
                info.width = jQuery('#width').val();
                info["default"] = jQuery('#default').val();
                
            // JSON/Base64 encode
                jsonScript = jQuery.base64Encode(jQuery.toJSON(info));
            // add Info
        		var field_name = "#" + "<?php echo @$_REQUEST["field"] ?>" + "_config";
        		jQuery(field_name).attr("value", jsonScript);
        		CloseDefaultAlert();
    	}
	// Set Default Info
    	function SetDefault(){
    		var field_name = "#" + "<?php echo @$_REQUEST["field"] ?>" + "_config";
            // Decode Info
    		  var defaultInfo = cuppa.jsonDecode(jQuery(field_name).val());
            // Set Info
        		if(!defaultInfo) return;
        		if(defaultInfo.type == "text"){
        			jQuery("#text").attr("checked", "checked");
        		}else if(defaultInfo.type == "password"){
        			jQuery("#password").attr("checked", "checked");
        			jQuery("#encode").val(defaultInfo.encode);
        		}else if(defaultInfo.type == "email"){
        			jQuery("#email").attr("checked", "checked");
        		}else if(defaultInfo.type == "number"){
        			jQuery("#number").attr("checked", "checked");
                    jQuery('#number_format').val( defaultInfo.number_fortmat );
        		}else if(defaultInfo.type == "alias"){
        			jQuery("#alias").attr("checked", "checked");
                    jQuery("#alias_from").val(defaultInfo.alias_from);
        		}else if(defaultInfo.type == "tags"){
        		  jQuery("#tags").attr("checked", "checked");
        		}
                // Extra params
                    jQuery("#maxlength").val(defaultInfo.maxlength);
                    jQuery('.panel_extra_params #tooltip').val(defaultInfo.tooltip);
                    jQuery('#width').val(defaultInfo.width);
                    jQuery('#default').val(defaultInfo["default"] );
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
        <div>
            <table>
                <tr>
                    <td><input type="radio" name="typeSelect" id="text" value="text" checked="checked" /></td>
                    <td><label for="text"><?php echo @$language->text ?></label></td>
                    <td></td>
                </tr>
                <tr>
                    <td><input type="radio" name="typeSelect" id="password" value="password" /></td>
                    <td>
                        <label for="password"><?php echo @$language->password ?> :: <?php echo @$language->encode ?>: </label>
                        <img class="tooltip" style="margin-left: 3px" src="templates/default/images/template/icon_help_12.png" title="<?php echo @$language->global_encode_message ?>" />
                    </td>
                    <td>
                        <select id="encode" name="encode">
                            <option value="none"><?php echo @$language->none ?></option>
                            <option value="global_encode" selected="selected">global_encode</option>
                            <option value="md5">md5</option>
                            <option value="sha1">sha1</option>
                            <option value="sha1Salt">sha1Salt</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><input type="radio" name="typeSelect" id="email" value="email" /></td>
                    <td><label for="email"><?php echo @$language->email ?></label></td>
                    <td></td>
                </tr>
                <tr>
                    <td><input type="radio" name="typeSelect" id="number" value="number"/></td>
                    <td><label for="number"><?php echo @$language->number ?> :: <?php echo @$language->format ?>: </label></td>
                    <td>
                        <select name="number_format" id="number_format" >
                            <option value="normal"><?php echo @$language->normal ?></option>
                            <option value="money"><?php echo @$language->money ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><input type="radio" name="typeSelect" id="alias" value="alias" /></td>
                    <td><label for="alias">Alias :: <?php echo @$language->alias_from ?>: </label></td>
                    <td>
                        <input type="text" name="typeSelect" id="alias_from" name="alias_from" />
                    </td>
                </tr>
                <tr>
                    <td><input type="radio" name="typeSelect" id="tags" value="tags" /></td>
                    <td><label for="tags"><?php echo @$language->tags ?></label></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <input style="margin-top: 5px;" class='button_form' type='button' value='<?php echo @$language->accept ?>' onclick="GetInfo()"/>
                    </td>
                </tr>
            </table>
        </div>
        <!-- Extra Params -->
            <div class="section" style="margin: 10px 0px;"><div></div><span><?php echo @$language->extra_params ?></span></div>
            <div style="padding-left: 20px;">
                <table class="panel_extra_params">
                    <tr>
                        <td><?php echo @$language->tooltip ?></td>
                        <td>
                            <input type="text" class="tooltip" id="tooltip" name="tooltip" value="" /> 
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo @$language->maxlength ?></td>
                        <td><input type="text" class="maxlength" id="maxlength" name="maxlength" value="" /></td>
                    </tr>
                    <tr>
                        <td><?php echo @$language->width ?></td>
                        <td><input type="text" class="width" id="width" name="width" value="" /></td>
                    </tr>
                    <tr>
                        <td><?php echo @$language->default_value ?></td>
                        <td><input type="input" class="default" id="default" name="default" value="" /></td>
                    </tr>
                </table>
            </div>
        <!-- -->
    </form>
</div>