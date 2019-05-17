<?php
/*
 * jQuery File Upload Plugin PHP Example
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * https://opensource.org/licenses/MIT
 */
require_once("../../../../config.php");
error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');

$upload_url = $rootURL."/".$_POST["path"]."/";
$upload_url = str_replace("://",":::", $upload_url);
$upload_url = str_replace("//","/",str_replace("//","/", str_replace("//","/", $upload_url)));
$upload_url = str_replace(":::","://", $upload_url);

$options = array(
    'upload_dir' => $rootFolder."/".$_POST["path"]."/",
    'upload_url' => $upload_url,
    'image_versions' => array()
);
$upload_handler = new UploadHandler($options);
