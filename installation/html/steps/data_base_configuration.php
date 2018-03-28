<form method="post" class="form" id="form" name="form" >
    <div class="buttons" style="position:absolute; top:20px; right: 20px; z-index: 2;" >
        <input class="button_form" type="button" value="Back" onclick="SubmitForm('check_info')" />
        <input class="button_form" type="button" value="Next" onclick="SubmitForm('installation_finished')" />
    </div>
    <div class="section" style="margin: 10px 0px;"><div></div><span>Database and User Administrator Setting</span></div>
    <div class="info_message">
		If you get error menssage check your host, database name, user and password are correct.
    </div>
    <div class="separator_dashed"  style="margin: 20px 0px 15px;"></div>
    <h1 style="font-size:18px;">Database</h1>
    <table style="margin-top: 10px;">
        <tr>
            <td style="width:200px;">
                Host
                &nbsp;<img title="Default: localhost" class="tooltip" src="../templates/default/images/template/icon_help_12.png" />
            </td>
            <td>
                <input class="text_field required" id="host" name="host" value="localhost" /> 
            </td>
        </tr>
        <tr>
            <td >
                Database Name
                &nbsp;<img title="The name of databas you want to run Cuppa CMS in" class="tooltip" src="../templates/default/images/template/icon_help_12.png" />
            </td>
            <td>
                <input class="text_field required" id="db" name="db" value="" />
            </td>
        </tr>
        <tr>
            <td >User</td>
            <td><input class="text_field required" id="user" name="user" value="root" /></td>
        </tr>
        <tr>
            <td >Password</td>
            <td><input class="text_field" type="password" id="password" name="password" value="" /></td>
        </tr>
        <tr>
            <td >
                Database Tables Prefix
                &nbsp;<img title="Default: cu_" class="tooltip" src="../templates/default/images/template/icon_help_12.png" />
            </td>
            <td>
                <input class="text_field" id="table_prefix" name="table_prefix" value="cu_" />
                <span style="color:#999;"></span>
            </td>
        </tr>
    </table>
    <div class="separator_dashed"  style="margin: 20px 0px 15px;"></div>
    <h1 style="font-size:18px; ">Administrator account</h1>
    <table style="margin-top: 10px;">
        <tr>
            <td style="width:200px;">Name</td>
            <td><input class="text_field required" id="name" name="name" value="Administrator" /></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input class="text_field required email" id="email" name="email" value="" /></td>
        </tr>
        <tr>
            <td>
                Username
                &nbsp;<img title="Default: admin" class="tooltip" src="../templates/default/images/template/icon_help_12.png" />
            </td>
            <td>
                <input class="text_field required" title=" " id="username" name="username" value="admin" />
            </td>
        </tr>
        <tr>
            <td>
                Password
                &nbsp;<img title="Default: admin" class="tooltip" src="../templates/default/images/template/icon_help_12.png" />
            </td>
            <td>
                <input type="password" class="text_field required" title=" " id="username_password" name="username_password" value="admin" />
            </td>
        </tr>
	</table>
    <input type="hidden" name="view" id="view" value="installation_finished" />
</form>
<script>
	function SubmitForm(task){
		jQuery('#view').attr('value',task);
		if(task == "check_info"){ document.forms["form"].submit(); return; }
		$('#form').submit();
	}
    jQuery(document).ready(function(){ cuppa.tooltip(); jQuery("#form").validate(); });
</script>