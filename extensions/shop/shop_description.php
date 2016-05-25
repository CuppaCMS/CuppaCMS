<?php
    @session_start();
    if(!@$_SESSION["cuSession"]){ echo '<script> window.location=document.URL; </script>'; exit(); }
    include_once($_SESSION["cuSession"]->paths->administrator->document_path."classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $token = $cuppa->security->token;
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $language = $cuppa->language->load("web");
    $product = $products[0];
    $images = $cuppa->dataBase->getList("ex_shop_product_images", $token, "enabled = 1 AND product = ".$product->id, "", "principal DESC, `order` ASC", true);
    $features = json_decode($product->features);
    //++ Get default price
        $specific_prices = $cuppa->utils->jsonDecode($product->specific_prices, false);
        $default_price = null;
        if($specific_prices){
            for($j = 0; $j < count($specific_prices); $j++){
                if(@$specific_prices[$j]->default){
                    $default_price = @$specific_prices[$j];
                    break;
                }
            }
        }
    //--
?>
<style>
    .shop_description{
        
    }
</style>
<script>
    shop_description = {}
    shop_description.product = cuppa.jsonDecode("<?php echo $cuppa->utils->jsonEncode($product, true) ?>",true);
    shop_description.uniqueClass = ".<?php echo @$_POST["uniqueClass"] ?>";
    //++ init
        shop_description.init = function(){
            cuppa.inputFilter(".shop_description .amount", "0-9");
            cuppa.range(".shop_description .amount", 1);
            jQuery(".shop_description .amount" ).spinner({min:1, stop:function(){ shop_description.update(); } } )
            shop_description.update(true);
        }; cuppa.addEventListener("ready",  shop_description.init, document, "shop_description");
    //--
    //++ Add to cart
        shop_description.addToCart = function(){
            var values = cuppa.getFormData(".shop_description form", true);
            cuppa.shop.add(values, jQuery(".shop_description .amount").val() );
            jQuery(".shop_description .amount").val(1);
        }
    //--
    //++ set info
        shop_description.setInfo = function(info){
            if(!info) return;
            if(info.available === "0" || info.stock === "0"){
                jQuery(".buy").css("display","none");
                jQuery(".stock .stock_number").html("Product not available");
                return;
            }
            jQuery(".buy").css("display","block");
            jQuery(".stock .stock_number").html(info.stock)
            for(var key in info){ jQuery("."+key).val(info[key]); }
            jQuery(".shop_description .price").val(info.price).html(cuppa.numberToMoney(parseFloat(info.price).toFixed(2)));
            jQuery(".shop_description .stock").val(info.stock);
            
            //++ Update description input
                var description = "";
                for(var i = 0; i < jQuery(".shop_description .option").length; i++){
                    var option = jQuery(".shop_description .option").get(i);
                    if(!jQuery(option).hasClass("description_ignore")){
                        if(i) description +=", ";
                        description+= jQuery(option).find(".option_name").text()+": "+jQuery(option).find("select option:selected").text();
                    }
                }
                jQuery(".shop_description .description").val(description);
            //--
        }
    //--
    //++ get info
        shop_description.getInfoSelected = function(){
            var specific_prices = cuppa.jsonDecode(shop_description.product.specific_prices, false);
            var data = null;
            var values = cuppa.getFormData(".shop_description form");
            if(specific_prices){
                for(var i = 0; i < specific_prices.length; i++){
                    var specific_price = specific_prices[i];
                    var enabled = true;
                    for(var key in specific_price){
                        if(key != "stock" &&  key != "price"){
                            if(values[key] && specific_price[key] && specific_price[key] != values[key]){
                                enabled = false;
                                break;
                            }
                        }
                    }
                    if(enabled){
                        data = specific_price;
                        break
                    }
                 }
             }
             if(!data){
                data = {}
                data.price = shop_description.product.price;
                data.stock = shop_description.product.stock;
             }
             return data;
        }
    //--
    //++ update configuration
        shop_description.update = function(default_params){
            if(default_params == undefined) default_params = false;
            var specific_prices = cuppa.jsonDecode(shop_description.product.specific_prices, false);
            var data = null;
            //++ specific data
                if(default_params && specific_prices){
                    for(var i = 0; i < specific_prices.length; i++){
                        if(specific_prices[i]["default"] == "1"){
                            data = specific_prices[i];
                            break;
                        }
                    }
                    if(!data) data = shop_description.getInfoSelected();
                }else{
                    data = shop_description.getInfoSelected();
                }
            //--
            shop_description.setInfo(data);
        };
    //--
    //++ end
        shop_description.end = function(){
            cuppa.removeEventGroup("shop_description");
        }; cuppa.addRemoveListener(".shop_description", shop_description.end);
    //--
</script>
<div class="shop_description">
    <form>
        <div class="name"><?php echo $product->name ?></div>
        <div class="code">Code: <?php echo $product->code ?></div>
        <div class="description"><?php echo $product->description ?></div>
        <div class="price"><?php echo number_format($product->price, 2) ?></div>
        <div class="stock"><strong>Stock: </strong><span class="stock_number">0</span></div>
        <a class="buy" onclick="shop_description.addToCart()"><strong>Add to cart</strong></a>
        <!-- Options -->
            <div class="options">
                <!-- Defined Options -->
                    <?php for($i = 0; $i < count($features); $i++){ ?>
                        <?php $feature = $cuppa->dataBase->getRow("ex_shop_features", "id = ".$features[$i], $token, true); ?>
                        <?php if($feature){ ?>
                            <?php
                                $feature_values = Shop::getFeatureValues($feature->id, $product->id);
                                $name = ($feature->label) ? $feature->label : $feature->name;                                
                            ?>
                            <div class="option">
                                <span class="option_name"><?php echo $name ?></span>
                                <select onchange="shop_description.update()" class="item_option <?php echo $cuppa->utils->getFriendlyUrl($name); ?> font_latoregular" name="<?php echo $cuppa->utils->getFriendlyUrl($name); ?>" id="<?php echo $cuppa->utils->getFriendlyUrl($name); ?>" >
                                    <?php foreach($feature_values as $value){ ?>
                                        <option value="<?php echo $value->id; ?>"><?php echo $value->label; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        <?php } ?>   
                    <?php } ?>
                <!-- -->
                <!-- Amount -->
                    <div class="option description_ignore">
                        <span class="option_name">Amount</span>
                        <input class="item_option amount" id="amount" name="amount" value="1" />
                    </div>
                <!-- -->
            </div>
        <!-- -->
        <input type="hidden" id="id" name="id" class="id" value="<?php echo $product->id ?>" />
        <input type="hidden" id="tax" name="tax" class="tax" value="<?php echo $product->tax ?>" />
        <input type="hidden" id="price" name="price" class="price" value="<?php echo $product->price ?>" />
        <input type="hidden" id="code" name="code" class="code" value="<?php echo $product->code ?>" />
        <input type="hidden" id="name" name="name" class="name" value="<?php echo $product->name ?>" />
        <input type="hidden" id="thumbnail" name="thumbnail" class="thumbnail" value="<?php echo $product->thumbnail ?>" />
        <input type="hidden" id="stock" name="stock" class="stock"  value="<?php echo @$product->stock ?>" />
        <input type="hidden" id="description" name="description" class="description" value="" />
    </form>
</div>