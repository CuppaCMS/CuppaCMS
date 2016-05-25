<?php
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load();
    if(@$cuppa->user->getVar("admin_login") != "1"){
        include_once "login.php";
    }else{
        include_once "logged.php";
    }
?>