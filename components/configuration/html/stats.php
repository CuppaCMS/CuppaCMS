<table>
    <tr>
        <td style="vertical-align: top; width:200px;">
            <?php echo @$language->tracking_codes ?>
            <img class="tooltip" style="margin-left: 3px" src="templates/default/images/template/icon_help_12.png" title="<?php echo @$language->tracking_codes_help ?>" />
        </td>
        <td>
            <textarea name="tracking_codes" style="width: 100%; height: 350px; resize: vertical;"><?php echo base64_decode(@$cuppa->configuration->tracking_codes) ?></textarea> 
        </td>
    </tr>      
</table>