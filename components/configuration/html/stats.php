<table>
    <tr>
        <td style="width:200px;"><?php echo @$language->google_client_id ?></td>
        <td><input type="text" title=" " name="ga_client_id" value="<?php echo @$cuppa->configuration->ga_client_id ?>" /></td>
    </tr>    
    <tr>
        <td>
            <?php echo @$language->analytics_view ?>
            <img class="tooltip" style="margin-left: 3px" src="templates/default/images/template/icon_help_12.png" title="<?php echo @$language->analytics_view_help ?>" />
        </td>
        <td><input type="text" title=" " name="ga_view" value="<?php echo @$cuppa->configuration->ga_view ?>" /></td>
    </tr>  
    <tr>
        <td style="vertical-align: top;">
            <?php echo @$language->tracking_codes ?>
            <img class="tooltip" style="margin-left: 3px" src="templates/default/images/template/icon_help_12.png" title="<?php echo @$language->tracking_codes_help ?>" />
        </td>
        <td>
            <textarea name="tracking_codes" style="width: 100%; height: 200px; resize: vertical;"><?php echo base64_decode(@$cuppa->configuration->tracking_codes) ?></textarea> 
        </td>
    </tr>      
</table>