<?php
    class Shop{
        // Get product, $product{id, alias}
            public static function getProduct($product = '', $condition = '', $include_features = false){
                $cuppa = Cuppa::getInstance();
                $token = $cuppa->security->token;
                if(!is_numeric($product)){
                    $product = $cuppa->dataBase->getRow("ex_shop_products", "alias = '".$product."'", $token, true);
                    $product = $product->id;
                }
                $sql = "SELECT p.*, i.thumbnail, i.src, i.type, f.filters_string
                        FROM ex_shop_products as p
                        JOIN ex_shop_product_media AS i ON i.product = p.id
                        LEFT JOIN (
                             SELECT f.product, GROUP_CONCAT(f.feature,':',f.values) as filters_string FROM ex_shop_product_filters as f 
                             GROUP BY product
                        ) as f ON f.product = p.id
                        WHERE p.enabled = 1 AND (p.language = '' OR p.language = '".$cuppa->language->current()."')
                        AND i.enabled = 1 AND i.principal = 1
                        AND p.id = ".$product;
                $product = $cuppa->dataBase->personalSql($sql, $token, true);
                $product = @$product[0];
                if($include_features){
                    //$product->filters = Shop::getProductFilters(@$product->id);
                }
                return @$product;
            }
        // Get product by category, $section{id, alias}
            public static function getProducts($section = '', $condition = '', $limit = '', $order = '', $include_filters = false){
                $cuppa = Cuppa::getInstance();
                $token = $cuppa->security->token;
                if(!is_numeric($section)){
                    $section = @$cuppa->dataBase->getRow("cu_menu_items", "alias = '".$section."'", $token, true);
                    $section = @$section->id;
                }
                $sql2 = $sql = "SELECT * FROM ( 
                            SELECT p.*, i.thumbnail, i.src, i.type, f.filters_string
                            FROM ex_shop_products as p
                            JOIN ex_shop_product_media AS i ON i.product = p.id
                            LEFT JOIN (
                                 SELECT f.product, GROUP_CONCAT(f.feature,':',f.values) as filters_string FROM ex_shop_product_filters as f 
                                 GROUP BY product
                            ) as f ON f.product = p.id
                            WHERE p.enabled = 1 AND (p.language = '' OR p.language = '".$cuppa->language->current()."')
                            AND i.enabled = 1 AND i.principal = 1
                        ) as data WHERE 1 ";
                //++ Conditions 
                    if($section) $sql2 = $sql .= " AND section LIKE '%\"".$section."\"%'";
                    if($condition) $sql2 = $sql .= " AND $condition ";
				    if($order) $sql2 = $sql .= " ORDER BY $order ";
				    if($limit) $sql .= " LIMIT $limit";                   
                //--
                //++ query
                    $products = $cuppa->dataBase->personalSql($sql, $token, true);
                //--
                //++ path
                    for($i = 0; $i < count($products); $i++){
                        $section = json_decode(@$products[$i]->section);
                        $section = $section[count($section)-1];
                        @$products[$i]->path = @$cuppa->dataBase->getRoadPath("cu_menu_items",$section,"id", "parent_id", "alias", $token, true);
                    }
                //--
                //++ include filters
                    if($include_filters){
                        for($i = 0 ; $i < count($products); $i++){
                            $products[$i]->filters = @Shop::getProductFilters($products[$i]->id);                           
                        }                        
                    }
                //--
                //++ Get total items
                    $sql2 = "SELECT COUNT(*) as total FROM (".$sql2.") as data";
                    $total_items = $cuppa->dataBase->personalSql($sql2, $token, true);
                    $total_items = @$total_items[0]->total;
                //--
                //++ result_data
                    $result = new stdClass();
                    $result->products = $products;
                    $result->total = ($total_items) ? $total_items : 0;
                //--
                return $result;
            }
        // Get description list
            public static function getDescriptionList($product){
                $cuppa = Cuppa::getInstance();
                $token = $cuppa->security->token;
                if(!is_numeric($product)){
                    $product = $cuppa->dataBase->getRow("ex_shop_products", "alias = '".$product."'", $token, true);
                    $product = $product->id;
                }
                $sql = "SELECT pd.*, d.name FROM ex_shop_product_description_list as pd
                        JOIN ex_shop_description_values as d ON d.id = pd.description
                        WHERE pd.product = ".$product." ORDER BY `order` ASC";
                return $cuppa->dataBase->personalSql($sql, $token, true);
            }
        // Get related product, $section{id, alias}
            public static function getRelatedProducts($product){
                $cuppa = Cuppa::getInstance();
                $token = $cuppa->security->token;
                if(!is_numeric($product)){
                    $product = $cuppa->dataBase->getRow("ex_shop_products", "alias = '".$product."'", $token, true);
                }else{
                    $product = $cuppa->dataBase->getRow("ex_shop_products", "id = '".$product."'", $token, true);
                }
                $related_products = json_decode($product->related_products);
                if(!$related_products) return null;
                $condition = "id IN (".join(",",$related_products).")";
                $related_products = Shop::getProducts("", $condition, "", "");
                return $related_products;
            }
        // Get the aviability product by id
            public static function getAvailability($id){
                $cuppa = Cuppa::getInstance();
                $token = $cuppa->security->token;
                // Get price
                    $condition = " enabled = 1 ";
                    $condition .= " AND product = ".$id;
                    $condition .= " AND country LIKE '%\"".$cuppa->getSessionVar("country")."\"%'";
                    $data = $cuppa->dataBase->getRow("ex_shop_product_prices", $condition, $token, true);
                    if(!@$data) return 0;
                // principal stock
                    $stock = (int) $data->stock;
                // specific prices
                    $specific_prices = $cuppa->utils->jsonDecode($data->specific_prices, false);
                    for($i = 0; $i < count($specific_prices); $i++){
                        if(@$specific_prices[$i]->stock && @$specific_prices[$i]->available){
                            $stock += (int) $specific_prices[$i]->stock;
                        }
                    }
                    return $stock;
            }
        /* Get product filters, $product{id, alias}
                Return: [[object(filter, data)], [object(filter, data)]]
        */
            public static function getProductFilters($product){
                $cuppa = Cuppa::getInstance();
                $token = $cuppa->security->token;
                if(!is_numeric($product)){
                    $product = $cuppa->dataBase->getRow("ex_shop_products", "alias = '".$product."'", $token, true);
                    $product = $product->id;
                }
                // Get features aviables
                    $sql = "SELECT pf.* FROM ex_shop_product_filters as pf
                            WHERE pf.product = '".$product."' ORDER BY feature ASC";
                    $data = $cuppa->dataBase->personalSql($sql, $token, true);
                    if(!is_array($data)) return null;
                // get object with unique_features
                    $featuresObject = new stdClass();
                    for($i = 0; $i < count($data); $i++){
                        $feature_id = @$data[$i]->feature;
                        @$featuresObject->{$feature_id} = new StdClass();
                        $featuresObject->{$feature_id}->filter = $cuppa->dataBase->getRow("ex_shop_features", "id = ".$feature_id, $token, true);
                        $featuresObject->{$feature_id}->data = new StdClass();
                    }
                    foreach($featuresObject as $key => $value) {
                        for($j = 0; $j < count($data); $j++){
                            if($data[$j]->feature == $key){
                                $values = json_decode($data[$j]->values);
                                for($k = 0; $k < count($values); $k++){
                                    $value_id = $values[$k];
                                    $featuresObject->{$key}->data->{$value_id} = $cuppa->dataBase->getRow("ex_shop_features_values", "id = ".$value_id, $token, true);
                                }
                            }
                        }
                    }
                // parsing data
                    $featuresObject = array_values((array) $featuresObject);
                    for($i = 0; $i < count($featuresObject); $i++){
                        $featuresObject[$i]->data = array_values((array) $featuresObject[$i]->data);
                    }
                    $featuresObject = array_values((array) $featuresObject);
                return @$featuresObject;
            }
        /* Get filters on section, $section{id, alias}
                Return: [[object(filter, data)], [object(filter, data)]]
        */
            public static function getSectionFilters($section){
                $cuppa = Cuppa::getInstance();
                $token = $cuppa->security->token;
                if(!is_numeric($section)){
                    $section = $cuppa->dataBase->getRow("cu_menu_items", "alias = '".$section."'", $token, true);
                    $section = @$section->id;
                }
                // Get features aviables
                    $sql = "SELECT pf.*, p.section FROM  ex_shop_product_filters as pf
                            JOIN ex_shop_products as p ON pf.product = p.id
                            WHERE p.section LIKE '%\"".$section."\"%' ORDER BY feature ASC";
                    $data = $cuppa->dataBase->personalSql($sql, $token, true);
                // get object with unique_features
                    $featuresObject = new stdClass();
                    for($i = 0; $i < count($data); $i++){
                        $feature_id = @$data[$i]->feature;
                        @$featuresObject->{$feature_id} = new StdClass();
                        $featuresObject->{$feature_id}->filter = $cuppa->dataBase->getRow("ex_shop_features", "id = ".$feature_id, $token, true);
                        $featuresObject->{$feature_id}->data = new StdClass();
                    }
                    foreach($featuresObject as $key => $value) {
                        for($j = 0; $j < count($data); $j++){
                            if($data[$j]->feature == $key){
                                $values = json_decode($data[$j]->values);
                                for($k = 0; $k < count($values); $k++){
                                    $value_id = $values[$k];
                                    $featuresObject->{$key}->data->{$value_id} = $cuppa->dataBase->getRow("ex_shop_features_values", "id = ".$value_id, $token, true);
                                }
                            }
                        }
                    }
                // parsing data
                    $featuresObject = array_values((array) $featuresObject);
                    $features_end = array();
                    foreach($featuresObject as $value) {
                        $value->data =  array_values((array) $value->data);
                        if(count($value->data) > 1){
                            array_push($features_end, $value);
                        }
                    }
                return @$features_end;
            }
        // Get Selected Products selected selected by user (Coockie) 
            public static function getSelectedProducts(){
                $cuppa = Cuppa::getInstance();
                return $cuppa->utils->jsonDecode( $cuppa->getSessionVar("cuppa_shop") );
            }
        // Get default price
            public static function getPriceMinimum($product_id){
                $default_price = (float) $products[$i]->price;
                $specific_prices = $cuppa->utils->jsonDecode($products[$i]->specific_prices, false);
                for($j = 0; $j < count($specific_prices); $j++){
                    if((float)@$specific_prices[$j]->price < $default_price && @$specific_prices[$j]->available && @$specific_prices[$j]->stock ){
                        $default_price = @$specific_prices[$j]->price;
                    }
                }
            }
        // Feature values
            public static function getFeatureValues($feature_id, $product_id){
                $cuppa = Cuppa::getInstance();
                $token = $cuppa->security->token;
                $feature = $cuppa->dataBase->getRow("ex_shop_features", "id = ".$feature_id,  $token, true);
                $feature_values = $cuppa->dataBase->getList("ex_shop_features_values", $token, "feature = ".$feature_id, "", "`order` ASC", true);
                $country = ($cuppa->getSessionVar("country")) ? $cuppa->getSessionVar("country") : "0";
                $language = $cuppa->getSessionVar("language");
                //++ Get product prices
                    $condition = "enabled = 1 AND product = '".$product_id."' AND ( language = '' OR language = '".$language."' ) AND ( country = '\"0\"' OR country LIKE '%\"".$country."\"%' )";
                    $product_prices = @$cuppa->dataBase->getRow("ex_shop_product_prices", $condition, $token, true);
                //--
                if($product_prices->specific_prices_available_only){
                    $specific_prices = json_decode($product_prices->specific_prices);
                    $values = array();
                    for($i = 0; $i < count($specific_prices); $i++){
                        $value = new stdClass();
                        $label = strtolower($feature->label);
                        array_push($values, $specific_prices[$i]->{$label});
                    }
                    $values = array_unique($values);
                    $values = implode(",", $values);
                    $condition = " id IN (".$values .")";
                    $feature_values = $cuppa->dataBase->getList("ex_shop_features_values", $token, $condition, "", "`order` ASC", true);
                    return $feature_values;
                }else{
                    return @$feature_values;
                }
            }
    }
?>