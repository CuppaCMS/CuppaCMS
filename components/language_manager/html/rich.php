<?php
    include_once(realpath(__DIR__ . '/../../..')."/classes/Cuppa.php");
    $cuppa = Cuppa::getInstance(); $cuppa->user->valid("admin_login");
    $language = $cuppa->language->load();
    $current_language = $cuppa->language->current();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    //++ tasks
        if($cuppa->POST("task") == "delete_row"){
            $cuppa->dataBase->delete($cuppa->configuration->table_prefix."language_rich_content", "id = ".$cuppa->POST("id"));
            echo "<script> stage.toast('".@$language->information_has_been_deleted."'); </script>";
        }
    //--
    $info = $cuppa->dataBase->getList($cuppa->configuration->table_prefix."language_rich_content", "", "", "", true);
?>
<style>
    .rich{
        position: relative;
    }
    .rich .edit_admin_table{ padding: 0px; }
    .rich .frame td{ position: relative; vertical-align: middle; }
    .rich .frame th{ padding-bottom: 5px; vertical-align: top; font-size: 11px; text-transform: uppercase; }
</style>
<script>
    rich = {}
    //++ edit
        rich.edit = function(id){
            var data = {}
                data.id = id;
                data.table = "<?php echo $cuppa->configuration->table_prefix ?>language_rich_content";
                data.redirect = "component/language_manager/rich_section";
            menu.showCharger(true);
            jQuery.ajax({url:"components/table_manager/html/edit_admin_table.php", type:"POST", data:data, success:Ajax_Result});
            function Ajax_Result(result){
                menu.showCharger(false);
                $(".language_manager .rich .editor").html(result);
                $(".language_manager .rich .editor").find(".btn_close").attr("onclick","rich.close()");
            }
        }
    //--
    //++ new
        rich.newRow = function(){
            var data = {}
                data.table = "<?php echo $cuppa->configuration->table_prefix ?>language_rich_content";
                data.redirect = "component/language_manager/rich_section";
            menu.showCharger(true);
            jQuery.ajax({url:"components/table_manager/html/edit_admin_table.php", type:"POST", data:data, success:Ajax_Result});
            function Ajax_Result(result){
                menu.showCharger(false);
                $(".language_manager .rich .editor").html(result);
                $(".language_manager .rich .editor").find(".btn_close").attr("onclick","rich.close()");
            }
        }
    //--
    //++ close
        rich.close = function(){
            cuppa.managerURL.setParams({path:"component/language_manager/rich_section"}, true)
        }
    //--
    //++ delete
        rich.deleteRow = function(id){
            var data = {}
                data.id = id;
                data.task = "delete_row";
                cuppa.managerURL.setParams({path:"component/language_manager/rich_section"}, true, data);
        }
    //--
    //++ init
        rich.init = function(){
            
        }; cuppa.addEventListener("ready",  rich.init, document, "rich");
    //--
</script>
<div class="rich table">
    <!-- Left -->
        <div class="frame td" style="width: 280px; padding: 15px;">
            <table>
                <tr>
                    <th style="text-align: left; color: #999;"><span><?php echo $language->label ?></span></th>
                    <th><img src="templates/default/images/template/language.png" /></th>
                    <th style="width: 20px;"></th>
                </tr>
                <?php for($i = 0; $i < @count($info); $i++){ ?>
                    <tr>
                        <td>
                            <img src="templates/default/images/template/file_10.png" />
                            <a onclick="rich.edit('<?php echo $info[$i]->id ?>')"><span style="margin-left: 5px;"><?php echo $info[$i]->label ?></span></a>
                        </td>
                        <td style="text-align: center; color: #999; text-transform: lowercase;">
                            <?php echo ($info[$i]->language) ? $info[$i]->language : $language->all ?>
                        </td>
                        <td style="text-align: center;">
                            <a onclick="rich.deleteRow('<?php echo $info[$i]->id ?>')" ><img src="templates/default/images/template/close.png" style="height: 18px;" /></a>
                        </td>
                    </tr>
                <?php } ?>
                <?php if(!$info){ ?>
                    <tr>
                        <td colspan="3" style="text-align: center; padding-top: 5px;"><span class="title_info"><?php echo $language->no_items_created ?></span></td>
                    </tr>
                <?php } ?>
            </table>
            <input onclick="rich.newRow()" class="button_blue" type="button" value="<?php echo $language->new_item ?>" style="margin-top: 20px; width: 100%;"  />
        </div>
    <!-- -->
    <!-- Right -->
        <div class="editor td" style="padding: 0px 15px;">
            <div class="no_file" style="text-align: center; padding: 40px 0px;">
                <img src="templates/default/images/template/face.png" style="vertical-align: middle;"  />
                <div style="display: inline-block; text-align: left; margin-left: 10px; vertical-align: middle;">
                    <h2 style="color: #777;"><?php echo $language->no_item_selected ?></h2>
                    <div style="max-width: 250px; color: #AAA;"><?php echo $language->no_item_selected_message ?></div>
                </div>
            </div>
        </div>
    <!-- -->
</div>