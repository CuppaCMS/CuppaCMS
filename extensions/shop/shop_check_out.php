<?php
    @session_start();
    if(!@$_SESSION["cuSession"]){ echo '<script> window.location=document.URL; </script>'; exit(); }
    include_once($_SESSION["cuSession"]->paths->administrator->document_path."classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $token = $cuppa->security->token;
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $language = $cuppa->language->load("web");
    include_once("classes/Shop.php");
    $products = Shop::getSelectedProducts();
    //++ Get Shipping
        if(!$cuppa->getSessionVar("country")) $cuppa->setSessionVar("country", "0");
        $sql = "Select * FROM ex_shop_shipping 
                WHERE country LIKE '%\"".$cuppa->getSessionVar("country")."\"%' ";
        $shipping = $cuppa->dataBase->personalSql($sql, $token, true);
        $shipping = (is_array($shipping)) ? $shipping = $shipping[0] : null;
    //--
?>
<style>
    .shop_check_out{
        
    }
    .shop_check_out .products{
        width: 100%;
    }
    .shop_check_out th{
        text-align: left;
    }
    .shop_check_out td{
        padding: 10px;
    }
    .shop_check_out .thumbnail{
        width: 50px;
        height: 50px;
        background-size: cover;
        background-position: center;
    }
</style>
<script>
    shop_check_out = {}
    shop_check_out.columns = 3;
    shop_check_out.shipping = cuppa.jsonDecode("<?php echo $cuppa->utils->jsonEncode($shipping); ?>");
    //++ init
        shop_check_out.init = function(){
            cuppa.inputFilter(".shop_check_out .amount", "0-9");
            cuppa.range(".shop_check_out .amount", 1);
            jQuery(".shop_check_out .amount" ).spinner({min:1, stop:shop_check_out.update } );
            shop_check_out.updatePage();
        }; cuppa.addEventListener("ready",  shop_check_out.init, document, "shop_check_out");
    //--
    //++ update
        shop_check_out.update = function(e){
            var amount = jQuery(this).val();
            if(!amount) return;
            var index = jQuery(".shop_check_out .amount").index(this);
                amount = cuppa.shop.changeAmount(index, amount);
            jQuery(this).val(amount);
            shop_check_out.updatePage();
        }
    //--
    //++ update page info
        shop_check_out.updatePage = function(){
            var sum_products = 0;
            for(var i = 0; i < cuppa.shop.products.length; i++){
                var item = jQuery(".shop_check_out .product").get(i);
                var total = cuppa.shop.products[i].amount*cuppa.shop.products[i].info.price;
                    total = total + total*(cuppa.shop.products[i].info.tax/100);
                jQuery(item).find(".sum_product .number").html( cuppa.numberFormat(total,2));
                sum_products += total;
            }
            var total = parseFloat(sum_products)+parseFloat(shop_check_out.shipping.price);
            jQuery(".sum_products .number").html(cuppa.numberFormat(sum_products,2));
            jQuery(".shipping .number").html(cuppa.numberFormat(shop_check_out.shipping.price,2));
            jQuery(".total .number").html(cuppa.numberFormat(total,2));
        }
    //--
    //++ Remove
        shop_check_out.remove = function(index){
            jQuery(jQuery(".shop_check_out .product").get(index)).remove();
            cuppa.shop.remove(index);
        }
    //--
    //++ end
        shop_check_out.end = function(){
            cuppa.removeEventGroup("shop_check_out");
        }; cuppa.addRemoveListener(".shop_check_out", shop_check_out.end);
    //--
</script>
<div class="shop_check_out">
    <table class="products">
        <tr>
            <th>Product</th>
            <th>description</th>
            <th>Unit price</th>
            <th>Quantity</th>
            <th>Stock</th>
            <th>Total</th>
            <th></th>
        </tr>
        <?php for($i = 0; $i < count($products); $i++){ ?>
            <tr class="product">
                <td>
                    <div class="thumbnail" style="background-image: url(administrator/<?php echo $products[$i]->info->thumbnail ?>);"></div>
                </td>
                <td>
                    <div>Name: <?php echo $products[$i]->info->name ?></div>
                    <div>Code: <?php echo $products[$i]->info->code ?></div>
                    <div>Tax: <?php echo $products[$i]->info->tax ?></div>
                    <?php if(@$products[$i]->info->description){ ?>
                        <div>Description: <?php echo @$products[$i]->info->description ?></div>
                    <?php } ?>
                </td>
                <td>
                    <span>$</span>
                    <span class="number"><?php echo number_format($products[$i]->info->price,2) ?></span>
                </td>
                <td>
                    <input class="amount" id="amount" name="amount" value="<?php echo $products[$i]->amount ?>"  />
                </td>
                <td>
                    <?php echo $products[$i]->info->stock ?>
                </td>
                <td class="sum_product">
                    <span>$</span>
                    <span class="number">0</span>
                </td>
                <td>
                    <a onclick="shop_check_out.remove(<?php echo $i ?>)">Remove</a>
                </td>
            </tr>
        <?php } ?>
        <tr class="sum_products">
            <td colspan="4"></td>
            <td colspan="2">Total products</td>
            <td>
                <span>$</span>
                <span class="number">0</span>
            </td>
        </tr>
        <tr class="shipping">
            <td colspan="4"></td>
            <td colspan="2">Shipping</td>
            <td>
                <span>$</span>
                <span class="number">0</span>
            </td>
        </tr>
        <tr class="total">
            <td colspan="4"></td>
            <td colspan="2">TOTAL</td>
            <td>
                <span>$</span>
                <span class="number">0</span>
            </td>
        </tr>
    </table>
</div>