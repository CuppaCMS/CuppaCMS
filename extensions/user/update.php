<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["web_document_path"]."administrator/classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $token = $cuppa->security->token;
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $language = $cuppa->language->load("web");
    // data
        $user = $cuppa->dataBase->getRow($cuppa->configuration->table_prefix."users", "id = ".@$cuppa->user->getVar("id"), $token, true);
?>
<style>
    .update{
        
    }
</style>
<script>
    update = {}
    update.uniqueClass = ".<?php echo @$_POST["uniqueClass"] ?>";
    //++ init
        update.init = function(){
            cuppa.configUploadFile(".update .image", "media/user_images", "administrator/js/jquery_file_upload/server/php/")
        }; cuppa.addEventListener("ready",  update.init, document, "update");
    //--
    //++ submit
        update.submit = function(){
            var data = {info:{}}
                data.info.name = jQuery(".update .name").val();
                data.info.email = jQuery(".update .email").val();
                data.info.username = jQuery(".update .username").val();
                data.info.current_password = jQuery(".update .current_password").val();
                data.info.password = jQuery(".update .password").val();
                data.info.image = jQuery(".update .image").val();
                data["function"] = "update";
            // Validation
                if(!cuppa.trim(data.info.name)){ alert("Please, fill the name field"); return;
                }else if(!cuppa.email(data.info.email)){ alert("Please, insert a valid email"); return;
                }else if(!cuppa.trim(data.info.username)){ alert("Please, insert a username"); return;
                }else if( cuppa.trim(data.info.password) && !cuppa.trim(data.info.current_password)){ alert("Please, insert the current password"); return;
                }
            // Send
                data.info = cuppa.jsonEncode(data.info,true);                        
                jQuery.ajax({url:"administrator/extensions/user/classes/functions.php", type:"POST", data:data, success:Ajax_Result});
                function Ajax_Result(result){
                    if(result == "-1"){
                        alert("The current password is incorrect"); return;
                    }
                    alert("Update success")
                    window.location=document.URL;
                }
        }
    //++ resize
        update.resize = function(){
            
        }; cuppa.addEventListener("resize", update.resize, window, "update"); update.resize();
    //--
    //++ end
        update.end = function(){
            cuppa.removeEventGroup("update");
        }; cuppa.addRemoveListener(".update", update.end);
    //--
</script>
<div class="update">
    <div>Update</div>
    <form>
        <table>
            <tr>
                <td>Name</td>
                <td><input id="name" class="name" value="<?php echo @$user->name ?>" /></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input id="email" class="email" value="<?php echo @$user->email ?>" /></td>
            </tr>
            <tr>
                <td>User</td>
                <td><input id="username" class="username" value="<?php echo @$user->username ?>" /></td>
            </tr>
            <tr>
                <td>Image</td>
                <td><input id="image" class="image" value="<?php echo @$user->image ?>" /></td>
            </tr>
        </table>
        <table>
            <tr>
                <td>Change password</td>
                <td>
                    <input type="password" id="current_password" class="current_password" placeholder="current password" />
                    <input type="password" id="password" class="password" placeholder="new password" />
                </td>
            </tr>
        </table>
        <input type="button" value="submit" onclick="update.submit()" />
    </form>
</div>
<?php include("administrator/js/cuppa/cuppa_assets.php"); ?>