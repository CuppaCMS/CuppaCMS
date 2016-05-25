<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["web_document_path"]."administrator/classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load("web");
    $current_language = $cuppa->language->current();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $categories = $cuppa->dataBase->getList("ex_blog_categories", "", "", "`order` ASC, name ASC", true);
?>
<style> 
    .blog .top_bar{ height: 50px; background: #FFF;  }
    .blog .top_bar .categories .item{ display: inline-block; padding: 0px 15px; text-transform: capitalize; }
    .blog .top_bar .categories .item:hover{ color: #F35809; }
    .blog .top_bar .search_box{ position: absolute; top: 10px; right: 0px; }
    .blog .top_bar .search_box .input{ width: 200px; height: 30px; border: 0px; background: #EEE; padding: 0px 10px; color: #444; }
    .blog .top_bar .search_box .btn{ position: absolute; right: 5px; top: 5px; opacity: 0.5; }
    .blog .top_bar .search_box .btn:hover{ opacity: 1; }        
</style> 
<script>
    top_bar = {}
    //++ resize
        top_bar.resize = function(){ }; 
    //--
    //++ init
        top_bar.init = function(){
            cuppa.addEventListener("resize", top_bar.resize, window, "top_bar"); top_bar.resize();
            if('<?php echo @$path[2] ?>') $(".top_bar .button_<?php echo @$path[2] ?>").addClass("selected");
            else $($(".top_bar .button").get(0)).addClass("selected");
            //++ selected
                try{
                    if(cuppa.managerURL.path_array[2]) $(".top_bar ."+cuppa.managerURL.path_array[2]).addClass("selected");
                    else $(".top_bar .all").addClass("selected");
                }catch(err){ }
            //--
        }; cuppa.addEventListener("ready",  top_bar.init, document, "top_bar");
    //--
</script>
<div class="top_bar">
    <div class="max_width">
        <div class="categories f_14 f_source_black" style="position: absolute; left: 0px; top: 13px;">
            <a href="<?php echo $current_language."/$blog_path" ?>" class="item btn link all"><?php echo $cuppa->language->value("All", $language) ?></a>
            <?php forEach($categories as $item){ ?>
                <a href="<?php echo $current_language."/$blog_path/".$item->alias ?>" class="item btn link <?php echo $item->alias ?>"><?php echo $cuppa->language->value($item->name, "web") ?></a>
            <?php } ?>
        </div>
        <form class="search_box" method="GET" action="<?php echo $current_language."/".$cuppa->language->value("$blog_path", $language, true)."/"; ?>" >
            <input class="input" name="q" placeholder="<?php echo $cuppa->language->value("Search", $language) ?>" />
            <a class="btn" onclick="$('.blog .search_box').submit()"><img src="administrator/js/cuppa/images/search.png"  height="15px" /></a>
        </form>
    </div>
</div>