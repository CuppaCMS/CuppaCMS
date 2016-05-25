<table>
    <tr>
        <td style="width:300px;"><span class="asterisk">*</span><?php echo @$language->default_administrator_template ?></td>
        <td><input type="text" class="required" title=" " name="administrator_template" value="<?php echo $cuppa->configuration->administrator_template ?>" /></td>
    </tr>
	<tr>
		<td><span class="asterisk">*</span><?php echo @$language->default_list_limit ?></td>
		<td><input type="text"  class="required" title=" " name="list_limit" value="<?php echo $cuppa->configuration->list_limit ?>" /></td>
    </tr>
   	<tr>
		<td>
            <?php echo @$language->font_list ?>
            <img class="tooltip" style="margin-left: 3px" src="templates/default/images/template/icon_help_12.png" title="<?php echo @$language->font_list_tooltip ?>" />
        </td>
		<td><input type="text" title=" " name="font_list" value="<?php echo @$cuppa->configuration->font_list ?>" /></td>
    </tr>
    <tr>
		<td>
            <?php echo @$language->secure_login ?>
            <img class="tooltip" style="margin-left: 3px" src="templates/default/images/template/icon_help_12.png" title="<?php echo @$language->comment_secure_login ?>" />
        </td>
		<td>
            <select style="width:55px" class="secure_login" name="secure_login">
                <option value="0">Off</option>
                <option value="1">On</option>
            </select>
		</td>
	</tr>
    <tr>
        <td ><?php echo @$language->secure_word; ?>:</td>
  		<td><input type="text" name="secure_login_value" value="<?php echo $cuppa->configuration->secure_login_value ?>" /></td>
    </tr>
    <tr>
		<td>
            <?php echo @$language->secure_login_failed ?> 
            <img class="tooltip" style="margin-left: 3px" src="templates/default/images/template/icon_help_12.png" title="<?php echo @$language->comment_secure_login_failed ?>" />
        </td>
		<td><input type="text" name="secure_login_redirect" value="<?php echo $cuppa->configuration->secure_login_redirect ?>" /></td>
    </tr>
    <tr>
        <td><?php echo @$language->default_language ?></td>
        <td>
            <select class="language_default" name="language_default">
                <?php for($i = 0; $i < count($language_files); $i++){ ?>
                    <option value="<?php echo $language_files[$i] ?>"><?php echo $language_files[$i] ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <td><?php echo @$language->default_country ?></td>
        <td>
            <select class="country_default" name="country_default" style="width: 200px;">
                <?php $countries = $cuppa->dataBase->getList( $cuppa->configuration->table_prefix."countries") ?>
                <?php for($i = 0; $i < count($countries); $i++){ ?>
                    <option value="<?php echo $countries[$i]["code"] ?>"><?php echo $countries[$i]["name"] ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo @$language->global_encode ?>
            <img class="tooltip" style="margin-left: 3px" src="templates/default/images/template/icon_help_12.png" title="<?php echo @$language->comment_global_encode ?>" />
        </td>
        <td>
            <select class="global_encode" name="global_encode">
                <option value="none"><?php echo @$language->none ?></option>
                <option value="md5">md5</option>
                <option value="sha1">sha1</option>
                <option value="sha1Salt">sha1Salt</option>
            </select>
            <input type="hidden" name="global_encode_salt" value="<?php echo @$cuppa->configuration->global_encode_salt ?>" />
        </td>
    </tr>
    <tr>
        <td>SSL</td>
        <td>
            <select class="ssl" name="ssl" style="width: 65px;">
                <option value="0"><?php echo @$language->false ?></option>
                <option value="1"><?php echo @$language->true ?></option>
            </select>
        </td>
    </tr>
</table>