<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["web_document_path"]."administrator/classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load("web");
    $current_language = $cuppa->language->current();
    $current_country = $cuppa->country->current();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"], false, $language, true);
    // example: $tiles = '13,42,20';
    $cond = "enabled = 1 AND (language = '' OR language = '".@$current_language."') AND id IN (".@$tiles.")";    
    $tiles = $cuppa->dataBase->getList("ex_tiles", $cond, "", "FIELD(id, ".$tiles.")", true);
?>
<?php if($tiles){ ?>
    <div class="tiles">
        <style>
            .tiles{ overflow: hidden; width: calc(100% + 1px); }
            .tiles .tile{ display: inline-block; }
            .tiles .tile .content1{ }
            .tiles .tile .content_over{ transition: 0.3s; opacity: 0;}
            .tiles .tile:hover .content_over{ opacity: 1; }
        </style>
        <script>
            tiles = {}
            //++ resize
                tiles.resize = function(){ 
                    jQuery(".tiles").packery({ columnWidth:0, gutter: 0, itemSelector: ".tiles .tile", transitionDuration:0 });
                }; 
            //--
            //++ end
                tiles.removed = function(e){ cuppa.removeEventGroup("tiles"); }
            //--
            //++ init
                tiles.init = function(){
                    cuppa.addEventListener("resize", tiles.resize, window, "tiles"); tiles.resize(); $(".tiles img").load(tiles.resize); TweenMax.delayedCall(0.1, tiles.resize);
                    cuppa.addEventListener("removed", tiles.removed, ".tiles", "tiles");
                }; cuppa.addEventListener("ready",  tiles.init, document, "tiles");
            //--
        </script>
        <?php forEach($tiles as $item){ ?>
            <div class="tile <?php echo str_replace(",", " ", @$item->classes) ?>" style="<?php echo @$item->css ?>" >
                <?php if(@$item->link){ ?>
                    <a href="<?php echo @$item->link ?>" target="<?php echo @$item->target ?>" class="content1 cover bg_cover" style="<?php if($item->margin){ ?>margin:<?php echo $item->margin ?>; <?php } ?> <?php if($item->background){ ?> background-image: url(administrator/<?php echo $item->background ?>); <?php } ?>">
                        <?php $cuppa->echoString($item->content) ?>
                        <?php if($item->content_over){ ?>
                            <div class="content_over cover" ><?php $cuppa->echoString($item->content_over) ?></div>
                        <?php } ?>  
                    </a>
                <?php }else{ ?>
                    <div class="content1 cover bg_cover" style="<?php if($item->margin){ ?>margin:<?php echo $item->margin ?>; <?php } ?> <?php if($item->background){ ?> background-image: url(administrator/<?php echo $item->background ?>); <?php } ?>">
                        <?php $cuppa->echoString($item->content) ?>
                        <?php if($item->content_over){ ?>
                            <div class="content_over cover" ><?php $cuppa->echoString($item->content_over) ?></div>
                        <?php } ?>  
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
<?php } ?>