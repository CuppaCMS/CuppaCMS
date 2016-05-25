<?php
    @session_start();
    include_once("../classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load();
?>
<script type="text/javascript">
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
        function GetInfo(){
            var info = {};
            var option_values =jQuery("#list").find("option");
    		if(!option_values.length){ alert("Plase add valid items to the list"); return;}
            // Get Array data
                var data = new Array();
                for(var i = 0; i < option_values.length; i++){ 
        			var value = jQuery(option_values[i]).attr("value");
        			var label = jQuery(option_values[i]).text();
                    data.push(new Array(value, label));
        		}
                info.data = data;
            // Get Extra data
                info.extraParams = {}
                info.tooltip = jQuery('.panel_extra_params #tooltip').val();
                info.extraParams.no_translate = jQuery(".no_translate").prop("checked");
            // Encode info
                info = jQuery.base64Encode(jQuery.toJSON(info));
            // update info
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
        		if(!obj) return;
                var data = obj.data
    			var info = "";
    			for(var i = 0; i<data.length; i++){
    				 info += "<option value='"+ data[i][0] +"'>"+data[i][1]+"</option>";
    			}
        		jQuery("#list").append(info);
            // Extra Params
                jQuery('.panel_extra_params #tooltip').val(obj.tooltip);
                jQuery(".panel_extra_params .no_translate").prop("checked",obj.extraParams.no_translate);
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
    <!-- Config personal data -->
        <div class="section" style="margin: 0px 0px 10px;"><div></div><span><?php echo @$language->general_configuration ?></span></div>
    	<div id="config_personal_data" class="config_personal_data" style="padding: 0px 20px;">
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
            <div class="separator_dotted" style="margin-bottom:15px; margin-top:15px; visibility:visible;"></div>
            <table style="width:100%">
            	<tr>
                    <td style="width:100px;"></td>
    				<td><input class='button_form' type='button' value='<?php echo @$language->accept ?>' onclick='GetInfo()' /></td>
                </tr>
            </table>
        </div>
    <!-- -->
    <!-- Extra Params -->
        <div class="section" style="margin: 10px 0px;"><div></div><span><?php echo @$language->extra_params ?></span></div>
        <div style="padding-left: 20px;">
            <table class="panel_extra_params" style="width: 100%" >
                <tr>
                    <td><?php echo @$language->tooltip ?></td>
                    <td>
                        <input type="text" class="tooltip" id="tooltip" name="tooltip" value="" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 150px;" ><?php echo @$language->no_translate_content ?></td>                         
                    <td ><input type="checkbox" class="no_translate" id="no_translate" name="no_translate" /></td>
                </tr>
            </table>
        </div>
    <!-- -->
</div>