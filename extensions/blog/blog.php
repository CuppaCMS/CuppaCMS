<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["web_document_path"]."administrator/classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load("web");
    $current_language = $cuppa->language->current();
    $blog_path = $cuppa->utils->getFriendlyUrl(@$language->noticias);
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $article = $cuppa->dataBase->getRow("ex_blog_articles", "alias = '".@$path[count(@$path)-1]."'", true);
?>
<style>
    .blog{ }
    @media screen and (max-width: 1200px){
        .blog .column1{ padding:0 30px 0 10px !important; }
        .blog .title{ font-size: 22px; }
        .blog .t_16{ font-size: 14px; }
        .blog .b_list .article{ margin: 0 0 10px !important; }
    }
    @media screen and (max-width: 850px){
        .blog .column1{ padding:0 10px !important; width: 100% !important; }
        .blog .column2{ width: 100% !important; }
        .blog .bar_lateral{ margin: 0px !important; }
    }
</style>
<script>
    blog = {}
    //++ resize
        blog.resize = function(){ }; 
    //--
    //++ end
        blog.removed = function(e){ cuppa.removeEventGroup("blog"); }
    //--
    //++ init
        blog.init = function(){
            cuppa.addEventListener("resize", blog.resize, window, "blog"); blog.resize();
            cuppa.addEventListener("removed", blog.removed, ".blog_wrapper", "blog");
        }; cuppa.addEventListener("ready",  blog.init, document, "blog");
    //--
</script>
<div class="blog blog_wrapper max_width" style="overflow: hidden;">
    <div class="column1" style="float: left; width: calc(100% - 300px); padding: 0px 80px 0px 10px;">
        <?php 
            if($article) include "article.php";
            else include "list.php"; 
        ?>
    </div>
    <div class="column2" style="float: right; width: 300px; ">
        <?php include "others/bar_lateral.php";  ?>
    </div>
    <?php 
        //include "others/top_bar.php"; 
        //if($article) include "article.php";
        //else include "list.php"; 
    ?>
</div>