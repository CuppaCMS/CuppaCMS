<?php
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load();
    $language_availables = $cuppa->language->getLanguageFiles();
    $path = $cuppa->utils->getUrlVars();

    if(isset($path["secure"])) header( 'Location: '.$cuppa->getPath() );
    // restore
        $restore = $cuppa->decrypt($path["restore"], "", true);

        if(@$restore){
            $user_restore = $cuppa->db->getRow("{$cuppa->configuration->table_prefix}users", "id = '".$restore."' AND enabled = 1 AND restore_password = 1", true);
            if($user_restore){
                $update = array("restore_password"=>0);
                $cuppa->db->update("{$cuppa->configuration->table_prefix}users", $update, "email = '".$user_restore->email."'");
            }
        }
?>
<style>
    body{ background: #F6F6F6 !important; }
    div{ position: relative; }
    .login{ position: fixed; width: 320px; background: #FFF; padding: 35px; left: 50%; top: 50%; transform: translate(-50%,-50%); box-shadow: 0px 0px 4px rgba(0,0,0,0.15); display: none; }
    .login .line{ position: absolute; top: 0px; left: 0px; right: 0px; height: 2px; box-shadow: 0px 1px 2px rgba(0,0,0,0.1); }
    .login .logo{ display: block; margin: 0 auto 15px; }
    .login .text1{ margin: 10px 0px; }
    input, .select_cuppa{ width: 100% !important; margin: 5px 0px; }
    input[type=button]{ margin-bottom: 0px; }
    .disabled{ opacity: 0.7; }
    @media (max-width:600px){
        body{}
        .login{ width: 280px; }
    }
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
                login = new function(){
                    // show
                        this.show = function(element){
                            element = element || ".login_user";
                            TweenMax.killTweensOf(".login");
                            TweenMax.to(".login", 0, {alpha:0, display:"none"});
                            TweenMax.to(element, 0.4, {delay:0.5, alpha:1,display:"block", onStart:login.resize, ease:Cubic.easeOut})
                        }.bind(this);
                    // sendEmail
                        this.sendEmail = function(e){
                            if(!$(e.currentTarget).valid()) return;
                            var state = new cuppa.state(e.currentTarget);
                                state.setState("btn_restore", "state2", {addClass:"disabled", blockade:true});
                            var data = {}
                                data.info = cuppa.serialize(e.currentTarget, true);
                                data["function"] = "restore";
                            $.ajax({url:"classes/ajax/Functions.php", data:data, type:"POST"}).done(function(result){
                                state.setState("btn_restore", "state1", {removeClass:"disabled", blockade:false});
                                e.currentTarget.reset(); this.show();
                                cuppa.blockade();
                                cuppa.instance({url:"js/cuppa/cuppa_html/alert.html", data:{title:"<?php echo $language->message ?>", message:"<?php echo $language->forgot_password_alert ?>", accept:"<?php echo $language->accept ?>"}, append:"body" });
                            }.bind(this));
                        }.bind(this);
                    // init
                        this.init = function(){
                            $(".language").val("<?php echo @$cuppa->language->current(); ?>");
                            cuppa.selectStyle("select");
                            this.show(".login_user");
                            $("#form_reset").submit(this.sendEmail);
                        }.bind(this); $(this.init);
                };
            </script>
    </head>
    <body>
        <!-- login -->
            <div class="login login_user">
                <div class="line"></div>
                <img class="logo" src="templates/default/images/template/logo_login.png" width="25" height="19" />
                <div class="text1"><?php echo @$language->login_message ?></div>
                <form id="form_login" method="POST" action="<?php echo $cuppa->getPath() ?>" >
                    <input class="user" name="user" placeholder="<?php echo @$language->user ?>" />
                    <input type="password" class="password" name="password" placeholder="<?php echo @$language->password ?>" />
                    <select class="language" name="language">
                        <?php for($i = 0; $i < count($language_availables); $i++){ ?>
                            <option value="<?php echo $language_availables[$i] ?>"><?php echo $language_availables[$i] ?></option>
                        <?php } ?>
                    </select>
                    <div style="text-align: right; color: #4B8DF8; font-size: 11px; margin: 5px 0px ;"><a onclick="login.show('.reset_password')"><?php echo $cuppa->langValue("forgot_password", $language); ?>?</a></div>
                    <input class="button_blue" type="submit" value="<?php echo $language->log_in ?>" />
                    <input type="hidden" id="task" name="task" value="login" />
                </form>
            </div>
        <!-- -->
        <!-- reset -->
            <div class="login reset_password" style="display: none;">
                <div class="line"></div>
                <img class="logo" src="templates/default/images/template/logo_login.png" width="25" height="19" />
                <div class="text1"><?php echo $cuppa->langValue("forgot_password_text", $language) ?>.</div>
                <form id="form_reset" onsubmit="return false;" method="post" action="<?php echo $cuppa->getPath() ?>" >
                    <div style="position: relative;"><input class="email required email" name="email" placeholder="<?php echo @$language->email ?>" /></div>
                    <input class="button_blue" state="btn_restore" state1="<?php echo $language->send_email ?>" state2="<?php echo $language->sending ?>..." type="submit" value="<?php echo $cuppa->langValue("send_email", $language) ?>" />
                </form>
            </div>
        <!-- -->
        <!-- restore -->
            <?php if(@$user_restore->email){ ?>
                    <div class="login restore_password" style="display: none;">
                        <div class="line"></div>
                        <img class="logo" src="templates/default/images/template/logo_login.png" width="25" height="19" />
                        <div class="text1"><?php echo $cuppa->langValue("restore_password_text", $language) ?>.</div>
                        <form id="form_restore" onsubmit="return false;" method="post" action="<?php echo $cuppa->getPath() ?>" >
                            <div class="form_row"><input id="password_to_restore" class="input required" name="password" placeholder="Password" type="password" /></div>
                            <div class="form_row"><input class="input required" equalto="#password_to_restore" placeholder="Confirm password" type="password" /></div>
                            <input class="button_blue" state="btn_restore" state1="<?php echo $language->restore ?>" state2="<?php echo $language->updating ?>..." type="submit" value="<?php echo $cuppa->langValue("restore", $language) ?>" />
                        </form>
                    </div>
                    <script>
                        restore = new function(){
                            // sendEmail
                                this.restore = function(e){
                                    if(!$(e.currentTarget).valid()) return;
                                    var state = new cuppa.state(e.currentTarget);
                                        state.setState("btn_restore", "state2", {addClass:"disabled", blockade:true});
                                    var data = {}
                                        data.info = cuppa.serialize(e.currentTarget, false); data.info.ref = "<?php echo $path["restore"] ?>";
                                        data.info = cuppa.jsonEncode(data.info);
                                        data["function"] = "restore2";
                                    $.ajax({url:"classes/ajax/Functions.php", data:data, type:"POST"}).done(function(result){
                                        state.setState("btn_restore", "state1", {removeClass:"disabled", blockade:false});
                                        cuppa.blockade();
                                        cuppa.instance({url:"js/cuppa/cuppa_html/alert.html", data:{title:"<?php echo $language->message ?>", message:"<?php echo $cuppa->langValue("restore_password_alert", $language) ?>.", accept:"<?php echo $language->accept ?>", callback:AlertCallBack}, append:"body" });
                                        function AlertCallBack(){
                                            window.location.href = "<?php echo $cuppa->getPath() ?>";
                                        }
                                    }.bind(this));
                                }.bind(this);
                            // init
                                this.init = function(){
                                    login.show(".restore_password");
                                    $("#form_restore").submit(this.restore);
                                }.bind(this); $(this.init);
                        }
                    </script>
            <?php } ?>
        <!-- -->
    </body>
</html>