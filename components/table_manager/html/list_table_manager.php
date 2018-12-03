<?php 
    include_once(realpath(__DIR__ . '/../../..')."/classes/Cuppa.php");
    $cuppa = Cuppa::getInstance(); $cuppa->user->valid("admin_login");
    $language = $cuppa->language->load();
    $info = $cuppa->dataBase->getList($cuppa->configuration->table_prefix."tables");
	$infoTables = $cuppa->dataBase->getTablesNoRegistered();
?>
<style>
    .content{ position: relative; }
    .content h1{ float: left; }
    .content .tools{ position: relative; float: right; top: 8px; }
/* Responsvie */
    .r780 .list_table_manager h1{ display: none !important; }
    .r780 .list_table_manager .tools{ margin-top: 7px !important; top: 0px !important;  }
    .r650 .list_table_manager .tools .add_new_table{ display: none !important; }
    .r650 .list_table_manager .tools .select_cuppa{ width: 100px !important; }
</style>
<script>
    list_table_manager = {}
    //++ new / edit / delete table
        list_table_manager.submit = function(task, id){
            var data = {};
            if(task == "new"){
                if(!$(".list_table_manager .table_name").val()){ 
                    cuppa.blockade({duration:0.2, opacity:0.2, autoDeleteContent:".cuppa_alert"});
                    cuppa.instance({url:"js/cuppa/cuppa_html/alert.html", data:{title:"<?php echo @$language->message ?>", message:"<?php echo $language->alert_new_table ?>", accept:"<?php echo $language->accept ?>"}, add:"body"})
                    return;
                }
                data.id = 0;
                data.task = task;
                data.table_name = $(".list_table_manager .table_name").val();
                stage.loadRightContent("components/table_manager/html/edit_table_manager.php", data);
            }else if(task == "edit"){
                if(!id) id = $(".list_table_manager .table_info td input[type=checkbox]:checked").val();
                if(!id){
                    cuppa.blockade({duration:0.2, opacity:0.2, autoDeleteContent:".cuppa_alert"});
                    cuppa.instance({url:"js/cuppa/cuppa_html/alert.html", data:{title:"<?php echo @$language->message ?>", message:"<?php echo $language->alert_delete_edit ?>", accept:"<?php echo $language->accept ?>"}, add:"body"})
                    return;
                }
                data.task = task;
                data.id = id;
                stage.loadRightContent("components/table_manager/html/edit_table_manager.php", data);
            }else if(task == "delete"){
                var ids = new Array();
                $(".list_table_manager .table_info td input[type=checkbox]:checked").each(function(e){ ids.push($(this).val()); });
                if(!ids.length){
                    cuppa.blockade({duration:0.2, opacity:0.2, autoDeleteContent:".cuppa_alert"});
                    cuppa.instance({url:"js/cuppa/cuppa_html/alert.html", data:{title:"<?php echo @$language->message ?>", message:"<?php echo $language->alert_delete_edit ?>", accept:"<?php echo $language->accept ?>"}, add:"body"})
                    return;
                }
                data.task = task;
                data.ids = cuppa.jsonEncode(ids);
                cuppa.managerURL.setParams({path:cuppa.managerURL.path}, true, data);    
            }else if(task == "reload"){
                cuppa.managerURL.setParams({path:cuppa.managerURL.path}, true);
            }
        }
    //--
    //++ end
        list_table_manager.end = function(){
            cuppa.removeEventGroup("list_table_manager");
        }; cuppa.addRemoveListener(".list_table_manager", list_table_manager.end);
    //--
    //++ init
        list_table_manager.init = function(){
            cuppa.selectStyle(".content select");
            cuppa.tooltip();
            cuppa.managerURL.updateLinks(".list_table_manager .link");
        }; cuppa.addEventListener("ready",  list_table_manager.init, document, "list_table_manager");
    //--
</script>
<div class="content list_table_manager">
    <div style="overflow: hidden; padding-bottom: 4px;">
        <h1>
            <span class="h1_title">
                <?php echo $language->table_manager ?> 
            </span>
            <span class="title_info">
                <?php echo $language->total_rows ?>: <?php echo count($info) ?> 
                <span style="text-transform: lowercase;"><?php echo $language->records ?></span>
            </span>
        </h1>
        <div class="tools">
            <a style="margin-right: 5px; background: none; border: 0px; border-radius: 50px; float: left;" class="tool_button tooltip" title="<?php echo @$language->reload_list ?>" onclick="list_table_manager.submit('reload')" ><span style="color: #DDD;">f</span></a>
            <div style="float: left; margin-right: 3px;">
                <span class="add_new_table" style=""><?php echo @$language->add_new_table ?></span>
                <select class="table_name" name="table_name" style="width: 150px;" >
                    <option value=""><?php echo @$language->select_table ?></option>
                    <?php for($i = 0; $i < count($infoTables); $i++){ ?>
                        <option value="<?php echo $infoTables[$i] ?>"><?php echo $infoTables[$i] ?></option>
                    <?php } ?>
                </select>
                <input class="button_blue" type="button" value="<?php echo @$language->configure ?>" onclick="list_table_manager.submit('new')" />
            </div>
            <div onclick="list_table_manager.submit('edit')" class="tool_button tool_left"><span>b</span></div>
            <div onclick="list_table_manager.submit('delete')" class="tool_button tool_right"><span>c</span></div>
        </div>
    </div>
    <div class="frame">
        <form id="form" action="" method="post">
            <table class="table_info">
                <tr>
                    <th class="header checkbox"><input id="selectAll" type="checkbox" onclick="cuppa.checkbox.selectAll(this.checked)"/></th>
                    <th class="header id"><?php echo @$language->id ?></th>
                    <th class="header"><?php echo @$language->table ?></th>
                    <th class="header"><?php echo @$language->params ?></th>
                    <th class="header"><?php echo @$language->options ?></th>
                </tr>
                <?php for($i = 0; $i < count($info); $i++){ ?>
                    <?php if(($i%2) != 0){ echo "<tr>"; }else{ echo "<tr class='grey'>"; } ?>
                        <td class="checkbox" ><input id="id" class="id" type="checkbox" value="<?php echo $info[$i]["id"] ?>" /></td>
                        <td class="id"><a onclick="list_table_manager.submit('edit','<?php echo $info[$i]["id"] ?>')" ><?php echo $info[$i]["id"] ?></a></td>
                        <td><a onclick="list_table_manager.submit('edit','<?php echo $info[$i]["id"] ?>')" ><?php echo $info[$i]["table_name"] ?></a></td>
                        <td><?php echo $language->json_params ?></td>
                        <td style="text-align: right; white-space: nowrap;">
                            <a onclick="stage.loadPermissionsFilterLightbox('<?php echo $info[$i]["table_name"] ?>', 'Filters for <?php echo @$info[$i]["table_name"]  ?> ')"  class="tooltip" title="<?php echo @$language->tooltip_admin_filters ?>" >
                                <img src="templates/default/images/template/filters.png" />
                            </a>
                            <a href="component/menu/&task=new&type_field=2&table_name_field=<?php echo @$info[$i]["table_name"] ?>" title="<?php echo @$language->add_menu_tooltip ?>" class="link tooltip">
                                <img src="templates/default/images/template/menu.png" />
                            </a>
                            <a onclick="stage.loadPermissionsApiKeyLightbox('<?php echo $info[$i]["table_name"] ?>', 'Api Keys permissions for <?php echo @$info[$i]["table_name"]  ?> ')" class="tooltip" title="<?php echo @$language->tooltip_admin_api_key_permissions ?>" >
                                <img src="templates/default/images/template/api.png" />
                            </a>
                        
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </form>
    </div>
</div>