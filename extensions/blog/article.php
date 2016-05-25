<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["web_document_path"]."administrator/classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load("web");
    $current_language = $cuppa->language->current();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $item = $cuppa->dataBase->getRow("ex_blog_articles", "alias = '".$path[count($path)-1]."'", true);
    // related articles by tags
        $tags = array_filter(explode(",", $item->tags)); 
        $condition = "enabled = 1 AND id <> ".$item->id." AND tags REGEXP '".join("|",$tags)."'";
        $related =  $cuppa->dataBase->getList("ex_blog_articles", $condition, "2", "date DESC, id DESC", true);
?>
<style>
    .b_article{ padding: 20px 0px; }
    .b_article .img{  background: center no-repeat; background-size: cover; border: 1px solid rgba(0,0,0,0.1); }

</style>
<script>
    b_article = {}
    //++ resize
        b_article.resize = function(){
            $(".b_article .img").height( $(".b_article .img").width()*0.4 );
        }; 
    //--
    //++ end
        b_article.removed = function(e){ cuppa.removeEventGroup("b_article"); }
    //--
    //++ init
        b_article.init = function(){
            cuppa.addEventListener("resize", b_article.resize, window, "b_article"); b_article.resize();
            cuppa.addEventListener("removed", blog.removed, ".b_article", "b_article");
        }; cuppa.addEventListener("ready",  b_article.init, document, "b_article");
    //--
</script>
<div class="b_article">
     <?php
        $user = $cuppa->dataBase->getRow("cu_users", "id = ".$article->user, true);
        $cat_id = json_decode($article->categories); $cat_id = @$cat_id[0];
        $cat_alias =  $cuppa->dataBase->getColumn("ex_blog_categories", "alias", "id = ".$cat_id);
     ?>
    <div class="img" style="background-image: url(administrator/<?php echo $article->image ?>);"></div>  
    <div class="title title1 t_30" ><?php echo $item->title ?></div>
    <div  style="display: none;" ><span><?php echo $cuppa->language->value("Tags", $language) ?>:</span> <span class="tags"><?php echo $item->tags ?></span></div>
    <div class="t_14 " style="text-transform: uppercase;" > <span class="f_source_bold"><?php echo $cuppa->language->value("By", $language)  ?>:</span> <span class="by"><?php echo $user->name ?></span></div>
    <div class="desc t_16" style="margin: 10px 0px;" ><?php $cuppa->echoString($item->content) ?></div>
    <!-- share -->
        <div class="share" style="text-align: center;" >
            <style> .share img{ margin: 5px 3px 0px; } </style>
            <div class="f_18 f_source_bold" style="color: #296DA4;" ><?php echo $cuppa->language->value("Share", $language); ?></div>
            <img class="button_alpha" onclick="cuppa.share('facebook')" src="administrator/media/page/template/share_facebook.png" />
            <img class="button_alpha" onclick="cuppa.share('twitter', '', '<?php echo $article->title ?>')" src="administrator/media/page/template/share_twitter.png" />
            <img class="button_alpha" onclick="cuppa.share('google')" src="administrator/media/page/template/share_google.png" />
            <img class="button_alpha" onclick="cuppa.share('linkedIn')" src="administrator/media/page/template/share_linkedIn.png" />
        </div>
    <!-- -->
    <!-- comments -->
        <div class="coments" style="margin-top: 20px;">
            <div id="disqus_thread"></div>
            <script type="text/javascript">
                var disqus_shortname = 'panamapacifico';
                (function() {
                    var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                        dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                })(); function disqus_config(e) { this.callbacks.onReady.push(function(e){ }); }
            </script>
        </div>
    <!-- -->
    <!-- related -->
        <?php if($related){ ?>
            <div class="related_list" style="clear: both; display: none;">
                <div class="f_18 f_source_semibold" style="color: #333333; text-transform: uppercase; padding: 30px 0px 0px; border-bottom: 1px solid #CCCCCC;"><?php echo $cuppa->language->value("related_post", $language)  ?></div>    
                <?php forEach($related as $item){ ?>
                    <?php
                        $user = $cuppa->dataBase->getRow("cu_users", "id = ".$item->user, true);
                        $cat_id = json_decode($item->categories); $cat_id = @$cat_id[0];
                        $cat_alias =  $cuppa->dataBase->getColumn("ex_blog_categories", "alias", "id = ".$cat_id);
                    ?>
                    <a class="r_item link" href="<?php echo $current_language.$cuppa->language->translatePath("/blog/".$cat_alias."/".$item->alias, $language, true, true) ?>" >
                        <div class="column column1" width="123">
                            <div class="user_img" style="background-image: url(administrator/<?php echo $user->image ?>);"></div>
                        </div>
                        <div class="column column2" >
                            <div class="f_30 "><?php echo $item->title ?></div>
                            <div class="f_16" style="margin: 5px 0px 0px;"><span><?php echo $cuppa->language->value("by", $language) ?></span> <span style="color: #F33A11;"><?php echo $user->name ?></span></div>
                        </div>
                    </a>
                <?php } ?>
            </div>
        <?php } ?>
    <!-- -->
</div>