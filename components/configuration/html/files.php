<table>
   <tr>
        <td style="width:230px;" ><span class="asterisk">*</span><?php echo @$language->allowed_extensions ?></td>
        <td><input style="width: 100%;" type="text" class="required" title=" " name="allowed_extensions" value="<?php echo $cuppa->configuration->allowed_extensions ?>" autocomplete="0" /></td>
    </tr>
    <tr>
        <td><span class="asterisk">*</span><?php echo @$language->upload_default_path ?></td>
        <td><input type="text" class="required" title=" " name="upload_default_path" value="<?php echo $cuppa->configuration->upload_default_path ?>" autocomplete="0" /></td>
    </tr>
    <tr>
        <td>
            <span class="asterisk">*</span><?php echo @$language->maximum_file_size ?>
            <img class="tooltip" style="margin-left: 3px" src="templates/default/images/template/icon_help_12.png" title="<?php echo @$language->comment_maximum_file_size ?>" />
        </td>
        <td><input type="text" class="required" title=" " name="maximum_file_size" value="<?php echo $cuppa->configuration->maximum_file_size ?>" autocomplete="0" /></td>
    </tr>
    <tr>
        <td>
            <span class="asterisk">*</span><?php echo @$language->csv_column_separator ?>
            <img class="tooltip" style="margin-left: 3px" src="templates/default/images/template/icon_help_12.png" title="<?php echo @$language->csv_column_separator_message ?>" />
        </td>
        <td><input type="text" class="required" title=" " name="csv_column_separator" value="<?php echo $cuppa->configuration->csv_column_separator ?>" autocomplete="0" /></td>
    </tr> 
    <tr>
        <td>
            Tinify Key
            <img class="tooltip" style="margin-left: 3px" src="templates/default/images/template/icon_help_12.png" title="<?php echo @$language->tinify_key_message ?>" />
        </td>
        <td><input type="text" title=" " name="tinify_key" value="<?php echo @$cuppa->configuration->tinify_key ?>" autocomplete="0" /></td>
    </tr>             
</table>