<table>
    <tr>
        <td style="vertical-align: top; width:200px;">
            <?php echo $cuppa->langValue("Global code") ?>
            <img class="tooltip" style="margin-left: 3px" src="templates/default/images/template/icon_help_12.png" title="<?php echo @$language->global_codes_help ?>" />
        </td>
        <td>
            
            <textarea name="code" style="width: 100%; height: 350px; resize: vertical;"><?php echo base64_decode(@$cuppa->configuration->code) ?></textarea> 
        </td>
    </tr>      
</table>