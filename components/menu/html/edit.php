<?php
    include_once(realpath(__DIR__ . '/../../..')."/classes/Cuppa.php");
    $cuppa = Cuppa::getInstance(); $cuppa->user->valid("admin_login");
    $language = $cuppa->language->load();
    $current_language = $cuppa->language->current();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    if(@$_POST["path"]){
        $getData = @$cuppa->utils->getUrlVars($cuppa->POST("path"));
        $_REQUEST = array_merge((array) $getData, $_REQUEST);
    }
    if(@$_POST["id"]){
		if(is_array($_POST["id"])) $_POST["id"] = $_POST["id"][0];
		$info = $cuppa->dataBase->getRow($cuppa->configuration->table_prefix."menu_items", "id=".$_POST["id"]);
	}else{
		$_POST["id"] = "0";
	}
    require_once realpath(__DIR__ . '/../../..')."/components/table_manager/fields/Select.php";
    require_once realpath(__DIR__ . '/../../..')."/components/table_manager/fields/File.php";
    require_once realpath(__DIR__ . '/../../..')."/components/table_manager/fields/Language_Selector.php";
	$menu_item_params = @json_decode($info["menu_item_params"]);
    
    // Get List of Alias
        $sql = "SELECT alias FROM ".$cuppa->configuration->table_prefix."menu_items WHERE alias <> '' AND alias <> '".@$info["alias"]."' AND menus_id ='".@$_REQUEST["menu_filter"]."' ";
        $alias = $cuppa->dataBase->sql($sql, true);
        if($alias == "1") $alias = null;
        $alias = $cuppa->jsonEncode($alias);
?>
<style>
    .edit_menu{
        position: relative;
        padding: 5px 15px 15px;
    }
    .edit_menu td *{ vertical-align: middle; }
/* Responsive */
    .r950 .edit_menu .general{ width: 100% !important; }
    .r950 .edit_menu .general_2{ padding-left: 10px !important; }
    .r950 .edit_menu .extra_params{ width: 100% !important; }
    .r650 .edit_menu{ padding: 5px 5px 15px !important;}
    .r650 .edit_menu h1{ display: none !important; }
    .r650 .edit_menu .tools{ top: 0px !important; margin-top: 8px !important; }
    .r650 .edit_menu table td{ position: relative !important; width: 100% !important; display: block !important; }
</style>
<script>
    edit_menu = {}
    //++ save
        edit_menu.save = function(task){
            if(!$('.edit_menu form').valid()) return;
            if(!edit_menu.validateAlias()) return;
            menu.showCharger();
            cuppa.blockadeScreen();
            var data = cuppa.urlToObject($(".edit_menu form").serialize());
                data.menu_item_params = edit_menu.getMenuTypeParams();
                data["function"] = "save";
            $.ajax({url:"components/menu/classes/functions.php", type:"POST", data:data, success:Ajax_Result});
            function Ajax_Result(result){
                menu.showCharger(false);
                //++ Set path
                    var path = cuppa.managerURL.path;
                    if("<?php echo @$cuppa->POST("redirect") ?>"){
                        path = "<?php echo @$cuppa->POST("redirect") ?>";
                    }
                //--
                if(task == "save"){
                    cuppa.managerURL.setParams({path:path}, true);
                    try{ w_right.close(); }catch(err){ } 
                }else if(task == "save_and_edit"){
                   cuppa.blockadeScreen(false);
                   if(result) $('.edit_menu #id').val(result);
                   $(".edit_menu .btn_close").attr("onclick", 'cuppa.managerURL.setParams({path:"'+path+'"}, true); try{ w_right.close(); }catch(err){}; ');
                }
                if( $("#menu_field").val() == "1" ) menu.update();
                else if( $("#menu_field").val() == "2" ) menu.update("admin_settings",".menu_settings .buttons");
                
                if(result == "0"){
                    stage.toast("<?php echo @$language->error_to_save_info ?>", "error");
                }else{
                    stage.toast("<?php echo @$language->information_has_been_saved ?>");
                }
            }
        }
    //--
    //++ Assign_Menu_Item_Params
        edit_menu.getMenuTypeParams = function(){
			var value = {};
			if(jQuery("#menu_item_type_id").val() == "2"){
			    value.table_name = jQuery('#table_name_field option:selected').text();
                value.defined_task = jQuery("#defined_task").val();                
			}else if(jQuery("#menu_item_type_id").val() == "3"){
                value.component_name = jQuery("#component_name_field").val();
			}else if(jQuery("#menu_item_type_id").val() == "4"){
                value.url = jQuery("#url_field").val();
                value.target = jQuery("#url_target_field").val();
			}else if(jQuery("#menu_item_type_id").val() == "5"){
                value.js_function = jQuery("#js_function_field").val();
			}else if(jQuery("#menu_item_type_id").val() == "7"){
			     value.other_menu = jQuery(".edit_menu #other_menu").val();
                 value.other_menu_item = jQuery(".edit_menu #other_menu_item").val();
			}
            value = jQuery.toJSON(value);
            return value;
        }
    //--
    //++ show more info
        edit_menu.showMore = function(){
            edit_menu.hideAll();
            var valueSelected = $("#menu_item_type_id").val();
    		if(valueSelected == "2") $(".table_name_tr").css("display", "table-row");
    		else if(valueSelected == "3") $(".component_name_tr").css("display", "table-row");
    		else if(valueSelected == "4") $(".url_tr").css("display", "table-row");
    		else if(valueSelected == "5") $(".js_function_tr").css("display", "table-row");
            else if(valueSelected == "7") $(".other_menu_item").css("display", "table-row");
            
        }
    //--
    //++ hide all
        edit_menu.hideAll = function(){
    		jQuery(".table_name_tr").css("display", "none");
    		jQuery(".component_name_tr").css("display", "none");
    		jQuery(".url_tr").css("display", "none");
            jQuery(".js_function_tr").css("display", "none");
            jQuery(".other_menu_item").css("display", "none");
    	}
    //--
    //++ validate alias
        var array_alias = cuppa.jsonDecode('<?php echo $alias ?>');
        edit_menu.validateAlias = function(){
            var valid = true;
            if(!array_alias) return valid;
            for(var i = 0; i < array_alias.length; i++){
                if(array_alias[i].alias == jQuery("#alias_field").val()){ valid = false; break; }
            }
            if(!valid) jQuery("#alias_field").addClass("error");
            else jQuery("#alias_field").removeClass("error");
            return valid;
        }
    //--
    //++ auto generate alias
        edit_menu.autoGenerateAlias = function(){
            var alias = cuppa.urlFriendly(cuppa.trim(jQuery("#title_field").val()));
            jQuery("#alias_field").val(alias);
            edit_menu.validateAlias();
        };
    //--
    //++ init
        edit_menu.init = function(){
            cuppa.inputFilter('#alias_field', 'a-z0-9-');
            cuppa.selectStyle(".edit_menu select");
            cuppa.tooltip();
            edit_menu.showMore();
             if(!'<?php echo @$info["alias"] ?>') edit_menu.autoGenerateAlias();            
        }; cuppa.addEventListener("ready",  edit_menu.init, document, "edit_menu");
    //--
</script>
<div class="edit_menu">
<form>
    <!-- Header -->
        <div style="overflow: hidden;">
            <h1 style="float: left;"><?php echo (!@$_POST["id"]) ? $language->new_item : $language->editing; ?></h1>
            <div class="tools">
                <input class="button_blue" type="button" value="<?php echo $language->save ?>" onclick="edit_menu.save('save')" />
                <input class="button_blue" type="button" value="<?php echo $language->save_edit ?>" onclick="edit_menu.save('save_and_edit')" />
                <input class="button_red btn_close" type="button" value="<?php echo $language->cancel ?>" onclick="w_right.close()" />
            </div>
        </div>
    <!-- -->
    <!-- Principal info --> 
        <div class="general" style="float: left; width: 58%;">
            <div class="section" style="margin: 20px 0px;"><div></div><span><?php echo $language->general_configuration ?></span></div>
            <div class="general_2" style="padding-left: 30px;">
                <table class="form" style="">
                    <tr>
                        <td style="width:150px;"><?php echo @$language->title ?></td>
                        <td>
                            <input class="required" title=" " id="title_field" name="title_field" value="<?php echo @$info["title"] ?>" oninput="edit_menu.autoGenerateAlias()" onchange="edit_menu.autoGenerateAlias()" />
                            <a onclick="stage.loadLightbox('<?php echo $language->language ?>', 'components/language_manager/html/files.php', '&width=80%&height=90%&mode=lightbox')" ><img class="tooltip" src="templates/default/images/template/language_manager_16_black.png" title="<?php echo @$language->tooltip_langauge_manager ?>" /></a>
                        </td>
                    </tr>
                    <tr>
                    	<td>
                            <?php echo @$language->alias ?>
                            <img class="tooltip" style="margin-left: 3px" src="templates/default/images/template/icon_help_12.png" title="<?php echo @$language->alias_text ?>" />
                        </td>
                        <td>
                            <input class="required" title=" " id="alias_field" name="alias_field" value="<?php echo @$info["alias"] ?>" oninput="edit_menu.validateAlias()" onchange="edit_menu.validateAlias()" />
                            <a onclick="stage.loadLightbox('<?php echo $language->language ?>', 'components/language_manager/html/files.php', '&width=80%&height=90%&mode=lightbox')" ><img class="tooltip" src="templates/default/images/template/language_manager_16_black.png" title="<?php echo @$language->tooltip_langauge_manager ?>" /></a>
                        </td>
                     </tr>
                     <tr>
                        <td style="width:150px;">
                            <?php echo @$language->title_tab ?>
                            <img class="tooltip" style="margin-left: 3px" src="templates/default/images/template/icon_help_12.png" title="<?php echo @$language->title_tab_tooltip ?>" />
                        </td>
                        <td>
                            <input title=" " id="title_tab_field" name="title_tab_field" value="<?php echo @$info["title_tab"] ?>" />
                            <a onclick="stage.loadLightbox('<?php echo $language->language ?>', 'components/language_manager/html/files.php', '&width=80%&height=90%&mode=lightbox')" ><img class="tooltip" src="templates/default/images/template/language_manager_16_black.png" title="<?php echo @$language->tooltip_langauge_manager ?>" /></a>
                        </td>
                    </tr>
                     <tr>
                    	<td>
                            Image
                        </td>
                        <td>
                            <?php
                                $config = '{"folder":"upload_files", "unique_name":"1"}';
                                $file = new File(); echo $file->GetItem("image_field", @$info["image"], $config ); 
                             ?>
                        </td>
                     </tr>
                    <tr>
                    	<td><?php echo @$language->parent_element ?></td>
                        <td>
        					<?php 
        						$db = DataBase::getInstance();
                                if(@!$_REQUEST["menu_filter"]) $_REQUEST["menu_filter"] = 1;
        						$sql = "SELECT id, title, parent_id, menus_id FROM ".$cuppa->configuration->table_prefix."menu_items WHERE menus_id = '".$_REQUEST["menu_filter"]."'  AND id <> '".$_POST["id"]."'";
        						$query = $db->sql($sql);
                                if($query == 1) $query = null;
                                $query = Cuppa::getInstance()->utils->createTree($query,"id", "parent_id", " - "); 
        						$data = array();
        							for( $i = 0; $i < count($query); $i++ ){ 
                                        array_push($data, array($query[$i]["id"], @$query[$i]["deep_string"].$query[$i]["title"] ));
                                    }
        						$json_data = json_encode($data);
        						$className = "Select";
        						$field = new $className();
        						echo $field->GetItem("parent_field", @$info["parent_id"], $json_data,  false, "", "", true);
        					?>
                        </td>
                    </tr>
                    <tr>
                    	<td><?php echo @$language->menu ?></td>
                        <td>
        					<?php
                                $menu_id = (@$info["menus_id"]) ? @$info["menus_id"] : $_REQUEST["menu_filter"];
        						$className = "Select";
        						$field = new $className();
        						$params = '{"table_name":"'.$cuppa->configuration->table_prefix.'menus","data_column":"id","label_column":"name, language"}';
        						echo $field->GetItem("menu_field", @$menu_id, $params);
        					?>
                        </td>
                    </tr>
                    <tr >
                    	<td><?php echo @$language->language ?></td>
                        <td>
        					<?php 
        						$className = "Language_Selector";
        						$field = new $className();
                                $config = '{"remove_option_all":false, "add_init_option":false, "init_value":"", "init_label":"Select"}';
        						echo $field->GetItem("language_field", @$info["language"], $params);
        					?>
                        </td>
                    </tr>
                    <tr>
                    	<td><?php echo @$language->enabled ?></td>
                        <td>
        					<?php 
        						$className = "Select";
        						$field = new $className();
        						$params = '[["1","true"],["0","false"]]';
        						echo $field->GetItem("enabled_field", @$info["enabled"], $params);
        					?>
                        </td>
                    </tr>
                    <tr>
                    	<td><?php echo @$language->default_page ?></td>
                        <td>
        					<?php 
        						$className = "Select";
        						$field = new $className();
        						$params = '[["0","false"],["1","true"]]';
        						echo $field->GetItem("default_page_field", @$info["default_page"], $params);
        					?>
                        </td>
                    </tr>
                    <tr>
                    	<td><?php echo @$language->error_page ?></td>
                        <td>
        					<?php 
        						$className = "Select";
        						$field = new $className();
        						$params = '[["0","false"],["1","true"]]';
        						echo $field->GetItem("error_page_field", @$info["error_page"], $params);
        					?>
                        </td>
                    </tr>
                    
                    <tr>
                    	<td>View</td>
                        <td>
        					<?php 
        						$className = "Select";
        						$field = new $className();
        						$params = '{"data":{"table_name":"'.$cuppa->configuration->table_prefix.'views","data_column":"id","label_column":"name"}}';
        						echo $field->GetItem("view_field", @$info["view"], $params);
        					?>
                        </td>
                    </tr>
                    <tr>
                    	<td style="vertical-align: top;">Description</td>
                        <td>
        					<textarea id="description_field" name="description_field" style="width: 100%; height: 70px;" ><?php echo @$info["description"] ?></textarea>
                        </td>
                    </tr>
                </table>
             </div>
        </div>
        <input type="hidden" name="id" id="id" value="<?php echo @$_POST["id"] ?>" />
    <!-- -->
    <!-- Other options -->
        <style>
            .extra_params table tr td:first-child{ padding-right: 10px; }
        </style>
        <div class="extra_params" style="float: right; width: 40%; clear: none; padding-bottom: 40px;" >
            <div class="section" style="margin: 20px 0px;"><div></div><span><?php echo $language->extra_params ?></span></div>
            <div style="padding-left: 10px;">
                <table class="form">
                    <tr>
                    	<td style="width:130px;"><?php echo @$language->type ?></td>
                        <td>
        					<?php 
        						$className = "Select";
        						$field = new $className();
                                $selected = (@$info["menu_item_type_id"]) ? @$info["menu_item_type_id"] : 6;
                                if(@$_REQUEST["type_field"]) $selected =  $_REQUEST["type_field"];
        						$params = '{"table_name":"'.$cuppa->configuration->table_prefix.'menu_item_type","data_column":"id","label_column":"name"}';
        						echo $field->GetItem("menu_item_type_id", $selected, $params, false, "", "onChange='edit_menu.showMore()'");
        					?>
                        </td>
                    </tr>
                    <!-- table_name_tr-->
                        <tr class="table_name_tr" style="display:none">
                        	<td>
                                <?php echo @$language->table_name ?>
                                <img class="tooltip" style="margin-left: 3px" src="templates/default/images/template/icon_help_12.png" title="<?php echo @$language->table_name_example ?>" />
                            </td>
                            <td>
                    			<?php 
                    				$className = "Select";
                    				$field = new $className();
                    				$params = '{"table_name":"'.$cuppa->configuration->table_prefix.'tables","data_column":"table_name","label_column":"table_name"}';
                                    $selected = @$menu_item_params->table_name;
                                    if(!@$selected) $selected = @$_REQUEST["table_name_field"];
                    				echo $field->GetItem("table_name_field", @$selected, $params, false);
                    			?>
                            </td>
                        </tr>
                        <tr class="table_name_tr" style="display:none">
                            <td><?php echo @$language->table_name_defined_task ?></td>
                            <td>
                                <input id="defined_task" name="defined_task" value="<?php echo @$menu_item_params->defined_task ?>" />
                            </td>
                        </tr>
                    <!-- -->
                    <!-- component_name_tr -->
                        <tr class="component_name_tr" style="display:none">
                        	<td><?php echo @$language->component_name ?></td>
                             <td><input id="component_name_field" name="component_name_field" value="<?php echo @$menu_item_params->component_name ?>" /></td>
                        </tr>
                    <!-- -->
                    <!-- url_tr -->
                        <tr class="url_tr" style="display:none">
                        	<td><?php echo @$language->url ?></td>
                            <td>
                            	<input id="url_field" name="url_field" value="<?php echo @$menu_item_params->url ?>" />
                            </td>
                        </tr>
                        <tr class="url_tr" style="display:none">
                        	<td><?php echo @$language->target ?></td>
                            <td>
                                <?php 
                                    $className = "Select";
                    				$field = new $className();
                    				$params = '[["_blank", "blank"],["_self", "self"],["_top", "top"],["_new", "new"],["iframe", "iframe"]]';
                    				echo $field->GetItem("url_target_field", @$menu_item_params->target, $params, false, "");
                    			?>
                            </td>
                        </tr>
                    <!-- -->
                    <!-- js_function_tr -->
                        <tr class="js_function_tr" style="display:none">
                        	<td><?php echo @$language->js_function ?></td>
                            <td><input id="js_function_field" name="js_function_field" value="<?php echo @$menu_item_params->js_function ?>" /></td>
                        </tr>
                    <!-- -->
                    <!-- other menu item -->
                        <tr class="other_menu_item" style="display:none;">
                        	<td>Menu</td>
                            <td>
                                <?php 
                                    $className = "Select";
                    				$field = new $className();
                                    $config = ' {"data":{"table_name":"'.$cuppa->configuration->table_prefix.'menus","data_column":"id","label_column":"name", "where_column":"1"}}';
                    				echo $field->GetItem("other_menu", @$menu_item_params->other_menu, $config, false, "");
                    			?>
                            </td>
                        </tr>
                        <tr class="other_menu_item" style="display:none;">
                            <td><?php echo $language->menu_items ?></td>
                            <td>
                                <select id="other_menu_item" name="other_menu_item">
                                    <option value=""></option>
                                </select>
                                <script>
                                    var opts = {}
                                        opts.nested_column = "id";
                                        opts.parent_column = "parent_id";
                                    cuppa.autoLoadSelect("<?php echo @$menu_item_params->other_menu_item ?>", "[name=other_menu]", "[name=other_menu_item]" ,"<?php echo @$cuppa->configuration->table_prefix ?>menu_items", "id", "title", "", "menus_id", true, "classes/ajax/Functions.php", opts);
                                </script>
                            </td>
                        </tr>
                    <!-- -->
                </table>
             </div>
             <!-- stats -->
                 <div class="section" style="margin: 20px 0px;"><div></div><span><?php echo $language->stats ?></span></div>
                 <div style="padding-left: 10px;">
                     <table class="form" style="">
                        <tr>
                            <td style="width:130px; vertical-align: top;"><?php echo @$language->tracking_codes ?>
                                <textarea name="tracking_codes" style="height: 120px; width: 100%;"><?php echo @$info["tracking_codes"]  ?></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
             <!-- -->
        </div>
    <!-- -->
</form>
</div>