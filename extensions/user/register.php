<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["web_document_path"]."administrator/classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $token = $cuppa->security->token;
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $language = $cuppa->language->load("web");
?>
<style>
    .register{
        
    }
</style>
<script>
    register = {}
    register.uniqueClass = ".<?php echo @$_POST["uniqueClass"] ?>";
    //++ init
        register.init = function(){
            
        }; cuppa.addEventListener("ready",  register.init, document, "register");
    //--
    //++ submit
        register.submit = function(){
            var data = {info:{}}
                data.info.name = jQuery(".register .name").val();
                data.info.email = jQuery(".register .email").val();
                data.info.username = jQuery(".register .username").val();
                data.info.password = jQuery(".register .password").val();
                data["function"] = "register";
            // Validation
                if(!cuppa.trim(data.info.name)){ alert("Please, fill the name field"); return;
                }else if(!cuppa.email(data.info.email)){ alert("Please, insert a valid email"); return;
                }else if(!cuppa.trim(data.info.username)){ alert("Please, insert a username"); return;
                }else if(!cuppa.trim(data.info.password)){ alert("Please, insert a password"); return;
                }
            // Send
                data.info = cuppa.jsonEncode(data.info,true);                        
                jQuery.ajax({url:"administrator/extensions/user/classes/functions.php", type:"POST", data:data, success:Ajax_Result});
                function Ajax_Result(result){
                    if(result == "-1"){
                        alert("The username already exist"); return;
                    }else if(result == "-2"){
                        alert("The email is registered"); return;
                    }
                    alert("Register success")
                    window.location=document.URL;
                }
        }
    //++ resize
        register.resize = function(){
            
        }; cuppa.addEventListener("resize", register.resize, window, "register"); register.resize();
    //--
    //++ end
        register.end = function(){
            cuppa.removeEventGroup("register");
        }; cuppa.addRemoveListener(".register", register.end);
    //--
</script>
<div class="register">
    <form>
        <div>Register</div>
        <table>
            <tr>
                <td>Name</td>
                <td><input id="name" class="name" /></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input id="email" class="email" /></td>
            </tr>
            <tr>
                <td>User</td>
                <td><input id="username" class="username" /></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" id="password" class="password" /></td>
            </tr>
        </table>
        <input type="button" value="submit" onclick="register.submit()" />
    </form>
</div>