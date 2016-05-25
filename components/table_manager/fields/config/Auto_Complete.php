<?php
    @session_start();
    include_once("../classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load();
?>
<script type="text/javascript">
	function SelectType(){
		var type = 1;
		if(jQuery("#personal").attr("checked")){ type = 2; }
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
		var data = jQuery("#new_label").attr("value");
		var label = jQuery("#new_label").attr("value");
		if(!label){
			if(!alert("Erorr. The field is empty")) return;
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
        			var table_name = jQuery("#select_table_name").attr("value");
        			var column = jQuery("#select_column").attr("value");
                    info.data = {};
        			if(!table_name){
        				alert("Please, specify table name"); return;
        			}else if(!column){
        				alert("Please, specify label column"); return;
        			}
                    info.data.table_name = table_name;
                    info.data.column = column;
                    info.data.where_column = jQuery("#select_where_coumn").val();
                }else{
        			var option_values =jQuery("#list").find("option");
        			if(!option_values.length){ alert("Plase add valid items to the list"); return;}
                    info.data = new Array();
        			for(var i = 0; i < option_values.length; i++){ 
                        info.data.push(jQuery(option_values[i]).val());
        			}
        		}
            // Extra params
                info.extraParams = {}
                info.extraParams.multi_values = jQuery(".multi_values").is(":checked");
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
        				 info += "<option value='"+ obj.data[i] +"'>"+obj.data[i]+"</option>";
        			}
        			jQuery("#list").append(info);
                    ShowConfigPanel(2);
        		}else{
                    ShowConfigPanel(1);
        			jQuery("#select_table_name").attr("value", obj.data.table_name);
        			jQuery("#select_column").attr("value", obj.data.column);
                    jQuery("#select_where_coumn").val(obj.data.where_column);
        		}
                jQuery("#select_column").attr("value", obj.data.column);
            // Extra Params
                jQuery(".multi_values").prop("checked", obj.extraParams.multi_values);
                jQuery('.panel_extra_params #tooltip').val(obj.tooltip);
                jQuery('#width').val(obj.extraParams.width);
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
<div style="text-align:center;">
    <input type="radio" name="typeSelect" id="table" value="typeSelect" onchange="SelectType()" />
    <label for="table" style="margin-right:50px;"><?php echo @$language->table ?></label>
    <input type="radio" name="typeSelect" id="personal" value="typeSelect" onchange="SelectType()" />
    <label for="personal"><?php echo @$language->personal_info ?></label>
</div>
<div class="separator" id="separator_config" style="margin-bottom:15px; margin-top:15px; visibility:hidden;"></div>
<div style="overflow: auto;">
    <!-- Config table -->
    	<div id="config_table" class="config_table" style="width:100%; display:block;">
            <table style="width:100%">
                <tr>
                    <td style="width:125px;"><?php echo @$language->table_name ?>:</td>
                    <td><input type='text' name='select_table_name' id='select_table_name' style="width: 90%" /></td>
                </tr>
                <tr>
                    <td><?php echo @$language->column ?>:</td>
                    <td><input type='text' name='select_column' id='select_column' style="width: 90%" /></td>
                </tr>
                <tr>
                    <td>
                        <?php echo @$language->condition ?>:
                        <img title="<?php echo @$language->condition_message ?>" class="tooltip" src="templates/default/images/template/icon_help_12.png"  />
                    </td>
                    <td><input type='text' name='select_where_coumn' id='select_where_coumn' style="width: 90%" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input class='button_form' type='button' value='<?php echo @$language->accept ?>' onclick='GetInfo("config_table")' /></td>
                </tr>
            </table>
        </div>
    <!-- Config personal data -->
    	<div id="config_personal_data" class="config_personal_data" style="width:100%; display:none;">
            <table style="width:100%">
                <tr>
                    <td style="width:100px;"><?php echo @$language->add ?>:</td>
                    <td>
                        <table>
                            <tr>
                                <td><input type='text' name='new_label' id='new_label' style="width:200px;" /></td>
                                <td><input class="button_form" type="button" value="<?php echo @$language->add ?>" onclick="AddItem()" /></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
    		<div class="separator" id="separator_config" style="margin-bottom:15px; margin-top:15px; visibility:visible;"></div>
            <table style="width:100%">
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
    <!-- Extra Params -->
        <div class="separator" id="separator_config" style=" margin-top: 15px; margin-bottom: 5px;"></div>
        <div style="height: 10px;"></div>
        <div style="color: #025A8D; font-weight: bold;"><?php echo @$language->extra_params ?></div>
        <table class="panel_extra_params" >
            <tr >
                <td style="width: 150px;" ><?php echo @$language->multi_values ?>:</td>                         
                <td ><input type="checkbox" class="multi_values" id="multi_values" name="multi_values" /></td>
            </tr>
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