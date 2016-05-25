<?php
    @session_start();
    if(!@$_SESSION["cuSession"]){ echo '<script> window.location=document.URL; </script>'; exit(); }
    include_once($_SESSION["cuSession"]->paths->administrator->document_path."classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $token = $cuppa->security->token;
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $language = $cuppa->language->load("web");
    $product = Shop::getProduct($path[count($path)-1], "");
    $images = $cuppa->dataBase->getList("ex_shop_product_media", $token, "type = 'image' AND enabled = 1 AND product = ".$product->id, "", "principal DESC, `order` ASC", true);
    $videos = $cuppa->dataBase->getList("ex_shop_product_media", $token, "type = 'youtube' AND enabled = 1 AND product = ".$product->id, "", "principal DESC, `order` ASC", true);
    $description_list = Shop::getDescriptionList($product->id);
    $related_products = Shop::getRelatedProducts($product->id);
    $related_products = @$related_products->products;
?>
<style>
    .shop_product{
        position: relative;
        max-width: 1100px;
        width: 100%;
        margin: 0px auto;
        padding: 0px 20px;
    }
    .shop_product .principal{
        position: relative;
        display: table;
        margin: 40px 0px;
    }
    .shop_product .principal .images_list{
        position: relative;
        display: table-cell;
        width: 80px;
        vertical-align: top;
    }
        .shop_product .principal .images_list .list{
            position: relative;
        }
        .shop_product .principal .images_list .item{
            position: relative;
            width: 80px;
            height: 80px;
            border: 1px solid #cecece;
            margin-bottom: 3px;
            background-position: center;
            background-size: contain;
            background-repeat: no-repeat;
            transition-duration: 0.2s;
            transition-property: border-color;
            cursor: pointer;
        }
            .shop_product .principal .images_list .item:hover{
                border-color: #999;
            }
        
        .shop_product .video_list{
            position: absolute;
            bottom: 0px;
            left: 0px;
        }
        .shop_product .video_list .item{
            position: relative;
            display: block;
            margin-bottom: 0px;
            margin-top: 3px;
        }

    .shop_product .principal .image_preview{
        position: relative;
        display: table-cell;
        min-width: 400px;
        vertical-align: top;
        overflow: hidden;
        height: 480px;
    }
        .shop_product .images_list .list_content{
            overflow: hidden;
            height: 249px;
        }
        .shop_product .principal .image_preview .item{
            position: absolute;
            width: 100%;
            height: 480px;
            background-position: center;
            background-size: contain;
            background-repeat: no-repeat;
            padding: 0px 20px 0px 0px;
            background-origin: content-box;
        }
        .shop_product .image_arrow_up{
            width: 19px;
            height: 19px;
            background-image: url("administrator/media/page/template/gallery_btn_arrow.png");
            background-position: left top;
            background-repeat: no-repeat;
            position: absolute;
            top: 255px;
            right: 22px;
            cursor: pointer;
            border-radius: 19px;
            opacity: 0.9;
        } .shop_product .image_arrow_up:hover{ background-position: right top;  }
        .shop_product .image_arrow_down{
            width: 19px;
            height: 19px;
            background-image: url("administrator/media/page/template/gallery_btn_arrow.png");
            background-position: left bottom;
            background-repeat: no-repeat;
            position: absolute;
            top: 255px;
            right: 0px;
            cursor: pointer;
            border-radius: 19px;
            opacity: 0.9;
        } .shop_product .image_arrow_down:hover{ background-position: right bottom; }
    .shop_product .principal .texts{
        position: relative;
        display: table-cell;
        vertical-align: top;
        width: 100%;
        border-left: 1px solid #e6e6e6;
        padding-left: 40px;
    }
        .shop_product .principal .title{
            position: relative;
            font-size: 36px;
            line-height: normal;
        }
        .shop_product .principal .abstract{
            position: relative;
            font-size: 14px;
            margin-top: -15px;
        }
            .shop_product .principal .abstract a{
                display: none;
            }
        .shop_product .principal .description{
            position: relative;
            font-size: 14px;
            margin-top: -10px;
            color: #737373;
            margin: 30px 0px;
        }
        .shop_product .principal .description_list{
            position: relative;
            margin-top: 20px;
        }
            .shop_product .principal .description_list .item{
                position: relative;
                display: table-header-group;
            }
            .shop_product .principal .description_list .item .name{
                position: relative;
                display: table-cell;
                white-space: nowrap;
                vertical-align: top;
                padding-right: 10px;
                min-width: 100px;
            }
            .shop_product .principal .description_list .item .value{
                position: relative;
                display: table-cell;
                vertical-align: top;
                color: #838383;
            }
            .share_buttons{
                position: relative;
                margin-top: 30px;
            }
            .share_buttons img{
                position: relative;
                margin-right: 5px;
                cursor: pointer;
                opacity: 0.9;
            }
/* Specific features */
    .specific_features{
        position: relative;
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        padding: 30px 20px 10px;
        background: #efefef;
    }
    .specific_features .title{
        position: relative;
        text-align: center;
        font-size: 19px;
        text-transform: uppercase;
    }
    .specific_features .text{
        position: relative;
        margin-top: 30px;
        max-width: 900px;
        margin: 30px auto 0px;
        
    }
        .specific_features .text td{
            vertical-align: top;
            padding: 0px 20px;
        }
/* Related products */
    .related_products{
        position: relative;
        padding: 0px 0px 20px;
        line-height: normal;
    }
    .related_products .content{
        position: relative;
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        padding: 0px 20px;
    }
    .related_products .title{
        position: relative;
        text-align: center;
        font-size: 38px;
        padding-top: 35px;
        text-transform: uppercase;
    }
    .related_products .items{
        padding: 35px 0px 0px;
        font-size: 0px;
        text-align: center;
    }
    .related_products .item{
        position: relative;
        display: inline-block;
        width: 25%;
        font-size: 12px;
        border: 1px solid;
        margin-bottom: 20px;
        padding: 10px 20px;
        border: 1px solid transparent;
        text-align: center;
        vertical-align: top;
    }
        .related_products .item:hover{
            border: 1px solid #DDD;
        }
    .related_products .name{
        font-size: 24px;
    }
    .related_products .description{
        font-size: 14px;
        color:  #737373;
        margin-top: -10px;
    }
    .related_products .btn_more{
        position: relative;
        color: #767677;
        font-size: 14px;
        margin-top: 10px;
    }
    .related_products .image{
        position: relative;
        display: block;
        margin: 0 auto;
        height: 190px;
        width: auto;
    }
/* Responsive */
    .r950 .shop_product .principal .image_preview{
        min-width: 280px;
        height: 450px;
    }
    .r950 .related_products .item .name {
        font-size: 18px;
    }
    .r950 .related_products .item .description {
        font-size: 11px;
    }
    .r950 .related_products .item .btn_more {
        font-size: 11px;
        margin-top: -10px;
    }
    .r780 .shop_product .principal .images_list {
        display: block;
        height: 280px;
        position: relative;
        vertical-align: top;
        width: 100%;
        z-index: 1;
    }
        .r780 .shop_product .image_arrow_up{
            left: 0px;
            right: auto;
        }
        .r780 .shop_product .image_arrow_down{
            left: 24px;
            right: auto;
        }
        .r780 .shop_product .video_list .item{
            margin: 0px 0px 3px 0px;
        }
    .r780 .shop_product .video_list{
        top: 0px;
        right: 0px;
        left: auto;
        bottom: auto;
    }
    .r780 .shop_product .principal .image_preview{
        display: block;
        position: absolute;
        top: 0px;
        left: 90px;
        right: 0px;
        height: 280px;
        min-width: 0px;
    }
        .r780 .shop_product .principal .image_preview .item{
            bottom: 0;
            height: auto;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            padding: 0px;
        }
    .r780 .shop_product .principal .texts{
        display: block;
        border: 0px;
        margin-top: 30px;
        padding: 0px;
    }
    .r780 .specific_features{
        font-size: 10px;
    }
    .r780 .specific_features .text td{
        padding: 0px 10px;    
    }
    
    
    .r780 .related_products .item{
        width: 33.3333%;
    }
    .r780 .related_products .item:last-child{
        display: none;
    }

    .r650 .shop_product .principal .description{
        font-size: 12px;
    }
    .r650 .related_products .item{
        width: 50%;
    }
    .r650 .related_products .item:last-child{
        display: inline-block;
    }
    .r650 .shop_product .video_list{
        display: none;
    }
    .r400 .related_products .item{
        width: 100%;
    }
</style>
<script>
    shop_product = {}
    shop_product.product = cuppa.jsonDecode("<?php echo $cuppa->utils->jsonEncode($product) ?>",true);
    //++ change image
        shop_product.changeImage = function(index){
            if(index == undefined) index = 0;
            var item = jQuery(".image_preview .item_"+index).get();
            TweenMax.to(".image_preview .item", 0, {delay:0.4, alpha:0, display:"none"});
            TweenMax.killTweensOf(item);
            TweenMax.to(item, 0.4, {alpha:1, display:"block"});
            jQuery(".image_preview").append(item);
            
            $(".zoomContainer").remove();
            $(item).elevateZoom({ zoomType:"inner", cursor:"crosshair" }); 
        }
    //--
    //++ slide images
        shop_product.slide_position = 0;
        shop_product.slide = function(e){
            var slide = $(e.currentTarget).attr("id");
            if(slide == "up") shop_product.slide_position--;
            else shop_product.slide_position++;
            if(shop_product.slide_position > $(".images_list .item_image").length - 3) shop_product.slide_position = 0;
             if(shop_product.slide_position < 0) shop_product.slide_position = $(".images_list .item_image").length - 3;
            
            var dimentions = cuppa.dimentions(".images_list .item_image");
            TweenMax.to(".images_list .list", 0.4, { top: -shop_product.slide_position*dimentions.height, ease:Cubic.easeInOut})
            
        }
    //--
    //++ end
        shop_product.end = function(){
            cuppa.removeEventGroup("shop_product");
        }; cuppa.addRemoveListener(".shop_product", shop_product.end);
    //--
    //++ init
        shop_product.init = function(){
            shop_product.changeImage();
            $(".shop_product .images_list .image_arrow_up, .shop_product .images_list .image_arrow_down").bind("click", shop_product.slide);
            if($(".shop_product .item_image").length <= 3)  $(".shop_product .images_list .image_arrow_up, .shop_product .images_list .image_arrow_down").css("display", "none")
            $(".item_cuttent").html("<?php echo @$product->name ?>");
        }; cuppa.addEventListener("ready",  shop_product.init, document, "shop_product");
    //--
</script>
<div class="shop_product">
    <div class="principal">
        <div class="images_list">
            <div class="list_content">
                <div class="list">
                    <?php for($i = 0; $i < count($images); $i++){ ?>
                        <div onclick="shop_product.changeImage(<?php echo $i ?>)"  class="item item_image" style="background-image: url(administrator/<?php echo @$images[$i]->thumbnail ?>);"></div>
                    <?php } ?>
                </div>
            </div>
            <div id="up" class="image_arrow_up"></div>
            <div id="down" class="image_arrow_down"></div>
            <div class="video_list">
                 <?php for($i = 0; $i < count($videos); $i++){ ?>
                    <a href="<?php echo $videos[$i]->src ?>" target="youtube" class="item" style="background-image: url(administrator/<?php echo @$videos[$i]->thumbnail ?>);"></a>
                 <?php } ?>
            </div>
        </div>
        <div class="image_preview">
            <?php for($i = 0; $i < count($images); $i++){ ?>
                <div data-zoom-image="administrator/<?php echo @$images[$i]->src ?>" class="item item_<?php echo $i ?>" style="background-image: url(administrator/<?php echo @$images[$i]->src ?>);"></div>
            <?php } ?>
        </div>
        <div class="texts">
            <h2 class="title font_new_sans_light"><?php echo $product->name ?></h2>
            <div class="abstract"><?php echo str_replace("media/", "administrator/media/", $product->abstract) ?></div>
            <div class="description"><?php echo str_replace("media/", "administrator/media/", $product->description) ?></div>
            <?php if(is_array($description_list)){ ?>
                <div class="description_list">
                    <?php for($i = 0; $i < count($description_list); $i++){ ?>
                        <div class="item">
                            <div class="name"><?php echo $description_list[$i]->name ?></div>
                            <div class="value"><?php echo $description_list[$i]->value_to_show ?></div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <div class="share_buttons">
                <?php
                    $url = $cuppa->getPath("web").join("/",$cuppa->utils->getUrlVars());
                    $image = $cuppa->getPath("web")."administrator/".$images[0]->thumbnail;
                    $title = "Samsonite: ".$product->name;
                    $description = $cuppa->utils->cutText(" ", $product->description, 150, "...", true, true);
                ?>
                <img onclick="cuppa.share('facebook','<?php echo $title ?>','<?php echo $description ?>','<?php echo $url ?>','<?php echo $image ?>')" src="administrator/media/page/template/btn_facebook2.png" />
                <img onclick="cuppa.share('twitter','<?php echo $title ?>','<?php echo $description ?>','<?php echo $url ?>','<?php echo $image ?>')" src="administrator/media/page/template/btn_twitter2.png"  />
            </div>
        </div>
    </div>
</div>
<?php if(@$product->specific_features){ ?>
    <div class="specific_features">
        <div class="title font_new_sans_light"><?php echo $language->caracteristicas_especificas ?></div>
        <div class="text">
            <?php echo str_replace("media/", "administrator/media/", @$product->specific_features) ?>
        </div>
    </div>
<?php } ?>
<?php if(is_array($related_products)){?>
    <div class="related_products">
        <div class="content">
            <h1 class="title font_new_sans_light"><?php echo $language->productos_relacionados ?></h1>
            <div class="items">
                <?php for($i = 0; $i < count($related_products); $i++){ ?>
                    <div class="item" >
                        <a href="<?php echo $cuppa->language->current()."/".$cuppa->language->getValue("shop", $language, true)."/".@$related_products[$i]->path."/".$related_products[$i]->alias ?>">
                            <div class="name font_new_sans_light"><?php echo $related_products[$i]->name ?></div>
                        </a> 
                        <div class="description"><?php $cuppa->echoString($related_products[$i]->abstract) ?></div>
                        <a href="<?php echo $cuppa->language->current()."/".$cuppa->language->getValue("shop", $language, true)."/".@$related_products[$i]->path."/".$related_products[$i]->alias ?>">
                            <img class="image" src="administrator/<?php echo $related_products[$i]->thumbnail ?>" />
                            <div class="btn_more"><?php echo $language->descubre_mas ?></div>
                        </a> 
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>