<?php
    @session_start();
    include_once(realpath(__DIR__ . '/../../../..')."/classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $language = $cuppa->language->load();
    include_once realpath(__DIR__ . '/../../../..')."/components/stats/index.php";
?>
