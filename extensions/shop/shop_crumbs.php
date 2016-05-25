<?php
    @session_start();
    if(!@$_SESSION["cuSession"]){ echo '<script> window.location=document.URL; </script>'; exit(); }
    include_once($_SESSION["cuSession"]->paths->administrator->document_path."classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $token = $cuppa->security->token;
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $language = $cuppa->language->load("web");
    
    $section = (@count($path) > 2) ? @$path[count($path)-2] : "";
    if($section){
        $section = $cuppa->dataBase->getRow("cu_menu_items", "menus_id IN (3,4,5) AND alias = '".$section."'", $token, true);
        $crumbs_title = $cuppa->dataBase->getRoadPath("cu_menu_items", @$section->id, "id", "parent_id", "title", $token, false, $language);
        $crumbs_alias = $cuppa->dataBase->getRoadPath("cu_menu_items", @$section->id, "id", "parent_id", "alias", $token, false, $language, true);
        $back = $path; array_pop($back);
        $back = $cuppa->utils->translatePath($back, $language, true, true);
    }
?>
<style>
    .crumbs{
        position: relative;
        font-size: 11px;
        color: #444444;
        max-width: 1100px;
        margin: 0 auto;
        padding: 5px 20px;
    }
    .crumbs .house{
        position: relative;
        top: 2px;
    }
    .crumbs .item{
        position: relative;
        display: inline-block;
        padding: 0px 5px;
        cursor: pointer;
        transition-duration: 0.2s;
        text-decoration: underline;
        vertical-align: top;
    }
    .crumbs .item:hover{
        background: #EDEDED;
    }
    .crumbs .arrow_crumbs{
        position: relative;
        vertical-align: top;
    }
    .crumbs .item_cuttent{
        position: relative;
        display: inline-block;
        padding: 0px 5px;
        color: #666;
    }
</style>
<script>
    crumbs = {}
    //++ resize
        crumbs.resize = function(){
            
        }; 
    //--
    //++ end
        crumbs.end = function(){
            cuppa.removeEventGroup("crumbs");
        }; cuppa.addRemoveListener(".crumbs", crumbs.end);
    //--
    //++ init
        crumbs.init = function(){
            cuppa.addEventListener("resize", crumbs.resize, window, "crumbs"); crumbs.resize();
        }; cuppa.addEventListener("ready",  crumbs.init, document, "crumbs");
    //--
</script>
<?php if(@$crumbs_title){ ?>
    <div class="crumbs font_new_sans_light">
        <a href=""><img class="house" src="administrator/media/page/template/house.png"  /></a>
        <?php
            for($i = 0; $i < count($crumbs_title); $i++){ ?>
            <?php 
                $url = array_slice($crumbs_alias, 0, $i+1);
            ?>
            <a class="item" href="<?php echo $cuppa->language->current()."/".$cuppa->utils->getFriendlyUrl(@$language->shop)."/".join("/",$url); ?>" >
                <span><?php echo $cuppa->language->getValue($crumbs_title[$i], $language, false); ?></span>
            </a>
            <span class="arrow_crumbs"> > </span>
        <?php } ?>
        <div class="item_cuttent"></div>
    </div>
<?php } ?>