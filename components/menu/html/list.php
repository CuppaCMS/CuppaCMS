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
    //++ Filters
		$filters = "";
		if(@$_REQUEST["menu_filter"]) $menu_filter = @$_REQUEST["menu_filter"]; else $menu_filter = @$_REQUEST["menu_filter"] = "1";
		$filters = " AND mi.menus_id = '$menu_filter' ";
	//--
    //++ Get menu list
    	$sql = "SELECT mi.id, mi.title, mi.alias, mi.language, mi.enabled, mi.default_page, mi.parent_id, '' as parent_title, mit.id as menu_item_type_id, mit.name as menu_item_type_name, m.name as menu_name, mi.order, v.name as view
    			FROM ".$cuppa->configuration->table_prefix."menu_items as mi
    			JOIN ".$cuppa->configuration->table_prefix."menu_item_type as mit
    			JOIN ".$cuppa->configuration->table_prefix."menus as m
                JOIN ".$cuppa->configuration->table_prefix."views as v
    			WHERE mit.id = mi.menu_item_type_id AND m.id = mi.menus_id AND mi.parent_id = '' $filters AND mi.view = v.id
    			UNION
    			SELECT mi.id, mi.title, mi.alias, mi.language, mi.enabled, mi.default_page, mi.parent_id, mi2.title as parent_title, mit.id as menu_item_type_id, mit.name as menu_item_type_name, m.name as menu_name, mi.order, v.name as view
    			FROM ".$cuppa->configuration->table_prefix."menu_items as mi
    			JOIN ".$cuppa->configuration->table_prefix."menu_item_type as mit
    			JOIN ".$cuppa->configuration->table_prefix."menus as m
    			JOIN ".$cuppa->configuration->table_prefix."menu_items as mi2
                JOIN ".$cuppa->configuration->table_prefix."views as v
    			WHERE mit.id = mi.menu_item_type_id AND m.id = mi.menus_id AND mi.parent_id = mi2.id $filters AND mi.view = v.id
    			ORDER BY parent_id ASC, `order` ASC";
        $info = $cuppa->dataBase->sql($sql);
        $info = $cuppa->utils->createTree($info,"id", "parent_id");
    //--
    include_once realpath(__DIR__ . '/../../..')."/components/table_manager/fields/Select.php";
?>
<style>
    .menu_list{ }
/* Responsive */
    .r950 .menu_list .deep{ font-size: 8px;  }
    .r950 .menu_list .alias{ display: block !important; }
    .r950 .menu_list .deep_alias{ display: none !important; }
    .r650 .menu_list h1{ display: none !important;}
    .r650 .menu_list .tools{ top: 0px !important; margin-top: 8px !important; }
    .r650 .menu_list .select_cuppa{ width: 130px !important; }
</style>
<script>
    menu_list = {}
    //++ filter change
        menu_list.changeMenu = function(task, id){
            var path = "component/menu/&menu_filter="+$("#menu_filter").val();
            cuppa.managerURL.setParams({path:path}, true);
        }
    //--
    //++ submit
        menu_list.submit = function(task, id){
            if(task == "new"){
                var data = {}
                    data.menu_filter = $("#menu_filter").val();
                stage.loadRightContent("components/menu/html/edit.php", data);
            }else if(task == "edit"){
                var data = {}
                    data.menu_filter = $("#menu_filter").val();
                    data.id = id;
                    if(!data.id) data.id = $(".menu_list .table_info td input[type=checkbox]:checked").val();
                    if(!data.id){
                        cuppa.blockade({duration:0.2, opacity:0.2, autoDeleteContent:".cuppa_alert"});
                        cuppa.instance({url:"js/cuppa/cuppa_html/alert.html", data:{title:"<?php echo @$language->message ?>", message:"<?php echo $language->alert_delete_edit ?>", accept:"<?php echo $language->accept ?>"}, add:"body"})
                        return;
                    }
                    stage.loadRightContent("components/menu/html/edit.php", data);
            }else if(task == "delete"){
                var ids = new Array();
                $(".menu_list .table_info td input[type=checkbox]:checked").each(function(e){ ids.push($(this).val()); });
                if(!ids.length){
                    cuppa.blockade({duration:0.2, opacity:0.2});
                    cuppa.instance({url:"js/cuppa/cuppa_html/alert.html", data:{title:"<?php echo @$language->message ?>", message:"<?php echo $language->alert_delete_edit ?>", accept:"<?php echo $language->accept ?>"}, add:"body"})
                    return;
                }
                cuppa.blockade({duration:0.2, opacity:0.2});
                cuppa.instance({url:"js/cuppa/cuppa_html/alert.html", data:{title:"<?php echo @$language->message ?>", message:"<?php echo $language->tooltip_delete_item ?>", show_cancel:true, cancel:"<?php echo $language->cancel ?>", accept:"<?php echo $language->accept ?>"}, add:"body"})
                function onConfirm(e, value){
                    $(cuppa).unbind("share", onConfirm);
                    if(value){
                        var data = {}
                            data.task = task;
                            data.ids = cuppa.jsonEncode(ids);
                        cuppa.managerURL.setParams({path:cuppa.managerURL.path}, true, data);
                    }
                }; $(cuppa).bind("share", onConfirm);
            }else if(task == "moveTop"){
                var data = {}
                    data.task = task;
                    data.id = id;
                cuppa.managerURL.setParams({path:cuppa.managerURL.path}, true, data);
            }
        }
    //--
    //++ init
        menu_list.init = function(){
            cuppa.selectStyle(".menu_list select");
            cuppa.tooltip();
            if("<?php echo $cuppa->REQUEST("task") ?>" == "new"){
                var data = {}
                    data.menu_filter = $("#menu_filter").val();
                    data.redirect = "component/menu/";
                stage.loadRightContent("components/menu/html/edit.php", data);
            }
        }; cuppa.addEventListener("ready",  menu_list.init, document, "menu_list");
    //--
</script>
<div class="menu_list">
    <form class="form_menu" method="post">
         <div style="overflow: hidden; margin-bottom: 4px;">
            <h1 style="float: left;"><?php echo $language->menu_items ?></h1>
            <div class="tools" style="float: right;">
                <div style="float: left; margin-right: 3px;">
                    <?php
                    	$className = "Select";
                    	$field = new $className();
                    	$params = '{"table_name":"'.$cuppa->configuration->table_prefix.'menus","data_column":"id","label_column":"name"}';
                    	echo $field->GetItem("menu_filter", @$_REQUEST["menu_filter"], $params, false, "", "onChange='menu_list.changeMenu()'");
                    ?>
                </div>
                <div onclick="menu_list.submit('new')" class="tool_button tool_left"><span>a</span></div>
                <div onclick="menu_list.submit('edit')" class="tool_button"><span>b</span></div>
                <div onclick="menu_list.submit('delete')" class="tool_button tool_right"><span>c</span></div>
            </div>
        </div>
        <div class="frame">
            <table class="table_info">
                <tr>
                    <th class="checkbox"><input id="selectAll" type="checkbox" onclick="cuppa.checkbox.selectAll(this)"/></th>
                    <th class="id"><?php echo @$language->id ?></th>
                    <th ><?php echo @$language->title ?></th>
                    <th ><?php echo @$language->item_type ?></th>
                    <th ><?php echo @$language->menu ?></th>
                    <th ><?php echo @$language->language ?></th>
                    <th ><?php echo @$language->order ?></th>
                    <th ><?php echo @$language->enabled ?></th>
                    <th ><?php echo @$language->default ?></th>
                    <th ><?php echo @$language->view ?></th>
                    <th ><?php echo @$language->options ?></th>
                </tr>
                <?php for($i = 0; $i < count($info); $i++){ ?>
                    <tr class="<?php if($i%2 == 0) echo "gray" ?>">
                        <td class="select"><input class="id" name="id" type="checkbox" value="<?php echo $info[$i]["id"] ?>" /></td>
                        <td class="id"><a onclick="menu_list.submit('edit','<?php echo $info[$i]["id"] ?>')" ><?php echo $info[$i]["id"] ?></a></td>
                        <td>
                            <span class="deep" style="color:#999"><?php echo @$info[$i]["deep_string"] ?></span><a onclick="menu_list.submit('edit','<?php echo $info[$i]["id"] ?>')" ><?php echo @$info[$i]["title"] ?></a>
                            <br />
                            <span class="deep_alias" style="color:#999; visibility: hidden;"><?php echo @$info[$i]["deep_string"] ?></span><samp class="alias" style="color:#999; font-size: 11px;">Alias: <?php echo @$info[$i]["alias"] ?></samp>
                        </td>
                        <td><?php echo $info[$i]["menu_item_type_name"] ?></td>
                        <td><?php echo $info[$i]["menu_name"] ?></td>
                        <td><?php echo (@$info[$i]["language"]) ?  @$info[$i]["language"] : @$language->all ?></td>
                        <td style="text-align:center; width:30px;"><?php echo $info[$i]["order"] ?></td>
                        <td style="text-align:center; width:30px;"><?php echo ($info[$i]["enabled"] == 1) ? $language->true : $language->false ?></td>
                        <td style="text-align:center; width:30px;"><?php echo ($info[$i]["default_page"] == 1) ? $language->true : $language->false ?></td>
                        <td><?php print_r($info[$i]["view"]) ?></td>
                        <td style="text-align: right; white-space: nowrap;">
                        	<?php if($info[$i]["order"] > 1){ ?>
                            	<a onclick="menu_list.submit('moveTop','<?php echo $info[$i]["id"] ?>')"  class="tooltip" title="<?php echo @$language->tooltip_move_field ?>">
                                    <img src="templates/default/images/template/arrow_top.png" />
                                </a>
                            <?php } ?>
                           <a onclick="stage.loadPermissionsLightbox('3', '<?php echo $info[$i]["id"] ?>', 'Permissions: <?php echo @$info[$i]["title"]  ?>' )" class="tooltip" title="<?php echo @$language->tooltip_admin_permissions ?>">
                                <img src="templates/default/images/template/permission.png" />
                           </a>
                        </td>
                    </tr>
                 <?php } ?>
            </table>
            <!-- List no info -->
                <?php if(!$info){ ?>
                    <div class="table_no_info">
                        <div class="no_file" style="text-align: center; padding: 40px 0px;">
                            <img src="templates/default/images/template/face.png" style="vertical-align: middle;"  />
                            <div style="display: inline-block; text-align: left; margin-left: 10px; vertical-align: middle;">
                                <h2 style="color: #777;"><?php echo $language->menu_without_info ?></h2>
                                <div style="max-width: 250px; color: #AAA;"><?php echo $language->menu_without_info_message ?></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <!-- -->
        </div>
    </form>
</div>