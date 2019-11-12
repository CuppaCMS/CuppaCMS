<?php
    include_once(realpath(__DIR__ . '/../..')."/classes/Cuppa.php");
    $cuppa = Cuppa::getInstance(); $cuppa->user->valid("admin_login");
    $language = $cuppa->language->load();
    $current_language = $cuppa->language->current();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $language_files = $cuppa->language->getLanguagesAvailable();
?>
<style>
    .configuration{ position: relative; }
    .configuration .contents{ position: relative; margin-top: 17px; }
    .configuration td{ padding: 3px 10px; }
</style>
<script>
    configuration = {}
    //++ chage tab
        configuration.chageTab = function(e, item){
            if(e) item = this;
            var ref = $(item).attr("ref");
            cuppa.managerURL.setParams({path:"component/configuration/"+ref}, true, null, false);
            $(".configuration .tab").removeClass("selected"); $(item).addClass("selected");
            $(".configuration .tab_content").css("display", "none"); $(".configuration .contents ."+ref).css("display", "block");
        }; $(".configuration .tabs .tab").bind("click", configuration.chageTab);
    //--
    //++ save
        configuration.save = function(){
            if(!$(".form_file").valid()) return;
            cuppa.blockadeScreen();
            menu.showCharger();
            var data = {}
                data.file = cuppa.urlToObject($(".form_file").serialize());
                data.file["code"] = $.base64Encode(data.file["code"]);
                data.file = cuppa.jsonEncode(data.file);
                data["function"] = "saveConfigData";
            jQuery.ajax({url:"classes/ajax/Functions.php", type:"POST", data:data, success:Ajax_Result});
            function Ajax_Result(result){
                menu.showCharger(false);
                cuppa.blockadeScreen(false);
                stage.toast("<?php echo @$language->information_has_been_saved ?>");
            }
        }
    //--
    //++ exit
        configuration.exit = function(){
            cuppa.managerURL.setParams({path:""}, true);
        }
    //--
    //++ c+s save
        configuration.ctr_s = function(event){
            if((event.ctrlKey || event.metaKey) && event.which == 83) {
                event.preventDefault();
                try{ configuration.save('save_and_edit'); }catch(err){ };
                return false;
            };
        }; $(document).off("keydown").on("keydown", configuration.ctr_s);
    //--
    //++ init
        configuration.init = function(){
            //++ Auto select items
                $(".secure_login").val('<?php echo @$cuppa->configuration->secure_login ?>');
                $(".language_default").val('<?php echo @$cuppa->configuration->language_default ?>');
                $(".country_default").val('<?php echo @$cuppa->configuration->country_default ?>');
                $(".global_encode").val('<?php echo @$cuppa->configuration->global_encode ?>');
                $(".smtp").val('<?php echo @$cuppa->configuration->smtp ?>');
                $(".smtp_security").val('<?php echo @$cuppa->configuration->smtp_security ?>');
                $(".ssl").val('<?php echo @$cuppa->configuration->ssl ?>');
                $(".lateral_menu").val('<?php echo @$cuppa->configuration->lateral_menu ?>');
                $(".redirect_to").val('<?php echo @$cuppa->configuration->redirect_to ?>');
            //--
            cuppa.aceEditor(".configuration [name=code]", "100%", "600px","php")
            cuppa.tooltip();
            cuppa.selectStyle(".configuration select");
            //++ Auto select tab
                if(cuppa.managerURL.path_array[2]){
                    var tab = $(".tabs .tab[ref='"+cuppa.managerURL.path_array[2]+"']").get();
                    configuration.chageTab(null, tab);
                }
            //--
        }; cuppa.addEventListener("ready",  configuration.init, document, "configuration");
    //--
</script>
<div class="configuration">
    <div style="overflow: hidden;">
        <h1 style="float: left;"><?php echo $language->settings ?></h1>
        <div class="tools" >
            <input class="button_blue" type="button" value="<?php echo $language->save ?>" onclick="configuration.save()" />
            <input class="button_red btn_close" type="button" value="<?php echo $language->cancel ?>" onclick="configuration.exit()" />
        </div>
    </div>
    <div class="tabs" style="margin-top: 15px;">
        <div class="line"></div>
        <div ref="general" class="tab selected"  ><div class="line"></div><?php echo $language->general ?></div>
        <div ref="database" class="tab"  ><div class="line"></div><?php echo $language->database ?></div>
        <div ref="files" class="tab" ><div class="line"></div><?php echo $language->files ?></div>
        <div ref="email" class="tab"  ><div class="line"></div><?php echo $language->email ?></div>
        <div ref="code" class="tab"  ><div class="line"></div><?php echo $language->code ?></div>
    </div>
    <div class="contents">
        <form class="form_file" autocomplete="0">
            <div class="tab_content general">
                <?php include "html/general.php"; ?>
            </div> 
            <div class="tab_content database" style="display: none;">
                <?php include "html/database.php"; ?>
            </div> 
            <div class="tab_content files" style="display: none;">
                <?php include "html/files.php"; ?>
            </div> 
            <div class="tab_content email" style="display: none;">
                <?php include "html/email.php"; ?>
            </div> 
            <div class="tab_content code" style="display: none;">
                <?php include "html/code.php"; ?>
            </div>
        </form>
    </div>
</div>