<?php
@session_start();
require_once "../config.php";
require_once "FileManager.php";

define("CU_FM_ROOT_FOLDER", $rootFolder);
define("CU_FM_ROOT_URL", $rootURL);
$json = json_decode(file_get_contents('php://input'));
//print_r($json);
$fileManager = FileManager::getInstance();
if($json->action == "createFolder"){
    $result = $fileManager->createFolder($json->name, CU_FM_ROOT_FOLDER);
    echo @json_encode($result);
}
if($json->action == "getList"){
    $result = $fileManager->getListExtend(CU_FM_ROOT_FOLDER."/".$json->path);
    echo @json_encode($result);
}
if($json->action == "deleteFolder"){
    $path = CU_FM_ROOT_FOLDER."/".$json->path;
    $result = $fileManager->deleteFolder($path);
    echo @json_encode($result);
}

if($json->action == "deleteFile"){
    $path = CU_FM_ROOT_FOLDER."/".$json->path;
    $result = $fileManager->deleteFile($path);
    echo @json_encode($result);
}

if($json->action == "rename"){
    $from = CU_FM_ROOT_FOLDER."/".$json->from;
    $to = CU_FM_ROOT_FOLDER."/".$json->to;
    $result = $fileManager->rename($from, $to);
    echo $result;
}

if($json->action == "copyFile"){
    $from = CU_FM_ROOT_FOLDER."/".$json->from;
    $to = CU_FM_ROOT_FOLDER."/".$json->to;
    $result = $fileManager->copyFile($from, $to);
    echo $result;
}

if($json->action == "copyFolder"){
    $from = CU_FM_ROOT_FOLDER."/".$json->from;
    $to = CU_FM_ROOT_FOLDER."/".$json->to;
    $result = $fileManager->copyFolder($from, $to);
    echo $result;
}