<?php
	// configuration_writeable
		@$configuration_writeable = "<font style='color:#CC0000'>No</font>";
		if(@is_writable('../Configuration.php')) $configuration_writeable = "<font style='color:#46882B'>Yes</font>";
	// PHP Version
		@$phpVersion = "<font style='color:#CC0000'>No</font>";
		if((float) @phpversion() >= 5.3) $phpVersion = "<font style='color:#46882B'>Yes</font>";
	// MySQL Version
		$mySQL = "<font style='color:#CC0000'>No</font>";
		if(@extension_loaded("mysqli")) $mySQL = "<font style='color:#46882B'>Yes</font>";
	// Json support
		@$json_support = "<font style='color:#CC0000'>No</font>";
		if(@extension_loaded("json")) $json_support = "<font style='color:#46882B'>Yes</font>";
	// File Upload Activate
		@$file_uploads = "<font style='color:#CC0000'>Off</font>";
		if(@ini_get('file_uploads')) $file_uploads = "<font style='color:#46882B'>On</font>";
	// Display Errors
		@$display_errors = "<font style='color:#CC0000'>On</font>";
		if(!@ini_get('display_errors')) $display_errors = "<font style='color:#46882B'>Off</font>";
	
?>
<style>
     table th{ white-space: nowrap !important; }
    table td{ padding: 5px !important; font-size: 12px; border: 0px !important; }
</style>
<form method="post" class="form" id="form" name="form" >
    <div class="buttons" style="position:absolute; top:20px; right: 20px; z-index: 2;" >
        <input class="button_form" type="button" value="Next" onclick="SubmitForm('data_base_configuration')" />
    </div>
    <div class="section" style="margin: 10px 0px;"><div></div><span>Check important info</span></div>
    <div class="info_message">
        If any of these items is not supported then please take actions to correct them. Failure to do so could lead to your Only-Back installation not functioning correctly
    </div>
    <div style="height:20px;"></div>
    <table class="table_info">
        <tr>
            <th style="width:250px;">Requiered</th>
            <th>Supported</td>
        </tr>
        <tr>
            <td>Configuration.php Writeable</td>
            <td><?php echo $configuration_writeable ?></td>
        </tr>
        <tr>
            <td>PHP Version >= 5.3</td>
            <td><?php echo $phpVersion ?></td>
        </tr>
        <tr>
            <td>MySQL Support</td>
            <td><?php echo $mySQL ?></td>
        </tr>
        <tr>
            <td>JSON Support</td>
            <td><?php echo $json_support ?></td>
        </tr> 
    </table>
    <table class="table_info" style="margin-top: 20px;">
        <tr>
            <th style="width:250px;">Apache configuration</td>
            <th style="width:180px;" >Recomended</th>
            <th>Now</th>
        </tr>
        <tr>
            <td >File Uploads</td>
            <td ><font style='color:#46882B'>On</font></td>
            <td><?php echo $file_uploads ?></td>
        </tr>
        <tr>
            <td style="font-weight:normal">Display Errors</td>
            <td><font style='color:#46882B'>Off</font></td>
            <td><?php echo $display_errors ?></td>
        </tr>
    </table>
    <input type="hidden" name="view" id="view" value="data_base_configuration" />
</form>
<script>
	function SubmitForm(task){
		jQuery('#view').attr('value',task);
		jQuery('#form').submit();
	}
</script>