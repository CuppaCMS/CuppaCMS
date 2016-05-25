<?php
    @session_start();
    if(!@$_SESSION["cuSession"]){ echo '<script> window.location=document.URL; </script>'; exit(); }
    include_once($_SESSION["cuSession"]->paths->administrator->document_path."classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $token = $cuppa->security->token;
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $language = $cuppa->language->load("web");
    $product = $cuppa->dataBase->getRow("ex_shop_products", "id = ".@$_REQUEST["product"], $token, true);
    $features = json_decode($product->features);
?>
<style>
    .specific_prices{
        margin-top: 20px;
    }
    .specific_prices .list{
        padding: 0px;
    }
    .specific_prices .list span{
        position: relative;
        margin: 0px 10px;
    }
</style>
<script>
    specific_prices = {}
    specific_prices.columns = 3;
    specific_prices.uniqueClass = ".<?php echo @$_POST["uniqueClass"] ?>";
    //++ init
        specific_prices.init = function(){
            jQuery("#cancel_form").after(jQuery(".btn_specific_prices"));
            specific_prices.setInfo();
            jQuery(".tr_specific-prices").css("display", "none");
        }; cuppa.addEventListener("ready",  specific_prices.init, document, "specific_prices");
    //--
    //++ set info
        specific_prices.setInfo = function(){
            var dataArray = cuppa.jsonDecode(jQuery("#specific_prices_field").val(), false);
            if(!dataArray) return;
            for(var i = 0; i < dataArray.length; i++){
                specific_prices.add(dataArray[i]);
            }
        }
    //--
    //++ Add
        specific_prices.add = function(info){
            var item = jQuery(".assets .item_template").clone();
                jQuery(item).removeClass("item_template");
                jQuery(".specific_prices .list").append(item);
                jQuery(item).find("select").bind("change", specific_prices.updateInfo)
                jQuery(item).find("input").bind("change", specific_prices.updateInfo).bind("input", specific_prices.updateInfo)
                if(info){
                    for(var key in info){
                       jQuery(item).find("."+key).val(info[key]);
                    }
                }
                ConfigureMoneyFormat(".specific_prices .price");
        }
    //--
    //++ get info
        specific_prices.updateInfo = function(){
            var items = jQuery(".specific_prices .list li").get();
            var dataArray = new Array();
            for(var i = 0; i < items.length; i++){
                var data = {}
                    data.price = jQuery(items[i]).find(".price").val();
                    data.stock = jQuery(items[i]).find(".stock").val();
                        //++ select data
                        var selects = jQuery(items[i]).find("select").get();
                        for(var j = 0; j < selects.length; j++){
                            data[jQuery(selects[j]).attr("id")] = jQuery(selects[j]).val();
                        }
                    //--
                    dataArray.push(data);
            }
            if(dataArray.length){
                jQuery("#specific_prices_field").val( cuppa.jsonEncode(dataArray, false) );
            }else{
                jQuery("#specific_prices_field").val("");
            }
        }
    //--
    //++ delete
        specific_prices.deleteItem = function(item){
           jQuery(item).parent().remove();
           specific_prices.updateInfo();
        };
    //--
</script>
<div class="specific_prices">
    <input onclick="specific_prices.add()" type="button" value="Add specific prices" class="button_form2 btn_specific_prices"  />
    <ul class="list option_panel">
        
    </ul>
</div>
<!-- Assets -->
    <div class="assets" style="display: none;">
        <li class="item_template">
            <span>Available</span>
            <select class="item_option available" id="available" >
                <option value="1" >True</option>
                <option value="0" >False</option>  
            </select>
            <span>Default configuration</span>
            <select class="item_option default" id="default" >
                <option value="0" >False</option>
                <option value="1" >True</option>
            </select>
            <span>Price</span>
            <input style="width: 100px;" class="price" id="price" />  
            <?php for($i = 0; $i < count($features); $i++){ ?>
                <?php $feature = $cuppa->dataBase->getRow("ex_shop_features", "id = ".$features[$i], $token, true); ?>
                <?php if($feature){ ?>
                    <?php
                        $feature_values = $cuppa->dataBase->getList("ex_shop_features_values", $token, "feature = ".$feature->id, "", "`order` ASC", true);
                        $name = ($feature->label) ? $feature->label : $feature->name;
                    ?>
                    <span><?php echo $name ?></span>
                    <select class="item_option <?php echo $cuppa->utils->getFriendlyUrl($name); ?>" id="<?php echo $cuppa->utils->getFriendlyUrl($name); ?>" >
                        <?php for($j = 0; $j < count($feature_values); $j++){ ?>
                            <option value="<?php echo $feature_values[$j]->id ?>"><?php echo $feature_values[$j]->label ?></option>
                        <?php } ?>
                    </select>
                 <?php } ?>
            <?php } ?>
            <span>Stock</span>
            <input style="width: 50px;" class="stock" id="stock" />
            <div onclick="specific_prices.deleteItem(this)" class="button_delete button" title="Delete item"></div>
        </li>
    </div>
<!-- -->