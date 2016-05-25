<?php
    @session_start();
    include_once(realpath(__DIR__ . '/../../..')."/classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load("web");
    $current_language = $cuppa->language->current();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    
?>
<style>
    .blank{
        
    }
</style>
<script>
    blank = {}
    //++ resize
        blank.resize = function(){
            
        }; 
    //--
    //++ end
        blank.end = function(){
            cuppa.removeEventGroup("blank");
        }; cuppa.addRemoveListener(".blank", blank.end);
    //--
    //++ init
        blank.init = function(){
            cuppa.addEventListener("resize", blank.resize, window, "blank"); blank.resize();
        }; cuppa.addEventListener("ready",  blank.init, document, "blank");
    //--
</script>
<div class="blank"></div>