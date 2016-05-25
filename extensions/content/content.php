<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["web_document_path"]."administrator/classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load("web");
    $current_language = $cuppa->language->current();
    $current_country = $cuppa->country->current();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    //section  
        if(@$content_ids){
            // example: $conten_ids = '13,42,20';
            $cond = " id IN (".@$content_ids.")";
        }else if(!$path || ( ( $cuppa->language->valid(@$path[0]) || $cuppa->country->valid(@$path[0]) ) && count($path) <= 1 )  ){
            $section_content = $cuppa->dataBase->getRow("ex_content_by_sections", "section = 0", true);
            $content_ids = $cuppa->dataBase->getColumn("ex_content_by_sections","contents","section = 0");
            $content_ids = @join(",",json_decode($content_ids));
            $cond = " id IN (".@$content_ids.")";
        }else{
            $condition = "menus_id NOT IN (1,2) AND alias = '".@$path[count($path)-1]."' AND (language = '' OR language = '".$cuppa->language->current()."')";
            $section = $cuppa->dataBase->getRow("cu_menu_items", $condition, true);
            //++ show_in_subsection
                if(!$section){
                    $rev = array_reverse($path); array_shift($rev);
                    for($i = 0; $i < count($rev); $i++){
                        $condition = "menus_id NOT IN (1,2) AND alias = '".$rev[$i]."' AND (language = '' OR language = '".$cuppa->language->current()."')";
                        $section_tmp = $cuppa->dataBase->getRow("cu_menu_items", $condition, true);
                        $show_in_subsection = $cuppa->dataBase->getColumn("ex_content_by_sections","show_in_subsection","section = ".@$section_tmp->id);
                        if($show_in_subsection){ $section = $section_tmp; break; }
                    }
                }
            //--
            $section_content = $cuppa->dataBase->getRow("ex_content_by_sections", "section = ".@$section->id, true);
            $content_ids = @$section_content->contents;
            //++ search up sections
                if(!$content_ids){
                    $rev = array_reverse($path); array_shift($rev);
                    for($i = 0; $i < count($rev); $i++){
                        $condition = "menus_id NOT IN (1,2) AND alias = '".$rev[$i]."' AND (language = '' OR language = '".$cuppa->language->current()."')";
                        $section_tmp = $cuppa->dataBase->getRow("cu_menu_items", $condition, true);
                        $section_content = $cuppa->dataBase->getRow("ex_content_by_sections", "section = ".@$section_tmp->id, true);
                        if(@$section_content->show_in_subsection){ $section = $section_tmp; break; }
                    }
                    $content_ids = $cuppa->dataBase->getColumn("ex_content_by_sections","contents","section = ".@$section->id);
                }
            //--
            $content_ids = @join(",",json_decode($content_ids));
            $cond = " id IN (".@$content_ids.")";
        }
        $cond .= " AND enabled = 1 ";
        $cond.= " AND (language = '' OR language = '".@$current_language."') ";
        $cond.= " AND (countries = '' OR countries LIKE '%\"".$current_country."\"%' ) ";
        $cond.= " AND countries_not NOT LIKE '%\"".$current_country."\"%' ";
        if($region) $cond.= " AND region = '".$region."' ";
        else $cond .= " AND region = '' ";
    //content
        $content = $cuppa->dataBase->getList("ex_content", $cond, "", "FIELD(id, ".$content_ids.")", true);
?>
<?php if($content){ ?>
    <style>
        .content{ }
        .content_extension .content_item{ background-position: center; background-size: cover; }
        /* custom */
        
    </style>
    <script>
        content = {}
        //++ resize
            content.resize = function(){ cuppa.grid(".content .content_item, .content .content_item *"); }; 
        //--
        //++ end
            content.removed = function(e){ cuppa.removeEventGroup("content"); }
        //--
        //++ init
            content.init = function(){
                cuppa.addEventListener("resize", content.resize, window, "content"); content.resize();
                cuppa.addEventListener("removed", content.removed, ".content", "content");
                cuppa.responsiveImagesWidth(".content img");
            }; cuppa.addEventListener("ready",  content.init, document, "content");
        //--
    </script>
    <div class="content content_extension">
        <?php forEach($content as $item){ ?>
            <div class="content_item <?php echo str_replace(",", " ", @preg_replace("/(inner)\w+/","", @$item->classes) ); ?>" id="<?php echo $item->id ?>" style="<?php  if($item->background_image) echo "background-image:url(administrator/".$item->background_image.")"; ?>" >
                <?php if(@$item->anchor){ ?> <a name="<?php echo @$item->anchor ?>" style="display: block !important; height: 0px!important; width: 0px !important; visibility: hidden !important; position: absolute !important;"></a><?php } ?>
                <?php $cuppa->echoString($item->content); ?>
            </div>
        <?php } ?>
        <?php @$cuppa->echoString(@$section_content->code); ?>
    </div>
<?php } ?>