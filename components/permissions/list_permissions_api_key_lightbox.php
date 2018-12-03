<?php 
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["administrator_document_path"]."classes/Cuppa.php";
    $cuppa = Cuppa::getInstance(); $cuppa->user->valid("admin_login");
    $cuppa->permissions->getListApiKey("6", @$_POST["params"]["table_name"], @$_POST["params"]["title"]);
?>