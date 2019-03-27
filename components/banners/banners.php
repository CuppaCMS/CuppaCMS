<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["web_document_path"]."administrator/classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load("web");
    $current_language = $cuppa->language->current();
    $current_country = $cuppa->country->current();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"], false, $language, true);
    //section
        if(@$banners_ids){
            // example: $banners_ids = '13,42,20';
            $cond = " id IN (".@$banners_ids.")";
        }else if(!$path || (( $cuppa->language->valid(@$path[0]) || $cuppa->country->valid(@$path[0])) && count($path) <= 1 ) ){
            $default = $cuppa->dataBase->getRow("{$cuppa->configuration->table_prefix}menu_items", "default_page = 1", true);
            $section_banners = $cuppa->dataBase->getRow("{$cuppa->configuration->table_prefix}banners_by_sections", "section = ".$default->id, true);
            $banners_ids = @$section_banners->banners;
        }else{
            $condition = "menus_id NOT IN (1,2) AND alias = '".@$path[count($path)-1]."' AND (language = '' OR language = '".$cuppa->language->current()."')";
            $section = $cuppa->dataBase->getRow("{$cuppa->configuration->table_prefix}menu_items", $condition, true);
            //++ show_in_subsection
                if(!$section){
                    $rev = array_reverse($path); array_shift($rev);
                    for($i = 0; $i < count($rev); $i++){
                        $condition = "menus_id NOT IN (1,2) AND alias = '".@$rev[$i]."' AND (language = '' OR language = '".$cuppa->language->current()."')";
                        $section_tmp = $cuppa->dataBase->getRow("{$cuppa->configuration->table_prefix}menu_items", $condition, true);
                        $show_in_subsection = $cuppa->dataBase->getColumn("{$cuppa->configuration->table_prefix}banners_by_sections","show_in_subsection","section = ".@$section_tmp->id);
                        if($show_in_subsection){ $section = $section_tmp; break; }
                    }
                }
            //--
            $section_banners = $cuppa->dataBase->getRow("{$cuppa->configuration->table_prefix}banners_by_sections", "section = ".@$section->id." AND show_in_subsection = 1", true);
            if(!$section_banners){
                $banners_ids = @$section_banners->banners;
                //++ search banners up sections
                    if(!$banners_ids){
                        $rev = array_reverse($path); array_shift($rev);
                        for($i = 0; $i < count($rev); $i++){
                            $condition = "menus_id NOT IN (1,2) AND alias = '".@$rev[$i]."' AND (language = '' OR language = '".$cuppa->language->current()."')";
                            $section_tmp = $cuppa->dataBase->getRow("{$cuppa->configuration->table_prefix}menu_items", $condition, true);
                            $show_in_subsection = $cuppa->dataBase->getColumn("{$cuppa->configuration->table_prefix}banners_by_sections","show_in_subsection","section = ".@$section_tmp->id);
                            if($show_in_subsection){ $section = $section_tmp; break; }
                        }
                        $section_banners = $cuppa->dataBase->getRow("{$cuppa->configuration->table_prefix}banners_by_sections", "section = ".@$section->id, true);
                        $banners_ids = @$section_banners->banners;
                    }
                //--
                //++ search home section
                    if(!$banners_ids){
                        $default = $cuppa->dataBase->getRow("{$cuppa->configuration->table_prefix}menu_items", "default_page = 1", true);
                        $section_banners = $cuppa->dataBase->getRow("{$cuppa->configuration->table_prefix}banners_by_sections", "section = ".$default->id." AND show_in_subsection = 1", true);
                        $banners_ids = @$section_banners->banners;
                    }
                //--
            }else{
                $banners_ids = @$section_banners->banners;
            }
        }
        $banners_ids = @join(",",json_decode($banners_ids));
    // banners
        $cond = " enabled = 1 ";
        $cond .= " AND (language = '' OR language = '".@$current_language."') AND id IN (".@$banners_ids.") ";
        $cond .= " AND (countries LIKE '' OR countries LIKE '%\"".$current_country."\"%' ) ";
        $cond .= " AND ( show_from <= '". date('Y-m-d') ."' OR show_from = '0000-00-00' ) ";
        $cond .= " AND ( show_to >= '". date('Y-m-d') ."' OR show_to = '0000-00-00' ) ";
        $banners = $cuppa->dataBase->getList("{$cuppa->configuration->table_prefix}banners", $cond, "", "FIELD(id, ".$banners_ids.")", true);
?>
<?php if(@$banners){ ?>
    <style>
        .banners{ height: 500px; }
        .banners .item{ background-position: center; background-size: cover; opacity: 0; }
        .banners .points{ position: absolute; bottom: 20px; left: 50%; transform: translate(-50%, 0px) !important; -webkit-transform: translate(-50%, 0px) !important; }
        .banners .points .point{ cursor: pointer; display: inline-block; width: 15px; height: 15px; background: #4991C7; border-radius: 50px; margin: 0px 5px; }
        .banners .points .point.selected{ opacity: 0.5; background: #FFF; cursor: default; pointer-events: none; }
        .banners .btn_back{ cursor: pointer; background: rgba(0,0,0,0.5); color: #FFF; position: absolute; top: 50%; margin-top: -20px; left: 20px; padding: 20px 10px; }
        .banners .btn_next{ cursor: pointer; background: rgba(0,0,0,0.5); color: #FFF; position: absolute; top: 50%; margin-top: -20px; right: 20px; padding: 20px 10px; }
        /* custom */
    </style>
    <script>
        banners = {}
        //++ change
            banners.change = function(params, init){
                if(init){ 
                    $(".banners .list").append(params.element);
                }else{
                    var time = new TimelineMax();
                        time.fromTo(params.element, 0.7, {alpha:0}, {alpha:1, ease:Cubic.easeInOut} );
                    $(".banners .list").append(params.element);
                }
            }
        //--
        //++ resize
            banners.resize = function(){ cuppa.grid(".banners"); }; 
        //--
        //++ end
            banners.removed = function(e){ cuppa.removeEventGroup("banners"); if(banners.slide) banners.slide.distroy(); }
        //--
        //++ init
            banners.init = function(){
                cuppa.addEventListener("resize", banners.resize, window, "banners"); banners.resize();
                cuppa.addEventListener("removed", banners.removed, ".banners", "banners");
                cuppa.responsiveImagesWidth(".banners img");
                cuppa.svgSwitch(".banners .svg");
                banners.slide = new cuppa.slider(".banners .item", {callback:banners.change, duration:<?php echo @$section_banners->duration ?>, nextButton:".banners .btn_next", backButton:".banners .btn_back", points:".banners .points .point"});
                banners.slide.change(0);
            }; document.addEventListener('DOMContentLoaded', banners.init, true);
        //--
    </script>
    <div class="banners <?php echo str_replace(","," ", $section_banners->classes) ?>" >
        <div class="list cover">
            <?php forEach($banners as $item){ ?>
                <div class="item item_<?php echo @$item->id ?> cover" style="background-image: url(administrator/<?php echo $item->background ?>); <?php echo @$item->css ?>" >
                    <div class="item_wrapper cover">
                        <?php $cuppa->echoString($item->content); ?>
                    </div>
                </div>
                <?php @$cuppa->echoString(@$item->code); ?>
            <?php } ?>
        </div>
        <div class="points noselect">
            <?php if(count($banners) > 1){ forEach($banners as $item){ ?>
                <div class="point"><div class="sub_point cover"></div></div>
            <?php } } ?>
        </div>
        <?php if(count($banners) > 1){ ?>
            <div class="btn_back"><span><</span></div>
            <div class="btn_next"><span>></span></div>
        <?php } ?>
        <?php @$cuppa->echoString(@$section_banners->code); ?>
    </div>
<?php } ?>