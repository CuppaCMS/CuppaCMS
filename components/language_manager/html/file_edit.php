<?php
    include_once(realpath(__DIR__ . '/../../..')."/classes/Cuppa.php");
    $cuppa = Cuppa::getInstance(); $cuppa->user->valid("admin_login");
    $language = $cuppa->language->load();
    $current_language = $cuppa->language->current();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $available_languages = $cuppa->language->getLanguagesAvailable();
    $info = $cuppa->language->getFileInfo($cuppa->POST("file"));
?>
<style>
    .file_edit{
        
    }
</style>
<script>
    file_edit = {}
    //++ add
        file_edit.add = function(){
            var row = $(".assets .row").clone();
            $(".file_edit .table_info tbody  tr:nth-child(1)").before(row);
            $('.file_edit tbody').sortable();
        }
    //--
    //++ delete
        file_edit.deleteRows = function(){
            var items = $(".file_edit .table_info td .checkbox:checked");
            $(".file_edit input[type='checkbox']").prop("checked", false);
            for(var i = 0; i < items.length; i++){
                $(items[i]).parent().parent().remove();
            }
            $('.file_edit tbody').sortable();
        }
    //++ save
        file_edit.save = function(){
            var info = {}
            for(var i = 0; i < $(".table_info .row").length; i++){
                var row = $(".table_info .row")[i];
                var label =  cuppa.trim($(row).find(".label input").val());
                for(var j = 0; j < $(row).find("input").length; j++){
                    var input = $(row).find("input")[j];
                    if($(input).attr("lang")){
                        var lang = $(input).attr("lang");
                        if(!info[lang]) info[lang]= {}
                        if(label) info[lang][label] = cuppa.trim($(input).val());
                    }
                }
            }
            var data = {}
                data.info = cuppa.jsonEncode(info);
                data.file = $(".file_edit .file").text();
                data["function"] = "saveFile";
            cuppa.blockadeScreen();
            menu.showCharger(true);
            jQuery.ajax({url:"classes/ajax/Language.php", type:"POST", data:data, success:Ajax_Result});
            function Ajax_Result(result){
                menu.showCharger(false);
                cuppa.blockadeScreen(false);
                if(result == "0"){
                    stage.toast("<?php echo @$language->error_to_save_info ?>", "error");
                }else{
                    stage.toast("<?php echo @$language->information_has_been_saved ?>");
                }
            }
        }
    //--
    //++ end
        file_edit.end = function(){
            cuppa.removeEventGroup("file_edit");
        }; cuppa.addRemoveListener(".file_edit", file_edit.end);
    //--
    //++ init
        file_edit.init = function(){
            cuppa.tooltip();
            if($(".file_edit .table_info tr").length <= 1) file_edit.add();
            $('.file_edit tbody').sortable();
        }; cuppa.addEventListener("ready",  file_edit.init, document, "file_edit");
    //--
</script>
<div class="file_edit">
    <div class="header" style="margin-bottom: 5px;">
        <h1 style="display: inline-block;"><?php echo $language->editing_file ?> <span class="title_info file"><?php echo str_replace(".json", "", $cuppa->POST("file")) ?></span></h1>
        <div class="tools" style="float: right; top: 8px; position: relative;">
	        <a onclick="file_edit.save()" class="tool_button tooltip tool_left" title="<?php echo @$language->tooltip_save ?>" ><span>e</span></a>
	        <a onclick="file_edit.add()" class="edit tool_button tooltip" title="<?php echo @$language->tooltip_add_row ?>" ><span>a</span></a>
	        <a onclick="file_edit.deleteRows()"class="tool_button tooltip tool_right" title="<?php echo @$language->tooltip_delete_item ?>" ><span>c</span></a>
        </div>
    </div>
    <table class="table_info">
        <thead>
            <tr class="row_header">
                <th class="checkbox">
                    <input onchange="cuppa.checkbox.selectAll(this)" type="checkbox" />
                </th>
                <th><?php echo @$language->label ?></th>
                <?php for($i = 0; $i < count($available_languages ); $i++){?>
                    <th style="text-transform: none;"><?php echo $available_languages[$i] ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php if($info){ ?>
                <!-- add File Info -->
                    <?php for($i = 0; $i < count($info->labels); $i++){ ?> 
                        <tr class="row">
                            <td><input class="checkbox" type="checkbox" /></td>
                            <td class="label">
                                <input class="text" value="<?php echo $info->labels[$i] ?>" style="width: 100%;" />
                            </td>
                            <?php
                                for($j = 0; $j < count($available_languages); $j++){
                                    $current_language = $available_languages[$j];
                                    $current_label = $info->labels[$i];
                            ?>
                                <td >
                                    <input class="text lang" lang="<?php echo @$available_languages[$j] ?>" value="<?php echo @$info->languages->{$current_language}->{$current_label} ?>" style="width: 100%;"  />
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                <!-- End add File Info -->
            <?php }else{ ?>
                <tr class="row">
                    <td><input class="checkbox" type="checkbox" /></td>
                    <td class="label"><input class="text" style="width: 100%;" /></td>
                    <?php for($j = 0; $j < count($available_languages); $j++){ ?>
                        <td><input class="text lang" lang="<?php echo @$available_languages[$j] ?>" style="width: 100%;" /></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <div class="assets" style="display: none;">
        <table>
            <tr class="row">
                <td><input class="checkbox" type="checkbox" /></td>
                <td class="label"><input class="text" style="width: 100%;" /></td>
                <?php for($j = 0; $j < count($available_languages); $j++){ ?>
                    <td><input class="text lang" lang="<?php echo @$available_languages[$j] ?>" style="width: 100%;" /></td>
                <?php } ?>
            </tr>
        </table>
    </div>
</div>