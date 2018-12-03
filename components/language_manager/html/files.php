<?php
    include_once(realpath(__DIR__ . '/../../..')."/classes/Cuppa.php");
    $cuppa = Cuppa::getInstance(); $cuppa->user->valid("admin_login");
    $language = $cuppa->language->load();
    $current_language = $cuppa->language->current();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    //++ tasks
        if($cuppa->POST("task") == "create_file"){
            $cuppa->language->createFile($cuppa->POST("file"));
            echo "<script> stage.toast('".@$language->information_has_been_saved."'); </script>";
        }else if($cuppa->POST("task") == "delete_file"){
            $cuppa->language->deleteFile($cuppa->POST("file"));
            echo "<script> stage.toast('".@$language->information_has_been_deleted."'); </script>";
        }else if($cuppa->POST("task") == "create_reference"){
            $cuppa->language->createReference($cuppa->POST("reference"), $cuppa->POST("base"));
            echo "<script> stage.toast('".@$language->information_has_been_saved."'); </script>";
        }else if($cuppa->POST("task") == "delete_reference"){
            $cuppa->language->deleteReference($cuppa->POST("reference"));
            echo "<script> stage.toast('".@$language->information_has_been_deleted."'); </script>";
        }
    //--
    $params = @$cuppa->utils->getUrlVars(@$_POST["params"]);
    $available_languages = $cuppa->language->languages();
    $aviable_languages_json = base64_encode(json_encode($available_languages));
    $file_list = $cuppa->file->getList(realpath(__DIR__ . '/../../..')."/language/".$cuppa->language->current(), true);

?>
<style>
    .language_files{
        position: relative;
    }
    .language_files .file{ position: relative; margin: 5px 0px;}
    .language_files .file span{ margin-left: 5px; }
    .cuppa_reference_base{
        width: 50px;
    }
    .cuppa_delete_reference{ width: 150px !important; }
</style>
<script>
    language_files = {}
    //++ edit
        language_files.edit = function(value){
            var data = {}
                data.file = value+".json";
            menu.showCharger(true);
            jQuery.ajax({url:"components/language_manager/html/file_edit.php", type:"POST", data:data, success:Ajax_Result});
            function Ajax_Result(result){
                menu.showCharger(false);
                $(".language_files .file_data").html(result);
            }
        }
    //--
    //++ create
        language_files.create = function(){
            var data = {}
                data.file = $(".language_files .file_name").val();
                data["task"] = "create_file";
            if(!data.file){
                cuppa.blockade();
                cuppa.setContent({url:"js/cuppa/html/alert.php", name:"alert", preload:false, data:{title:"Message", message:"<?php echo @$language->write_a_name_message ?>"}, duration:0.3 });
                return;
            }
            if("<?php echo $params["mode"] ?>" == "lightbox"){  
                try{ alert_lightbox.reload(data); }catch(err){};
            }else{
                cuppa.managerURL.setParams({path:"component/language_manager"}, true, data);
            }
        }
    //--
    //++ delet
        language_files.deleteFile = function(file){
            var data = {}
                data.file = file;
                data["task"] = "delete_file";
            if("<?php echo $params["mode"] ?>" == "lightbox"){  
                try{ alert_lightbox.reload(data); }catch(err){};
            }else{
                cuppa.managerURL.setParams({path:"component/language_manager"},true, data);
            }
        }
    //--
    //++ create reference
        language_files.createReference = function(){
            var data = {}
                data.reference = $(".language_files .reference").val();
                data.base = $(".language_files .reference_base").val();
                data["task"] = "create_reference";
            if(!data.reference){
                cuppa.blockade();
                cuppa.setContent({url:"js/cuppa/html/alert.php", name:"alert", preload:false, data:{title:"Message", message:"<?php echo $language->write_a_reference_message ?>"}, duration:0.3});
                return;
            }
            if("<?php echo $params["mode"] ?>" == "lightbox"){  
                try{ alert_lightbox.reload(data); }catch(err){};
            }else{
                cuppa.managerURL.setParams({path:"component/language_manager"},true, data);
            }
        }
    //--
    //++ delete reference
        language_files.deleteReference = function(){
            var data = {}
                data.reference = $(".language_files .delete_reference").val();
                data["task"] = "delete_reference";
            if(!data.reference){
                cuppa.blockade();
                cuppa.setContent({url:"js/cuppa/html/alert.php", name:"alert", preload:false, data:{title:"Message", message:"<?php echo @$language->select_reference_to_delete ?>"}, duration:0.3 });
                return;
            }
            if("<?php echo $params["mode"] ?>" == "lightbox"){  
                try{ alert_lightbox.reload(data); }catch(err){};
            }else{
                cuppa.managerURL.setParams({path:"component/language_manager"},true,data);
            }
        }
    //++ end
        language_files.end = function(){
            cuppa.removeEventGroup("language_files");
        }; cuppa.addRemoveListener(".language_files", language_files.end);
    //--
    //++ init
        language_files.init = function(){
            cuppa.selectStyle(".language_files select");
            $(".reference_base").val("<?php echo @$cuppa->configuration->language_default ?>").change();
        }; cuppa.addEventListener("ready",  language_files.init, document, "language_files");
    //--
</script>
<div class="language_files table">
    <!-- Left -->
        <div class="frame td" style="width: 280px; padding: 15px;">
            <!-- List -->
                <div class="section" style="margin: 0px 0px 10px;"><div></div><span><?php echo $language->files ?></span></div>
                <?php for($i = 0; $i < count($file_list); $i++){ ?>
                    <div class="file">
                        <img src="templates/default/images/template/file_10.png" />
                        <a onclick="language_files.edit('<?php echo $file_list[$i] ?>')"><span><?php echo $file_list[$i] ?></span></a>
                        <?php if( $file_list[$i] != "administrator" ){ ?>
                            <a onclick="language_files.deleteFile('<?php echo $file_list[$i] ?>')" style="position: absolute; top: 0px; right: 0px;" ><img src="templates/default/images/template/close.png" height="18px" /></a>
                        <?php } ?>
                    </div>
                <?php } ?>
            <!-- -->
            <!-- New file -->
                <div class="section" style="margin: 15px 0px 10px;"><div></div><span><?php echo $language->create_new_file ?></span></div>
                <div>
                    <input class="file_name" style="width: 150px;" value=""/>
                    <input onclick="language_files.create()" class="button_blue" type="button" value="<?php echo $language->create ?>" />
                
                </div>
            <!-- -->
            <!-- New reference -->
                <div class="section" style="margin: 15px 0px 10px;"><div></div><span><?php echo $language->create_language_reference ?></span></div>
                <div>
                    <input class="reference" style="width: 150px;" placeholder="ej. (en, es, fr, ...)" />
                    <input type="button" onclick="language_files.createReference()" value="<?php echo @$language->create ?>" class="button_blue" />
                    <div style="margin-top: 10px;">
                        <span style="font-style: italic; margin-right: 5px; color: #999999; text-transform: lowercase;"><?php echo $language->reference_base ?></span>
                        <select class="reference_base" name="reference_base" >
                            <?php for($i = 0; $i < count($available_languages); $i++){ ?>
                                <option value="<?php echo $available_languages[$i] ?>"><?php echo $available_languages[$i] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            <!-- -->
            <!-- Delete -->
                <div class="section" style="margin: 15px 0px 10px;"><div></div><span><?php echo $language->delete_language_reference ?></span></div>
                 <select class="delete_reference" name="delete_reference">
                    <option value="" ><?php echo $language->select_language ?></option>
                    <?php for($i = 0; $i < count($available_languages); $i++){ ?>
                        <option value="<?php echo $available_languages[$i] ?>"><?php echo $available_languages[$i] ?></option>
                    <?php } ?>
                </select>
                <input class="button_red" type="button" onclick="language_files.deleteReference()" value="<?php echo @$language->delete ?>" />
            <!-- -->
        </div>
    <!-- -->
    <!-- Right -->
        <div class="file_data td" style="padding: 0px 15px;">
            <div class="no_file" style="text-align: center; padding: 40px 0px;">
                <img src="templates/default/images/template/face.png" style="vertical-align: middle;"  />
                <div style="display: inline-block; text-align: left; margin-left: 10px; vertical-align: middle;">
                    <h2 style="color: #777;"><?php echo $language->no_file_selected ?></h2>
                    <div style="max-width: 250px; color: #AAA;"><?php echo $language->no_file_selected_message ?></div>
                </div>
            </div>
        </div>
    <!-- -->
</div>