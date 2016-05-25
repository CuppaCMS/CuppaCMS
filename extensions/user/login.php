<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["web_document_path"]."administrator/classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load("web");
    $current_language = $cuppa->language->current();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
?>
<form class="login" onsubmit="return login.submit();" method="post">
    <style>
        .login{ }
    </style>
    <script>
        login = {}
        //++ submit
            login.submit = function(){
                if(!$("form.login").valid()){ return false; }
                // Send
                    var data = {}
                        data.user = jQuery(".login [name=user]").val();
                        data.password = jQuery(".login [name=password]").val();
                        data["function"] = "login";
                    jQuery.ajax({url:"administrator/classes/ajax/Functions.php", type:"POST", data:data, success:Ajax_Result});
                    function Ajax_Result(result){
                        if(!result){
                            cuppa.blockade();
                            cuppa.instance({url:"administrator/js/cuppa/html/alert.php",data:{title:"<?php echo $cuppa->langValue("message", $language); ?>", message:"<?php echo $cuppa->langValue("user_or_password_incorrect", $language); ?>"}, append:"body"});
                            return;
                        }
                        window.location=document.URL;
                    }
                return false;
            };
        //--
        //++ resize
            login.resize = function(){ }; 
        //--
        //++ end
            login.removed = function(e){ cuppa.removeEventGroup("login"); }
        //--
        //++ init
            login.init = function(){
                cuppa.addEventListener("resize", login.resize, window, "login"); login.resize(); $(".login img").load(login.resize); TweenMax.delayedCall(0.1, login.resize);
                cuppa.addEventListener("removed", login.removed, ".login", "login");
            }; cuppa.addEventListener("ready",  login.init, document, "login");
        //--
    </script>
    <input class="required" name="user" placeholder="<?php echo $cuppa->langValue("user", $language); ?>" />
    <input class="required" name="password" type="password" placeholder="<?php echo $cuppa->langValue("password", $language); ?>" />
    <button type="submit"><?php echo $cuppa->langValue("submit", $language); ?></button>
</form>