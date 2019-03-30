<?php
    include_once(realpath(__DIR__ . '/../../..')."/classes/Cuppa.php");
    $cuppa = Cuppa::getInstance(); $cuppa->user->valid("admin_login");
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $language = $cuppa->language->load();
    $configuration = $cuppa->configuration;
    $db = $cuppa->dataBase;
    $utils = $cuppa->utils;
    if(@$_POST["path"]){
        $getData = @$cuppa->utils->getUrlVars($cuppa->POST("path"));
        $_REQUEST = array_merge((array) $getData, $_REQUEST);
        $_REQUEST["path"] = htmlentities($_REQUEST["path"], ENT_QUOTES, "UTF-8");
    }
    $view = @$path[3];
    if($cuppa->POST("table")) $view = $cuppa->POST("table");
    // Info
        $table_data = $db->getList($configuration->table_prefix."tables", "table_name = '".$view."'");
        $field_types = json_decode(base64_decode($table_data[0]["params"]));
        if(!$field_types) $field_types = @json_decode($table_data[0]["params"]);

    	$table_name = $view;
    	if(@$_REQUEST["id"]){ 
    		if(is_array($_REQUEST["id"])) $_REQUEST["id"] = $_REQUEST["id"][0];
    		if($_REQUEST["id"] == "session") $_REQUEST["id"] = $cuppa->user->getVar("id");
    		$info = $db->getRow($view, $field_types->primary_key."='".@$_REQUEST["id"]."'");
    	}else{ @$_REQUEST["id"] = "0";  }
    	$infoColumns = $db->getColums($table_name);
     // Incude files
        $include_files = json_decode(base64_decode(@$field_types->include_file));
     // tabs
        $tabs = $cuppa->jsonDecode(@$field_types->tabs);
    // Defined personal language file
        if(@$field_types->language_file_reference){
            $personal_language_reference = $cuppa->language->load(@$field_types->language_file_reference);             
            $language = (object) array_merge((array) @$language, (array) @$personal_language_reference);
        }
	// Get type fields
		$files = scandir(realpath(__DIR__."/..")."/fields/");
		for($i = 0; $i < count($files); $i++){
			if($files[$i] != "." && $files[$i] != ".."){
				$file = explode(".", $files[$i]);
				if(count($file) > 1) @require_once( realpath(__DIR__."/..")."/fields/". $file[0].".php");
			}
		}
    // Create inputs with the POST data;
        $inputs_request = $utils->getInputsWithRequestVars();
    // Updating user row reference on log_table
        if(is_array(@$_POST["id"])) $_POST["id"] = @$_POST["id"][0];
        if(@$_POST["id"]){
            $data = new stdClass();
            $data->user_id_updating = "'".$cuppa->user->getVar("id")."'";
            $data->date_updating = "'".date('Y-m-d H:i:s')."'";
            $data->table_name = "'".$view."'";
            $data->reference_id = "'".@$_POST["id"]."'";
            $db->add($cuppa->configuration->table_prefix."tables_log", $data);
        }
?>
<style>
    .edit_admin_table{ position: relative; padding: 5px 15px 15px; }
    .edit_admin_table h1{ float: left; }
    .edit_admin_table .tools{ position: relative; float: right; top: 4px; }
    .edit_admin_table .td_label{ white-space: nowrap !important; padding-right: 20px !important; }
    .edit_admin_table .ace_editor_text_area{ display: none !important; }
/* Responsive */
    .r1100 .defaultSkin table.mceLayout{ width: 100% !important; }
    .r650 .edit_admin_table{ padding: 5px 5px 15px !important; }
    .r650 .edit_admin_table h1{ display: none !important; } 
    .r650 .edit_admin_table .tools{ top: 0px !important; margin-top: 8px !important; }
    .r650 .edit_admin_table .td_label, .r650 .edit_admin_table .td_field { display: block !important; position: relative !important; width: 100% !important; }
    .r650 .edit_admin_table .form_admin_table_1{ padding-left: 10px !important;}
</style>
<script>
    edit_admin_table = {}
    //++ AutoUpdate 
        edit_admin_table.updating_User_TableLog = function(){
            if('<?php echo @$_POST["id"] ?>' == "0") return;
            try{ TweenMax.killTweensOf(edit_admin_table.updating_User_TableLog); }catch(err){ };
            var data = {};
                data["function"] = 'updateUserTableLog';
                data.table_name = '<?php echo @$view ?>';
                data.reference_id = '<?php echo @$_POST["id"] ?>';
            if(!data.reference_id) return;                                
            jQuery.ajax({url:"classes/ajax/Functions.php", type:"POST", data:data, success:Ajax_Result});
                function Ajax_Result(result){
                    TweenMax.delayedCall(10, edit_admin_table.updating_User_TableLog);                    
                }
        };
    //--
    //++ Save
        edit_admin_table.callback = null;
        edit_admin_table.save = function(task){
            if(!$('.edit_admin_table form').valid()) return;
            $(".ace_autocomplete").hide();
            cuppa.tinyMCEUpdate();
            menu.showCharger();
            cuppa.blockadeScreen();
            var data = {}
                data = cuppa.serialize(".edit_admin_table");
                data["view"] = "<?php echo $view ?>";
                data["function"] = "saveAdminTable";
                $.ajax({url:"components/table_manager/classes/functions.php", type:"POST", data:data, success:Ajax_Result});
                function Ajax_Result(result){
                    if(edit_admin_table.callback) edit_admin_table.callback(result, data);
                    menu.showCharger(false);
                    //++ Set path
                        var path = cuppa.managerURL.path;
                        if("<?php echo @$cuppa->POST("redirect") ?>"){
                            path = "<?php echo @$cuppa->POST("redirect") ?>";
                        }
                    //--
                    if(task == "save"){
                        if(result != "0"){
                            cuppa.managerURL.setParams({path:path}, true, cuppa.serialize(".form_list"));
                            try{ w_right.close(); }catch(err){ cuppa.tinyMCEDestroy(); }
                        }else{
                            cuppa.blockadeScreen(false);
                        }
                    }else if(task == "save_and_edit"){
                       edit_admin_table.updating_User_TableLog();
                       cuppa.blockadeScreen(false);
                       if(result) $('.edit_admin_table form #<?php echo $field_types->primary_key ?>_field').val(result);
                       $(".edit_admin_table .btn_close").bind("click", function(e){
                            cuppa.managerURL.setParams({path:path}, true, cuppa.serialize(".form_list")); 
                       });
                    }
                    if(result == "0"){
                        stage.toast("<?php echo @$language->error_to_save_info ?>", "error");
                    }else{
                        stage.toast("<?php echo @$language->information_has_been_saved ?>");
                    }
                }
        }
    //--
    //++ chage tab
        edit_admin_table.chageTab = function(e, item){
            if(e) item = this; if(!item) return;
            var ref = $(item).attr("ref");
            $(item).parent().find(".tab").removeClass("selected"); $(item).addClass("selected");
            $(".edit_admin_table [tab]").css("display", "none");
            $(".edit_admin_table .form tr").css("display", "none"); $(".edit_admin_table [tab='"+ref+"']").css("display", "table-row");
            //++ Reload all TinyMCE instances
                TweenMax.delayedCall(0.2, function(){
                    $(".edit_admin_table .mceEditor").remove();
                    var textAreas = $(".edit_admin_table textarea").get();
                    for(var i = 0; i < textAreas.length; i++){
                        var textArea = textAreas[i];
                        $(textArea).css("display","block");
                        if(textArea.tiny_config){ tinyMCE.init(textArea.tiny_config); }
                    }
                });
            //--
        }; $(".edit_admin_table .tabs .tab").bind("click", edit_admin_table.chageTab);
    //--
    //++ end
        edit_admin_table.end = function(){
            try{ TweenMax.killTweensOf(edit_admin_table.updating_User_TableLog); }catch(err){ };
            cuppa.removeEventGroup("edit_admin_table");
        }; cuppa.addRemoveListener(".edit_admin_table", edit_admin_table.end);
    //--
    //++ c+s save
        edit_admin_table.ctr_s = function(event){
            if((event.ctrlKey || event.metaKey) && event.which == 83) {
                event.preventDefault();
                try{ edit_admin_table.save('save_and_edit'); }catch(err){ };
                return false;
            };
        }; $(document).off("keydown").on("keydown", edit_admin_table.ctr_s);
    //--
    //++ init
        edit_admin_table.init = function(){
            cuppa.tinyMCEDestroy();
            edit_admin_table.updating_User_TableLog();
            cuppa.selectStyle(".edit_admin_table select"); 
                $(".edit_admin_table select[disabled=disabled]").parent().css("opacity", 0.6);
            cuppa.tooltip();
            edit_admin_table.chageTab(null, $(".edit_admin_table .tabs .tab").get(0));
        }; cuppa.addEventListener("ready",  edit_admin_table.init, document, "edit_admin_table");
    //--
</script>
<div class="edit_admin_table">
    <!-- Include file (top) -->
        <?php
            for($i = 0; $i < @count($include_files); $i++){
                if( $include_files[$i]->add_to == "form" && $include_files[$i]->position == "top" ){
                    $include_file = $cuppa->getDocumentPath().$include_files[$i]->path;
                    if(strpos($include_file, "../") !== false){
                        $f_backs = substr_count($include_file,  "../");
                        $back_string = str_repeat("/..", $f_backs);
                        $file = str_replace("../", "", $include_files[$i]->path);
                        $include_file = realpath($cuppa->getDocumentPath().$back_string)."/".$file;
                    }
                    @include($include_file);
                }
            } 
        ?>
    <!-- -->
    <form class="form_admin_table" method="post">
        <!-- Header -->
            <div style="overflow: hidden;">
                <h1><?php echo (!@$_POST["id"]) ? $language->new_item : $language->editing; ?></h1>
                <div class="tools">
                    <?php if( ( $cuppa->permissions->getValue(2,$view, 3) || $cuppa->permissions->getValue(2,$view, 4) ) && ( !isset($_REQUEST["save"]) || @$_REQUEST["save"] == "true") ){ ?>
                        <?php if( $cuppa->permissions->getValue(2,$view, 3) && !@$_POST["id"] ){ ?>
                            <input class="button_blue btn_save" type="button" value="<?php echo $language->save ?>" onclick="edit_admin_table.save('save')" />
                        <?php }else if($cuppa->permissions->getValue(2,$view, 4) && @$_POST["id"]){ ?>
                            <input class="button_blue btn_save" type="button" value="<?php echo $language->save ?>" onclick="edit_admin_table.save('save')" />
                        <?php } ?>
                    <?php } ?>
                    <?php if( ( $cuppa->permissions->getValue(2,$view, 3) || $cuppa->permissions->getValue(2,$view, 4) ) && (!isset($_REQUEST["save_and_edit"]) || @$_REQUEST["save_and_edit"] == "true") ){ ?>
                        <?php if( $cuppa->permissions->getValue(2,$view, 3) && !@$_POST["id"] ){ ?>
                            <input class="button_blue btn_save_and_edit" type="button" value="<?php echo $language->save_edit ?>" onclick="edit_admin_table.save('save_and_edit')" />
                        <?php }else if($cuppa->permissions->getValue(2,$view, 4) && @$_POST["id"]){ ?>
                            <input class="button_blue btn_save_and_edit" type="button" value="<?php echo $language->save_edit ?>" onclick="edit_admin_table.save('save_and_edit')" />
                        <?php } ?>
                    <?php } ?>
                    <?php if( !isset($_REQUEST["cancel"]) || @$_REQUEST["cancel"] == "true"){ ?>
                        <input class="button_red btn_close" type="button" value="<?php echo $language->cancel ?>" onclick="w_right.close()" />
                    <?php } ?>
                </div>
            </div>
            <?php if($tabs){ ?>
                <!-- tabs -->
                    <div style="margin: 5px 0px 10px;" class="tabs">
                        <div class="line"></div>
                        <?php for( $z = 0; $z < count($tabs->info); $z++){ ?>
                            <div onclick="" class="tab <?php if(!$z) echo "selected"; ?>" ref="<?php echo $tabs->info[$z]->tab_name ?>"><div class="line"></div><?php echo $cuppa->language->getValue($tabs->info[$z]->tab_name, $language) ?></div>
                        <?php } ?>
                    </div>
                <!-- -->
            <?php }else{ ?>
                <div class="separator" style="margin-bottom: 15px;"></div>
            <?php } ?>
        <!-- -->
        <div class="form_admin_table_1" style="padding-left: 30px;">
            <!-- Include file (before table) -->
                <?php
                    for($i = 0; $i < @count($include_files); $i++){
                        if( $include_files[$i]->add_to == "form" && $include_files[$i]->position == "before_to_fields" ){
                            $include_file = $cuppa->getDocumentPath().$include_files[$i]->path;
                            if(strpos($include_file, "../") !== false){
                                $f_backs = substr_count($include_file,  "../");
                                $back_string = str_repeat("/..", $f_backs);
                                $file = str_replace("../", "", $include_files[$i]->path);
                                $include_file = realpath($cuppa->getDocumentPath().$back_string)."/".$file;
                            }
                            @include($include_file);
                        }
                    } 
                ?>
            <!-- -->
            <!-- Principal -->
                <table class="form">
                	<?php for($i = 0; $i < count($infoColumns); $i++){ ?>
                        <?php if(@$cuppa->permissions->getValue("5", $view.",".$infoColumns[$i], "7") === "hidden"){ ?>
                            <?php 
                                $value = ""; 
                                $value = @$info[$infoColumns[$i]];
                                //++ Permissions data
                                    $default_value = $cuppa->permissions->getDefault("5", $view.",".$infoColumns[$i], "7");
                                    if($default_value && !$value){ 
                                        $value = $cuppa->utils->evalString($default_value);
                                        if($value == "session" || $value == "user") $value = $cuppa->user->value("id");
                                    }
                                //--
                                if($value === false || $value == "") $value = @$_REQUEST[$infoColumns[$i]];
                                $config = @json_decode(base64_decode($field_types->{$infoColumns[$i]}->config));
                                    if(!$config) $config = @$field_types->{$infoColumns[$i]}->config;
                            ?>
                            <?php if(  @$field_types->{$infoColumns[$i]}->type ==  "Personal_Script" ){ ?>
                                <input type="hidden" name="<?php echo @$infoColumns[$i]."_field" ?>"  value="<?php echo eval('return '.$config->script.';'); ?>" />
                            <?php }else{ ?>
                                <input type="hidden" name="<?php echo @$infoColumns[$i]."_field" ?>"  value="<?php echo @$value; ?>" />
                            <?php } ?>
                        <?php }else if(@$cuppa->permissions->getValue("5", $view.",".$infoColumns[$i], "7")){ ?>
                            <?php
                                $config = @json_decode(base64_decode($field_types->{$infoColumns[$i]}->config));
                                    if(!$config) $config = @$field_types->{$infoColumns[$i]}->config;
                            ?>
                            <tr tab="<?php echo @$tabs->fields_tab->{$infoColumns[$i]} ?>" class="tr_<?php echo @$cuppa->utils->getFriendlyUrl(@$field_types->{$infoColumns[$i]}->label) ?>" >
                                <td class="td_label" style="width:150px; white-space: nowrap; padding-right: 10px; <?php if($field_types->{$infoColumns[$i]}->type == "TextArea" || $field_types->{$infoColumns[$i]}->type == "Select_List" ) echo "vertical-align:top; padding-top:5px" ?>">
                                    <?php
                                        echo $cuppa->language->getValue($field_types->{$infoColumns[$i]}->label, $language); 
                                    ?>
                                    <?php if(@$config->tooltip){ ?>
                                        <?php  
                                            $message = $cuppa->language->getValue(@$config->tooltip, $language);
                                        ?>
                                        &nbsp;<img title="<?php echo @$message ?>" class="tooltip" src="templates/default/images/template/icon_help_12.png" />
                                    <?php } ?>
                                </td>
                                <td class="td_field">
                                    <?php 
                                        if(!@$field_types->language_file_reference) $field_types->language_file_reference = "administrator";
                                        if(@$field_types->{$infoColumns[$i]}->type == "Text" && @$config->type == "password" && !$_REQUEST["id"]){
                                            $className = "Text";
                                            $field = new $className();
                                            $value = "";
                                            $config = $cuppa->utils->jsonDecode(@$field_types->{$infoColumns[$i]}->config, true);
                                            $config->new = true;
                                            $extraParams = "";
                                             //++ Permissions data
                                                if(@$cuppa->permissions->getValue("5", $view.",".$infoColumns[$i], "7") == "blocked"){
                                                    $extraParams.= "readonly='readonly' ";
                                                }
                                                $default_value = $cuppa->permissions->getDefault("5", $view.",".$infoColumns[$i], "7");
                                                if($default_value && !$value){ 
                                                    $value = $cuppa->utils->evalString($default_value);
                                                    if($value == "session" || $value == "user") $value = $cuppa->user->value("id");
                                                }
                                            //--
                                            echo $field->GetItem($infoColumns[$i]."_field", $value,json_encode($config), $field_types->{$infoColumns[$i]}->required,"", $extraParams);
                                        }else if(@$field_types->{$infoColumns[$i]}->type == "Text" && @$config->type == "password"){
                                            $className = $field_types->{$infoColumns[$i]}->type;
                                            $field = new $className();
                                            $value = @$info[$infoColumns[$i]];
                                            if($value === false || $value == "") $value = @$_REQUEST[$infoColumns[$i]];
                                            $extraParams = "";
                                            //++ Permissions data
                                                $default_value = $cuppa->permissions->getDefault("5", $view.",".$infoColumns[$i], "7");
                                                if($default_value && !$value){ 
                                                    $value = $cuppa->utils->evalString($default_value);
                                                }
                                                if(@$cuppa->permissions->getValue("5", $view.",".$infoColumns[$i], "7") == "blocked"){
                                                    $extraParams.= " disabled='disabled' ";
                                                    echo '<input type="hidden" name="'.@$infoColumns[$i].'_field" value="'.@$value.'" />';
                                                }
                                            //--
                                            $config = @json_decode(base64_decode($field_types->{$infoColumns[$i]}->config));
                                                if(!$config) $config = @$field_types->{$infoColumns[$i]}->config;
                                            $config->table = $view;
                                            $config->field = $infoColumns[$i];
                                            $config->primary_key_field = $field_types->primary_key;
                                            $config->id = @$info[$field_types->primary_key];
                                            $config = json_encode($config);
                                            echo $field->GetItem($infoColumns[$i]."_field", $value, $config, $field_types->{$infoColumns[$i]}->required, "", $extraParams);
                                        }else if(@$field_types->{$infoColumns[$i]}->type == "Text" && @$config->type == "alias"){
                                            $className = $field_types->{$infoColumns[$i]}->type;
                                            $field = new $className();
                                            $value = @$info[$infoColumns[$i]];
                                            if($value === false || $value == "") $value = @$_REQUEST[$infoColumns[$i]];
                                            $extraParams = "";
                                            //++ Permissions data
                                                $default_value = $cuppa->permissions->getDefault("5", $view.",".$infoColumns[$i], "7");
                                                if($default_value && !$value){ 
                                                    $value = $cuppa->utils->evalString($default_value);
                                                }
                                                if(@$cuppa->permissions->getValue("5", $view.",".$infoColumns[$i], "7") == "blocked"){
                                                    $extraParams.= " disabled='disabled' ";
                                                    echo '<input type="hidden" name="'.@$infoColumns[$i].'_field" value="'.@$value.'" />';
                                                }
                                            //--
                                            $config = @json_decode(base64_decode($field_types->{$infoColumns[$i]}->config));
                                                if(!$config) $config = @$field_types->{$infoColumns[$i]}->config;
                                            $config->table = $view;
                                            $config->field = $infoColumns[$i];
                                            $config = json_encode($config);
                                            echo $field->GetItem($infoColumns[$i]."_field", $value, $config, $field_types->{$infoColumns[$i]}->required, "", $extraParams);
                                        }else if(@$field_types->{$infoColumns[$i]}->type == "Select" || @$field_types->{$infoColumns[$i]}->type == "Select_List" ){
                                            $className = $field_types->{$infoColumns[$i]}->type;
                                            $field = new $className();
                                            $value = @$info[$infoColumns[$i]];
                                            if($value === false || $value == ""){ 
                                                $value = @$_REQUEST[$infoColumns[$i]];
                                                if(@$field_types->{$infoColumns[$i]}->type == "Select_List") $value = '["'.$value.'"]';
                                            }
                                            $extraParams = "";
                                            //++ Permissions data
                                                $default_value = $cuppa->permissions->getDefault("5", $view.",".$infoColumns[$i], "7");
                                                if($default_value && !$value){ 
                                                    $value = $cuppa->utils->evalString($default_value);
                                                    if($value == "session" || $value == "user") $value = $cuppa->user->value("id");
                                                }
                                                if(@$cuppa->permissions->getValue("5", $view.",".$infoColumns[$i], "7") == "blocked"){
                                                    $extraParams.= " disabled='disabled' ";
                                                    echo '<input type="hidden" name="'.@$infoColumns[$i].'_field" value="'.@$value.'" />';
                                                }
                                            //--
                                            $config = @base64_decode(@$field_types->{$infoColumns[$i]}->config);
                                                if(!$config) $config = json_encode(@$field_types->{$infoColumns[$i]}->config);
                                            echo $field->GetItem($infoColumns[$i]."_field", $value, $config, $field_types->{$infoColumns[$i]}->required, "", $extraParams, "", true, $field_types->language_file_reference);
                                        }else if(@$field_types->{$infoColumns[$i]}->type == "TextArea" ){
                                            $className = $field_types->{$infoColumns[$i]}->type;
                                            $field = new $className();
                                            $value = @$info[$infoColumns[$i]];
                                            if($value === false || $value == "") $value = @$_REQUEST[$infoColumns[$i]];
                                            $extraParams = "";
                                            //++ Permissions data
                                                $default_value = $cuppa->permissions->getDefault("5", $view.",".$infoColumns[$i], "7");
                                                if($default_value && !$value){ 
                                                    $value = $cuppa->utils->evalString($default_value);
                                                    if($value == "session" || $value == "user") $value = $cuppa->user->value("id");
                                                }
                                                if(@$cuppa->permissions->getValue("5", $view.",".$infoColumns[$i], "7") == "blocked"){
                                                    $extraParams.= " disabled='disabled' ";
                                                    echo '<input type="hidden" name="'.@$infoColumns[$i].'_field" value="'.@$value.'" />';
                                                }
                                            //--
                                            $config = @base64_decode(@$field_types->{$infoColumns[$i]}->config);
                                                if(!$config) $config = json_encode(@$field_types->{$infoColumns[$i]}->config);
                                            echo $field->GetItem($infoColumns[$i]."_field", $value, $config, $field_types->{$infoColumns[$i]}->required, "", $extraParams, @$cuppa->configuration->font_list);
                                        }else{
                                            $className = $field_types->{$infoColumns[$i]}->type;
                                            $field = new $className();
                                            $value = @$info[$infoColumns[$i]];
                                            if($value === false || $value == "") $value = @$_REQUEST[$infoColumns[$i]];
                                            $extraParams = "";
                                            //++ Permissions data
                                                $default_value = $cuppa->permissions->getDefault("5", $view.",".$infoColumns[$i], "7");
                                                if($default_value && !$value){ 
                                                    $value = $cuppa->utils->evalString($default_value);
                                                    if($value == "session" || $value == "user") $value = $cuppa->user->value("id");
                                                }
                                                if(@$cuppa->permissions->getValue("5", $view.",".$infoColumns[$i], "7") == "blocked"){
                                                    $extraParams.= " disabled='disabled' ";
                                                    echo '<input type="hidden" name="'.@$infoColumns[$i].'_field" value="'.@$value.'" />';
                                                }
                                            //--
                                            $config = @base64_decode(@$field_types->{$infoColumns[$i]}->config);
                                                if(!$config) $config = json_encode(@$field_types->{$infoColumns[$i]}->config);
                                            echo $field->GetItem($infoColumns[$i]."_field", $value, $config, @$field_types->{$infoColumns[$i]}->required, "", @$extraParams);
                                        }
                                    ?>
                                    <!-- language button -->
                                        <?php if(@$field_types->{$infoColumns[$i]}->language_button){ ?>
                                              <a class="tooltip" onclick="stage.loadLightbox('<?php echo $language->language ?>', 'components/language_manager/html/files.php', '&width=80%&height=90%&mode=lightbox')"><img style="vertical-align: middle;" src="templates/default/images/template/language_manager_16_black.png" title="<?php echo @$language->tooltip_langauge_manager ?>" /></a> 
                                        <?php } ?>
                                    <!-- -->
            					</td>
                            </tr>
                         <?php } ?>
                     <?php } ?>
                    </tbody>
                </table>
            <!-- -->
            <!-- Include file (after table) -->
                <?php
                    for($i = 0; $i < @count($include_files); $i++){
                        if( $include_files[$i]->add_to == "form" && $include_files[$i]->position == "after_to_fields" ){
                            $include_file = $cuppa->getDocumentPath().$include_files[$i]->path;
                            if(strpos($include_file, "../") !== false){
                                $f_backs = substr_count($include_file,  "../");
                                $back_string = str_repeat("/..", $f_backs);
                                $file = str_replace("../", "", $include_files[$i]->path);
                                $include_file = realpath($cuppa->getDocumentPath().$back_string)."/".$file;
                            }
                            @include($include_file);
                        }
                    } 
                ?>
            <!-- -->
        </div>
    </form>
    <!-- Include file (end) -->
        <?php
            for($i = 0; $i < @count($include_files); $i++){
                if( $include_files[$i]->add_to == "form" && $include_files[$i]->position == "end" ){
                    $include_file = $cuppa->getDocumentPath().$include_files[$i]->path;
                    if(strpos($include_file, "../") !== false){
                        $f_backs = substr_count($include_file,  "../");
                        $back_string = str_repeat("/..", $f_backs);
                        $file = str_replace("../", "", $include_files[$i]->path);
                        $include_file = realpath($cuppa->getDocumentPath().$back_string)."/".$file;
                    }
                    @include($include_file);
                }
            } 
        ?>
    <!-- -->
</div>