<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["web_document_path"]."administrator/classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load("web");
    $current_language = $cuppa->language->current();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $group = $cuppa->dataBase->getRow("ex_accordion_groups", "id = '".$group ."' || name = '".$group."'", true);
    
    $cond = " enabled = 1 ";
    $cond .= " AND `group` = ".@$group->id;
    $cond.= " AND (language = '' OR language = '".@$current_language."') ";
    $cond.= " AND (countries = '' OR countries LIKE '%\"".$current_country."\"%' ) ";
    $cond.= " AND countries_not NOT LIKE '%\"".$current_country."\"%' ";
    $accordion = $cuppa->dataBase->getList("ex_accordion", $cond, "", "`order` ASC", true);
?>
<?php if($accordion){ ?>
    <div class="accordion">
        <style>
            .accordion{ }
            .accordion .item{ margin-bottom: 20px; }
            .accordion .item .title{ color: #00a9e0; text-transform: uppercase; padding: 10px 10px 10px 40px; cursor: pointer; }
            .accordion .item.selected .title{ /*cursor: default !important; pointer-events: none; */ }
            .accordion .item .content_wrapp{ overflow: hidden; height: 0px; }
            .accordion .item .content{ padding: 10px 10px 10px 40px; color: #888b8d;  }
            /* custom */
            .accordion .item .icon{ transition: 0.3s; position: absolute; top: 12px; left: 10px; }
            .accordion .item .icon_more{ opacity: 1; }
            .accordion .item .icon_less{ opacity: 0; }
            .accordion .item.selected .icon_more{ opacity: 0; }
            .accordion .item.selected .icon_less{ opacity: 1; }
            @media screen and (max-width:600px){
                .accordion{ }
            }
        </style>
        <script>
            window.accordion = {}
            //++ switch
                accordion.open = function(index){
                    var item = $(".accordion .item").get(index);
                    var height = $(item).find(".content_wrapp").height();
                    if(!height){
                        $(item).addClass("selected");
                        var dim = cuppa.dim($(item).find(".content_wrapp .content"));
                        var tl = new TimelineMax();
                            tl.to($(item).find(".content_wrapp"), 0.3, {height:dim.height, ease:Cubic.easeInOut});
                            tl.set($(item).find(".content_wrapp"), {height:"auto"});
                    }else{
                        $(item).removeClass("selected");
                        var tl = new TimelineMax();
                            tl.to($(item).find(".content_wrapp"), 0.3, {height:0, ease:Cubic.easeInOut});
                    }
                }
            //--
            //++ resize
                accordion.resize = function(){ }; 
            //--
            //++ end
                accordion.removed = function(e){ cuppa.removeEventGroup("accordion"); }
            //--
            //++ init
                accordion.init = function(){
                    cuppa.addEventListener("resize", accordion.resize, window, "accordion"); accordion.resize(); $(".accordion img").load(accordion.resize); TweenMax.delayedCall(0.1, accordion.resize);
                    cuppa.addEventListener("removed", accordion.removed, ".accordion", "accordion");
                    if($(".accordion .item").length <= 1) accordion.open(0);
                }; cuppa.addEventListener("ready",  accordion.init, document, "accordion");
            //--
        </script>
        <?php forEach($accordion as $index=>$item){ ?>
            <div class="item">
                <div class="title f_effra_medium t_22" onclick="accordion.open('<?php echo $index ?>')" >
                    <?php echo $item->title ?>
                    <img class="icon icon_more" src="administrator/media/template/icon_plus.png" />
                    <img class="icon icon_less" src="administrator/media/template/icon_less.png" />
                </div>
                <div class="content_wrapp">
                    <div class="content f_effra_light t_20"><?php echo $item->content ?></div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>