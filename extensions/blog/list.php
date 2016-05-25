<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["web_document_path"]."administrator/classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load("web");
    $current_language = $cuppa->language->current();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    if(!@$path2) $path2 = $cuppa->utils->getUrlVars(@$_POST["path2"]);
    // articles
        $cond = "enabled = 1 AND (language = '' OR language = '".$current_language."')";
        if(@$path2["q"]){
            $cond .= " AND ( title LIKE '%".@$path2["q"]."%' ";
            $cond .= " <> abstract LIKE '%".@$path2["q"]."%'";
            $cond .= " <> content LIKE '%".@$path2["q"]."%'";
            $cond .= " <> tags LIKE '%".@$path2["q"]."%'";
            $cond .= " ) ";
        }else if(@$path[2]){ 
            $category = $cuppa->dataBase->getRow("ex_blog_categories", "alias = '".$path[count($path)-1]."'", true);
            $cond .= " AND categories LIKE '%\"".$category->id."\"%'";
        }
        if(@$_REQUEST["q"]){
            $cond .= " AND ( title LIKE '%".@$_REQUEST["q"]."%' ";
            $cond .= " <> abstract LIKE '%".@$_REQUEST["q"]."%'";
            $cond .= " <> content LIKE '%".@$_REQUEST["q"]."%'";
            $cond .= " <> tags LIKE '%".@$_REQUEST["q"]."%'";
            $cond .= " ) ";
        }
        $item_amounts = 10;
        $articles = $cuppa->dataBase->getList("ex_blog_articles", $cond, $item_amounts, "date DESC, id DESC", true);
    //--
?>
<style>
    .b_list{ padding: 20px 0px; }
    .b_list .article{ cursor: pointer; }
    .b_list .article .img{  background: center no-repeat; background-size: cover; border: 1px solid rgba(0,0,0,0.1); }

</style>
<script>
    b_list = {}
    //++ load more
        b_list.item_amounts = <?php echo $item_amounts ?>;
        b_list.page = b_list.item_amounts;
        b_list.disableLoad = false;
        b_list.load = function(){
            b_list.disableLoad = true;
            var data = {}
                data.info = {};
                data.info.condition = "<?php echo $cuppa->jsonEncode(@$cond); ?>";
                data.info.limit = b_list.page+","+b_list.item_amounts;
                data.info = cuppa.jsonEncode(data.info);
                data["function"] = "loadArticles";
                $.ajax({url:"html/items/blog/classes/functions.php", type:"POST", data:data, success:Ajax_Result});
                function Ajax_Result(result){
                    result = cuppa.jsonDecode(result);
                    if(result){
                        if(result.length >= b_list.item_amounts){ b_list.disableLoad = false; b_list.page += b_list.item_amounts; }
                        for(var i = 0; i < result.length; i++){
                            var item = $($(".b_list .article")[0]).clone();
                                item.attr("href", "<?php echo $current_language ?>/blog/"+result[i].cat_alias+"/"+result[i].alias);
                                item.find(".title").html(result[i].title);
                                item.find(".tags").html(result[i].tags);
                                item.find(".by").html(result[i].user.name);
                                item.find(".desc").html(result[i]["abstract"]);
                            $(".b_list").append(item);
                        }
                    }
                }
        }
        //++ onScroll
            b_list.onScroll = function(){
                var scroll = cuppa.scrollPercentStatus("body");
                if(scroll.y < 0.7 || b_list.disableLoad) return;
                b_list.load();
            };
        //--
    //--
    //++ resize
        b_list.resize = function(){ $(".b_list .article .img").height( $(".b_list .article .img").width()*0.4 ); }; 
    //--
    //++ end
        b_list.removed = function(e){ cuppa.removeEventGroup("b_list"); }
    //--
    //++ init
        b_list.init = function(){
            cuppa.addEventListener("resize", b_list.resize, window, "b_list"); b_list.resize();
            cuppa.addEventListener("removed", b_list.removed, ".wrapper .b_list", "b_list");
            TweenMax.delayedCall(1, function(){ cuppa.addEventListener("scroll", b_list.onScroll, window, "b_list"); b_list.onScroll(); });
        }; cuppa.addEventListener("ready",  b_list.init, document, "b_list");
    //--
</script>
<div class="b_list">
    <?php if(@$articles){ forEach($articles as $index => $item){ ?>
        <?php
            $user = $cuppa->dataBase->getRow("cu_users", "id = ".$item->user, true);
            $cat_id = json_decode($item->categories); $cat_id = @$cat_id[0];
            $cat_alias = $cuppa->dataBase->getPathRoad("ex_blog_categories", $cat_id, "id", "parent", "alias", true, $language, true);
            $url = $current_language.$cuppa->language->translatePath("/$blog_path/".$cat_alias."/".$item->alias, $language, true, true);
            
        ?>
        <a class="article max_width" style="overflow: hidden; display: block; margin: 0px 0px 30px;" href="<?php echo $url ?>"  >
            <div class="title title1 t_30" ><?php echo $item->title ?></div>
            <div  style="display: none;" ><span><?php echo $cuppa->language->value("Tags", $language) ?>:</span> <span class="tags"><?php echo $item->tags ?></span></div>
            <div class="t_14 " style="text-transform: uppercase;" > <span class="f_source_bold"><?php echo $cuppa->language->value("By", $language)  ?>:</span> <span class="by"><?php echo $user->name ?></span></div>
            <div class="img" style="background-image: url(administrator/<?php echo $item->image ?>);"></div>  
            <div class="desc t_16" style="margin: 10px 0px;" ><?php echo $item->abstract ?></div>
        </a>
    <?php } }else{ ?>
        <div class="no_result" style="height: 300px;" >
            <div class="center_align_1">
                <div class="center_align_2">
                    <?php $cuppa->echoString($cuppa->langValueRich("blog_no_result_message")); ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>