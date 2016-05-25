<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["web_document_path"]."administrator/classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load("web");
    $current_language = $cuppa->language->current();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $blog_path = $cuppa->utils->getFriendlyUrl(@$language->noticias);
    $articles = $cuppa->dataBase->getList("ex_blog_articles", "enabled = 1 AND (language = '' OR language = '".$current_language."')", "3", "date DESC, id DESC", true);
?>
<style>
    .grid_last{ padding: 0px; text-align: center;  }
    .grid_last .item{ padding: 10px; transition: 0.3s; transition-property: background-color; vertical-align: top; text-align: left; display: inline-block; background: #FFFFFF; max-width: 356px; width: 100%;}
    .grid_last .item .img{ background-position: center; background-size: cover; }
    .grid_last .item:hover{ background: #eee; }
    
    @media screen and (max-width: 850px){
        .grid_last .cell{ width:50% !important; }
    }
    @media screen and (max-width: 550px){
        .grid_last .cell{ width:100% !important; }
    }
</style>
<script>
    grid_last = {}
    //++ resize
        grid_last.resize = function(){ 
            $(".grid_last .list .img").height( $(".grid_last .list .img").width()*0.62 );
            $(".grid_last .item").height("auto");
            var height = cuppa.getCSSValues(".grid_last .item", "height", "max");
            $(".grid_last .item").height( height );
        }; 
    //--
    //++ init
        grid_last.init = function(){
            cuppa.addEventListener("resize", grid_last.resize, window, "grid_last"); grid_last.resize();
        }; cuppa.addEventListener("ready",  grid_last.init, document, "grid_last");
    //--
</script>
<?php if($articles){ ?>
    <div class="grid_last row max_width">
        <div class="list" >
            <?php if($articles){ forEach($articles as $item){ ?>
                <?php
                    $user = $cuppa->dataBase->getRow("cu_users", "id = ".$item->user, true);
                    $cat_id = json_decode($item->categories); $cat_id = @$cat_id[0];
                    $cat_alias =  $cuppa->dataBase->getColumn("ex_blog_categories", "alias", "id = ".$cat_id);
                ?>
                <div class="cell" style="width: 33.3333%; padding: 0px 15px 15px; ">
                    <a class="item" href="<?php echo $current_language.$cuppa->language->translatePath("/$blog_path/".$cat_alias."/".$item->alias, $language, true, true) ?>">
                        <div class="img" style="background-image: url(administrator/<?php echo @$item->background ? $item->thumbnail : $item->image ?>);" ></div>
                        <div class="t_20 " style="margin: 10px 0px 0px; color: #8bb54e;"><?php echo $item->title ?></div>
                        <div class="t_12 f_source_light" style="font-style: italic; "><?php echo $cuppa->language->value("by", $language) ?> <span ><?php echo $user->name ?></span></div>
                        <div class="t_14" style="padding: 10px 0px 20px;" ><?php echo $cuppa->utils->cutText(" ", $item->abstract, 100,"...") ?></div>
                    </a>
                </div>
            <?php } } ?>
        </div>
    </div>
<?php } ?>