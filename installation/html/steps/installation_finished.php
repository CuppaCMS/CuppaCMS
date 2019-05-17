<?php
    require_once("../classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $global_encode_salt = $cuppa->utils->getRandomString(32);
	$installation_error = true;
//++ Save File Configuration
$contentToSave = 
'<?php 
	class Configuration{
        public $host = "'.trim($cuppa->POST("host")).'";
		public $db = "'.trim($cuppa->POST("db")).'";
		public $user = "'.trim($cuppa->POST("user")).'";
		public $password = "'.trim($cuppa->POST("password")).'";
		public $administrator_template = "default";
		public $list_limit = "25";
		public $font_list = "Raleway";
		public $secure_login = "0";
		public $secure_login_value = "";
		public $secure_login_redirect = "";
		public $language_default = "en";
		public $country_default = "us";
		public $global_encode = "sha1Salt";
		public $global_encode_salt = "'.$global_encode_salt.'";
		public $ssl = "0";
		public $lateral_menu = "expanded";
		public $base_url = "";
		public $auto_logout_time = "30";
		public $redirect_to = "false";
		public $table_prefix = "'.trim($cuppa->POST("table_prefix")).'";
		public $allowed_extensions = "*.gif; *.jpg; *.jpeg; *.pdf; *.ico; *.png; *.svg";
		public $upload_default_path = "upload_files";
		public $maximum_file_size = "5242880";
		public $csv_column_separator = ",";
		public $tinify_key = "";
		public $email_outgoing = "";
		public $forward = "";
		public $smtp = "0";
		public $email_host = "";
		public $email_port = "";
		public $email_password = "";
		public $smtp_security = "";
		public $code = "";
	} 
?>';
//--
        // Create file Configuration.php
    		$fp = @fopen("../Configuration.php","w");
    		$edit_configuration = "<font style='color:#CC0000'>No</font>";
    		if($fp){
    			fwrite($fp, $contentToSave);
    			fclose($fp);
    			$edit_configuration = "<font style='color:#46882B'>Yes</font>";
    			$installation_error = false;
    		}
		// DataBase
            $db = new DataBase($cuppa->POST("db"), $cuppa->POST("host"), $cuppa->POST("user"), $cuppa->POST("password"));
			$sql = file_get_contents('script.sql');
			$sql = str_replace("cu_", $cuppa->POST("table_prefix"), $sql);
			$data_base_state = "<font style='color:#46882B'>Yes</font>";
            $result = $db->sqlMultiQuery($sql);
		// Create Administrator's count
			$data = array();
			$data["id"] = "'1'";
			$data["name"] = "'".$cuppa->POST("name")."'";
			$data["email"] = "'".$cuppa->POST("email")."'";
			$data["username"] = "'".$cuppa->POST("username")."'";
			$data["password"] = "'".$cuppa->utils->sha1Salt($cuppa->POST("username_password"), $global_encode_salt)."'";
            $data["image"] = "'media/user_images/default.jpg'";
			$data["enabled"] = "'1'";
			$data["user_group_id"] = "'1'";
            $data["date_registered"] = "NOW()";
			$result = $db->insert($cuppa->POST("table_prefix")."users", $data);
			$administrator_user = "<font style='color:#CC0000'>No</font>";
			if($result){
				$administrator_user = "<font style='color:#46882B'>Yes</font>";
			}else{
				$installation_error = true;
			}
?>
<form method="post" class="form" id="form" name="form" >
    <div class="buttons" style="position:absolute; top:20px; right: 20px; z-index: 2;" >
        <input class="button_form" type="button" value="Back" onclick="SubmitForm('data_base_configuration')"/>
        <?php if(!$installation_error){ ?>
	        <input class="button_form" type="button" value="delete installation folder and go to administrator" onclick="SubmitForm('delete_installation_folder')" />
		<?php } ?>
        
    </div>
    <div class="section" style="margin: 10px 0px;"><div></div><span><?php if($installation_error) echo "Installation Error"; else echo "Installation Complete" ?></span></div>
    <table class="form">
    	<tr>
            <td style="width:200px;">Edit file [Configuration.php]</td>
            <td><?php echo $edit_configuration ?></td>
        </tr>
        <tr>
            <td>Create tables</td>
            <td><?php echo $data_base_state ?></td>
        </tr>
        <tr>
            <td>Administrator's user created</td>
            <td><?php echo $administrator_user ?></td>
        </tr>
    </table>
    <div style="height:20px;"></div>
    <input type="hidden" name="view" id="view" value="go_to_administration_page" />
</form>
<script>
	function SubmitForm(task){
		jQuery('#view').attr('value',task);
		$('#form').submit();
	}
</script>