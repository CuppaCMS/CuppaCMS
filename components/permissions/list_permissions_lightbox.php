<?php 
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["administrator_document_path"]."classes/Cuppa.php";
    $cuppa = Cuppa::getInstance(); $cuppa->user->valid("admin_login");
    $cuppa->permissions->getList(@$_POST["params"]["group"], @$_POST["params"]["reference"], @$_POST["params"]["title"]);
?>