<?php 
    @session_start();
    include_once("../classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load();
?>
<script type="text/javascript">
    /// Get Info
    	function GetInfo(){
            var info = {};
                info.multiSelect = $('.panel_extra_params input[name=multiSelect]').is(":checked");
                info.height = $('.panel_extra_params input[name=height]').val();
                info.width = $('.panel_extra_params input[name=width]').val();
                info.tooltip = $('.panel_extra_params input[name=tooltip]').val();
           	var config_field = "#" + "<?php echo $_REQUEST["field"] ?>" + "_config";
            $(config_field).val(cuppa.jsonEncode(info));
            CloseDefaultAlert();
    	}
	// Set Default Info
	   function SetDefault(){
	       var config_field = "#" + "<?php echo $_REQUEST["field"] ?>" + "_config";
           var info = cuppa.jsonDecode($(config_field).val());
           if(!info) return;
           $('.panel_extra_params input[name=multiSelect]').prop("checked", info.multiSelect);
           $('.panel_extra_params input[name=height]').val(info.height);
           $('.panel_extra_params input[name=width]').val(info.width);
           $('.panel_extra_params input[name=tooltip1]').val(info.tooltip);
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
<div class="text">
    <form id="text_form" name="text_form">
        <div class="section" style="margin: 0px 0px 10px;"><div></div><span><?php echo @$language->general_configuration ?></span></div>
        <div>
            <table class="panel_extra_params" >
                <tr>
                    <td style="width:200px;"><?php echo @$language->multiselect ?>:</td>
                    <td>
                        <input type="checkbox" class="multiSelect" name="multiSelect" />
                    </td>
                </tr>
                <tr>
                    <td ><?php echo @$language->height ?>:</td>
                    <td>
                        <input type="text" class="height" name="height" value="1" />
                    </td>
                </tr>
                <tr>
                    <td><?php echo @$language->width ?></td>
                    <td><input type="text" class="width" name="width" value="" /></td>
                </tr>
                <tr>
                    <td><?php echo @$language->tooltip ?></td>
                    <td>
                        <input type="text" class="tooltip" name="tooltip" value="" /> 
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input class='button_form' type='button' value='<?php echo @$language->accept ?>' onclick='GetInfo("config_personal_data")' />
                    </td>
                </tr>
            </table>
        </div>
    </form>
</div>