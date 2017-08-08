<?php
    @session_start();
    include_once(realpath(__DIR__ . '/../../../..')."/classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $language = $cuppa->language->load();
?>
<style>
    body{ overflow: hidden; }
    .w_right{
        position: fixed;
        background: #fff;
        top: 50px;
        bottom: 0px;
        right: 0px;
        width: 93%;
        z-index: auto ;
        right: -100%;
        overflow: auto;
        overflow-x: hidden;
        box-shadow: 0px 2px 8px rgba(0,0,0,0.2);
    }
/* Responsvie */
    .r1200 .w_right{ width: 99% !important; }
    .r1100 .w_right{ width: 100% !important; }
</style>
<script>
    w_right = {}
    //++ end
        w_right.end = function(){
            cuppa.removeEventGroup("w_right");
        }; cuppa.addRemoveListener(".w_right", w_right.end);
    //--
    //++ close
        w_right.close = function(){
            $(".ace_autocomplete").hide();
            var w_right = $(".w_right").last();
            TweenMax.to(w_right, 0.3, {right:"-100%", ease:Cubic.easeIn, onComplete:function(){
                cuppa.setContent({load:false, name:".window_right", last:true});
                cuppa.blockade({load:false, name:".blockade_w_right", duration:0.2, last:true});
                cuppa.tinyMCEDestroy();
            }});
        }
    //--
    //++ init
        w_right.init = function(){
            TweenMax.to(".w_right", 0.4, {right:0, ease:Cubic.easeOut});
        }; cuppa.addEventListener("ready",  w_right.init, document, "w_right");
    //--
</script>
<div class="w_right">
    <?php 
        include realpath(__DIR__ . '/../../../..')."/".$_POST["url"];
    ?>
</div>