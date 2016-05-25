<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["web_document_path"]."administrator/classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load("web");
    $current_language = $cuppa->language->current();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $categories = $cuppa->dataBase->getList("ex_blog_categories", "", "", "`order` ASC, name ASC", true);
    $lasts = $cuppa->dataBase->getList("ex_blog_articles", "enabled = 1 AND (language = '' OR language = '".$current_language."')", "3", "`date` DESC", true);
?>
<style> 
    .blog .bar_lateral{ background: #FFF; margin: 20px 0px; overflow: hidden; padding: 20px; }
    .blog .bar_lateral .categories .item{ display: block; padding: 2px 0px; text-transform: capitalize; }
    .blog .bar_lateral .categories .item:hover{ color: #315B8C; }
    
    .blog .bar_lateral .last .item{ padding: 10px 0px; }
    .blog .bar_lateral .last .item .img{ height: 140px; background: center no-repeat; background-size: cover; }
    
    
    
    .blog .bar_lateral .search_box .input{ width: 100%; height: 30px; border: 0px; background: #EEE; padding: 0px 10px; color: #444; }
    .blog .bar_lateral .search_box .btn{ position: absolute; right: 5px; top: 8px; opacity: 0.5; }
    .blog .bar_lateral .search_box .btn:hover{ opacity: 1; }        
</style> 
<script>
    bar_lateral = {}
    //++ resize
        bar_lateral.resize = function(){ $(".bar_lateral .item .img").height( $(".bar_lateral .item .img").width()*0.5 ); }; 
    //--
    //++ init
        bar_lateral.init = function(){
            cuppa.addEventListener("resize", bar_lateral.resize, window, "bar_lateral"); bar_lateral.resize();
            if('<?php echo @$path[2] ?>') $(".bar_lateral .button_<?php echo @$path[2] ?>").addClass("selected");
            else $($(".bar_lateral .button").get(0)).addClass("selected");
            //++ selected
                try{
                    if(cuppa.managerURL.path_array[2]) $(".bar_lateral ."+cuppa.managerURL.path_array[2]).addClass("selected");
                    else $(".bar_lateral .all").addClass("selected");
                }catch(err){ }
            //--
        }; cuppa.addEventListener("ready",  bar_lateral.init, document, "bar_lateral");
    //--
</script>
<div class="bar_lateral">
    <form class="search_box" method="GET" action="<?php echo $current_language."/".$cuppa->language->value("$blog_path", $language, true)."/"; ?>" >
        <input class="input" name="q" placeholder="<?php echo $cuppa->language->value("Search", $language) ?>" />
        <a class="btn" onclick="$('.blog .search_box').submit()"><img src="administrator/js/cuppa/images/search.png"  width="15px" /></a>
    </form>
    <div class="title1 t_18" style="margin: 20px 0px 0px;"><?php echo $cuppa->langValue("categories",$language); ?></div>
    <div class="categories" style="margin: 10px 15px;" >
        <a href="<?php echo $current_language."/$blog_path" ?>" class="item btn link all"><?php echo $cuppa->language->value("All", $language) ?></a>
        <?php forEach($categories as $item){ ?>
            <a href="<?php echo $current_language."/$blog_path/".$item->alias ?>" class="item btn link <?php echo $item->alias ?>"><?php echo $cuppa->language->value($item->name, "web") ?></a>
        <?php } ?>
    </div>
    <div class="title1 t_18" style="margin: 20px 0px 0px;"><?php echo $cuppa->langValue("last news",$language); ?></div>
    <div class="last" style="margin: 0px ;">
        <?php forEach($lasts as $item){ ?>
            <?php
                $cat_id = json_decode($item->categories); $cat_id = @$cat_id[0];
                $cat_alias =  $cuppa->dataBase->getColumn("ex_blog_categories", "alias", "id = ".$cat_id);
                $url = $current_language.$cuppa->language->translatePath("/$blog_path/".$cat_alias."/".$item->alias, $language, true, true);
            ?>
            <a class="item button_alpha" style="display: block;" href="<?php echo $url ?>"  >
                <div class="img" style="background-image: url(administrator/<?php echo $item->image ?>);"></div>
                <div class="title1 t_16 f_source_regular" style="color: #333; margin: 3px 0px;"><?php echo $item->title ?></div>
            </a>
        <?php } ?>
    </div>
</div>