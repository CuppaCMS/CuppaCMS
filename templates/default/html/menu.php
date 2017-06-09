<?php
    @session_start();
    include_once(realpath(__DIR__ . '/../../..')."/classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $menu_left = $cuppa->menu->getList("admin_menu");
    $menu_settings = $cuppa->menu->getList("admin_settings"); 
?>
<style>
    .wrapper{ margin-left: 220px; }
    .menu{ position: fixed; top: 0px; left: 0px; right: 0px; height: 50px; background: #F6F6F6; z-index: 20; box-shadow: 0px 1px 2px rgba(0,0,0,0.15); }
     /* Logo area */
        .menu .logo_area{ position: absolute; top: 0px; left: 0px; bottom: 0px; width: 220px; background: #4B8DF8; }
        .menu .logo{ position: absolute; left: 0px; top: 0px; bottom: 0px; width: 50px; background-color: transparent; background-position: center; background-repeat: no-repeat; transition-duration: 0.2s; }
        .menu .logo:hover{background-color: #3779E5;  }
    /* Principa menu */
        .menu .buttons_left{ position: absolute; left: 220px; right: 0px; top: 0px; text-transform: capitalize; }
        .menu .buttons_left .vertical_divider{ display: none; }
        .menu .buttons_left ul{ position: relative; margin: 0px; padding: 0px; list-style-type: none; list-style: none; }
        .menu .buttons_left li{ position: relative; display: inline-block; margin: 0px; padding: 0px; float: left;  }
        .menu .buttons_left a{ position: relative; display: block; padding: 0px 13px; height: 50px; transition-duration: 0.2s; }
        .menu .buttons_left span{ position: relative; top: 17px; }
        .menu .buttons_left a:hover{ background: #E9E9E9; }
        .menu .buttons_left ul ul{ position: absolute; display: none; background: #FFF; white-space: nowrap; left: 0px; top: 50px; box-shadow: 0px 2px 2px rgba(0,0,0,0.2);  }
        .menu .buttons_left li li{ position: relative; display: block; float: none; }
        .menu .buttons_left li li a{ position: relative; height: 39px; padding: 0px 26px 0px 13px; }
        .menu .buttons_left li li a:hover{ background: #F6F6F6; }
        .menu .buttons_left li li span{ top: 11px; }
        .menu .buttons_left .horizontal_divider{ position: relative; display: block; height: 0px; border-bottom: 1px solid #F0F2F4; }
        .menu .buttons_left .arrow{ background-image: none; }
        .menu .buttons_left .arrow .arrow{ background-image: url("templates/default/images/template/arrow_menu.png"); background-position: right 13px center; background-repeat: no-repeat; }
        .menu .buttons_left ul ul ul{ top: -1px; left: 100%; margin-left: -8px; }
        .menu .buttons_left li:hover ul{ display: block; }
        .menu .buttons_left li:hover a{ background-color: #FFF; box-shadow: 0px -2px 2px rgba(0,0,0,0.2); }
        .menu .buttons_left li:hover li a{ background:none; box-shadow: none; }
        .menu .buttons_left li li a:hover{ background:#F8F8F8;  box-shadow: none;  }          
        .menu .buttons_left li:hover ul ul{ display: none; }
        .menu .buttons_left li li:hover ul{ display: block; }
    /* Setting button */
        .menu .settings_button{ position: absolute; top: 0px; right: 0px; width: 50px; height: 50px; cursor: pointer; background-color: transparent; background-image: url("templates/default/images/template/btn_mobile.png"); background-position: center; background-repeat: no-repeat; transition-duration: 0.2s; z-index: 21; }
        .menu .settings_button:hover{background-color: #3779E5;  }
/* Menu settings */
    .menu .selected{ background-color: #FFF !important; }
    .menu_settings{ position: fixed; top: 50px; left: 0px; bottom: 0px; background: #F6F6F6; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2) inset; overflow: hidden; width: 220px; display: block !important; }
    .menu_settings .buttons{ position: absolute; top: 0px; bottom: 0px; left: 0px; right: 0px; overflow: hidden; }
    .menu_settings a *{vertical-align: middle;}
    .menu_settings ul { position: relative; display: block; text-transform: capitalize; margin: 0px; padding: 0px; list-style: none; list-style-type: none; }
    .menu_settings ul li{ margin: 0px; padding: 0px; white-space: nowrap; }
    .menu_settings .vertical_divider, .menu_settings .horizontal_divider{ display: none; }
    .menu_settings .vertical_first_divider, .menu_settings .vertical_last_divider{ display: none;}
    .menu_settings a{ padding: 10px 60px 10px 30px; border-bottom: 1px solid #eeeeee;}
    .menu_settings a:hover{ background: #EEE;}
    .menu_settings a:not([href]){ background: none !important;}
    .menu_settings a img{ padding-right: 10px; }
    .menu_settings li li a{ border:none; }    
    .menu_settings ul ul{ border: 0px; }
    .menu_settings li li a{ padding-left: 70px; }
    .menu_settings li li li a{ padding-left: 80px; }
    .menu_collapsed .scroll_settings{ display: block; }
    .menu .load_animation{ background-image: url("templates/default/images/template/loader.png"); background-repeat: no-repeat; background-position: left top; position: absolute; width: 20px; height: 20px; position: absolute; top: 15px; right: 15px; opacity: 0; display: none; }
 /* Menu settings Collapsed */
    .menu_collapsed .wrapper{ margin-left: 50px; }
    .menu_collapsed .menu{ box-shadow: none; }
    .menu_collapsed .menu .buttons_left{ box-shadow: 0px 1px 2px rgba(0,0,0,0.15); }
    .menu_collapsed .menu .logo_area{ width: 50px; }
    .menu_collapsed .menu .logo{ display: none; }
    .menu_collapsed .menu .buttons_left{ left: 50px; }
    /* menu settiongs */
        .menu_collapsed .menu_settings{ z-index: 21; overflow: visible; width: 50px; box-shadow: 0 0px 2px rgba(0, 0, 0, 0.05); border-right: 1px solid #EEE; }
        .menu_collapsed .menu_settings .buttons{ overflow: visible; }
        .menu_collapsed .scroll_settings{ display: none !important; }
        .menu_collapsed .menu_settings a{ padding: 10px 40px 10px 15px; }
        .menu_collapsed .menu_settings a img{ padding-right: 20px; }
        .menu_collapsed .menu_settings ul{ }
        .menu_collapsed .menu_settings li{ position: relative; float: left; width: 50px; background: #F6F6F6; overflow: hidden; border-right: 1px solid #EEE; }
        .menu_collapsed .menu_settings li:hover{ width: auto; overflow: visible; box-shadow: 0 0px 2px rgba(0, 0, 0, 0.05); background: #FFF; }
        .menu_collapsed .menu_settings li:hover a{ background: #FFF; }
        .menu_collapsed .menu_settings li ul{ position: absolute; left: 50px; top: 100%; margin-top: -1px; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1); display: none; }
        .menu_collapsed .menu_settings li:hover ul{ display: block; background: #F6F6F6; }
        .menu_collapsed .menu_settings li:hover li{ display: block; width: 100%; }
        .menu_collapsed .menu_settings li li:hover{ background: #EEE; box-shadow: none; }
        .menu_collapsed .menu_settings li li a:hover{ background: #F6F6F6; }
/* Menu Mobile */
    .menu_mobile_blockade{ position: fixed; top: 50px; left: -250px; right: 0px; bottom: 0px; background: rgba(0,0,0,0.3); z-index: 22; display: none; opacity: 0; }
    .menu_mobile{ position: fixed; top: 50px; left: -250px; width: 250px; bottom: 0px; background: #F6F6F6; overflow: auto; z-index: 22; display: none; }
    .menu_mobile .separator{ border-bottom-color: #b9b9b9; }
    .menu_mobile a *{vertical-align: middle; }
    .menu_mobile ul { position: relative; display: block; text-transform: capitalize; margin: 0px; padding: 0px; list-style: none; list-style-type: none; }
    .menu_mobile ul li{ margin: 0px; padding: 0px; white-space: nowrap; }
    .menu_mobile .vertical_divider, .menu_settings .horizontal_divider{ display: none; }
    .menu_mobile .vertical_first_divider, .menu_settings .vertical_last_divider{ display: none;}
    .menu_mobile a{ padding: 10px 10px 10px 10px; border-bottom: 1px solid #eeeeee;}
    .menu_mobile a:hover{ background: #EEE;}
    .menu_mobile a:not([href]){ background: none !important; color: #888; }
    .menu_mobile a img{ padding-right: 10px; }
    .menu_mobile li li a{ border:none; }
    .menu_mobile ul ul{ border: 0px; }
    .menu_mobile li li a{ padding-left: 20px; }
    .menu_mobile li li li a{ padding-left: 40px; }
/* Responsive */
    .r1100 .menu{ box-shadow: 0px 2px 2px rgba(0,0,0,0.1) !important; }
    .r1100 .menu .logo{ display: none !important; }
    .r1100 .menu .logo_area{ width: 50px !important; }
    .r1100 .menu .buttons_left{ box-shadow: none !important; left: 50px !important; display: none !important; }
    .r1100 .menu_settings{ display: none !important; }
    .r1100 .menu_mobile{ display: block !important; }
</style>
<script>
    menu = {}
    //++ toggle mobile menu
        menu.toggleMenuMobile = function(){
            if(cuppa.css(".menu_mobile","left")){
                if($(window).width() <= 1100){
                    TweenMax.to(".menu_mobile_blockade", 0.3, {display:"block", alpha:1, ease:Cubic.easeOut});
                    TweenMax.to(".menu_mobile", 0.3, {left:0, ease:Cubic.easeOut});
                }
            }else{
                TweenMax.to(".menu_mobile_blockade", 0.3, {display:"none", alpha:0, ease:Cubic.easeOut});
                TweenMax.to(".menu_mobile", 0.2, {left:-250, ease:Cubic.easeIn});
            }
        }
    //--
    //++ show/hide settings
        menu.collapseMenu = function(value){
            if(value == undefined){
                value = ($("body").hasClass("menu_collapsed") ) ? false : true;
            } 
            if(value){
                $("body").addClass("menu_collapsed"); 
                cuppa.setCookie("menu_collapsed","true");
            }else{ 
                $("body").removeClass("menu_collapsed");
                cuppa.setCookie("menu_collapsed","false");
            }
            $(window).trigger("resize");
        }
    //--
    //++ update menu
        menu.update = function(menuStr, container){
            if(menuStr == undefined) menuStr = "admin_menu";
            if(container == undefined) container = ".menu .buttons_left";
            TweenMax.to(container, 0.3, {alpha:0, yoyo:true, repeat:-1});
            var data = {}
                data.menu = menuStr;
                data["function"] = "getMenu";
            jQuery.ajax({url:"classes/ajax/Functions.php", type:"POST", data:data, success:Ajax_Result});
            function Ajax_Result(result){
                $(container).html(cuppa.jsonDecode(result));
                TweenMax.to(container, 0.2, {alpha:1});
                cuppa.managerURL.updateLinks(container+" a", true);
            }
        };
    //--
    //++ show loader indicator
        menu.showCharger = function(value){
            if(value == undefined) value = true;
            if(value){
                TweenMax.killTweensOf(".load_animation");
                TweenMax.to(".load_animation", 0, {rotation:0});                
                TweenMax.to(".load_animation", 0.4, {alpha:1, display:"block", ease:Cubic.easeOut});
                TweenMax.to(".load_animation", 0.5, {rotation:360, repeat:-1, ease:Linear.easeNone});
            }else{
                TweenMax.to(".load_animation", 0.4, {alpha:0, display:"none", ease:Cubic.easeIn, onComplete:function(){
                    TweenMax.killTweensOf(".load_animation");
                }});
            }
        }
    //--
    //++ init
        menu.init = function(){
            cuppa.managerURL.updateLinks(".menu .buttons_left a, .menu_settings a, .menu_mobile a", true);
            $(".menu_mobile a").click(menu.toggleMenuMobile);
            $(".menu_settings .item_logout a, .menu_mobile .item_logout a").unbind("click");
            var scroll = new cuppa.scroll(".scroll_settings .bar", ".scroll_settings .track", "y");
                scroll.setContent(".menu_settings .buttons");
                scroll.rollOutHide(true);
                scroll.hideBar(null, 1.5);            
        }; cuppa.addEventListener("ready",  menu.init, document, "menu");
    //--
    if( cuppa.getCookie("menu_collapsed") == "true" || ("<?php echo @$cuppa->configuration->lateral_menu ?>" == "collapsed" && !cuppa.getCookie("menu_collapsed")) )  menu.collapseMenu(true);
</script>
<div class="menu">
    <div class="logo_area">
        <a href="#" class="logo" style="background-image: url(templates/default/images/template/logo.png);" ></a>
        <div class="settings_button" onclick="menu.collapseMenu(); menu.toggleMenuMobile();" ></div>
    </div>
    <div class="line" style="display: none;"></div>
    <div class="buttons_left"><?php echo $menu_left; ?></div>
    <div class="load_animation"></div>
</div>
<div class="menu_settings">
    <div class="buttons">
        <?php echo $menu_settings; ?>
    </div>
    <div class="scroll scroll_settings">
        <div class="track"></div>
        <div class="bar"></div>
    </div>
</div>
<div class="menu_mobile_blockade" onclick="menu.toggleMenuMobile();"></div>
<div class="menu_mobile">
    <div class="main_menu"><?php echo $menu_left; ?></div>
    <div class="separator"></div>
    <div class="setting_menu"><?php echo $menu_settings; ?></div>
</div>