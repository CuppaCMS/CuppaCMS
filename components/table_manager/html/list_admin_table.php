<?php 
    include_once(realpath(__DIR__ . '/../../..')."/classes/Cuppa.php");
    $cuppa = Cuppa::getInstance(); $cuppa->user->valid("admin_login");
    $language = $cuppa->language->load();
    $configuration = $cuppa->configuration;
    $db = $cuppa->dataBase;
    $utils = $cuppa->utils;
    if(@$_POST["path"]){
        $getData = @$cuppa->utils->getUrlVars($cuppa->POST("path"));
        $_REQUEST = array_merge((array) $getData, $_REQUEST);
    }
    $view = @$path[3];
    // Field Types
        $table_data = $db->getList($configuration->table_prefix."tables", "table_name='".$view."'");
        $field_types = @json_decode(base64_decode($table_data[0]["params"]));
        if(!$field_types) $field_types = @json_decode($table_data[0]["params"]);
    // Filters
        @$filters_params = $db->getList($configuration->table_prefix."permissions_data", "`group` = 4 AND reference LIKE '".$view.",%'","","`order` ASC, id ASC", true);
        $filter_array = Array();
        if(is_array($filters_params)){
            for($i = 0; $i < count($filters_params); $i++){
                $filter = explode(",", $filters_params[$i]->reference); 
                array_push($filter_array, $filter[1]);
            }
        }
        $filters_params = $filter_array;
    // Paginator 
		$currentPage = @$_REQUEST["page"];
        $list_limit = (@$field_types->list_limit) ? @$field_types->list_limit : $configuration->list_limit;
		$limit = @($currentPage*$list_limit) . "," . ($list_limit);
    // Get Columns
        $columns = $db->getColums($view);
    // Get Filters
        $conditions = " 1 ";
        $search_word = " 1 ";
        if($cuppa->POST("custom_condition")){
            $conditions.= " AND ".$cuppa->POST("custom_condition")." ";
        }
        for($i = 0; $i < count($filters_params); $i++){
            $permission_filter_value = $cuppa->permissions->getValue("4", $view.",".$filters_params[$i],"10");
            if($permission_filter_value != "disable"){
                //++ Get default value
                    $permission_filter_default = $cuppa->permissions->getDefault("4", $view.",".$filters_params[$i],"10");
                    $permission_filter_default = $cuppa->utils->evalString($permission_filter_default);
                    if($permission_filter_default === "session" || $permission_filter_default === "user") $permission_filter_default = $cuppa->user->value("id");
                    if(!isset($_REQUEST["filter_".$filters_params[$i]]) && $permission_filter_default !== "") @$_REQUEST["filter_".$filters_params[$i]] = @$permission_filter_default;
                //--
                if( (string) @$_REQUEST["filter_".$filters_params[$i]] != "" && $field_types->{$filters_params[$i]}->type != "Date"){
                    if($field_types->{$filters_params[$i]}->type == "Select_List"){
                        $conditions .= " AND " . $view.".".$filters_params[$i] . " LIKE '%" . $_REQUEST["filter_".$filters_params[$i]] . "%' ";
                    }else{
                        $conditions .= " AND " . $view.".".$filters_params[$i] . " = '" . $_REQUEST["filter_".$filters_params[$i]] . "' ";  
                    } 
                }else if(@$field_types->{$filters_params[$i]}->type == "Date"){
                    $permission_filter_default = json_decode($permission_filter_default);
                    //echo $myDate = date('Y-m-d');
                    if(!@$_REQUEST["filter_".$filters_params[$i]."_from"] && @$permission_filter_default->from && !@$_REQUEST["filter_".$filters_params[$i]."_to"] && @$permission_filter_default->to){
                        $_REQUEST["filter_".$filters_params[$i]."_from"] = @$permission_filter_default->from;
                        $_REQUEST["filter_".$filters_params[$i]."_to"] = @$permission_filter_default->to;
                    }
                    if(@$_REQUEST["filter_".$filters_params[$i]."_from"] != "" && @$_REQUEST["filter_".$filters_params[$i]."_to"] != "" ){
                        $conditions .= " AND ( ";
                        $conditions .= $view.".".$filters_params[$i]." >= '". $_REQUEST["filter_".$filters_params[$i]."_from"] ."'";
                        $conditions .= " AND " . $view.".".$filters_params[$i]." <= '". $_REQUEST["filter_".$filters_params[$i]."_to"] ."'";
                        $conditions .= " ) ";
                    }  
                }
            }  
        }
        if(@$_REQUEST["search_word"]){
            for($i = 0; $i < count($columns); $i++){
                if($i == 0) $search_word .= " AND ( `" . $columns[$i] . "` LIKE '%" . trim($_REQUEST["search_word"]) . "%' ";
                else $search_word .= " OR `" . $columns[$i] . "` LIKE '%" . trim($_REQUEST["search_word"]) . "%' ";
                if($i == count($columns) -1) $search_word.= " ) ";
            }
        }
    // Get Order By
        $order_by = "";
        if(@$_REQUEST["order_by"]){
            $order_by .= " ORDER BY `" . @$_REQUEST["order_by"] ."`";
            $order_by .= " " . @$_REQUEST["order_orientation"];
        }else if(@$field_types->order_by && @$field_types->order_by_order){
            $order_by .= " ORDER BY `" . @$field_types->order_by ."`";
            $order_by .= " " . @$field_types->order_by_order;
             @$_REQUEST["order_by"] = @$field_types->order_by;
             @$_REQUEST["order_orientation"] = @$field_types->order_by_order;
        }else{
            $order_by = " ORDER BY id ASC";
        }
    // Get List 
        $array_keys = array_keys((array) $field_types); array_pop($array_keys);
        $joins_data = array(); $consults = array();
        for($i = 0; $i <count($array_keys); $i++){
            @$config = json_decode(base64_decode($field_types->{$array_keys[$i]}->config));
        }
    // Permision verify - Only List Own Info
        if($cuppa->permissions->getValue(2,$view, 2) == "only own info"){
            $data = new stdClass();
            $data->aditional_fields = $configuration->table_prefix."tables_log.date_updating, " . $configuration->table_prefix."tables_log.user_id_updating ";
            $data->join_typle = "JOIN";
            $data->join = $configuration->table_prefix."tables_log";
            $data->where = $view.".".$field_types->primary_key." = ".$configuration->table_prefix."tables_log.reference_id AND ".$configuration->table_prefix."tables_log.user_id_creator = '".$cuppa->user->getVar("id")."'";                        
            $data->where .= " AND ".$configuration->table_prefix."tables_log.table_name = '".$view."'";
            @$joins_data[@$array_keys[$i]] = $data;
        }else{
            $data = new stdClass();
            $data->aditional_fields = $configuration->table_prefix."tables_log.date_updating, " . $configuration->table_prefix."tables_log.user_id_updating ";
            $data->join_typle = "LEFT JOIN";
            $data->join = $configuration->table_prefix."tables_log";
            $data->where = $view.".".$field_types->primary_key." = ".$configuration->table_prefix."tables_log.reference_id";
            $data->where .= " AND ".$configuration->table_prefix."tables_log.table_name = '".$view."'";
            @$joins_data[@$array_keys[$i]] = $data;
        }
    // Build Sql
        $sql = "SELECT * FROM ( SELECT ";
        $sql_real_info = "SELECT * FROM ( SELECT ".$view.".* ";
        // add Fields
            for($i = 0; $i < count($columns); $i++){
                if($i > 0) $sql .= ", ";
                if(@$joins_data[$array_keys[$i]]) $sql .= $joins_data[$array_keys[$i]]->query;
                else $sql .= $view.".".$array_keys[$i];
            }
        // add more fields
            foreach ($joins_data as $data){
                if(@$data->aditional_fields){
                    $sql .= ", ".@$data->aditional_fields;
                    $sql_real_info .= ", ".@$data->aditional_fields;
                }
            }
        // add FROM
            $sql .= " FROM " . $view;
            $sql_real_info .= " FROM " . $view;
        // add Joins
            foreach ($joins_data as $data){
                $sql .= " ".$data->join_typle." ". $data->join;
                $sql .= " ON " . $data->where;
                
                $sql_real_info .= " ".$data->join_typle." ". $data->join;
                $sql_real_info .= " ON " . $data->where;
            }
        // add Conditions
            $sql .= " WHERE " . $conditions; 
            $sql_real_info .= " WHERE " . $conditions; 
        $sql .= " ) AS  data ";
        $sql_real_info .= " ) AS  data ";
        // add Search Word
            $sql .= " WHERE ". $search_word;
            $sql_real_info .= " WHERE ". $search_word;
        // add Order
            $sql .= $order_by;
            $sql_real_info .= $order_by;
        // Create Sql_Download
            $sql_download = $sql;
        // Get Total rows
            $sql_total = "SELECT count(*) as total FROM ( ".$sql.") as data";
            $totalRows = $db->sql($sql_total, true);
            $totalRows = (@$totalRows[0]->total) ?  @$totalRows[0]->total : 0;
        // Add Limit
            if($field_types->show_list_like_tree) $limit = "";
   	        if($limit){
   	            $sql .= " LIMIT ".$limit;
                $sql_real_info .= " LIMIT ".$limit;
            }
       $info = $db->sql($sql);
       $real_info = $db->sql($sql_real_info);
       if(!is_array($info)) $info = null;
    // Get Info Colums
       $infoColumns = $db->getColums($view);
    // Create sql for users download
        if($cuppa->permissions->getValue(2,$view, "8")){
            $_SESSION["cuSession"]->sql_download = base64_encode($sql_download);
            $_SESSION["cuSession"]->table_params = base64_encode(json_encode($field_types));
        }
    // Incude files
        $include_files = json_decode(base64_decode(@$field_types->include_file));
    // Defined personal language file
        if(@$field_types->language_file_reference){
            $personal_language_reference = $cuppa->language->load(@$field_types->language_file_reference);                
            $language = (object) array_merge((array) @$language, (array) @$personal_language_reference);
        }
    // Include Field Items
        include("fields/Select.php");
        include("fields/Text.php");
    // Permissions verify
        if(!$cuppa->permissions->getValue(2,$view, 2)){
			echo '<meta http-equiv="Refresh" content="0;url=./">'; exit();
        }
    // Option Panel
        $option_panel = @json_decode(base64_decode($field_types->option_panel));
        if(!$option_panel) $option_panel = @$field_types->option_panel;
    // Create Info Array
        $info_array = array();
        $info_array["data"] = array();
        for($i = 0; $i < @count($info); $i++){
            $fields = array();
            // Time field calculator
                $fields["last_updating_time"] = strtotime(@date("Y-m-d H:i:s")) - strtotime(@date($info[$i]["date_updating"]));
                if($fields["last_updating_time"] < 30){
                    $fields["user_id_updating"] = $info[$i]["user_id_updating"];
                    $fields["user_updating"] = @$db->getRow($configuration->table_prefix."users", "id = '".$fields["user_id_updating"]."'", true)->name;
                }                        
            // Checkbox field
                $field = '<input id="id" class="id checkbox" name="id[]" type="checkbox" value="'.$info[$i][$field_types->primary_key].'" />';
                array_push($fields,$field);
            // Other Fields
                for($j = 0; $j < count($infoColumns); $j++){
                    if(strtolower($infoColumns[$j]) == $field_types->primary_key){
                        $show_list = @$field_types->{$infoColumns[$j]}->showList;
                        //++ validate permission
                            $permission_value = @$cuppa->permissions->getValue("5", $view.",".$infoColumns[$j], "11", true);
                            if($permission_value === 0 || $permission_value === 1 ){
                                $show_list = $permission_value;
                            }
                        //--
                        if($show_list){ 
                            if( $cuppa->permissions->getValue(2,$view, "4") || $cuppa->permissions->getValue(2,$view, "2")  ) {
                                $field = '<a onclick="list_admin_table.submit(\'edit\',\''.$info[$i][$field_types->primary_key].'\' )">'.$info[$i][$infoColumns[$j]].'</a>';
                            }else{
                                $field = $info[$i][$infoColumns[$j]];
                            }
                            $fields[$infoColumns[$j]] = @$field;
                            $fields[$infoColumns[$j]."_real_data"] = @$real_info[$i][$infoColumns[$j]];
                        }
                    }else{
                        // Show in list
                            $show_list = @$field_types->{$infoColumns[$j]}->showList;
                        //++ validate permission
                            $permission_value = @$cuppa->permissions->getValue("5", $view.",".$infoColumns[$j], "11", true);
                            if($permission_value === 0 || $permission_value === 1 ){
                                $show_list = $permission_value;
                            }
                        //--
                            if(@$show_list){
                                $field = "";
                                    @$config = @json_decode(base64_decode($field_types->{$infoColumns[$j]}->config));
                                        if(!$config) $config = @$field_types->{$infoColumns[$j]}->config;

                                    if($field_types->{$infoColumns[$j]}->type == "Select_List"){
                                        $field_info = json_decode(@$info[$i][$infoColumns[$j]]);
                                        $string_total_result = "";
                                        if(is_array(@$config->data)){
                                            for($k = 0; $k < count(@$field_info); $k++){
                                                $result_search = $cuppa->utils->searchInArray($config->data, "0", $field_info[$k]);
                                                if(isset($result_search[0][1])){
                                                    $string_total_result .= (@$config->extraParams->no_translate) ? $result_search[0][1].", " : $cuppa->language->getValue( $result_search[0][1], @$language) . ", ";
                                                }else{
                                                    if($config->extraParams->custom_data == $field_info[$k][0]){
                                                        $string_total_result .= (@$config->extraParams->no_translate) ? @$config->extraParams->custom_label.", " : $cuppa->language->getValue( @$config->extraParams->custom_label, @$language ) . ", ";
                                                    }
                                                }                                                   
                                            }
                                        }else{
                                            $query = $db->getList($config->data->table_name,'', '', '', true);
                                            for($k = 0; $k < count(@$field_info); $k++){
                                                $result_search = $cuppa->utils->searchInArray($query, $config->data->data_column, $field_info[$k]);
                                                $labels_tmp = explode(",", $config->data->label_column);
                                                forEach($labels_tmp as $label_tmp){
                                                    if(isset($result_search[0][$label_tmp])){
                                                        $string_total_result .= (@$config->extraParams->no_translate) ? @$result_search[0][$label_tmp] : $cuppa->language->getValue(@$result_search[0][$label_tmp], @$language);
                                                    }else{
                                                        if($config->extraParams->custom_data == $field_info[$k]){
                                                            $string_total_result .= (@$config->extraParams->no_translate) ? @$config->extraParams->custom_label.", " : $cuppa->language->getValue(@$config->extraParams->custom_label, @$language) . ", ";
                                                        }else{
                                                            $string_total_result .= $label_tmp;
                                                        }
                                                    }
                                                }
                                                $string_total_result .= ", ";
                                            }
                                        }
                                        $field .= $cuppa->utils->cutText(",",$string_total_result, 9999, "", true);
                                    }else if($field_types->{$infoColumns[$j]}->type == "Select" || $field_types->{$infoColumns[$j]}->type == "Radio"){
                                        // link indicator
                                            if(@$infoColumns[$j] == @$field_types->link_indicator) $field .= '<a onclick="list_admin_table.submit(\'edit\',\''.$info[$i][$field_types->primary_key].'\' )">';
                                        
                                        if(is_array(@$config->data)){
                                            $value = $utils->searchInArray($config->data, "0", $info[$i][$infoColumns[$j]]);
                                            if(@$config->extraParams->add_custom_item &&  $info[$i][$infoColumns[$j]] == @$config->extraParams->custom_data ){
                                                $field .= (@$config->extraParams->no_translate) ? @$config->extraParams->custom_label : $cuppa->language->getValue(@$config->extraParams->custom_label, @$language);
                                            }else if(count($value)){
                                                $field .= (@$config->extraParams->no_translate) ? @$value[0][1] : $cuppa->language->getValue(@$value[0][1], @$language);
                                            }
                                        }else{
                                            if(@$config->extraParams->add_custom_item &&  $info[$i][$infoColumns[$j]] == null){
                                                $field .= (@$config->extraParams->no_translate) ? @$config->extraParams->custom_label : $cuppa->language->getValue(@$config->extraParams->custom_label, @$language) ;  
                                            }else{
                                                $query = $db->getRow($config->data->table_name, $config->data->data_column.' = '.$info[$i][$infoColumns[$j]]);
                                                $labels_tmp = explode(",", $config->data->label_column);
                                                forEach($labels_tmp as $label_tmp){
                                                    $value_tmp = trim($label_tmp);
                                                    if(@$config->extraParams->no_translate){
                                                        if(@$query[trim($label_tmp)]) $field .= $query[trim($label_tmp)];
                                                         else $field .= $label_tmp;
                                                    }else{
                                                        $value_tmp = $cuppa->language->getValue(@$query[$label_tmp], @$language);
                                                        if($value_tmp) $field .= $value_tmp;
                                                        else $field .= $label_tmp;   
                                                    }
                                                }
                                            }
                                        }
                                        // link indicator
                                            if(@$infoColumns[$j] == @$field_types->link_indicator) $field .= '</a>';
                                    }else if($field_types->{$infoColumns[$j]}->type == "Language_Selector"){
                                        if(!@$info[$i][$infoColumns[$j]]){
                                            $field.= $language->alls;
                                        }else{
                                            $field .= @$info[$i][$infoColumns[$j]];
                                        }
                                    }else if($field_types->{$infoColumns[$j]}->type == "File" ){
                                        if(@$config->show_image){
                                            $image_file = @explode(".",strtolower($info[$i][$infoColumns[$j]]));
                                            $image_file = $image_file[count($image_file)-1];
                                            $image_file = ($image_file == "jpg" || $image_file == "jpeg" || $image_file == "bmp" || $image_file == "png" || $image_file == "gif") ? 1 : 0;
                                            $file_exist = file_exists( $cuppa->getDocumentPath().str_replace("administrator/","",@$info[$i][$infoColumns[$j]]));
                                            if(@$config->download_enabled) $field .= "<a href='".str_replace("administrator/", "",@$info[$i][$infoColumns[$j]])."' target='_blak'>";
                                            if($file_exist && $image_file){
                                                $field .= "<div style='text-align: center'><img src='".str_replace("administrator/","",@$info[$i][$infoColumns[$j]])."' ";
                                                if(@$config->dimention_priority == "width") $field .= " width='".@$config->dimention_image."'";
                                                else $field .= " height='".@$config->dimention_image."'"; 
                                                $field .= "/></div>";
                                            }else{
                                                $field .= "<div style='text-align: center'><img align='center' src='templates/default/images/template/file_128.png'";
                                                if(@$config->dimention_priority == "width") $field .= " width='".@$config->dimention_image."'";
                                                else $field .= " height='".@$config->dimention_image."'";
                                                $image_file = @explode("/",strtolower($info[$i][$infoColumns[$j]]));
                                                $image_file = $image_file[count($image_file)-1]; 
                                                $field .= "/><div style='text-align: center; color:#666; font-size: 11px;'>$image_file</div></div>";
                                            }
                                            if(@$config->download_enabled) $field .= "</a>";
                                        }else{
                                            if(@$config->download_enabled) $field .= "<a href='".str_replace("administrator/","",@$info[$i][$infoColumns[$j]])."' target='_blak'>".str_replace("administrator/","",@$info[$i][$infoColumns[$j]])."</a>";
                                            else $field .= @$info[$i][$infoColumns[$j]];
                                        }
                                    }else if($field_types->{$infoColumns[$j]}->type == "Check"){
                                        if(!@$info[$i][$infoColumns[$j]])
                                            $field.= @$language->false;
                                        else 
                                            $field .= @$language->true;
                                    }else if($field_types->{$infoColumns[$j]}->type == "Text"){
                                        // link indicator init
                                            if(@$infoColumns[$j] == @$field_types->link_indicator && ($cuppa->permissions->getValue(2,$view, "4") || $cuppa->permissions->getValue(2,$view, "2")) ) $field .= '<a onclick="list_admin_table.submit(\'edit\',\''.$info[$i][$field_types->primary_key].'\' )">';
                                        // text
                                            if(@$config->type == "number" && @$config->number_fortmat == "money"){
                                                $field .= @number_format(@$info[$i][$infoColumns[$j]], 2);
                                            }else{
                                                $field .= @$info[$i][$infoColumns[$j]];
                                            }
                                        // link indicator end
                                            if(@$infoColumns[$j] == @$field_types->link_indicator && ($cuppa->permissions->getValue(2,$view, "4") || $cuppa->permissions->getValue(2,$view, "2")) ) $field .= '</a>';
                                    
                                    }else{
                                        // link indicator init
                                            if(@$infoColumns[$j] == @$field_types->link_indicator && ($cuppa->permissions->getValue(2,$view, "4") || $cuppa->permissions->getValue(2,$view, "2")) ) $field .= '<a onclick="list_admin_table.submit(\'edit\',\''.$info[$i][$field_types->primary_key].'\' )">';
                                        // text
                                            $field .= @$info[$i][$infoColumns[$j]];
                                        // link indicator end
                                            if(@$infoColumns[$j] == @$field_types->link_indicator && ($cuppa->permissions->getValue(2,$view, "4") || $cuppa->permissions->getValue(2,$view, "2")) ) $field .= '</a>';
                                    }
                                $fields[$infoColumns[$j]] = @$field;
                                $fields[$infoColumns[$j]."_real_data"] = @$real_info[$i][$infoColumns[$j]];
                            }
                        // End If Show in list
                    }
                }
            //-- End Other Fields
            //++ Option Panel
                if($option_panel){ 
                    $field = "";                    
                    for($l = 0; $l < count($option_panel); $l++){
                        $tooltip = $cuppa->language->getValue(@$option_panel[$l]->tooltip, $language);
                        $field .= "<a style='margin-left:2px; margin-right:2px' title='".$tooltip."' class='button link tooltip button_".$l."' href='".$option_panel[$l]->url;
                        parse_str($option_panel[$l]->var_name, $array_var_name);
                        if($array_var_name){
                            foreach ($array_var_name as $key=>$value){ 
                                if(@$fields[$value."_real_data"])
                                    $field.= "&".trim($key)."=".@$fields[$value."_real_data"];
                                else 
                                    $field.= "&".trim($key)."=".$value;
                            }
                        }
                        $field .= "'><img src='".$option_panel[$l]->image_src."' /></a>";
                    }
                    $fields["options"] = $field;
                }
            //--            
            array_push($info_array["data"],$fields);
        }
        //++ Tree format
            if(@$field_types->show_list_like_tree){
                $info_array["data"] = $cuppa->utils->createTree($info_array["data"], $field_types->show_list_like_tree_column."_real_data", $field_types->show_list_like_tree_validate."_real_data");
                for($i = 0; $i < count($info_array["data"]); $i++){
                    foreach($info_array["data"][$i] as $key => $value){
                        if($key === @$field_types->show_list_like_tree_indicator){
                            $info_array["data"][$i][$key] = "<span class='tree_indicator'>".@$info_array["data"][$i]["deep_string"]."</span>".'<a onclick="list_admin_table.submit(\'edit\',\''.@$info_array["data"][$i][$field_types->primary_key."_real_data"].'\')">'.@$info_array["data"][$i][$key]."</a>";
                        }                        
                    }
                }
		    }
        //--
?>
<style>
    .list_admin_table{ position: relative; }
    .list_admin_table h1{ float: left; }
    .list_admin_table .tools{ position: relative; float: right; top: 4px; }
    .list_admin_table .filter_content{ padding: 0px 0px 0px 5px; }
    .list_admin_table .filter_content .input{ max-width: 90px; margin: 0px 2px; }
    .list_admin_table .filter_content .select_cuppa{ max-width: 100px;}
    .list_admin_table .pag_top .paginator{ margin: 1px 0 5px; }
    .list_admin_table .pag_bottom .paginator{ margin: 5px 0 1px; }  
    .list_admin_table .paginator .cuppa_select_page{ width: 108px !important; }
/* Responsive */
    .r780  .list_admin_table .filters .right{ display: none !important; }
    .r650 .list_admin_table .h1_title{ display: none !important; }
    .r650 .list_admin_table .tools{ margin-top: 7px !important; top: 0px !important;  }
    .r650 .list_admin_table .div_title{ margin-top: 0px !important; }
    
</style>
<script>
    list_admin_table = {}
    //++ end
        list_admin_table.end = function(){
            cuppa.removeEventGroup("list_admin_table");
        }; cuppa.addRemoveListener(".list_admin_table", list_admin_table.end);
    //--
    //++ update content
        list_admin_table.update = function(){
            var data = cuppa.urlToObject($("form").serialize());      
            cuppa.managerURL.setParams({path:cuppa.managerURL.path}, true, data);
            return false;
        }
    //--
    //++ update content
        list_admin_table.submit = function(task, id){
            var data = {};
            if(task == "new"){
                data.id = 0;
                stage.loadRightContent("components/table_manager/html/edit_admin_table.php", data);
            }else if(task == "edit"){
                if(!id) id = $(".list_admin_table .table_info td input[type=checkbox]:checked").val();
                if(!id){
                    cuppa.blockade({duration:0.2, opacity:0.2, autoDeleteContent:".cuppa_alert"});
                    cuppa.instance({url:"js/cuppa/cuppa_html/alert.html", data:{title:"<?php echo @$language->message ?>", message:"<?php echo $language->alert_delete_edit ?>", accept:"<?php echo $language->accept ?>"}, add:"body"})
                    return;
                }
                data.id = id;
                stage.loadRightContent("components/table_manager/html/edit_admin_table.php", data);
            }else if(task == "delete"){
                var ids = new Array();
                $(".list_admin_table .table_info td input[type=checkbox]:checked").each(function(e){ ids.push($(this).val()); });
                if(!ids.length){
                    cuppa.blockade({duration:0.2, opacity:0.2, autoDeleteContent:".cuppa_alert"});
                    cuppa.instance({url:"js/cuppa/cuppa_html/alert.html", data:{title:"<?php echo @$language->message ?>", message:"<?php echo $language->alert_delete_edit ?>", accept:"<?php echo $language->accept ?>"}, add:"body"})
                    return;
                }
                cuppa.blockade({duration:0.2, opacity:0.2, autoDeleteContent:".cuppa_alert"});
                cuppa.instance({url:"js/cuppa/cuppa_html/alert.html", data:{title:"<?php echo @$language->message ?>", message:"<?php echo $language->tooltip_delete_item ?>", show_cancel:true, cancel:"<?php echo $language->cancel ?>", accept:"<?php echo $language->accept ?>"}, add:"body" })
                function onConfirm(e, value){
                    $(cuppa).unbind("share", onConfirm);
                    if(value){
                        data = cuppa.serialize(".form_list");
                        data.page = "0";
                        data.task = task;
                        data.view = "<?php echo $view ?>";
                        data.ids = cuppa.jsonEncode(ids);
                        cuppa.managerURL.setParams({path:cuppa.managerURL.path}, true, data);
                    }
                }; $(cuppa).bind("share", onConfirm);
            }else if(task == "duplicate"){
                if(!id) id = $(".list_admin_table .table_info td input[type=checkbox]:checked").val();
                if(!id){
                    cuppa.blockade({duration:0.2, opacity:0.2, autoDeleteContent:".cuppa_alert"});
                    cuppa.instance({url:"js/cuppa/cuppa_html/alert.html", data:{title:"<?php echo @$language->message ?>", message:"<?php echo $language->alert_delete_edit ?>", accept:"<?php echo $language->accept ?>"}, add:"body" })
                    return;
                }
                data.id = id;
                data.page = "0";
                data.task = task;
                data.view = "<?php echo $view ?>";
                cuppa.managerURL.setParams({path:cuppa.managerURL.path}, true, data);
            }else if(task == "reload"){
                data = cuppa.serialize(".form_list");
                cuppa.managerURL.setParams({path:cuppa.managerURL.path}, true, data);
            }
        }
    //--
    //++ Valid correct date rang
        list_admin_table.validDateRange = function(name){
            if( jQuery("#"+name+"_from").val() && jQuery("#"+name+"_to").val() ){
                jQuery("#page").val(0); jQuery("#form").submit();
            }else if( !jQuery("#"+name+"_from").val() && !jQuery("#"+name+"_to").val() ){
                jQuery("#page").val(0); jQuery("#form").submit();
            }
        }
    //--
    //++ init
        list_admin_table.init = function(){
            cuppa.selectStyle(".list_admin_table select"); 
                $(".list_admin_table .filters select[disabled=disabled]").parent().css("opacity", 0.6);
            cuppa.tooltip();
            if("<?php echo $cuppa->REQUEST("task") ?>" == "new"){
                stage.loadRightContent("components/table_manager/html/edit_admin_table.php", {redirect:"component/table_manager/view/<?php echo @$view ?>"});
            }
            cuppa.managerURL.updateLinks(".list_admin_table .link");
        }; cuppa.addEventListener("ready",  list_admin_table.init, document, "list_admin_table");
    //--
</script>
<form class="form_list" id="form" name="form" method="post" onsubmit="return list_admin_table.update()" >
    <div class="list_admin_table">
        <!-- Include file (top) -->
            <?php
                for($i = 0; $i < @count($include_files); $i++){
                    if( $include_files[$i]->add_to == "list" && $include_files[$i]->position == "top" ){
                        $include_file = $cuppa->getDocumentPath().$include_files[$i]->path;
                        if(strpos($include_file, "../") !== false){
                            $f_backs = substr_count($include_file,  "../");
                            $back_string = str_repeat("/..", $f_backs);
                            $file = str_replace("../", "", $include_files[$i]->path);
                            $include_file = realpath($cuppa->getDocumentPath().$back_string)."/".$file;
                        }
                        @include($include_file);
                    }
                } 
            ?>
        <!-- -->
        <!-- Filters -->
           	<div class="filters" style="overflow: hidden;">
                <div class="left" style="float: left; margin-bottom: 5px;">
                    <?php if(!$field_types->show_list_like_tree){ ?>
                        <input style="float: left; max-width: 150px;" class="input" id="search_word" name="search_word" value="<?php echo @$_REQUEST["search_word"] ?>" placeholder="<?php echo @$language->write_some_word ?>" />
                        <input style="float: left;" type="submit" class="button_left" value="<?php echo @$language->search ?>" />
                    <?php } ?>
                </div>
                <div class="right" style="float: right;">
                    <?php for($i = 0; $i < count($filters_params); $i++){ ?>
                        <?php
                            $permission_filter_value = $cuppa->permissions->getValue("4", $view.",".$filters_params[$i],"10");
                            if($permission_filter_value != "disable" && @$field_types->{$filters_params[$i]}){
                                $label = $cuppa->language->getValue($field_types->{$filters_params[$i]}->label, $language);
                        ?>
                            <div class="filter_content" title="<?php echo @$label ?>" style="margin-bottom: 5px; float: left; <?php echo ($permission_filter_value == "hidden") ? "display: none;" : "" ; ?>">
                                <?php
                                    $type = @$field_types->{$filters_params[$i]}->type;
                                    if(!@$field_types->language_file_reference) $field_types->language_file_reference = "administrator";
                                    if($type == "Date"){
                                        echo '<span >'.$label.': '.'</span>';
                                        if($permission_filter_value == "blocked" ){
                                            echo '<input disabled="disabled" value="'.@$_REQUEST["filter_".$filters_params[$i]."_from"].'" onchange="list_admin_table.validDateRange(\'filter_'.$filters_params[$i].'\')" value="" name="filter_'.$filters_params[$i].'_from" id="filter_'.$filters_params[$i].'_from" class="input filter_input filter_date" placeholder="'.@$language->from.'" >';
                                            echo '<input disabled="disabled" value="'.@$_REQUEST["filter_".$filters_params[$i]."_to"].'" onchange="list_admin_table.validDateRange(\'filter_'.$filters_params[$i].'\')" value="" name="filter_'.$filters_params[$i].'_to" id="filter_'.$filters_params[$i].'_to" class="input filter_input filter_date" placeholder="'.@$language->to.'" >';
                                        }else{
                                            echo '<input value="'.@$_REQUEST["filter_".$filters_params[$i]."_from"].'" onchange="list_admin_table.validDateRange(\'filter_'.$filters_params[$i].'\')" value="" name="filter_'.$filters_params[$i].'_from" id="filter_'.$filters_params[$i].'_from" class="input filter_input filter_date" placeholder="'.@$language->from.'" >';
                                            echo '<input value="'.@$_REQUEST["filter_".$filters_params[$i]."_to"].'" onchange="list_admin_table.validDateRange(\'filter_'.$filters_params[$i].'\')" value="" name="filter_'.$filters_params[$i].'_to" id="filter_'.$filters_params[$i].'_to" class="input filter_input filter_date" placeholder="'.@$language->to.'" >';
                                        }
                                        echo "<script>cuppa.rangeDate('#filter_".$filters_params[$i]."_from', '#filter_".$filters_params[$i]."_to') </script>";
                                    }else if($type == "Check"){
                                        $selectItem = new Select();
                                        $config = '{"data":[["1","true"],["0","false"]]}';
                                        $extraParams = "onChange='jQuery(\"#page\").val(0); jQuery(\"#form\").submit()'";
                                        if($permission_filter_value == "blocked" ){
                                            $extraParams .= " disabled='disabled' ";
                                        }
                                        echo $selectItem->GetItem("filter_".$filters_params[$i], @$_REQUEST["filter_".$filters_params[$i]], $config, false, "",$extraParams, array("", ucfirst($label) ), true, $field_types->language_file_reference);
                                    }else{
                                        $selectItem = new Select();
                                        if($type == "Language_Selector"){
                                            $languages = Cuppa::getInstance()->language->getLanguageFiles();
                                            $config = array(); 
                                            foreach ($languages as $value){
                                                array_push($config,array($value, $value));
                                            }
                                            $config = json_encode($config);
                                        }else{
                                            $config = @base64_decode($field_types->{$filters_params[$i]}->config);
                                            if(!$config) $config = json_encode($field_types->{$filters_params[$i]}->config);
                                        }
                                        $config = json_decode($config);
                                            @$config->extraParams->width = "";
                                            if(@$config->data->dinamic_update_field){
                                                if(@$config->data->where_column)@$config->data->where_column .= " AND ".$config->data->dinamic_update_column." = " .${"tmp_filter_".$config->data->dinamic_update_field};
                                                else @$config->data->where_column = $config->data->dinamic_update_column." = " .${"tmp_filter_".$config->data->dinamic_update_field};
                                                @$config->data->dinamic_update_field = "";
                                                @$config->data->dinamic_update_column = "";
                                            }                                                                           
                                        $config = json_encode($config);
                                        $extraParams = "onChange='jQuery(\"#page\").val(0); jQuery(\"#form\").submit()'";
                                        if($permission_filter_value == "blocked" ){
                                            $extraParams .= " disabled='disabled' ";
                                        }
                                        $value = @$_REQUEST["filter_".$filters_params[$i]];
                                        if($value){
                                            ${"tmp_filter_".$filters_params[$i]} = $value;
                                        }
                                        if($value === "session" || $value === "user") $value = $cuppa->user->value("id");
                                        echo $selectItem->GetItem("filter_".$filters_params[$i], $value, $config, false, "",$extraParams, array("", ucfirst($label)), true, $field_types->language_file_reference);
                                    }
                                ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        <!-- -->    
        <!-- Title -->            
             <div class="div_title" style="overflow: hidden; margin-top: 15px; margin-bottom: 4px;">
                <h1>
                    <span class="h1_title">
                        <?php 
                            if(@$field_types->custom_table_name){ echo $cuppa->language->getValue(@$field_types->custom_table_name, @$language);
                            }else{ echo @$view; }
                         ?>
                     </span>
                    <span class="title_info">
                        <?php echo @$language->total_rows ?>: <?php echo $totalRows ?> 
                        <span style="text-transform: lowercase;"><?php echo @$language->records ?></span>
                    </span>
                </h1>
                <div class="tools">
                    <a style="margin-right: 5px; background: none; border: 0px; border-radius: 50px;" class="tool_button tooltip" title="<?php echo @$language->reload_list ?>" onclick="list_admin_table.submit('reload')" ><span style="color: #DDD;">f</span></a>
                    <?php if($cuppa->permissions->getValue(2,$view, "3")) {?>
            	        <a class="add tool_button tooltip tool_left" title="<?php echo @$language->tooltip_add_item ?>" onclick="list_admin_table.submit('new')"><span>a</span></a>
            		<?php } ?>
                    <?php if($cuppa->permissions->getValue(2,$view, "4")) {?>
            	        <a class="edit tool_button tooltip" title="<?php echo @$language->tooltip_edit_item ?>" onclick="list_admin_table.submit('edit')"><span>b</span></a>
            		<?php } ?>
                    <?php if($cuppa->permissions->getValue(2,$view, "12")){ ?>
            	        <a class="duplicate tool_button tooltip" title="<?php echo @$language->tooltip_duplicate_item ?>" onclick="list_admin_table.submit('duplicate')"><span>g</span></a>
                    <?php } ?>
                    <?php if($cuppa->permissions->getValue(2,$view, "5")){ ?>
            	        <a class="delete tool_button tooltip" title="<?php echo @$language->tooltip_delete_item ?>" onclick="list_admin_table.submit('delete')"><span>c</span></a>
                    <?php } ?>
                     <?php if($cuppa->permissions->getValue(2, $view, "8")){ ?>
                        <a class="download tool_button tooltip tool_right" href="classes/DownloadBigFile.php" target="_self" title="<?php echo @$language->tooltip_download_item ?>"><span>d</span></a>
                	<?php } ?>  
                </div>
            </div>
        <!-- -->
        <!-- List Info -->
            <div class="frame">
                <!-- Include file (before_to_table) -->
                    <?php
                        for($i = 0; $i < @count($include_files); $i++){
                            if( $include_files[$i]->add_to == "list" && $include_files[$i]->position == "before_to_table" ){
                                $include_file = $cuppa->getDocumentPath().$include_files[$i]->path;
                                if(strpos($include_file, "../") !== false){
                                    $f_backs = substr_count($include_file,  "../");
                                    $back_string = str_repeat("/..", $f_backs);
                                    $file = str_replace("../", "", $include_files[$i]->path);
                                    $include_file = realpath($cuppa->getDocumentPath().$back_string)."/".$file;
                                }
                                @include($include_file);
                            }
                        } 
                    ?>
                <!-- -->
                <!-- Paginator Top -->
                    <div class="pag_top">
                       	<?php
                            //++ if is not a tree list, show paginator
                                if(!$field_types->show_list_like_tree){
                                    if($search_word != " 1 "){
                                        $search_word = str_replace(" 1  AND", " AND ",$search_word);
                                        $conditions .= $search_word;
                                    }
                                    echo $cuppa->paginator->getAutoPaginator($view, @$_POST["page"], $list_limit, 1, @$conditions, false, "stage.changePage");
                                }
                            //--
                    	?>
                    </div>
                <!-- End Paginator -->
                <table class="table_info">
                    <thead>
                        <tr>
                            <th class="header checkbox"><input type="checkbox" onclick="cuppa.checkbox.selectAll(this.checked)"/></th>
                        	<?php for($i = 0; $i < count($infoColumns); $i++){ ?>
                            	<?php if(strtolower($infoColumns[$i]) == "id"){ ?>
                                    <?php 
                                        $show_list = @$field_types->{$infoColumns[$i]}->showList;
                                        //++ validate permission
                                            $permission_value = @$cuppa->permissions->getValue("5", $view.",".$infoColumns[$i], "11", true);
                                            if($permission_value === 0 || $permission_value === 1 ){
                                               $show_list = $permission_value;
                                            }
                                        //--
                                    ?>
                                	<?php if(@$show_list){ ?>
        	                    		<th class="header id">
                                            <a onclick="jQuery('#order_by').val('<?php echo $infoColumns[$i] ?>'); jQuery('#order_orientation').val('<?php echo (@$_REQUEST["order_orientation"] == "ASC" && @$_REQUEST["order_by"] == $infoColumns[$i]) ? "DESC" : "ASC" ?>'); jQuery('#form').submit()" >
                                                <?php
                                                    echo $cuppa->language->getValue($field_types->{$infoColumns[$i]}->label, $language);
                                                ?>
                                                <?php if(@$_REQUEST["order_by"] == $infoColumns[$i]){ ?>
                                                    <?php if(@$_REQUEST["order_orientation"] == "ASC"){ ?>
                                                        <img src="templates/default/images/template/arrow_down.gif" />
                                                    <?php }else{ ?>
                                                        <img src="templates/default/images/template/arrow_up.gif" />
                                                    <?php } ?>
                                                <?php } ?>
                                            </a>
                                        </th>
                                	<?php } ?>
        						<?php }else{ ?>
                                    <?php 
                                        $show_list = @$field_types->{$infoColumns[$i]}->showList;
                                        //++ validate permission
                                            $permission_value = @$cuppa->permissions->getValue("5", $view.",".$infoColumns[$i], "11", true);
                                            if($permission_value === 0 || $permission_value === 1 ){
                                               $show_list = $permission_value;
                                            }
                                        //--
                                    ?>
                                	<?php if($show_list){ ?>
                                		<th class="header header_<?php echo $cuppa->utils->getFriendlyUrl($field_types->{$infoColumns[$i]}->label) ?>">
                                            <a onclick="jQuery('#order_by').val('<?php echo $infoColumns[$i] ?>'); jQuery('#order_orientation').val('<?php echo (@$_REQUEST["order_orientation"] == "ASC" && @$_REQUEST["order_by"] == $infoColumns[$i] ) ? "DESC" : "ASC" ?>'); jQuery('#form').submit()" >
                                                <?php
                                                    echo $cuppa->language->getValue($field_types->{$infoColumns[$i]}->label, $language);
                                                ?>
                                                <?php if(@$_REQUEST["order_by"] == $infoColumns[$i]){ ?>
                                                    <?php if(@$_REQUEST["order_orientation"] == "ASC"){ ?>
                                                        <img src="templates/default/images/template/arrow_down.gif" />
                                                    <?php }else{ ?>
                                                        <img src="templates/default/images/template/arrow_up.gif" />
                                                    <?php } ?>
                                                <?php } ?>
                                            </a>
                                        </th>
                                	<?php } ?>
        						<?php }?>
                            <?php } ?>
                            <?php if($option_panel){ ?>
                                <th  class="header header_option_panel"><?php echo @$language->options ?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            //++ Print Info 
            					if($info){
            						for($i = 0; $i < count($info_array["data"]); $i++){
                                        if($info_array["data"][$i]['last_updating_time'] < 30 && $info_array["data"][$i]["user_id_updating"] != $cuppa->user->getVar("id") ){
                                            $title_updating = str_replace("#name#", @$info_array["data"][$i]['user_updating'], @$language->item_is_being_updated);
                                            echo '<tr class="red tooltip" title="'.@$title_updating.'" >';
                                            echo "<td class='select'><img src='templates/default/images/template/blocked_16.png' width='13' />".$info_array["data"][$i][0]."</td>";
                                        }else{
                                            if(($i%2) != 0) echo "<tr>"; else echo "<tr class='gray'>";
                                            echo "<td class='select'>".$info_array["data"][$i][0]."</td>";
                                        }
                                        for($j = 0; $j < count($infoColumns); $j++){
                                            if(isset($info_array["data"][$i][$infoColumns[$j]])){
                                                if($j == 0) echo "<td class='td_".$infoColumns[$j]."' >".$info_array["data"][$i][$infoColumns[$j]]."</td>";
                                                else echo "<td class='td_".$infoColumns[$j]."'>".nl2br($info_array["data"][$i][$infoColumns[$j]])."</td>";
                                            }                        
                                        }
                                        if($option_panel) echo "<td class='td_option_panel' >".@$info_array["data"][$i]["options"]."</td>";
                                        echo "</tr>";
                                    }
                                }   
                            //-- 
                        ?>
                    </tbody>
                </table>
                <!-- List no info -->
                    <?php if(!$info){ ?>
                        <div class="table_no_info">
                            <div class="no_file" style="text-align: center; padding: 40px 0px;">
                                <img src="templates/default/images/template/face.png" style="vertical-align: middle;"  />
                                <div style="display: inline-block; text-align: left; margin-left: 10px; vertical-align: middle;">
                                    <h2 style="color: #777;"><?php echo $language->table_without_info ?></h2>
                                    <div style="max-width: 250px; color: #AAA;"><?php echo $language->table_without_info_message ?></div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <!-- -->
                <!-- Other vars -->
                    <input type="hidden" name="order_by" id="order_by" value="<?php echo @$_REQUEST["order_by"] ?>" />
                    <input type="hidden" name="order_orientation" id="order_orientation" value="<?php echo @$_REQUEST["order_orientation"] ?>" />
                <!-- -->
                <!-- Include file (after_to_table) -->
                    <?php
                        for($i = 0; $i < @count($include_files); $i++){
                            if( $include_files[$i]->add_to == "list" && $include_files[$i]->position == "after_to_table" ){
                                $include_file = $cuppa->getDocumentPath().$include_files[$i]->path;
                                if(strpos($include_file, "../") !== false){
                                    $f_backs = substr_count($include_file,  "../");
                                    $back_string = str_repeat("/..", $f_backs);
                                    $file = str_replace("../", "", $include_files[$i]->path);
                                    $include_file = realpath($cuppa->getDocumentPath().$back_string)."/".$file;
                                }
                                @include($include_file);
                            }
                        } 
                    ?>
                <!-- -->
                <!-- Paginator -->
                    <div class="pag_bottom">
                       	<?php
                            //++ if is not a tree list, show paginator
                                if(!$field_types->show_list_like_tree){
                                    if($search_word != " 1 "){
                                        $search_word = str_replace(" 1  AND", " AND ",$search_word);
                                        $conditions .= $search_word;
                                    }
                                    echo $cuppa->paginator->getAutoPaginator($view, @$_POST["page"], $list_limit, 1, @$conditions, false, "stage.changePage");
                                }
                            //--
                    	?>
                    </div>
                <!-- End Paginator -->
            </div>
        <!-- -->
        <!-- Include file (end) -->
            <?php
                for($i = 0; $i < @count($include_files); $i++){
                    if( $include_files[$i]->add_to == "list" && $include_files[$i]->position == "end" ){
                        $include_file = $cuppa->getDocumentPath().$include_files[$i]->path;
                        if(strpos($include_file, "../") !== false){
                            $f_backs = substr_count($include_file,  "../");
                            $back_string = str_repeat("/..", $f_backs);
                            $file = str_replace("../", "", $include_files[$i]->path);
                            $include_file = realpath($cuppa->getDocumentPath().$back_string)."/".$file;
                        }
                        @include($include_file);
                    }
                } 
            ?>
        <!-- -->
    </div>
</form>