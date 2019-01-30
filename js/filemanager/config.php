<?php
include_once(realpath(__DIR__ . '/../..')."/classes/Cuppa.php");
$cuppa = Cuppa::getInstance();

// Absolute path to the media folder e.g. C:/path/to/media/
$rootFolder = realpath(__DIR__ . '/../..')."/media";

// Absolute URL to the media file, e.g. http://domain.com/media/
$rootURL = $cuppa->getPath()."media/";

// Absolute URL to the php file to process the upload
$phpPath = "../jquery_file_upload/server/php/";

