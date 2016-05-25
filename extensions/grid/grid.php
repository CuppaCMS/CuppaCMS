<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["web_document_path"]."administrator/classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load("web");
    $current_language = $cuppa->language->current();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    //section  
        if(@$grid_ids){
            // example: $grid_ids = '13,42,20';
            $cond = "(language = '' OR language = '".@$current_language."') AND id IN (".@$grid_ids.")";
        }else if(!$path){
            $grid_ids = $cuppa->dataBase->getColumn("ex_grid_by_sections","grids","section = 0");
            $grid_ids = @join(",",json_decode($grid_ids));
            $cond = "enabled = 1 and (language = '' OR language = '".@$current_language."') AND id IN (".@$grid_ids.")";
        }else{
            $condition = "menus_id NOT IN (1,2) AND alias = '".@$path[count($path)-1]."' AND (language = '' OR language = '".$cuppa->language->current()."')";
            $section = $cuppa->dataBase->getRow("cu_menu_items", $condition, true);
            //++ show_in_subsection
                if(!$section){
                    $rev = array_reverse($path); array_shift($rev);
                    for($i = 0; $i < count($rev); $i++){
                        $condition = "menus_id NOT IN (1,2) AND alias = '".$rev[$i]."' AND (language = '' OR language = '".$cuppa->language->current()."')";
                        $section_tmp = $cuppa->dataBase->getRow("cu_menu_items", $condition, true);
                        $show_in_subsection = $cuppa->dataBase->getColumn("ex_grid_by_sections","show_in_subsection","section = ".@$section_tmp->id);
                        if($show_in_subsection){ $section = $section_tmp; break; }
                    }
                }
            //--
            $grid_ids = $cuppa->dataBase->getColumn("ex_grid_by_sections","grids","section = ".@$section->id);
            $grid_ids = @join(",",json_decode($grid_ids));
            $cond = "enabled = 1 and (language = '' OR language = '".@$current_language."') AND id IN (".@$grid_ids.")";
        }
    //content
        $contents = $cuppa->dataBase->getList("ex_grid", $cond, "", "FIELD(id, ".$grid_ids.")", true);
?>
<div class="grid max_width">
    <style>
        .grid{ overflow: hidden; }
        .grid .box{ display: block; }
        .grid .content_over{ transition: 0.3s; opacity: 0;}
        .grid .box:hover .content_over{ opacity: 1; }
    </style>
    <script>
        grid = {}
        //++ resize
            grid.resize = function(){ 
                cuppa.grid(".grid .box");
                jQuery(".grid").packery({ columnWidth:0, gutter: 0, itemSelector: ".grid .box", transitionDuration:0 });
            }; 
        //--
        //++ end
            grid.removed = function(e){ cuppa.removeEventGroup("grid"); }
        //--
        //++ init
            grid.init = function(){
                cuppa.addEventListener("resize", grid.resize, window, "grid"); grid.resize(); $(".grid img").load(grid.resize); TweenMax.delayedCall(0.1, grid.resize);
                cuppa.addEventListener("removed", grid.removed, ".grid", "grid");
            }; cuppa.addEventListener("ready",  grid.init, document, "grid");
        //--
    </script>
    <?php forEach($contents as $item){ ?>
        <?php if(@$item->link){ ?>
            <a href="<?php echo @$item->link ?>" target="<?php echo @$item->target ?>" class="box <?php echo str_replace(",", " ", @$item->classes) ?>" >
                <div class="cover" style="margin:<?php echo $item->margin ?>; <?php echo @$item->css ?>; background-image: url(administrator/<?php echo $item->background ?>);"></div>
                <div class="content cover" style="margin:<?php echo $item->margin ?>;" ><?php $cuppa->echoString($item->content) ?></div>
                <?php if($item->content_over){ ?>
                    <div class="content_over cover" style="margin:<?php echo $item->margin ?>;" ><?php $cuppa->echoString($item->content_over) ?></div>
                <?php } ?>
            </a>
        <?php }else{ ?>
            <div class="box bg_cover <?php echo str_replace(",", " ", @$item->classes) ?>" >
                <div class="cover" style="margin:<?php echo $item->margin ?>; <?php echo @$item->css ?>; background-image: url(administrator/<?php echo $item->background ?>);"></div>
                <div class="content cover" style="margin:<?php echo $item->margin ?>;" ><?php $cuppa->echoString($item->content) ?></div>
                <?php if($item->content_over){ ?>
                    <div class="content_over cover" style="margin:<?php echo $item->margin ?>;" ><?php $cuppa->echoString($item->content_over) ?></div>
                <?php } ?>
            </div>
        <?php } ?>
    <?php } ?>
</div>