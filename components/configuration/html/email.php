<table>
    <tr>
        <td style="width:200px;"><?php echo $language->email_outgoing ?></td>
        <td><input type="text" title=" " name="email_outgoing" value="<?php echo @$cuppa->configuration->email_outgoing ?>" autocomplete="0" /></td>
    </tr>
    <tr>
        <td style="width:200px;"><?php echo $cuppa->language->value("forward", $language); ?></td>
        <td><input type="text" title=" " name="forward" value="<?php echo @$cuppa->configuration->forward ?>" autocomplete="0" /></td>
    </tr>
    <tr>
        <td>SMTP</td>
        <td>
            <select class="smtp" name="smtp">
                <option value="0"><?php echo $language->false ?></option>
                <option value="1"><?php echo $language->true ?></option>
            </select>
        </td>
    </tr>
    <tr>
        <td style="padding-left: 20px;">Host</td>
        <td><input type="text" title=" " name="email_host" value="<?php echo @$cuppa->configuration->email_host ?>" autocomplete="0" /></td>
    </tr>
    <tr>
        <td style="padding-left: 20px;">Port</td>
        <td><input type="text" title=" " name="email_port" value="<?php echo @$cuppa->configuration->email_port ?>" autocomplete="0" /></td>
    </tr>
	<tr>
        <td style="padding-left: 20px;"><?php echo $language->password ?></td>
        <td><input type="password" title=" " name="email_password" value="<?php echo @$cuppa->configuration->email_password ?>" autocomplete="0" /></td>
    </tr>
    <tr>
        <td style="padding-left: 20px;"><?php echo $language->security ?></td>
        <td>
            <select class="smtp_security" name="smtp_security">
                <option value="">No</option>
                <option value="ssl">SSL</option>
                <option value="tls">TLS</option>
            </select>
        </td>
    </tr>
</table>