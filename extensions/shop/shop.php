<?php
    @session_start();
    if(!@$_SESSION["cuSession"]){ echo '<script> window.location=document.URL; </script>'; exit(); }
    include_once($_SESSION["cuSession"]->paths->administrator->document_path."classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $token = $cuppa->security->token;
    $language = $cuppa->language->load("web");
    $path = $cuppa->utils->getUrlVars(@$_POST["path"], false, $language, true);
    $path_string = @join("/", @$path);
    $path2 = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $path2_string = @join("/", @$path2);
    include_once("classes/Shop.php");
    //++ Configuration
        $key_word = "shop";                     // default: shop
        $key_word2 = $cuppa->language->getValue("shop", $language, true);
        if(!$cuppa->getSessionVar("country")) $cuppa->setSessionVar("country", "0");
    //--
    //++ Get path just blog or key word
        $path_shop = $cuppa->utils->getUrlVars($cuppa->utils->getStringPart(@$path_string, $key_word, false, true));
        $path_just_shop_string = $cuppa->utils->getStringPart(@$path_string, $key_word, true, false)."/";
        $path_just_shop_string2 = $cuppa->utils->getStringPart(@$path2_string, $key_word2, true, false)."/";
    //--
    //++ show check-out
        if($path_shop[0] == "check-out"){
            include("shop_check_out.php");
            return;
        }
    //--
    //++ get category inf
        $section = @$cuppa->menu->getArrayData("shop", $token, "alias = '".@$path_shop[count($path_shop)-1]."'");
        $section = $section[0];
        if(@$cuppa->GET("q")){
            include_once("shop_list.php");
        }else if($section){
            $section = (object) $section;
            include_once("shop_list.php");
        }else{
            include_once("shop_product.php");
        }
    //--
?>