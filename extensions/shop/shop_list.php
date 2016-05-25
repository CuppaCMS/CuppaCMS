<?php
    @session_start();
    if(!@$_SESSION["cuSession"]){ echo '<script> window.location=document.URL; </script>'; exit(); }
    include_once($_SESSION["cuSession"]->paths->administrator->document_path."classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
    $token = $cuppa->security->token;
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $language = $cuppa->language->load("web");
    $limit = 12;
    //++ if search
        $condition = " show_in_list = 1 ";
        if($cuppa->GET("q")){
            $condition .= " AND name LIKE '%".$cuppa->GET("q")."%'";
        }
    //--
    $products = Shop::getProducts(@$section->id, $condition, $limit, "`order` ASC, id ASC");
    $filters = Shop::getSectionFilters(@$section->id);  
?>
<style>
    .shop_list{
        position: relative;
        max-width: 1100px;
        margin: 0 auto 40px;
        width: 100%;
        line-height: normal;
    }
    .shop_list .title{
        font-size: 40px;
        margin: 20px 0px 0px;
        text-align: left;
        text-transform: uppercase;
        padding: 0px 20px;
    }
    .shop_list .separator{
        position: relative;
        border-bottom: 1px solid #E2E2E2;
        margin: 20px 20px 10px;
        
    }
    .shop_list .items{
        position: relative;
        margin-top: 23px;
        font-size: 0px;
    }
    .shop_list .item{
        position: relative;
        display: inline-block;
        width: 25%;
        vertical-align: top;
        font-size: 12px;
    }
    .shop_list .item_content{
        position: relative;
        margin: 0 auto;
        width: 100%;
        max-width: 240px;
        padding: 10px 10px;
        border: 1px solid transparent;
        transition-duration: 0.2s;
        transition-property: border-color;
    }
        .shop_list .item_content:hover{
            border: 1px solid #DDD;
        }
        .shop_list .item .image{
            position: relative;
            display: block;
            margin: 0 auto;
            background-position: center;
            background-size: contain;
            background-repeat: no-repeat;
            max-height: 270px;
        }
        .shop_list .item .name{
            font-size: 19px;
            color: #040405;
            text-transform: uppercase;
        }
        .shop_list .item .abstract{
            font-size: 14px;
            color:  #737373;
        }
/* Filters */
    .shop_list .filters{
        padding: 0px 15px;
    }
        .shop_list .filter{
            position: relative;
            display: inline-block;
            margin: 0px 2px;
        } 
            .shop_list .btn_filter{
                position: relative;
                border: 1px solid #CECECE;
                padding: 0px 25px 0px 10px;
                background-color: #FFF;
                background-image: url("administrator/media/page/template/arrow_down.png");
                background-position: right 5px center;
                background-repeat: no-repeat;
                cursor: pointer;
                height: 26px;
                z-index: 11;
                border-radius: 3px;
                
            }
            .shop_list .btn_filter span{ 
                position: relative; top: 5px;
            }
        .shop_list .filter .dropdown{
            position: absolute;
            top: 25px;
            left: 0px;
            border: 1px solid #CECECE;
            z-index: 10;
            min-width: 200px;
            background: #FFF;
            display: none;
            border-radius: 0px 0px 3px 3px;
            background-color: #F7F7F7;
            padding: 7px;
        }
        .shop_list .filter .item_filter{
            padding: 4px 10px;
            white-space: nowrap;
        }
        .shop_list .item_filter .checkbox{
            position: relative;
        }
        .shop_list .item_filter .text{
            position: relative;
            top: 2px;
            vertical-align: top;
        }
        .shop_list .item_filter  .color{
            position: relative;
            display: inline-block;
            width: 20px;
            height: 20px;
            top: 2px;
            border-radius: 20px;
            opacity: 1;
            cursor: pointer;
            transition-duration: 0.2s;
            transition-property: border-color;
            border: 1px solid transparent;
        } 
            .shop_list .item_filter .color .color_img{
                position: absolute;
                top: 2px;
                bottom: 2px;
                left: 2px;
                right: 2px;
                background-position: center;
                background-size: cover;
                border-radius: 20px;
            }
        
            .shop_list .item_filter .color:hover{
                border: 1px solid #a0a0a0;
            }
            .shop_list .item_filter .selected{
                border: 1px solid #a0a0a0;
            }
        .shop_list .item_filter .text_color{
            margin-left: 3px;
            position: relative;
            top: -3px;
        }
        .shop_list .filters .result{
            position: relative;
            display: inline-block;
            margin-left: 10px;
            top: 4px;
            color: #737373;
        }
        .shop_list .filter:hover .btn_filter{
            border-bottom: 0px solid transparent;
            border-radius: 3px 3px 0px 0px;
            background-color: #F7F7F7;
        }
        .shop_list .filter:hover .dropdown{
            display: block;
        }
        .shop_list .filter_order{
            position: relative;
            float: right;
        }
        .shop_list .filter_order .dropdown{
            left: auto;
            right: 0px;
        }
        .shop_list .loader{
            position: relative;
            background: #FFF;
            border: 1px solid #cecece;
            color: #888;
            margin: 10px auto;
            padding: 10px 20px;
            text-align: center;
            max-width: 150px;
            border-radius: 3px;
            transition-duration: 0.2s;
            transition-property: opacity;
            background-image: url("administrator/media/page/template/loader_16.gif");
            background-position: right 10px center;
            background-repeat: no-repeat;
            opacity: 0;
        }
        .shop_list .activated{
            opacity: 1;
        }
        /* Filter color */
            .shop_list .filter_color .dropdown{
                width: 231px;
            }
            .shop_list .filter_color .dropdown .item_filter{
                width: 50%;
                float: left;
            }
        /* Filter colection */
            .shop_list .filter_coleccion .dropdown{
                width: 440px;
            }
            .shop_list .filter_coleccion .dropdown .item_filter{
                width: 33.3333%;
                float: left;
            }
/* Responsive */
    .r950 .shop_list .item .name  {
        font-size: 18px;
    }
    .r950 .shop_list .item .abstract  {
        font-size: 11px;
    }
    .r950 .shop_list .item .image {
        max-height: 230px;
    }
    .r780 .shop_list .item{
        width: 33.3333%;
    }
    .r650 .shop_list .item{
        width: 50%;
    }
    .r650 .shop_list .filters .result {
        display: none;
    }
    .r650 .shop_list .filters .filter_order{
        display: none;
    }
    .r650 .shop_list .filter_coleccion .dropdown{
        width: 300px;
    }
    .r650 .shop_list .filter_coleccion .dropdown .item_filter{
        width: 50%;
        float: left;
    }
            
    .r400 .shop_list .item{
        width: 100%;
    }    
</style>
<script>
    shop_list = {}
    shop_list.ajax = null;
    shop_list.page = 0;
    shop_list.blockade_load = false;
    shop_list.condition = "";
    shop_list.order = "`order` ASC, id ASC";
    //++ update filters
        shop_list.updateFilters = function(){
            var filters_selected = {};
            var selected_filters = $(".filter input[type=checkbox]:checked").get();
            for(var i = 0; i < selected_filters.length; i++){
                var filter = $(selected_filters[i]).attr("filter");
                var value = '"'+$(selected_filters[i]).attr("id")+'"';
                if(!filters_selected[filter]) filters_selected[filter] = new Array();
                filters_selected[filter].push(value);
            }
            //++ Crate condition
                var condition = new Array();
                for (var i in filters_selected) {
                   filters_selected[i] = "( filters_string LIKE '%"+filters_selected[i].join("%' OR filters_string LIKE '%")+"%' )";
                }
                for (var i in filters_selected) { condition.push(filters_selected[i]); }
                condition = condition.join(" AND ");
                shop_list.condition = condition;
            //--
            cuppa.moveContent("body", "", false, true, 0, 0, 0.4, Cubic.easeInOut);
            shop_list.page = 0;
            shop_list.updateList();
        }
    //--
    //++ update order
        shop_list.updateOrder = function(){
            shop_list.order =  $(".shop_list .order_by:checked").attr("order");
            cuppa.moveContent("body", "", false, true, 0, 0, 0.4, Cubic.easeInOut);
            shop_list.page = 0;
            shop_list.updateList();
        }
    //--
    //++ update list
        shop_list.updateList = function(append, blockade_screen){
            if(blockade_screen == undefined) blockade_screen = true;
            //++ send info
                if(blockade_screen){
                    cuppa.blockade({duration:0.2});
                    cuppa.charger({duration:0.2});
                }
                //++ Add search condition
                    if( !shop_list.condition && "<?php echo @$condition ?>"){
                        shop_list.condition = "<?php echo @$condition ?> ";
                    }
                //--
                var data = {}
                    data.section = "<?php echo @$section->id ?>";
                    data.condition = $.base64Encode( " show_in_list = 1 AND " + shop_list.condition);
                    data.limit = parseFloat("<?php echo $limit ?>")*shop_list.page + ",<?php echo $limit ?>";
                    data.order = shop_list.order;
                    data["function"] = "getProducts";
                    if(shop_list.ajax) shop_list.ajax.abort();
                    shop_list.ajax = jQuery.ajax({url:"administrator/extensions/shop/classes/ShopAjax.php", type:"POST", data:data, success:Ajax_Result});
                    function Ajax_Result(result){
                        cuppa.blockade({load:false, delay:0.2, duration:0.2});
                        cuppa.charger({load:false, duration:0.2});
                        $(".shop_list .loader").removeClass("activated");
                        result = cuppa.jsonDecode(result);
                        if(result.products){
                            shop_list.refreshList(result, append);
                            shop_list.blockade_load = false;
                        }else{
                            /*
                            var data = {}
                                data.title = "<?php echo $language->mensaje ?>";
                                data.message = "<?php echo $language->mensaje_alerta_1 ?>";
                            cuppa.setContent({url:"js/cuppa/html/alert.php", data:data, name:"alert", executeFunction:"cuppa_alert.resize", preload:false, duration:0.3});
                            */
                        }
                    }
            //--
        }
    //--
    //++ reload list
        shop_list.refreshList = function(data, append){
            var item_tmp = $(".shop_list_assets .item").clone().get(0);
            if(!append) $(".shop_list .items").html("");
            for(var i = 0; i < data.products.length; i++){
                var item = $(item_tmp).clone();
                    $(item).find("a").attr("href","<?php echo $path_just_shop_string2 ?>"+data.products[i].path+"/"+data.products[i].alias);
                    $(item).find(".image").attr("src","administrator/"+data.products[i].thumbnail);
                    $(item).find(".name").html( data.products[i].name );
                    $(item).find(".abstract").html( cuppa.replace(data.products[i]["abstract"], "media/", "administrator/media/") );
                $(".shop_list .items").append(item);
            }
            var result_string = "<?php echo $language->numero_resultados ?>";
                result_string = result_string.replace("#n1#", $(".shop_list .item").length)
                result_string = result_string.replace("#n2#", data.total)
            $(".shop_list .result").html(result_string);
        }
    //++ onScroll
        shop_list.onScroll = function(){
            var scroll_status = cuppa.statusScrollPorcent("body");
            if(scroll_status.y >= 0.7 && !shop_list.blockade_load){
                shop_list.blockade_load = true;
                shop_list.page++;
                shop_list.updateList(true, false);
                $(".shop_list .loader").addClass("activated");
            }
        }
    //--
    //++ resize
        shop_list.resize = function(){
            $(".shop_list .filter").each(function(){
                $(this).find(".dropdown").css("min-width", $(this).find(".btn_filter").outerWidth() );
            });
        };
    //--
    //++ end
        shop_list.end = function(){
            cuppa.removeEventGroup("shop_list");
        }; cuppa.addRemoveListener(".shop_list", shop_list.end);
    //--
    //++ Modify color filter
        shop_list.modifyColorFilter = function(){
            var filter = $(".filter_color").get();
            $(filter).find("input").css("display", "none");
            $(filter).find(".color").bind("click", function(e){
                if(!$(this).parent().find("input").prop("checked")){
                    $(this).addClass("selected");
                    $(this).parent().find("input").prop("checked", true).trigger("change");
                }else{
                    $(this).removeClass("selected");
                    $(this).parent().find("input").prop("checked", false).trigger("change");
                }
            });
        }
    //--
    //++ init
        shop_list.init = function(){
            cuppa.addEventListener("resize", shop_list.resize, window, "shop_list"); shop_list.resize();
            $(".shop_list .filters input[type=checkbox]").bind("change",shop_list.updateFilters);
            $(".shop_list .order_by").bind("change", shop_list.updateOrder);
            $(window).scroll(shop_list.onScroll);
            shop_list.modifyColorFilter();
            $(".item_cuttent").html("<?php echo @$section->title ?>");
        }; cuppa.addEventListener("ready",  shop_list.init, document, "shop_list");
    //--
</script>
<div class="shop_list">
    <h1 class="title font_new_sans_light"><?php echo (@$section->title) ? @$section->title : @$language->resultados ?></h1>
    <div class="separator"></div>
    <!-- Filters -->
        <div class="filters">
            <?php for($i = 0; $i < count($filters); $i++){ ?>
                <div class="filter filter_<?php echo $cuppa->utils->getFriendlyUrl($filters[$i]->filter->label) ?>" >
                    <div class="btn_filter" >
                        <span><?php echo $cuppa->language->getValue($filters[$i]->filter->label, @$language); ?></span>
                    </div>
                    <div class="dropdown" >
                        <?php for($j = 0; $j < count($filters[$i]->data); $j++){ ?>
                            <div class="item_filter">
                                <input class="checkbox" type="checkbox" id="<?php echo $filters[$i]->data[$j]->id ?>" filter="<?php echo @$filters[$i]->filter->id ?>" />
                                <?php if($filters[$i]->data[$j]->image){ ?>
                                    <div class="color" >
                                        <div class="color_img" style="background-image: url(administrator/<?php echo $filters[$i]->data[$j]->image ?>);" ></div>
                                    </div>
                                    <span class="text_color"><?php echo $cuppa->language->getValue($filters[$i]->data[$j]->label, @$language); ?></span>
                                <?php }else{ ?>
                                    <span class="text"><?php echo $cuppa->language->getValue($filters[$i]->data[$j]->label, @$language); ?></span>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <div class="result"><span class="number1">
                <?php 
                    $result_string = $language->numero_resultados;
                    $result_string = str_replace(array("#n1#", "#n2#"), array(count($products->products), $products->total), $result_string);
                    echo $result_string; 
                ?>
            </div>
            <div class="filter filter_order">
                <div class="btn_filter" id="<?php echo @$filters[$i]->filter->id ?>">
                    <span><?php echo $language->ordenar_por ?></span>
                </div>
                <div class="dropdown">
                    <div class="item_filter">
                        <input class="order_by" order="size DESC" name="order_by" type="radio" />
                        <span class="text"><?php echo @$language->peso_desc ?></span>
                    </div>
                    <div class="item_filter">
                        <input class="order_by" order="size ASC" name="order_by" type="radio" />
                        <span class="text"><?php echo @$language->peso_asc ?></span>
                    </div>
                    <div class="item_filter">
                        <input class="order_by" order="weight DESC" name="order_by" type="radio" />
                        <span class="text"><?php echo @$language->tamano_desc ?></span>
                    </div>
                    <div class="item_filter">
                        <input class="order_by" order="weight ASC" order="weight ASC" name="order_by" type="radio" />
                        <span class="text"><?php echo @$language->tamano_asc ?></span>
                    </div>
                </div>
            </div>
        </div>
    <!-- -->
    <!-- Items -->
        <div class="items">
            <?php for($i = 0; $i < count($products->products); $i++){ ?>
                <div class="item">
                    <div class="item_content">
                        <a class="link item" href="<?php echo $path_just_shop_string2.$products->products[$i]->path."/".$products->products[$i]->alias ?>" >
                            <span style="display: none;"><?php echo $products->products[$i]->name ?></span>
                            <img class="image" src="administrator/<?php echo $products->products[$i]->thumbnail ?>" />
                            <div class="name font_new_sans_light"><?php echo $products->products[$i]->name ?></div>
                        </a>
                        <div class="abstract"><?php $cuppa->echoString($products->products[$i]->abstract) ?></div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <!--  -->
    <!-- load more -->
        <div class="loader"><?php echo @$language->cargando ?></div>
    <!-- -->
    
</div>
<div class="shop_list_assets" style="display: none;">
    <div class="item">
        <div class="item_content">
             <a class="link item" >
                <span class="name" style="display: none;"></span>
                <img class="image"  />
                <div class="name font_new_sans_light"></div>
             </a>
            <div class="abstract"></div>
        </div>
    </div>
</div>