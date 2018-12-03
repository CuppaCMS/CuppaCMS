<?php 
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["administrator_document_path"]."classes/Cuppa.php";
    $cuppa = Cuppa::getInstance(); $cuppa->user->valid("admin_login");
    $language = $cuppa->language->load();
    if(is_numeric($group)){
        $group_data = $cuppa->dataBase->getRow($cuppa->configuration->table_prefix."permissions_group", "id = '$group'", true);
    }else{
        $group_data = $cuppa->dataBase->getRow($cuppa->configuration->table_prefix."permissions_group", "name = '$group'", true);    
        $group = $group_data->id;
    }
    $wrapper_div = $group."_".str_replace(array(",","."),array("-","-"),$reference);
    $permissions = $cuppa->dataBase->getList($cuppa->configuration->table_prefix."permissions", "`group` = ".$group_data->id, "", "", true);
    $user_groups = $cuppa->dataBase->getList($cuppa->configuration->table_prefix."api_keys", "", "", "", true);
    $info = $cuppa->dataBase->getRow($cuppa->configuration->table_prefix."permissions_data", "`group` = '".$group."' AND `reference` = '".$reference."'", true);
    $info_data = (array) @$cuppa->utils->jsonDecode($info->data);
?>
<style>
    .permissions{
        position: relative;
        margin: 0px 0px;
    }
    .permissions .title{
        padding-left: 0px;
        font-weight: bold;
    }
    .permissions .user_group{
        position: relative;
        margin: 0px 0px 10px;
    }
    .permissions table td{
        white-space: nowrap;
        padding: 0px 0px 5px;
    }
    .permissions table tr td:first-child{
        padding-left: 15px;
    }
</style>
<script>
    permissionsClass = {}
    permissionsClass.ajax = null;
    //++ Register data
        permissionsClass.registerData = function(wrapper_div){
            var permissions = jQuery(wrapper_div).get();
            var data_to_save = {};
            //++ get select info
                var selects = jQuery(permissions).find(".value").get();
                for(var i = 0; i < selects.length; i++){
                    var label = "value_"+jQuery(selects[i]).attr("group")+"_"+jQuery(selects[i]).attr("permission");
                    data_to_save[label] = jQuery(selects[i]).val();
                }
            //--
            //++ get default value
                 var defaults = jQuery(permissions).find(".default").get();
                 for(var i = 0; i < defaults.length; i++){
                    var label = "default_"+jQuery(selects[i]).attr("group")+"_"+jQuery(selects[i]).attr("permission");
                    data_to_save[label] = jQuery(defaults[i]).val();
                 }
            //--
            // Save data
                var data = {}
                    data["function"] = "updatePermisionFiles";
                    data.group = jQuery(permissions).find(".group").val();
                    data.reference = jQuery(permissions).find(".reference").val();
                    data.data = cuppa.jsonEncode(data_to_save);
                    if(permissionsClass.ajax){ permissionsClass.ajax.abort(); }
                    permissionsClass.ajax = jQuery.ajax({url:"classes/ajax/Functions.php", type:"POST", data:data, success:permissionsClass.result});
        }
        permissionsClass.result = function(result){ }
    //--
    cuppa.selectStyle(".permissions select");
</script>
<div class="permissions <?php echo $wrapper_div ?>" >
    <?php for($i = 0; $i < count($user_groups); $i++){ ?>
        <div class="user_group">
            <div class="section" style="margin: 0px 0px 10px;"><div></div><span style="color: #999999 !important;"><?php echo $user_groups[$i]->name ?></span></div>
            <table>
                <?php for($j = 0; $j < count($permissions); $j++){ ?>
                <?php
                    $values = $cuppa->dataBase->getList($cuppa->configuration->table_prefix."permissions_values", "permission = ".$permissions[$j]->id, "", "", true);
                ?>
                    <tr class="permission">
                        <td style="width: 150px;">
                            <?php
                                echo $cuppa->language->getValue($permissions[$j]->name, $language); 
                            ?>
                        </td>
                        <td style="width: 150px;">
                            <select class="value" style="width: 100%;" onchange="permissionsClass.registerData('.<?php echo $wrapper_div ?>')" group="<?php echo $user_groups[$i]->id ?>" permission="<?php echo @$permissions[$j]->id ?>" >
                                <?php for($k = 0; $k < count($values); $k++){ ?>
                                    <?php if(@$info_data["value_".$user_groups[$i]->id."_".$permissions[$j]->id] == $values[$k]->id){ ?>
                                        <option selected="selected" value="<?php echo $values[$k]->id ?>"><?php echo $cuppa->language->getValue($values[$k]->value, $language); ?></option>
                                    <?php }else{ ?>
                                        <option value="<?php echo $values[$k]->id ?>"><?php echo $cuppa->language->getValue($values[$k]->value, $language); ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </td>
                        <td style="width: 80px;">
                            <label class="tooltip" title="Tdd" style="font-style: italic; padding-right: 10px; padding-left: 10px;"><?php echo $language->default_value ?></label>
                            <?php if($permissions[$j]->accept_default_value){ ?>
                                <input type="text" value="<?php echo @$info_data["default_".$user_groups[$i]->id."_".$permissions[$j]->id] ?>" class="default" onchange="permissionsClass.registerData('.<?php echo $wrapper_div ?>')" oninput="permissionsClass.registerData('.<?php echo $wrapper_div ?>')" group="<?php echo $user_groups[$i]->id ?>" permission="<?php echo @$permissions[$j]->id ?>" />
                            <?php }else{ ?>
                                <input disabled="disabled" type="text" value="<?php echo @$info_data["default_".$user_groups[$i]->id."_".$permissions[$j]->id] ?>" class="default" onchange="permissionsClass.registerData('.<?php echo $wrapper_div ?>')" oninput="permissionsClass.registerData('.<?php echo $wrapper_div ?>')" group="<?php echo $user_groups[$i]->id ?>" permission="<?php echo @$permissions[$j]->id ?>" />
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    <?php } ?>
    <input type="hidden" class="group" value="<?php echo $group ?>" />
    <input type="hidden" class="reference" value="<?php echo $reference ?>" />
    <script>
        permissionsClass.registerData('.<?php echo $wrapper_div ?>');
    </script>
</div>
