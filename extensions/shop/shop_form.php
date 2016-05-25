<?php
    @session_start();
    if(!@$_SESSION["cuSession"]){ echo '<script> window.location=document.URL; </script>'; exit(); }
    include_once($_SESSION["cuSession"]->paths->administrator->document_path."classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $token = $cuppa->security->token;
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $language = $cuppa->language->load("web");
    $language2 = $cuppa->language->load("shop_form");
?>
<style>
    .shop_form{
        position: fixed;
        top: 0px;
        right: 0px;
        bottom: 0px;
        width: 400px;
        background: #151A1D;
        z-index: 40;
        padding: 10px 20px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        overflow: auto;
        display: none;
    }
    .shop_form .btn_close{
        position: absolute;
        top: 0px;
        right: 0px;
        background-color: #FFE000;
        width: 45px;
        height: 45px;
        background-repeat: no-repeat;
        background-position: center;
        cursor: pointer;
    }
    .shop_form .principal_title2{
        position: relative;
        margin-top: 60px;
        color: #FFE000;
    }
    .shop_form .text1{
        font-size: 14px;
        color: #FFF;
        margin-top: 20px;
        text-align: center;
    }
    .shop_form .form{
        margin-top: 40px;
        margin-bottom: 40px;
    }
    .shop_form textarea{
        height: 100px;
    }
    .shop_form .product_data{
        display: none;
    }
    .shop_form .button{
        position: relative;
        text-align: center;
        border: 1px solid #FFF;
        background: #151A1D;
        color: #FFF;
        padding: 15px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        margin: 0 auto;
    }
    /* Responsive */
        .less_400 .shop_form{
            width: 300px;
        }
</style>
<script>
    shop_form = {}
    shop_form.uniqueClass = ".<?php echo @$_POST["uniqueClass"] ?>";
    shop_form.name = "<?php echo @$language2->name ?>";
    shop_form.phone = "<?php echo @$language2->phone ?>";
    shop_form.email = "<?php echo @$language2->email ?>";
    shop_form.message = "<?php echo @$language2->message ?>";
    //++ init
        shop_form.init = function(){
            cuppa.button(".shop_form .button", "#151A1D","#333333");
        }; cuppa.addEventListener("ready",  shop_form.init, document, "shop_form");
    //--
    //++ Send
        shop_form.send = function(){
            jQuery(".shop_form input, .shop_form textarea").removeClass("error");
            if( jQuery(".shop_form .name").val() == shop_form.name ){
                jQuery(".shop_form .name").addClass("error").focus();
                return;
            }else if( !cuppa.email(jQuery(".shop_form .email").val()) ){
                jQuery(".shop_form .email").addClass("error").focus();
                return;
            }else if( jQuery(".shop_form .phone").val() == shop_form.message ){
                jQuery(".shop_form .phone").addClass("error").focus();
                return;
            }else if( jQuery(".shop_form .message").val() == shop_form.message ){
                jQuery(".shop_form .message").addClass("error").focus();
                return;
            }
            
            var data = {}
                data.info = {}
                data.info.name = jQuery(".shop_form .name").val();
                data.info.email = jQuery(".shop_form .email").val();
                data.info.phone = jQuery(".shop_form .phone").val();
                data.info.message = jQuery(".shop_form .message").val();
                data.info.product_data = jQuery(".shop_form .product_data").val();
                data.info = cuppa.jsonEncode(data.info, true);
                data["function"] = "saveShopForm";
            cuppa.blockade({duration:0.4})
            cuppa.charger({duration:0.4})
            jQuery.ajax({url:"classes/functions.php", type:"POST", data:data, success:Ajax_Result})
                function Ajax_Result(result){
                    alert("Thank you for write us, we will contact you shortly");
                    cuppa.charger({load:false, duration:0.4});
                    cuppa.blockade({load:false, duration:0.4, delay:0.3});
                    TweenMax.delayedCall(0.5,shop_form.show);
                }
        }
    //--
    //++ show form
        shop_form.show = function(value, duration){
            if(value){
                if(duration == undefined) duration = 0.4;
                TweenMax.to(".shop_form", duration, {right:0, display:"block", ease:Cubic.easeOut});
                shop_form.config();
                shop_form.getDataProduct();
            }else{
                if(duration == undefined) duration = 0.3;
                TweenMax.to(".shop_form", duration, {right:-jQuery(".shop_form").width(), display:"none", ease:Cubic.easeIn});
            }
        }; shop_form.show(false);
    //--
    //++ get data product
        shop_form.getDataProduct = function(){
            var string = "";
            var items = jQuery(".shop_description input, .shop_description select").get();
            for(var i = 0; i < items.length; i++){
                if(jQuery(items[i]).attr("id")){
                    string += jQuery(items[i]).attr("id")+": ";
                    string += jQuery(items[i]).val();
                    string += "\n";
                }
            }
            jQuery(".shop_form .product_data").val(string);
        }
    //--
    //++ config
        shop_form.config = function(){
            jQuery(".shop_form .name").val("").preText(shop_form.name);
            jQuery(".shop_form .email").val("").preText(shop_form.email);
            jQuery(".shop_form .phone").val("").preText(shop_form.phone);
            jQuery(".shop_form .message").val("").preText(shop_form.message);
        }; shop_form.config();
    //--
    //++ resize
        shop_form.resize = function(){
            
        }; cuppa.addEventListener("resize", shop_form.resize, window, "shop_form"); shop_form.resize();
    //++ end
        shop_form.end = function(){
            cuppa.removeEventGroup("shop_form");
        }; cuppa.addRemoveListener(".shop_form", shop_form.end);
    //--
</script>
<div class="shop_form">
    <div class="btn_close" onclick="shop_form.show(false)"></div>
    <div class="principal_title2"><?php echo @$language2->text1 ?></div>
    <div class="text1"><?php echo @$language2->text2 ?></div>
    <div class="form">
        <input class="name" value="" />
        <input class="email" value="" />
        <input class="phone" value="" />
        <textarea class="message"></textarea>
        <div class="button" onclick="shop_form.send()"><?php echo @$language2->send ?></div>
        <textarea class="product_data" ></textarea>
    </div>
</div>