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
            $default = $cuppa->dataBase->getRow("cu_menu_items", "default_page = 1", true);
            $section_content = $cuppa->dataBase->getRow("ex_content_by_sections", "section = ".$default->id, true);
            $content_ids = $section_content->contents;
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
            //++ search home section
                if(!$content_ids){
                    $default = $cuppa->dataBase->getRow("cu_menu_items", "default_page = 1", true);
                    $section_content = $cuppa->dataBase->getRow("ex_content_by_sections", "section = ".$default->id." AND show_in_subsection = 1", true);
                    $content_ids = $section_content->contents;
                }
            //--
            //++ search error section
                if(!$content_ids){
                    $error = $cuppa->dataBase->getRow("cu_menu_items", "error_page = 1", true);
                    $section_content = $cuppa->dataBase->getRow("ex_content_by_sections", "section = ".$error->id, true);
                    $content_ids = $section_content->contents;
                }
            //--
            $content_ids = @join(",",json_decode($content_ids));
            $cond = " id IN (".@$content_ids.")";
        }
        $cond .= " AND enabled = 1 ";
        $cond .= " AND (language = '' OR language = '".@$current_language."') ";
        $cond .= " AND (countries = '' OR countries LIKE '%\"".$current_country."\"%' ) ";
        $cond .= " AND countries_not NOT LIKE '%\"".$current_country."\"%' ";
        if(@$region) $cond .= " AND region = '".$region."' ";
        else $cond .= " AND region = '' ";
    //content
        $contents = $cuppa->dataBase->getList("ex_content", $cond, "", "FIELD(id, ".$content_ids.")", true);
?>
<?php if($contents){ ?>
    <style>
        .contents{ }
        .content_extension .content_item{ background-position: center; background-size: cover; }
        /* custom */
        
    </style>
    <script>
        contents = {}
        //++ resize
            contents.resize = function(){ 
                cuppa.grid(".contents .content_item, .contents .content_item *");
            }; 
        //--
        //++ end
            contents.removed = function(e){ cuppa.removeEventGroup("content"); }
        //--
        //++ init
            contents.init = function(){
                cuppa.addEventListener("resize", contents.resize, window, "contents"); contents.resize();
                cuppa.addEventListener("removed", contents.removed, ".contents", "contents");
                cuppa.responsiveImagesWidth(".contents img");
            }; cuppa.addEventListener("ready",  contents.init, document, "contents");
        //--
    </script>
    <div class="contents content_extension">
        <?php forEach($contents as $item){ ?>
            <div class="content_item <?php echo str_replace(",", " ", @preg_replace("/(inner)\w+/","", @$item->classes) ); ?>" id="content_<?php echo $item->id ?>" style="<?php echo @$item->css ?>" >
                <?php if(@$item->anchor){ ?> <a class="anchor" name="<?php echo @$item->anchor ?>" style="display: block !important; height: 0px!important; width: 0px !important; visibility: hidden !important; position: absolute !important;"></a><?php } ?>
                <?php $cuppa->echoString($item->content); ?>
                <?php $cuppa->echoString(@$item->code); ?>
            </div>
        <?php } ?>
        <?php echo $cuppa->echoString(@$section_content->code); ?>
    </div>
<?php } ?>