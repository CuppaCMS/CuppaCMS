<?php
    @session_start();
    include_once realpath(__DIR__ . '/../../../..')."/classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $path = $cuppa->getPath();
    if(@!$cuppa->user->getVar("admin_login") && @!$cuppa->user->getVar("super_admin_login")) exit();
    $php_path = @$path."js/jquery_file_upload/server/php/";
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8" />
    	<title>Upload</title>
        <link href="<?php echo $path ?>templates/default/css/template.css" type="text/css" rel="stylesheet" />
        <link href="<?php echo $path ?>templates/default/css/datagrid.css" type="text/css" rel="stylesheet" />
        <link href="<?php echo $path ?>templates/default/css/form.css" type="text/css" rel="stylesheet" />
        <link href="css/css.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="../../tiny_mce_popup.js"></script>
        <script src="jquery.js" type="text/javascript"></script>
        <!-- File Upload Packages -->
            <link href="<?php echo $path ?>js/jquery_file_upload/css/jquery_file_upload.css" rel="stylesheet" type="text/css" />
            <script src="<?php echo $path ?>js/jquery_file_upload/vendor/jquery.ui.widget.js"></script>
            <script src="<?php echo $path ?>js/jquery_file_upload/jquery.iframe-transport.js"></script>
            <script src="<?php echo $path ?>js/jquery_file_upload/jquery.fileupload.js"></script>
        <!-- -->
        <!-- Cuppa Packages -->
            <link href="<?php echo $path ?>js/cuppa/cuppa.css" rel="stylesheet" type="text/css" />
            <script src="<?php echo $path ?>js/cuppa/cuppa.min.js" type="text/javascript"></script>
           <!-- -->
        <script>
            jQuery(document).on("ready", init);
            function init(event){
                cuppa.fileUpload('#upload_file', tinyMCE.activeEditor.settings.folder_path, "<?php echo $php_path ?>", true)
                $(".text_button").html("Drop file here<br/>or click to select it");
            }
            function Insert(){
                if(!jQuery("#upload_file").val()){
                    jQuery("#upload_file").addClass("error");
                    return;
                }
                tinyMCEPopup.editor.execCommand('mceInsertContent', false, '<img src="' + jQuery("#upload_file").val() +'" alt="" />');
			    tinyMCEPopup.close();
            }
            
        </script>
    </head>
    <body>
        <style>
            .upload_file *{ vertical-align: middle;}
            
            input.upload_file{
                border-radius: 0;
                box-shadow: none;
                outline: 0 none !important;
                overflow: auto;
                width: 100%;
            }
            .button_upload {
                background: none !important;
                border: 1px solid #AAA !important;
                color: #fff;
                display: block;
                height: 200px !important;
                left: 0 !important;
                margin: 5px 0px;
                overflow: hidden;
                padding: 4px 12px;
                position: relative;
                text-align: center;
                width: 100%;
                border-radius: 0px !important;
            }
                .button_upload:hover{
                    background: #FFF !important;
                    border: 1px solid #999 !important;
                    box-shadow: none !important;
                }
            .button_upload .text_button {
                color: #aaa !important;
                font-size: 20px !important;
                margin: 0;
                position: relative;
                text-shadow: none !important;
                text-transform: none !important;
                top: 68px !important;
            }
            .file_type {
                font-size: 250px !important;
                height: auto !important;
                width: auto !important;
            }
        </style>
        <div style="margin: 5px; padding:5px;">
            <div class="upload_file">
                <input readonly="readonly" class="upload_file" id='upload_file' name='upload_file' value='' />
            </div>
            <input style="width: 100%; margin: 0px;" class="button_form" onclick="Insert()" type="button" value="Insert" class="jbButton" />
        </div>
    </body>
</html>
