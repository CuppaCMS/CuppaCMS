<?php
    @session_start();
    include_once("../classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load();
?>
<script type="text/javascript">
	function SelectType(){
		var type = 1;
		if( jQuery("#personal:checked")[0] ){ type = 2; }
		ShowConfigPanel(type);
	}
		function ShowConfigPanel(type){
            jQuery("#config_table").css("display", "none");
            jQuery("#config_personal_data").css("display", "none");
			jQuery("#separator_config").css('visibility', "visible");
			if(type == 1){
				jQuery("#config_table").css("display", "table");
				jQuery("#config_personal_data").css("display", "none");
				jQuery("#table").attr("checked", "checked")
			}else{
				jQuery("#config_personal_data").css("display", "table");
				jQuery("#config_table").css("display", "none");
				jQuery("#personal").attr("checked", "checked")
			}
		}
	function DeleteItem(allItems){
		if(!allItems){
			var value = jQuery("#list").attr("value");
			if(value) jQuery("#list").find("option[value='"+value+"']").remove();
			else alert("Please, select a item of the list");
		}else{
			jQuery("#list").html('');
		}
	}
	function AddItem(){
		var data = jQuery("#new_data").attr("value");
		var label = jQuery("#new_label").attr("value");
		if(!data){
			if(!confirm("The data field is empty, click on accept to add")) return;
		}
		if(!label){
			if(!confirm("The label field is empty, click on accept to add")) return;
		}
		var newOption = "<option value='"+data+"'>"+label+"</option>"
		var value = jQuery("#list").append(newOption);
		jQuery("#new_data").attr("value", ""); jQuery("#new_label").attr("value", "");
	}
    // Get Info
        function GetInfo(value){
            var info = {};            
    		// add Data
        		if(value == "config_table"){
        			var table_name = cuppa.trim(jQuery("#select_table_name").val());
        			var data_column = cuppa.trim(jQuery("#select_data_coumn").val());
        			var label_column = cuppa.trim(jQuery("#select_label_coumn").val());
                    info.data = {};
        			if(!table_name){
        				alert("Please, specify table name"); return;
        			}else if(!data_column){
        				alert("Please, specify data column"); return;
        			}else if(!label_column){
        				alert("Please, specify label column"); return;
        			}
                    info.data.table_name = table_name;
                    info.data.data_column = data_column;
                    info.data.label_column = label_column;
                    info.data.where_column = jQuery("#select_where_coumn").val();
                    info.data.nested_column = jQuery("#select_nested_column").val();
                    info.data.parent_column = cuppa.trim(jQuery("#select_parent_column").val());
                    info.data.init_id = jQuery("#init_id").val();
                    info.data.dinamic_update_field = cuppa.trim(jQuery("#dinamic_update_field").val());
                    info.data.dinamic_update_column = cuppa.trim(jQuery("#dinamic_update_column").val());
                }else{
        			var option_values =jQuery("#list").find("option")
        			if(!option_values.length){ alert("Plase add valid items to the list"); return;}
                    info.data = new Array();
        			for(var i = 0; i < option_values.length; i++){ 
        				var value = jQuery(option_values[i]).attr("value");
        				var label = jQuery(option_values[i]).text();
                        info.data.push([value, label]);
        			}
        		}
            // Extra params
                info.extraParams = {}
                info.extraParams.add_custom_item = (jQuery(".add_custom_item").attr("checked")) ? 1 : 0;
                info.extraParams.custom_data = jQuery(".custom_data").val();
                info.extraParams.custom_label = jQuery(".custom_label").val();
                info.extraParams.no_translate = jQuery(".no_translate").prop("checked");
                info.tooltip = jQuery('.panel_extra_params #tooltip').val();
                info.extraParams.width = jQuery('#width').val();
            // Json / Base64 encode
                info = jQuery.toJSON(info);
                info = jQuery.base64Encode(info);
            // add Info
        		var field_name = "#" + "<?php echo $_REQUEST["field"] ?>" + "_config";
        		jQuery(field_name).attr("value", info);
        		CloseDefaultAlert();
    	}
    // Set Default Info
    	function SetDefault(){
    		var field_name = "#" + "<?php echo $_REQUEST["field"] ?>" + "_config";
            // Decode Info
                var obj = cuppa.jsonDecode(jQuery(field_name).val());
            // Set Info
        		if(!obj){ShowConfigPanel(1); return;}
        		if(obj.data.constructor === Array){
        			var info = "";
        			for(var i = 0; i<obj.data.length; i++){
        				 info += "<option value='"+ obj.data[i][0] +"'>"+obj.data[i][1]+"</option>";
        			}
        			jQuery("#list").append(info);
                    ShowConfigPanel(2);
        		}else{
                    ShowConfigPanel(1);
        			jQuery("#select_table_name").val(obj.data.table_name);
        			jQuery("#select_data_coumn").val(obj.data.data_column);
        			jQuery("#select_label_coumn").val(obj.data.label_column);
                    jQuery("#select_where_coumn").val(obj.data.where_column);
                    jQuery("#select_nested_column").val(obj.data.nested_column);
                    jQuery("#select_parent_column").val(obj.data.parent_column);
                    jQuery("#init_id").val(obj.data.init_id);
                    jQuery("#dinamic_update_field").val(obj.data.dinamic_update_field);
                    jQuery("#dinamic_update_column").val(obj.data.dinamic_update_column);
                    
        		}
                jQuery("#select_label_coumn").attr("value", obj.data.label_column);
            // Extra Params
                if(obj.extraParams.add_custom_item == "1") jQuery(".add_custom_item").attr("checked", "checked");
                jQuery(".custom_data").val(obj.extraParams.custom_data);
                jQuery(".custom_label").val(obj.extraParams.custom_label);
                jQuery('.panel_extra_params #tooltip').val(obj.tooltip);
                jQuery('#width').val(obj.extraParams.width);
                jQuery(".no_translate").prop("checked",obj.extraParams.no_translate);
    	}; SetDefault();
     cuppa.tooltip();        
</script>
<style>
	.alert_config_field td{
		padding:3px;
		vertical-align:central;
		text-align:left;
	}
</style>
<div class="section" style="margin: 0px 0px 10px;"><div></div><span><?php echo @$language->general_configuration ?></span></div>
<div style="padding: 0px 20px;">   	
        <!-- Tabs -->
        <div style="text-align:center;">
            <input type="radio" name="typeSelect" id="table" value="typeSelect" onchange="SelectType()" />
            <label for="table" style="margin-right:50px;"><?php echo @$language->table ?></label>
            <input type="radio" name="typeSelect" id="personal" value="typeSelect" onchange="SelectType()" />
            <label for="personal"><?php echo @$language->personal_info ?></label>
        </div>
    <!-- -->
    <div class="separator_dotted" id="separator_config" style="margin-bottom:15px; margin-top:15px; visibility:hidden;"></div>
    <!-- Config table -->
    	<div id="config_table" class="config_table" style="width:100%; display:block;">
            <table style="width:100%">
                <tr>
                    <td style="width:180px;"><?php echo @$language->table_name ?>:</td>
                    <td><input type='text' name='select_table_name' id='select_table_name' style="width: 90%" /></td>
                </tr>
                <tr>
                    <td><?php echo @$language->data_column ?>:</td>
                    <td><input type='text' name='select_data_coumn' id='select_data_coumn' style="width: 90%" /></td>
                </tr>
                <tr>
                    <td><?php echo @$language->label_column ?>:</td>
                    <td><input type='text' name='select_label_coumn' id='select_label_coumn' style="width: 90%" /></td>
                </tr>
                <tr>
                    <td>
                        <?php echo @$language->condition ?>:
                        <img title="<?php echo @$language->condition_message ?>" class="tooltip" src="templates/default/images/template/icon_help_12.png" />
                    </td>
                    <td><input type='text' name='select_where_coumn' id='select_where_coumn' style="width: 90%" /></td>
                </tr>
                <tr>
                    <td style="vertical-align: top; padding-top: 13px;">
                        <?php echo @$language->show_nested_information ?>: 
                        <img title="<?php echo @$language->show_nested_information_message ?>" class="tooltip" src="templates/default/images/template/icon_help_12.png" />
                    </td>
                    <td>
                        <table style="width: 100%;">
                            <tr>
                                <td><?php echo @$language->column ?>: </td>
                                <td><input type='text' name='select_nested_column' id='select_nested_column' /></td>
                            </tr>
                            <tr>
                                <td><?php echo @$language->parent ?>: </td>
                                <td><input type='text' name='select_parent_column' id='select_parent_column'  /></td>
                            </tr>
                            <tr>
                                <td><?php echo @$language->init_id ?> </td>
                                <td><input type='text' name='init_id' id='init_id'  /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; padding-top: 13px;">
                        <?php echo @$language->dinamic_update_content ?>: 
                        <img title="<?php echo @$language->load_ajax_message ?>" class="tooltip" src="templates/default/images/template/icon_help_12.png" />
                    </td>
                    <td>
                        <table style="width: 100%;">
                            <tr>
                                <td><?php echo $cuppa->language->getValue("update field", $language) ?></td>
                                <td><input type='text' name='dinamic_update_field' id='dinamic_update_field' /></td>
                            </tr>
                            <tr>
                                <td><?php echo @$language->filter_column ?>: </td>
                                <td><input type='text' name='dinamic_update_column' id='dinamic_update_column'  /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><input class='button_form' type='button' value='<?php echo @$language->accept ?>' onclick='GetInfo("config_table")' /></td>
                </tr>
            </table>
        </div>
     <!-- -->
    <!-- Config personal data -->
    	<div id="config_personal_data" class="config_personal_data" style="width:100%; display:none;">
            <table style="width:100%">
                <tr>
                    <td style="width:100px;"><?php echo @$language->add ?>:</td>
                    <td>
                        <table style="width:100%">
                            <tr>
                                <td><?php echo @$language->data ?> <input type='text' name='new_data' id='new_data' style="width:40px;" /></td>
                                <td><?php echo @$language->label ?> <input type='text' name='new_label' id='new_label' style="width:90px;" /></td>
                                <td><input class="button_form" type="button" value="<?php echo @$language->add ?>" onclick="AddItem()" /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
    		<table style="margin-top: 10px;">
                <tr>
                    <td style="width:100px; vertical-align:top;"><?php echo @$language->list_values ?>:</td>
                    <td>
                        <select name="list" id="list" size="4" style="width:91%"></select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                    	<input style="margin-top: 3px;" class="button_form" type="button" value="<?php echo @$language->delete_selected_item ?>" onclick="DeleteItem()" />
                    	<input style="margin-top: 3px;" class="button_form" type="button" value="<?php echo @$language->delete_all_items ?>" onclick="DeleteItem(true)" />
                    </td>
                </tr>
            </table>
            <div style="height: 10px;"></div>
            <table style="width:100%">
            	<tr>
                    <td style="width:100px;"></td>
    				<td><input class='button_form' type='button' value='<?php echo @$language->accept ?>' onclick='GetInfo("config_personal_data")' /></td>
                </tr>
            </table>
        </div>
    <!-- -->
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
            <tr>
                <td style="width: 150px;" ><?php echo @$language->add_custom_item_to_start ?></td>                         
                <td ><input type="checkbox" class="add_custom_item" id="add_custom_item" name="add_custom_item" /></td>
            </tr>
            <tr>
                <td style="padding-left: 15px;"><?php echo @$language->data ?></td>
                <td><input type="text" id="custom_data" class="custom_data" name="custom_data" value="" /></td>
            </tr>
            <tr>
                <td style="padding-left: 15px;"><?php echo @$language->label ?></td>
                <td><input type="text" id="custom_label" class="custom_label" name="custom_label" value="" /></td>
            </tr>
            <tr>
                <td style="width: 150px;" ><?php echo @$language->no_translate_content ?></td>                         
                <td ><input type="checkbox" class="no_translate" id="no_translate" name="no_translate" /></td>
            </tr>
        </table> 
    </div>
<!-- -->       