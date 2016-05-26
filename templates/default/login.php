<?php
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load();
    $language_availables = $cuppa->language->getLanguageFiles();
    $path = $cuppa->utils->getUrlVars();
    if(isset($path["secure"])) header( 'Location: '.$cuppa->getPath() );
?>
<style>
    body{ background: #F6F6F6 !important; }
    .login{ position: fixed; width: 320px; background: #FFF; padding: 35px; left: 50%; top: 50%; margin-left: -160px; box-shadow: 0px 0px 4px rgba(0,0,0,0.15); display: none; }
    .login .line{ position: absolute; top: 0px; left: 0px; right: 0px; height: 2px; box-shadow: 0px 1px 2px rgba(0,0,0,0.1); }
    .login .logo{ display: block; margin: 0 auto 15px; }
    .login .text1{ margin: 10px 0px; }
    input, .select_cuppa{ width: 100% !important; margin: 5px 0px; }
    input[type=button]{ margin-bottom: 0px; }
</style>
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
            <link href="templates/default/css/template.css" rel="stylesheet" type="text/css" />
        <!-- JQuery -->
            <script src="js/jquery/jquery.js" type="text/javascript"></script>
        <!-- Cuppa Packages -->
            <link href="js/cuppa/cuppa.min.css" rel="stylesheet" type="text/css" />
            <script src="js/cuppa/extras.js" type="text/javascript"></script>
            <script src="js/cuppa/cuppa.min.js" type="text/javascript"></script>
        <!-- JavaScripts code -->
            <script>
                login = {}
                //++ resize
                    login.resize = function(){
                        var dimentions = cuppa.dimentions(".login");
                        $(".login").css("margin-top",-dimentions.height*0.5);
                    }; 
                //--
                //++ end
                    login.end = function(){
                        cuppa.removeEventGroup("login");
                    }; cuppa.addRemoveListener(".login", login.end);
                //--
                //++ init
                    login.init = function(){
                        cuppa.addEventListener("resize", login.resize, window, "login"); login.resize();
                        TweenMax.to(".login", 0, {alpha:0, display:"block"})
                        TweenMax.to(".login", 0.4, {delay:0.5, alpha:1,display:"block", onStart:login.resize, ease:Cubic.easeOut})
                        jQuery(".language").val("<?php echo @$cuppa->language->current(); ?>");
                        cuppa.selectStyle("select");
                    }; cuppa.addEventListener("ready",  login.init, document, "login");
                //--
            </script>
    </head>
    <body>
        <div class="login">
            <div class="line"></div>
            <img class="logo" src="templates/default/images/template/logo_login.png" />
            <div class="text1"><?php echo @$language->login_message ?></div>
            <form id="form_login" method="post" action="<?php echo $cuppa->getPath() ?>" >
                <input class="user" name="user" placeholder="<?php echo @$language->user ?>" />
                <input type="password" class="password" name="password" placeholder="<?php echo @$language->password ?>" />
                <select class="language" name="language">
                    <?php for($i = 0; $i < count($language_availables); $i++){ ?>
                        <option value="<?php echo $language_availables[$i] ?>"><?php echo $language_availables[$i] ?></option>
                    <?php } ?>
                </select>
                <input class="button_blue" type="submit" value="<?php echo $language->log_in ?>" />
                <input type="hidden" id="task" name="task" value="login"/>
            </form>
        </div>
    </body>
</html>