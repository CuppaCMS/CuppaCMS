<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["web_document_path"]."administrator/classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load("web");
    $current_language = $cuppa->language->current();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    if(!isset($limit)) $limit = 3;
    if(!isset($order)) $order = "date DESC";
    $blog_path = $cuppa->utils->getFriendlyUrl(@$language->noticias);
    $cond = "enabled = 1 AND (language = '' OR language = '".$current_language."')";
    if(isset($categories)){ // $categories = '2,3'
        $categories = explode(",", $categories);
        $cond .= " AND ( ";
        forEach($categories as $index=>$cat){ if($index) $cond .= " OR  "; $cond .= " categories LIKE '%\"".$cat."\"%'"; }
        $cond .= " ) ";
    }
    if($condition) $cond .= " AND  $condition ";
    $lasts = $cuppa->dataBase->getList("ex_blog_articles", $cond, $limit, $order, true);
?>
<style> 
    .blog_list_category{ overflow: hidden; padding: 0px; }
    .blog_list_category .item{ padding: 10px 0px ; }
    .blog_list_category .item .img{ height: 140px; background: center no-repeat; background-size: cover; }     
</style> 
<script>
    blog_list_category = {}
    //++ resize
        blog_list_category.resize = function(){ $(".blog_list_category .item .img").height( $(".blog_list_category .item .img").width()*0.6 ); }; 
    //--
    //++ init
        blog_list_category.init = function(){
            cuppa.addEventListener("resize", blog_list_category.resize, window, "blog_list_category"); blog_list_category.resize();
            if('<?php echo @$path[2] ?>') $(".blog_list_category .button_<?php echo @$path[2] ?>").addClass("selected");
            else $($(".blog_list_category .button").get(0)).addClass("selected");
            //++ selected
                try{
                    if(cuppa.managerURL.path_array[2]) $(".blog_list_category ."+cuppa.managerURL.path_array[2]).addClass("selected");
                    else $(".blog_list_category .all").addClass("selected");
                }catch(err){ }
            //--
        }; cuppa.addEventListener("ready",  blog_list_category.init, document, "blog_list_category");
    //--
</script>
<div class="blog_list_category">
    <?php forEach($lasts as $item){ ?>
        <?php
            $cat_id = json_decode($item->categories); $cat_id = @$cat_id[0];
            $cat_alias =  $cuppa->dataBase->getColumn("ex_blog_categories", "alias", "id = ".$cat_id);
            $url = $current_language.$cuppa->language->translatePath("/$blog_path/".$cat_alias."/".$item->alias, $language, true, true);
        ?>
        <a class="item button_alpha" href="<?php echo $url ?>"  >
            <?php 
                $months = explode(",",$language->months_array);
                $date = explode("-", $item->date);
            ?>
            <div class="date t_20" style="color: #0074af;"><?php   echo $months[$date[1]-1]." ".$date[2].", ".$date[0]; ?></div>
            <div class="t_22 f_source_bold " style="color: #0074af; margin: 0px 0px;"><?php echo $item->title ?></div>
            <div class="img" style="background-image: url(administrator/<?php echo $item->image ?>);"></div>
            <div style="margin-top: 3px;"><?php echo $item->abstract ?></div>
        </a>
    <?php } ?>
</div>