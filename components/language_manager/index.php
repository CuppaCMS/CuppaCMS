<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["administrator_document_path"]."classes/Cuppa.php";
    $cuppa = Cuppa::getInstance(); $cuppa->user->valid("admin_login");
    $language = $cuppa->language->load();
    $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
?>
<style>
    .language_manager{ position: relative; }
    .language_manager .contents{ position: relative; margin-top: 17px; }
    .language_manager .files_section{ position: relative; }
    .section span{ color: #999999 !important; }
    .language_manager a:hover{ color: #1A6DEB;  }
</style>
<script>
    language_manager = {}
    //++ chage tab
        language_manager.chageTab = function(e, item, value){
            if(e) item = this;
            var ref = $(item).attr("ref");
            $(".language_manager .tab").removeClass("selected"); $(item).addClass("selected");
            $(".language_manager .tab_content").css("display", "none");
            $(".language_manager .contents ."+ref).css("display", "block");
            cuppa.managerURL.setParams({path:"component/language_manager/"+ref}, true, null, false);
        }; $(".language_manager .tabs .tab").bind("click", language_manager.chageTab);
    //--
    //++ init
        language_manager.init = function(){
            cuppa.selectStyle(".language_manager select");
            
            if(cuppa.managerURL.path_array[2] == "rich_section" ){
                language_manager.chageTab(null, $(".language_manager .tabs .tab").get(1), ".rich_section")
            }
        }; cuppa.addEventListener("ready",  language_manager.init, document, "language_manager");
    //--
</script>
<div class="language_manager">
    <h1><?php echo @$language->language_manager ?></h1>
    <div class="tabs" style="margin-top: 15px;">
        <div class="line"></div>
        <div class="tab selected" ref="files_section"  ><div class="line"></div><?php echo @$language->standard_content ?></div>
        <div class="tab" ref="rich_section"  ><div class="line"></div><?php echo @$language->rich_content ?></div>
    </div>
    <div class="contents">
        <div class="files_section tab_content">
            <?php include "html/files.php" ?>
        </div>
        <div class="rich_section tab_content" style="display: none;">
            <?php include "html/rich.php" ?>
        </div>
    </div>
</div>