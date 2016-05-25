<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["web_document_path"]."administrator/classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $token = $cuppa->security->token;
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $language = $cuppa->language->load("web");
?>
<style>
    .logout{
    
    }
</style>
<script>
    logout = {}
    logout.uniqueClass = ".<?php echo @$_POST["uniqueClass"] ?>";
    //++ init
        logout.init = function(){
            
        }; cuppa.addEventListener("ready",  logout.init, document, "logout");
    //--
    //++ submit
        logout.submit = function(){
            var data = {}
                data["function"] = "logout";
            // Send
                jQuery.ajax({url:"administrator/extensions/user/classes/functions.php", type:"POST", data:data, success:Ajax_Result});
                function Ajax_Result(result){
                    window.location=document.URL;
                }
        };
    //--
    //++ resize
        logout.resize = function(){
            
        }; cuppa.addEventListener("resize", logout.resize, window, "logout"); logout.resize();
    //--
    //++ end
        logout.end = function(){
            cuppa.removeEventGroup("logout");
        }; cuppa.addRemoveListener(".logout", logout.end);
    //--
</script>
<div class="logout">
    <input type="button" value="logout" onclick="logout.submit()" />
</div>