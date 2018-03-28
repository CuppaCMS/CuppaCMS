<?php
    $cuppa = Cuppa::getInstance(); $cuppa->setDocumentPath();
    $language = $cuppa->language->load();
    $path = $cuppa->utils->getUrlVars();
    if(isset($path["secure"])) header( 'Location: '.$cuppa->getPath() );
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- Aplication icons -->
            <meta name="mobile-web-app-capable" content="yes" />
            <meta name="apple-mobile-web-app-capable" content="yes" />
            <meta name="apple-mobile-web-app-status-bar-style" content="black" />
            <link rel="shortcut icon" sizes="196x196" href="templates/default/images/app_icon_196.png"  />                
            <link rel="shortcut icon" sizes="128x128" href="templates/default/images/app_icon_128.png" />
            <link rel="shortcut icon" sizes="24x24" href="templates/default/images/icon.png" />
            <link rel="apple-touch-icon" sizes="128x128" href="templates/default/images/app_icon_128.png" />
            <link rel="apple-touch-icon-precomposed" sizes="128x128" href="templates/default/images/app_icon_128.png" />
            <link rel="apple-touch-startup-image" href="templates/default/images/app_icon_196.png" />
        <!-- -->
        <base href="<?php echo @$cuppa->getPath(); ?>" />
        <title><?php echo @$language->cuppa_title ?></title>
        <!-- Principals Packages  -->
            <link href="js/jquery/jquery-ui.css" rel="stylesheet" type="text/css" />
            <link href="templates/default/css/template.css" rel="stylesheet" type="text/css" />
        <!-- JQuery -->
            <script src="js/jquery/jquery.js" type="text/javascript"></script>
        <!-- JQuery UI -->
            <script src="js/jquery/jquery-ui.js" type="text/javascript"></script>
        <!-- Cuppa Packages -->
            <link href="js/cuppa/cuppa.min.css" rel="stylesheet" type="text/css" />
            <script src="js/cuppa/extras.js" type="text/javascript"></script>
            <script src="js/cuppa/cuppa.min.js" type="text/javascript"></script>
        <!-- File upload -->
            <link href="js/jquery_file_upload/css/jquery_file_upload.css" rel="stylesheet" type="text/css" />
            <script src="js/jquery_file_upload/vendor/jquery.ui.widget.js"></script>
            <script src="js/jquery_file_upload/jquery.iframe-transport.js"></script>
            <script src="js/jquery_file_upload/jquery.fileupload.js"></script>
        <!-- Editors -->
            <script src="js/tiny_mce/tiny_mce.js" type="text/javascript"></script>
            <link  href="js/jsoneditor/jsoneditor.css" rel="stylesheet" type="text/css"/>
            <script src="js/jsoneditor/jsoneditor.js" type="text/javascript"></script>
            <script src="js/ace_editor/src-noconflict/ace.js"></script>
            <script src="js/ace_editor/src-noconflict/ext-language_tools.js"></script>
        <!-- Toast -->
            <link  href="js/toastr/toastr.css" rel="stylesheet" type="text/css"/>
            <script src="js/toastr/toastr.js" type="text/javascript"></script>
        <!-- Others Packages -->
            <script src="js/functions.js" type="text/javascript"></script>
        <!-- JavaScripts code -->
            <script>
                stage.language = cuppa.jsonDecode("<?php echo $cuppa->utils->jsonEncode($language, true) ?>");
                stage.currentLanguage = "<?php echo @$cuppa->language->current() ?>";
            </script>
    </head>
    <body>
        <?php include "html/menu.php" ?>
        <div class="wrapper"></div>
    </body>
</html>