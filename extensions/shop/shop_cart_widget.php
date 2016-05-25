<?php
    @session_start();
    if(!@$_SESSION["cuSession"]){ echo '<script> window.location=document.URL; </script>'; exit(); }
    include_once($_SESSION["cuSession"]->paths->administrator->document_path."classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $token = $cuppa->security->token;
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $language = $cuppa->language->load("web");
    //++ Get Shipping
        if(!$cuppa->getSessionVar("country")) $cuppa->setSessionVar("country", "0");
        $sql = "Select * FROM ex_shop_shipping 
                WHERE country LIKE '%\"".$cuppa->getSessionVar("country")."\"%' ";
        $shipping = $cuppa->dataBase->personalSql($sql, $token, true);
        $shipping = (is_array($shipping)) ? $shipping = $shipping[0] : null;
    //--
?>
<style>
    .shop_cart_widget{
        background: #EEE;
        position: absolute;
        display: inline-block;
        top: 0px;
        right: 0px;
    }
    .shop_cart_widget .summary{
        position: relative;
        cursor: pointer;
        width: 170px;
        padding: 10px;
    }
    .shop_cart_widget .list{
        position: relative;
        width: 300px;
        overflow: hidden;
        display: none;
        margin-left: -110px;
    } .shop_cart_widget:hover .list{ display: block; }
        .shop_cart_widget .list .more_data{
             background: #EEE;
             padding: 10px;
             box-sizing: border-box;
             -moz-box-sizing: border-box;
        }
        .shop_cart_widget .list .item{
            position: relative;
            overflow: hidden;
            background: #EEE;
        }
            .shop_cart_widget .list .item .separator{
                position: relative;
                clear: both;
                height: 1px;
                border-bottom: 1px solid #AAA;
                width: 90%;
                margin: 0 auto;
            }
            .shop_cart_widget .list .item_image{
                position: absolute;
                top: 0px;
                left: 0px;
                bottom: 0px;
                width: 30%;
                
            }
                .shop_cart_widget .list .item_image .image{
                    position: absolute;
                    top: 10px;
                    left: 10px;
                    right: 10px;
                    bottom: 10px;
                    background-position: center;
                    background-size: cover;
                }
            .shop_cart_widget .list .item_data{
                position: relative;
                float: right;
                width: 70%;
                padding: 8px 10px;
                box-sizing: border-box;
                -moz-box-sizing: border-box;
            }
</style>
<script>
    shop_cart_widget = {}
    shop_cart_widget.total_items = 0;
    shop_cart_widget.total_price = 0;
    shop_cart_widget.shipping = cuppa.jsonDecode("<?php echo $cuppa->utils->jsonEncode($shipping); ?>");
    shop_cart_widget.uniqueClass = ".<?php echo @$_POST["uniqueClass"] ?>";
    //++ init
        shop_cart_widget.init = function(){
            shop_cart_widget.update();
            jQuery(cuppa.shop).bind("added", shop_cart_widget.update);
        }; cuppa.addEventListener("ready",  shop_cart_widget.init, document, "shop_cart_widget");
    //--
    //++ update items
        shop_cart_widget.update = function(){
            shop_cart_widget.total_items = 0;
            shop_cart_widget.total_price = 0;
            if(!cuppa.shop.products) return;
            for(i = 0; i < cuppa.shop.products.length; i++){
                shop_cart_widget.total_items += parseFloat(cuppa.shop.products[i].amount);
                shop_cart_widget.total_price += parseFloat(cuppa.shop.products[i].info.price)*parseFloat(cuppa.shop.products[i].amount);
            }
            jQuery(".shop_cart_widget .summary .total_items").html( shop_cart_widget.total_items);
            jQuery(".shop_cart_widget .summary .total_price").html(shop_cart_widget.total_price.toFixed(2));
            shop_cart_widget.updateList();
        } 
    //--
    //++ update list
        shop_cart_widget.updateList = function(){
            jQuery(".shop_cart_widget .list").html("");
            if(!cuppa.shop.products.length) return;
            var total = 0;
            //++ Set items
                for(i = 0; i < cuppa.shop.products.length; i++){
                        var item = jQuery(".shop_assets .item").clone();
                            jQuery(item).find(".amount").html( cuppa.shop.products[i].amount );
                            jQuery(item).find(".name .text").html(cuppa.shop.products[i].info.name );
                            jQuery(item).find(".code .text").html(cuppa.shop.products[i].info.code );
                            jQuery(item).find(".Tax .text").html(cuppa.shop.products[i].info.tax );
                            // Add options
                                if(cuppa.shop.products[i].info.description){
                                    jQuery(item).find(".description .text").html(cuppa.shop.products[i].info.description );
                                }else{
                                    jQuery(item).find(".description").remove();
                                }
                            //++ 
                            jQuery(item).find(".price .number").html( (cuppa.shop.products[i].info.price*cuppa.shop.products[i].amount).toFixed(2) );
                            jQuery(item).find(".item_image .image").css("background-image", "url(administrator/"+cuppa.shop.products[i].info.thumbnail+")")
                            jQuery(item).find(".remove").attr("onclick", "cuppa.shop.remove("+i+")");
                            //++ calculate more_data values
                                total += cuppa.shop.products[i].info.price*cuppa.shop.products[i].amount*(cuppa.shop.products[i].info.tax*0.01+1);
                            //--
                        jQuery(".shop_cart_widget .list").append(item);
                    //--
                }
            //--
            //++ Set more data info
                var more_data = jQuery(".shop_assets .more_data").clone();
                    total += parseFloat(shop_cart_widget.shipping.price);
                    jQuery(more_data).find(".shipping .number").html(parseFloat(shop_cart_widget.shipping.price).toFixed(2));
                    jQuery(more_data).find(".total .number").html(total.toFixed(2))
                    jQuery(".shop_cart_widget .list").append(more_data);
            //--
        }
    //--
    //++ end
        shop_cart_widget.end = function(){
            cuppa.removeEventGroup("shop_cart_widget");
        }; cuppa.addRemoveListener(".shop_cart_widget", shop_cart_widget.end);
    //--
</script>
<div class="shop_cart_widget">
    <div class="summary">
        <strong>Shopping cart</strong>
        <div><span class="total_items">0</span> Item(s) - $<span class="total_price">0</span></div>
    </div>
    <div class="list"></div>
</div>
<div class="shop_assets" style="display: none;">
    <!-- Item -->
        <div class="item">
            <div class="item_image">
                <div class="image"></div>
            </div>
            <div class="item_data">
                <div class="name"><span class="amount">0</span> x <span class="text">name</span></div>
                <div class="code">Code: <span class="text"></span></div>
                <div class="description">Description: <span class="text"></span></div>
                <div class="price">Price: $ <span class="number">0</span></div>
                <div class="Tax">Tax: <span class="text">0</span>%</div>
                <a class="remove"><strong>remove</strong></a>
            </div>
            <div class="separator"></div>
        </div>
    <!-- -->
    <!-- More data -->
        <div class="more_data">
            <div class="shipping"><strong>Shipping:</strong> $ <span class="number">0</span></div>
            <div class="total"><strong>Total:</strong> $ <span class="number">0</span></div>
            <a class="link" href="<?php echo $cuppa->language->current()."/$key_word/check-out" ?>" >
                <strong>Check out</strong>
            </a>
        </div>
    <!-- -->
</div>