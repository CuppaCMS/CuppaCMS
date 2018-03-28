<table>
    <tr>
        <td style="width:300px;"><span class="asterisk">*</span><?php echo @$language->default_administrator_template ?></td>
        <td><input type="text" class="required" title=" " name="administrator_template" value="<?php echo $cuppa->configuration->administrator_template ?>" autocomplete="0" /></td>
    </tr>
	<tr>
		<td><span class="asterisk">*</span><?php echo @$language->default_list_limit ?></td>
		<td><input type="text"  class="required" title=" " name="list_limit" value="<?php echo $cuppa->configuration->list_limit ?>" autocomplete="0" /></td>
    </tr>
   	<tr>
		<td>
            <?php echo @$language->font_list ?>
            <img class="tooltip" style="margin-left: 3px" src="templates/default/images/template/icon_help_12.png" title="<?php echo @$language->font_list_tooltip ?>" />
        </td>
		<td><input type="text" title=" " name="font_list" value="<?php echo @$cuppa->configuration->font_list ?>" autocomplete="0" /></td>
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
  		<td><input type="text" name="secure_login_value" value="<?php echo $cuppa->configuration->secure_login_value ?>" autocomplete="0" /></td>
    </tr>
    <tr>
		<td>
            <?php echo @$language->secure_login_failed ?> 
            <img class="tooltip" style="margin-left: 3px" src="templates/default/images/template/icon_help_12.png" title="<?php echo @$language->comment_secure_login_failed ?>" />
        </td>
		<td><input type="text" name="secure_login_redirect" value="<?php echo $cuppa->configuration->secure_login_redirect ?>" autocomplete="0" /></td>
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
            <input type="hidden" name="global_encode_salt" value="<?php echo @$cuppa->configuration->global_encode_salt ?>" autocomplete="0" />
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
    <tr>
        <td><?php echo $cuppa->langValue("Lateral menu", $language) ?></td>
        <td>
            <select class="lateral_menu" name="lateral_menu" style="width: 100px;">
                <option value="expanded"><?php echo $cuppa->langValue("expanded", $language) ?></option>
                <option value="collapsed"><?php echo $cuppa->langValue("collapsed", $language) ?></option>
            </select>
        </td>
    </tr>
    <tr>
        <td><?php echo $cuppa->langValue("Base URL", $language) ?></td>
        <td>
            <input type="text" name="base_url" value="<?php echo @$cuppa->configuration->base_url; ?>" autocomplete="0" />
        </td>
    </tr>
    <tr>
        <td><?php echo $cuppa->langValue("Auto logout time", $language) ?></td>
        <td>
            <input type="text" name="auto_logout_time" value="<?php echo @$cuppa->configuration->auto_logout_time; ?>" autocomplete="0" />
        </td>
    </tr>
    <tr>
        <td><?php echo $cuppa->langValue("Redirect to", $language) ?></td>
        <td>
            <select class="redirect_to" name="redirect_to" style="width: 65px;">
                <option value="false"><?php echo @$language->false ?></option>
                <option value="www">www</option>
                <option value="not_www">not www</option>
            </select>
        </td>
    </tr>
</table>