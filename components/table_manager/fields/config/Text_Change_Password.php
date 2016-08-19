<?php 
    @session_start();
    include_once("../classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load();
    $params = json_decode(base64_decode($_POST["params"]));
?>
<style>
    .alert_config_field{
        width: 500px;
        height: 200px;
        margin-left: -250px;
        margin-top: -100px;
    }
	.text td{
		padding:3px;
		vertical-align:central;
		text-align:left;
	}
</style>
<script type="text/javascript">
    //++ init
            ChangePassword.init = function(){
                jQuery("*").blur();
            }; cuppa.addEventListener("ready",  ChangePassword.init, document, "ChangePassword");
    //--
    // Get Info
    	function ChangePassword(){
            var current_password = cuppa.trim(jQuery("#current_password").val());
            var new_password = cuppa.trim(jQuery("#new_password").val());
            var verification_password = cuppa.trim(jQuery("#verification_password").val());
            if(!new_password){
                cuppa.blockade({duration:0.2, opacity:0.2, autoDeleteContent:".cuppa_alert"});
                cuppa.instance({url:"js/cuppa/cuppa_html/alert.html", data:{title:"<?php echo @$language->error ?>", message:"<?php echo $language->password_new_write ?>"}, add:"body"});
                return;
            }else if(new_password != verification_password){
                cuppa.blockade({duration:0.2, opacity:0.2, autoDeleteContent:".cuppa_alert"});
                cuppa.instance({url:"js/cuppa/cuppa_html/alert.html", data:{title:"<?php echo @$language->error ?>", message:"<?php echo $language->password_verification_incorrect ?>"}, add:"body"});                   
                return;
            }
            //++ Get data
                var data = {}
                    data.current_password_encode = jQuery("#current_password_encode").val();
                    data.current_password = current_password;
                    data.new_password = new_password;
                    data.new_password_encode = "<?php echo @$params->encode ?>";
                    data.table = "<?php echo @$params->table ?>";
                    data.column = "<?php echo $_POST["field"] ?>";
                    data.column = data.column.replace(/\_field$/,'');
                    data.primary_key_field = "<?php echo @$params->primary_key_field ?>";
                    data.id = "<?php echo @$params->id ?>";
                    data["function"] = "changePassword";
            //--
            cuppa.blockadeScreen();
            jQuery.ajax({url:"classes/ajax/Functions.php", type:"POST", data:data, success:ChangePassword_Result});
            function ChangePassword_Result(result){
                cuppa.blockadeScreen(false);
                if(result == 0){
                    cuppa.blockade({duration:0.2, opacity:0.2, autoDeleteContent:".cuppa_alert"});
                    cuppa.instance({url:"js/cuppa/cuppa_html/alert.html", data:{title:"<?php echo @$language->error ?>", message:"<?php echo $language->password_error_message ?>"}, add:"body"});                   
                    return;
                }else if(result == -1){ 
                    cuppa.blockade({duration:0.2, opacity:0.2, autoDeleteContent:".cuppa_alert"});
                    cuppa.instance({url:"js/cuppa/cuppa_html/alert.html", data:{title:"<?php echo @$language->error ?>", message:"<?php echo $language->password_invalid_current_password ?>"}, add:"body"});
                    return;
                }else{ 
                    cuppa.blockade({duration:0.2, opacity:0.2, autoDeleteContent:".cuppa_alert"});
                    cuppa.instance({url:"js/cuppa/cuppa_html/alert.html", data:{title:"<?php echo @$language->message ?>", message:"<?php echo $language->password_changed_correctly ?>"}, add:"body"});
                }
                CloseDefaultAlert();
            }   
    	}
    // Set Default
        cuppa.tooltip();
        jQuery("#current_password_encode").val("<?php echo @$cuppa->configuration->global_encode ?>");
        cuppa.selectStyle("#change_password_form select");
</script>
<div class="text">
    <form id="change_password_form" name="change_password_form">
        <table style="margin: 0 auto;">
            <tr>
                <td style="text-align: right;"><label for="current_password"><?php echo @$language->current_password ?>: </label></td>
                <td>
                    <input type="password" name="current_password" id="current_password" />
                    <img class="tooltip" style="margin:0px 3px" src="templates/default/images/template/icon_help_12.png" title="<?php echo @$language->password_current_encode_message ?>" />
                    <select id="current_password_encode" name="current_password_encode" style="width: 85px;">
                        <option value="none"><?php echo @$language->none ?></option>
                        <option value="md5">md5</option>
                        <option value="sha1">sha1</option>
                        <option value="sha1Salt">sha1Salt</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;"><label for="new_password"><?php echo @$language->new_password ?>: </label></td>
                <td>
                    <input type="password" name="new_password" id="new_password" />
                </td>
            </tr>
            <tr>
                <td style="text-align: right;"><label for="verification_password"><?php echo @$language->confirm_password ?>: </label></td>
                <td><input type="password" name="verification_password" id="verification_password" /></td>
            </tr>
            <tr>
                <td></td>
                <td><input style="width: 200px;" type="button" onclick="ChangePassword()" value="<?php echo @$language->update_password ?>" class="button_form" /></td>
            </tr>
        </table>
    </form>
</div>