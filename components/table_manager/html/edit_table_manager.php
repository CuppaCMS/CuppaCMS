<?php
    include_once(realpath(__DIR__ . '/../../..')."/classes/Cuppa.php");
    $cuppa = Cuppa::getInstance(); $cuppa->user->valid("admin_login");
    $language = $cuppa->language->load();
    // Task conditions
        if(@$_POST["task"] == "edit"){ 
            $info = $cuppa->dataBase->getRow($cuppa->configuration->table_prefix."tables", "id=".@$_POST["id"]);
            $_POST["table_name"] =  $info["table_name"];
        }else $_POST["id"] = 0;
    //--
    //++ Get clumns
        $infoColumbs = $cuppa->dataBase->getColums($_POST["table_name"]);
    //--
    //++ Get type fields
		$files = scandir( $cuppa->getDocumentPath()."components/table_manager/fields/");
		$field_types = array();
		for($i = 0; $i < count($files); $i++){
			if($files[$i] != "." && $files[$i] != ".."){ 
				$file = explode(".", $files[$i]); 
				if(count($file) > 1) array_push($field_types, array($file[0], str_replace("_", " ",$file[0])));
			}
		}
		$field_types = json_encode($field_types);
	//--
    // Create array with URL for config
        $default_info = @json_decode(base64_decode($info["params"]));
        if(!$default_info) $default_info = @json_decode($info["params"]);

        $fields_array = json_decode($field_types);
        $urlConfig = array();
        for($i = 0; $i < count($fields_array); $i++){
            $className = $fields_array[$i][0];
            require( $cuppa->getDocumentPath()."components/table_manager/fields/".$className.".php");
            $item = new $className();
            echo "<input type='hidden' name='".$className."_urlConfig"."' id='".$className."_urlConfig"."' value='".@$item->urlConfig."' />";
            $urlConfig[$className] = @$item->urlConfig;
        }
    // Option panel
        $option_panel = @json_decode(base64_decode(@$default_info->option_panel));
        if(!$option_panel) $option_panel = $default_info->option_panel;
    // Fields                        
        $file = new File();
    // Language files
        $language_file_list = Cuppa::getInstance()->file->getList($cuppa->getDocumentPath()."language/".$cuppa->language->getCurrentLanguage(), true);
?>
<style>
    .edit_table{ padding: 5px 15px 15px; }
    .edit_table h1{ float: left; }
    .edit_table .tools{ position: relative; float: right; top: 4px; }
    .edit_table .button_gray{ margin-bottom: 3px;}
    /* Option panel */
        .option_panel ul{ margin: 0px; padding: 0px; list-style: none; list-style-type: none; }
        .option_panel li{
            position: relative;
            background: #F5F5F5;
            border: 1px solid #DDD;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            padding: 7px 10px;
            margin: 0 5px 2px 0px;
            font-size: 11px;
            color: #444444;
            display: inline-block;
        }
        .option_panel li *{  vertical-align: middle; }
        .option_panel .item span{ margin: 0px 5px; }
    /* Include file pane */
        .include_file_pane ul{ margin: 0px; padding: 0px; list-style: none; list-style-type: none; }
        .include_file_pane li{
            position: relative;
            background: #F5F5F5;
            border: 1px solid #DDD;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            padding: 7px 10px;
            margin: 0 5px 2px 0px;
            font-size: 11px;
            color: #444444;
            display: inline-block;
        }
        .include_file_pane li *{  vertical-align: middle; }
        .include_file_pane .item span{ margin: 0px 5px; }
    .conf_input{ display: none; margin-left: 5px; width: 0px; }
    .conf_input.error, .conf_input.show{ display: inline-block !important; width: 100px !important; }
    /* Tabs */
        .tabs ul{ margin: 0px; padding: 0px; list-style: none; list-style-type: none; }
        .tabs li{
            position: relative;
            background: #F5F5F5;
            border: 1px solid #DDD;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            padding: 7px 10px;
            margin: 0 5px 2px 0px;
            font-size: 11px;
            color: #444444;
            display: inline-block;
        }
        .tabs li *{  vertical-align: middle; }
        .tabs .item span{ margin: 0px 5px; }
/* Responsive */
    .r1200 .edit_table{ }
    .r650 .edit_table .h1_title{ display: none !important; } 
    .r650 .edit_table .tools{ top: 0px !important; margin-top: 5px !important; }
    .r650 .edit_table .tools{ float: left; clear: both; }
</style>
<script>
    edit_table = {}
    //++ Add Option Panel
        edit_table.addOptionPanel = function(){
            if($('.option_panel').css("display") == "none") $('.option_panel').css("display","block");
            var item = $(".assets .option_panel_item").clone();
                $(item).find(".button_delete").bind("click", edit_table.removeOptionPanel);
                $(".option_panel ul").append(item);
                cuppa.fileUpload($(item).find(".image_src"));
            cuppa.tooltip();
        }
        edit_table.removeOptionPanel = function(){
            $(this).parent().remove();
            if( !$('.option_panel ul li').length ) $('.option_panel').css("display","none");
        }
        edit_table.setInfoOptionPanel = function(){
            var data = "<?php echo $cuppa->jsonEncode($option_panel); ?>";
                data = cuppa.jsonDecode(data);
                if(!data || !data.length) return;
            $('.option_panel').css("display", "block");
            for(var i = 0; i < data.length; i++){
                var item = $(".assets .option_panel_item").clone();
                    $(item).find(".button_delete").bind("click", edit_table.removeOptionPanel );
                    $(".option_panel ul").append(item);
                    $(item).find(".image_src").val(data[i].image_src)
                    $(item).find(".url").val(data[i].url)
                    $(item).find(".var_name").val(data[i].var_name)
                    $(item).find(".tooltip").val(data[i].tooltip)
                    cuppa.fileUpload($(item).find(".image_src"));
            }
            cuppa.tooltip();
        }; 
        
        edit_table.getInfoOptionPanel = function(){
            var items = $(".option_panel ul li");
            if(items.length == 0){ $(".edit_table form #option_panel").val(""); return}
            var data = new Array();
            for(var i = 0; i < items.length; i++){
                var item = {}
                    item.image_src = $(items[i]).find(".image_src").val();
                    item.url = $(items[i]).find(".url").val();
                    item.var_name = $(items[i]).find(".var_name").val();
                    item.tooltip = $(items[i]).find(".tooltip").val();
                data.push(item);
            }
            $(".edit_table form #option_panel").val(cuppa.jsonEncode(data));
        }
    //--
    //++ Include file
        edit_table.addIncludeFile = function(){
            if($('.include_file_pane').css("display") == "none") $('.include_file_pane').css("display", "block");
            var item = $(".assets .item_include_file").clone();
                $(item).find(".button_delete").bind("click", edit_table.removeIncludeFile );
                $(item).find(".add_to").bind("change", edit_table.changeAddTo );
                $(".include_file_pane ul").append(item);
            cuppa.selectStyle($(item).find("select"));
            cuppa.tooltip();
        }
        edit_table.removeIncludeFile = function(){
             $(this).parent().remove();
             if( !$('.include_file_pane ul li').length ) $('.include_file_pane').css("display","none");
        }
        edit_table.changeAddTo = function(e, item){
            var element = (item) ? item : this;
            if($(element).val() == "list"){
                var data = '<option value="top"><?php echo @$language->top ?></option>';
                    data += '<option value="end"><?php echo @$language->end ?></option>';
                    data += '<option value="before_to_table"><?php echo @$language->before_to_table ?></option>';
                    data += '<option value="after_to_table"><?php echo @$language->after_to_table ?></option>';
                $(element).parent().parent().find(".position").html(data);
            }else{
                var data = '<option value="top"><?php echo @$language->top ?></option>';
                    data += '<option value="end"><?php echo @$language->end ?></option>';
                    data += '<option value="before_to_fields"><?php echo @$language->before_to_fields ?></option>';
                    data += '<option value="after_to_fields"><?php echo @$language->after_to_fields ?></option>';
                $(element).parent().parent().find(".position").html(data);
            }
        }
        edit_table.setIncludeFiles = function(){
            var data = "<?php echo @$default_info->include_file ?>";
                data = cuppa.jsonDecode(data);
                if(!data || !data.length) return;
            $('.include_file_pane').css("display", "block");
            for(var i = 0; i < data.length; i++){
                var item = $(".assets .item_include_file").clone();
                    $(item).find(".button_delete").bind("click", edit_table.removeIncludeFile );
                    $(item).find(".add_to").bind("change", edit_table.changeAddTo );
                    $(item).find(".path").val(data[i].path);
                    $(item).find(".add_to").val(data[i].add_to);
                    edit_table.changeAddTo(null, $(item).find(".add_to") );
                    $(item).find(".position").val(data[i].position);
                    $(".include_file_pane ul").append(item);
             }
             cuppa.tooltip();
        }; 
        edit_table.getIncludeFiles = function(){
            var elements = $('.include_file_pane ul li');
            var data = new Array();
            for(var i = 0; i < $(elements).length; i++){
                if( $(elements[i]).find(".path").val() ){
                    var item = {}
                        item.path = $(elements[i]).find(".path").val();
                        item.add_to = $(elements[i]).find(".add_to").val();
                        item.position = $(elements[i]).find(".position").val();
                    data.push(item);
                }
            }
            if(!data.length){ $(".edit_table form #include_file").val(""); return }
            $(".edit_table form #include_file").val( cuppa.jsonEncode(data));
        }
    //--
    //++ Tabs
        edit_table.addTab = function(){
            if($('.tabs').css("display") == "none") $('.tabs').css("display", "block");
            var item = $(".assets .item_tab").clone();
                $(item).find(".button_delete").bind("click", edit_table.removeTab );
                cuppa.autoComplete($(item).find(".tab_fields"), '<?php echo json_encode($infoColumbs) ?>', true);
                $(".tabs ul").append(item);
        }
        edit_table.removeTab = function(){
             $(this).parent().remove();
             if( !$('.tabs ul li').length ) $('.tabs').css("display","none");
        }
        edit_table.getInfoTab = function(){
            var items = $(".tabs .item_tab");
            if(items.length == 0){ $(".edit_table form #tabs").val(""); return}
            var data = {}; data.info = new Array(); data.fields_tab = {};
            for(var i = 0; i < items.length; i++){
                var item = {}
                    item.tab_name = $(items[i]).find(".tab_name").val();
                    item.tab_fields = $(items[i]).find(".tab_fields").val();
                if(item.tab_name){ 
                    data.info.push(item);
                    if(item.tab_fields){
                        for(var j = 0; j <= item.tab_fields.length; j++){
                            data.fields_tab[item.tab_fields[j]] = item.tab_name;
                        }
                    }
                }
            }
            if(data.info.length) $(".edit_table form #tabs").val(cuppa.jsonEncode(data));
        }
        edit_table.setTabs = function(){
            var data = cuppa.jsonDecode("<?php echo @$default_info->tabs ?>");
            try{ data = data.info }catch(err){ };
            if(!data || !data.length) return;
            $('.tabs').css("display", "block");
            $(data).each(function(){
                var item = $(".assets .item_tab").clone();
                    item.find(".tab_name").val(this.tab_name);
                    item.find(".tab_fields").val(this.tab_fields);
                    item.find(".button_delete").bind("click", edit_table.removeTab);
                $(".tabs ul").append(item);
            });
        }; 
    //--
    //++ Set init info
        edit_table.setInitInfo = function(){
            jQuery("#language_file_reference").val("<?php echo @$default_info->language_file_reference ?>");
            jQuery("#show_list_like_tree").prop("checked", parseInt("<?php echo @$default_info->show_list_like_tree ?>"));
            jQuery("#show_list_like_tree_column").val("<?php echo @$default_info->show_list_like_tree_column ?>");
            jQuery("#show_list_like_tree_validate").val("<?php echo @$default_info->show_list_like_tree_validate ?>");
            jQuery("#show_list_like_tree_indicator").val("<?php echo @$default_info->show_list_like_tree_indicator ?>");            
            jQuery("#order_by").val("<?php echo @$default_info->order_by ?>");
            jQuery("#order_by_order").val("<?php echo @$default_info->order_by_order ?>");
            jQuery("#link_indicator").val("<?php echo @$default_info->link_indicator ?>");
        }; 
    //--
    //++ Load config
        edit_table.loadConfig = function(e, item){
            if(e) item = this;
    		var itemName = $(item).attr("name");
    		var itemValue = $(item).val();
    		var urlConfig = $("#"+itemValue+"_urlConfig").attr("value");
    		var contentToDelete = '#'+itemName + "_div";
            cuppa.setContent({'load':false, 'name':contentToDelete});
    		if(urlConfig){
    			var newField = "<div style='float:left' name='" + itemName + "_div" + "' id='" + itemName +"_div"+"'>";
    				newField += "<input style='margin-left: 5px;' class='button_blue' type='button' value='<?php echo @$language->config_field ?>' onclick='stage.loadConfigAlert(\""+itemName+"\", \"" + urlConfig + "\")'/>";
    				newField += "<input class='conf_input required readonly' title='<?php echo $language->this_field_is_required ?>' name='" + itemName + "_config" +"' id='" + itemName + "_config" +"' />"
    				newField += "</div>";
    			$("." + itemName + " .configuration_field_info").append(newField);
    		}
    	}
    //--
    //++ Save
        edit_table.save = function(task){
            if(!$('.edit_table form').valid()) return;
            menu.showCharger(true);
            cuppa.blockadeScreen();
            edit_table.getInfoOptionPanel();
            edit_table.getIncludeFiles();
            edit_table.getInfoTab();
            var data = {}
                data = cuppa.urlToObject($('.edit_table form').serialize());
                data["function"] = "saveTableManager";
                $.ajax({url:"components/table_manager/classes/functions.php", type:"POST", data:data, success:Ajax_Result});
                function Ajax_Result(result){
                    menu.showCharger(false);
                    if(task == "save"){
                        cuppa.managerURL.setParams({path:"component/table_manager/"}, true);
                        w_right.close();
                    }else if(task == "save_and_edit"){
                        cuppa.blockadeScreen(false);
                        if(result) $('.edit_table form #id').val(result);
                        $(".edit_table .btn_close").attr("onclick", 'cuppa.managerURL.setParams({path:"component/table_manager/"}, true); w_right.close()');
                    }
                }
        }
    //--
    //++ show configuration input
        edit_table.showConfInput = function(){
            $(".edit_table .conf_input").toggleClass("show");  
        };
    //++ end
        edit_table.end = function(){
            cuppa.removeEventGroup("edit_table");
        }; 
    //--
    //++ init
        edit_table.init = function(){
            cuppa.addRemoveListener(".edit_table", edit_table.end);
            edit_table.setInitInfo();
            edit_table.setInfoOptionPanel();
            edit_table.setIncludeFiles();
            edit_table.setTabs();
            cuppa.selectStyle(".edit_table select");
            $( ".option_panel ul" ).sortable({ items: "li" });
            $( ".include_file_pane ul" ).sortable({ items: "li" });
            $( ".tabs ul" ).sortable({ items: "li" });
            cuppa.tooltip();
            $(".table_info .config_field select").bind("change", edit_table.loadConfig);
        }; cuppa.addEventListener("ready",  edit_table.init, document, "edit_table");
    //--
</script>
<div class="edit_table">
    <form class="form_edit_table" method="post">
        <!-- Header -->
            <div style="overflow: hidden;">
                <h1>
                    <span class="h1_title"><?php echo ($_POST["task"] == "new") ? $language->configure_new_table : $language->editing_table; ?></span>
                    <span class="title_info"><?php echo @$_POST["table_name"] ?></span>
                </h1>
                <div class="tools">
                    <input class="button_blue" type="button" value="Save" onclick="edit_table.save('save')" />
                    <input class="button_blue" type="button" value="Save and edit" onclick="edit_table.save('save_and_edit')" />
                    <input class="button_red btn_close" type="button" value="Cancel" onclick="w_right.close()" />
                </div>
            </div>
        <!-- -->
        <!-- Global configuration -->
            <div class="section" style="margin: 20px 0px;"><div></div><span><?php echo $language->global_configuration ?></span></div>
            <div class="global_1" style="overflow: auto;">
                <div class="global_2" style="padding-left: 30px; min-width: 900px; white-space: nowrap;">
                    <table class="form">
                        <tr>
                            <td style="width: 230px;"><?php echo @$language->personal_table_name ?></td>
                            <td>
                                <input id="custom_table_name" name="custom_table_name" type="text" value="<?php echo @$default_info->custom_table_name ?>" />
                                <a class="tooltip" onclick="stage.loadLightbox('<?php echo $language->language ?>', 'components/language_manager/html/files.php', '&width=80%&height=90%&mode=lightbox')"><img style="vertical-align: middle;" src="templates/default/images/template/language_manager_16_black.png" title="<?php echo @$language->tooltip_langauge_manager ?>" /></a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo @$language->language_file_reference ?>&nbsp;
                                <img title="<?php echo @$language->language_file_reference_tooltip ?>" class="tooltip" src="templates/default/images/template/icon_help_12.png" />
                            </td>
                            <td>
                                <select id="language_file_reference" name="language_file_reference" >
                                    <option value=""><?php echo @$language->none ?></option>
                                    <?php for($i = 0; $i < count($language_file_list); $i++){ ?>
                                        <?php if($language_file_list[$i] != "administrator"){ ?>
                                            <option value="<?php echo @$language_file_list[$i] ?>"><?php echo @$language_file_list[$i] ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>    
                                <a class="tooltip" onclick="stage.loadLightbox('<?php echo $language->language ?>', 'components/language_manager/html/files.php', '&width=80%&height=90%&mode=lightbox')"><img style="vertical-align: middle;" src="templates/default/images/template/language_manager_16_black.png" title="<?php echo @$language->tooltip_langauge_manager ?>" /></a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo @$language->show_list_nested ?>&nbsp;
                                <img title="<?php echo @$language->show_nested_information_message ?>" class="tooltip" src="templates/default/images/template/icon_help_12.png" />
                            </td>
                            <td>
                                <input id="show_list_like_tree" name="show_list_like_tree" type="checkbox" />&nbsp;
                                <?php echo @$language->column ?>&nbsp;
                                <select id="show_list_like_tree_column" name="show_list_like_tree_column" style="width: 100px;" >
                               	    <?php for($i = 0; $i < count($infoColumbs); $i++){ ?>
                                        <option value="<?php echo $infoColumbs[$i] ?>"><?php echo $infoColumbs[$i] ?></option>
                                    <?php } ?>
                                </select>
                                <?php echo @$language->validate ?>&nbsp;
                                <select id="show_list_like_tree_validate" name="show_list_like_tree_validate" style="width: 100px;" >
                                    <?php for($i = 0; $i < count($infoColumbs); $i++){ ?>
                                        <option value="<?php echo $infoColumbs[$i] ?>"><?php echo $infoColumbs[$i] ?></option>
                                    <?php } ?>
                                </select>
                                <?php echo @$language->show_the_character_indicator_on ?>&nbsp;
                                <select id="show_list_like_tree_indicator" name="show_list_like_tree_indicator" style="width: 100px;" >
                                    <?php for($i = 0; $i < count($infoColumbs); $i++){ ?>
                                        <option value="<?php echo $infoColumbs[$i] ?>"><?php echo $infoColumbs[$i] ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo @$language->order_by ?></td>
                            <td>
                                <select id="order_by" name="order_by" style="width: 100px;" >
                               	    <?php for($i = 0; $i < count($infoColumbs); $i++){ ?>
                                        <option value="<?php echo $infoColumbs[$i] ?>"><?php echo $infoColumbs[$i] ?></option>
                                    <?php } ?>
                                </select>
                                <select id="order_by_order" name="order_by_order" style="width: 100px;" >
                                    <option value="ASC">ASC</option>
                                    <option value="DESC">DESC</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo @$language->link_indicator ?></td>
                            <td>
                                <select id="link_indicator" name="link_indicator" style="width: 100px;" >
                                    <option value=""><?php echo $language->none ?></option>
                                    <?php for($i = 0; $i < count($infoColumbs); $i++){ ?>
                                        <option value="<?php echo $infoColumbs[$i] ?>"><?php echo $infoColumbs[$i] ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo @$language->list_limit ?></td>
                            <td>
                                <input id="list_limit" name="list_limit" value="<?php echo @$default_info->list_limit ?>" placeholder="<?php echo @$cuppa->configuration->list_limit ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo @$language->url_to_this_table ?></td>
                            <td>
                                <input style="width: 100%; max-width: 500px; border: 1px solid #F5F5F5 !important;" class="readonly" readonly="readonly" value="<?php echo "component/table_manager/view/". @$_POST["table_name"] ?>" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        <!-- -->
        <!-- Principal -->
            <div class="section" style="margin: 20px 0px;"><div></div><span><?php echo $language->fields_configuration ?></span></div>
            <div class="frame">
                <table class="table_info" style="min-width: 1200px;">
                    <tr>
                        <th style="width:100px;"><?php echo @$language->column ?></th>
                        <th style="width:150px;"><?php echo @$language->label ?></th>
                        <th style="width:100px; text-align:center;"><?php echo @$language->show_in_list ?></th>
                        <th style="width:150px; text-align:center;"><?php echo @$language->include_in_download ?></th>
                        <th style="width:100px; text-align:center;"><?php echo @$language->required ?></th>
                        <th style="width:100px; text-align:center;"><?php echo @$language->language_button ?></th>
                        <th style="width:100px; text-align:center;"><?php echo @$language->primary_key ?></th>
                        <th>
                            <?php echo @$language->configuration_field ?>
                            <img class="button_alpha tooltip" class="btn_show_config" title="<?php echo @$language->show_configuration ?>" onclick="edit_table.showConfInput()" src="templates/default/images/template/icon-eye.svg" style="position: absolute; top: 10px; right: 10px; height: 15px; cursor: default; cursor: pointer;" />
                        </th>
                    </tr>
                    <?php for($i = 0; $i < count($infoColumbs); $i++){ ?>
                        <?php if(($i%2) != 0){ echo "<tr>"; }else{ echo "<tr class='grey'>"; } ?>
                            <td><?php echo $infoColumbs[$i]; ?></td>
                            <td>
                            	<?php  if(isset($default_info->{$infoColumbs[$i]}->label)){ ?>
                                	<input style="width: 100%;" class="text_field required" title="<?php echo $language->this_field_is_required ?>" id="<?php echo $infoColumbs[$i] ?>_label" name="<?php echo $infoColumbs[$i] ?>_label" value="<?php echo @$default_info->{$infoColumbs[$i]}->label ?>" />
                                <?php } else{ ?>
                                	<input style="width: 100%;" class="text_field required" title="<?php echo $language->this_field_is_required ?>" id="<?php echo $infoColumbs[$i] ?>_label" name="<?php echo $infoColumbs[$i] ?>_label" value="<?php echo ucfirst( str_replace("_"," ",$infoColumbs[$i])) ?>" />
                                <?php } ?>
        					</td>
                            <td style="text-align:center;" >
                            	 <?php  if(isset($default_info->{$infoColumbs[$i]}->showList)){ ?>
                                 	<?php if($default_info->{$infoColumbs[$i]}->showList == 1){?>
        	                         	<input value="1" type="checkbox" name="<?php echo $infoColumbs[$i] ?>_showList" id="<?php echo $infoColumbs[$i] ?>_showList" checked="checked" />
                                    <?php }else {?>
                                    	<input value="1" type="checkbox" name="<?php echo $infoColumbs[$i] ?>_showList" id="<?php echo $infoColumbs[$i] ?>_showList" />
        							<?php } ?>
        						<?php } else{ ?>
        	                    	<input type="checkbox" name="<?php echo $infoColumbs[$i] ?>_showList" id="<?php echo $infoColumbs[$i] ?>_showList" checked="checked" />
                                 <?php } ?>
                            </td>
                            <td style="text-align:center;">
                                <?php  if(isset($default_info->{$infoColumbs[$i]}->includeDownload)){ ?>
                                 	<?php if($default_info->{$infoColumbs[$i]}->includeDownload == 1){?>
        	                         	<input value="1" type="checkbox" name="<?php echo $infoColumbs[$i] ?>_includeDownload" id="<?php echo $infoColumbs[$i] ?>_includeDownload" checked="checked" />
                                    <?php }else {?>
                                    	<input value="1" type="checkbox" name="<?php echo $infoColumbs[$i] ?>_includeDownload" id="<?php echo $infoColumbs[$i] ?>_includeDownload" />
        							<?php } ?>
        						<?php } else{ ?>
        	                    	<input type="checkbox" name="<?php echo $infoColumbs[$i] ?>_includeDownload" id="<?php echo $infoColumbs[$i] ?>_includeDownload" checked="checked" />
                                 <?php } ?>
                            </td>
                            <td style="text-align:center;" >
                            	<?php  if(isset($default_info->{$infoColumbs[$i]}->required)){ ?>
                                	<?php if($default_info->{$infoColumbs[$i]}->required == 1){?>
        	                         	<input type="checkbox" name="<?php echo $infoColumbs[$i] ?>_required" id="<?php echo $infoColumbs[$i] ?>_required" checked="checked" />
                                    <?php }else {?>
                                    	<input type="checkbox" name="<?php echo $infoColumbs[$i] ?>_required" id="<?php echo $infoColumbs[$i] ?>_required" />
        							<?php } ?>
                                <?php } else{ ?>
                                    <?php if($i == 0){?>    
                                        <input type="checkbox" name="<?php echo $infoColumbs[$i] ?>_required" id="<?php echo $infoColumbs[$i] ?>_required" checked="checked" />
                                    <?php } else{ ?>
                                        <input type="checkbox" name="<?php echo $infoColumbs[$i] ?>_required" id="<?php echo $infoColumbs[$i] ?>_required" />
                                    <?php } ?>
                                <?php } ?>
                            </td>
                            <td style="text-align:center;" >
                            	<?php if(@$default_info->{$infoColumbs[$i]}->language_button == 1){?>
    	                         	<input type="checkbox" name="<?php echo $infoColumbs[$i] ?>_language_button" id="<?php echo $infoColumbs[$i] ?>_language_button" checked="checked" />
                                <?php }else {?>
                                	<input type="checkbox" name="<?php echo $infoColumbs[$i] ?>_language_button" id="<?php echo $infoColumbs[$i] ?>_language_button" />
    							<?php } ?>
                            </td>
                            <td style="text-align:center;" >
                            	<?php  if(isset($default_info->primary_key)){ ?>
                                	<?php if($default_info->primary_key ==  $infoColumbs[$i]){?>
        	                         	<input class="required" title=" " type="radio" name="primary_key" id="primary_key" value="<?php echo $infoColumbs[$i] ?>" checked="checked" />
                                    <?php }else {?>
                                    	<input class="required" title=" " type="radio" name="primary_key" id="primary_key" value="<?php echo $infoColumbs[$i] ?>" />
        							<?php } ?>
                                <?php } else{ ?>
                                	<?php if($i == 0){?>
        	                        	<input class="required" title=" " type="radio" name="primary_key" id="primary_key" value="<?php echo $infoColumbs[$i] ?>" checked="checked" />
        							<?php }else{ ?>
                                    	<input class="required" title=" " type="radio" name="primary_key" id="primary_key" value="<?php echo $infoColumbs[$i] ?>" />
                                    <?php } ?>
                                <?php } ?>
                            </td>
                            <td class="config_field <?php echo $infoColumbs[$i]."_field" ?>" style="white-space: nowrap;">
                                <div style="float: left; padding-top: 5px; margin-bottom: 5px;">
                                    &nbsp;<?php echo @$language->type ?>: &nbsp;
                                </div>
                                <div class="configuration_field_info" style="float: left; margin-bottom: 5px;">
            						<?php
            							echo "<div style='float:left'>";
            								$className = "Select";
            								$value = ($i == 0) ? "Id" : "Text"; if(@$default_info->{$infoColumbs[$i]}->type) $value = @$default_info->{$infoColumbs[$i]}->type;
            								$item = new $className();
            								echo $item->GetItem($infoColumbs[$i]."_field", $value, $field_types, true, "", "style='width:70px'","",false);
                                        echo "</div>";
            							//++ Assing default config
            								$defaultConfig = json_encode(@$default_info->{$infoColumbs[$i]}->config);

                                            $config = @$default_info->{$infoColumbs[$i]}->config;
                                            if(is_object($config)) $config = $cuppa->jsonEncode($config);

            								if($defaultConfig != "null"){
            									$itemName = $infoColumbs[$i]."_field";
            									$urlConfig_link = @$urlConfig[$default_info->{$infoColumbs[$i]}->type];
            										$newField = "<div style='float:left' name='".$itemName."_div"."' id='".$itemName."_div"."'>";
            										$newField .= "<input style='margin-left: 5px;' class='button_blue' type='button' value='".@$language->config_field."' onclick='stage.loadConfigAlert(\"".$itemName."\", \"".$urlConfig_link."\")' />";
            										$newField .= "<input value='".$config."' class='conf_input required readonly' name='" . $itemName . "_config" . "' id='".$itemName."_config"."' />";
            										$newField .= "</div>";
            									echo $newField;
            								}else if($value == "Text"){
            								    $itemName = $infoColumbs[$i]."_field";
            									$urlConfig_link = $urlConfig[$value];
            										$newField = "<div style='float:left' name='".$itemName."_div"."' id='".$itemName."_div"."'>";
            										$newField .= "<input style='margin-left: 5px;' class='button_blue' type='button' value='".@$language->config_field."' onclick='stage.loadConfigAlert(\"".$itemName."\", \"".$urlConfig_link."\")' />";
            										$newField .= "<input value='".$config."' class='conf_input required readonly' name='" . $itemName . "_config" . "' id='".$itemName."_config"."' />";
            										$newField .= "</div>";
            									echo $newField;    								        								    
            								}
            							//--
            						?>
                                </div>
                                <input style="margin-left: 5px;" class="button_blue" type="button" value="<?php echo @$language->field_permissions ?>" onclick="stage.loadPermissionsLightbox('5', '<?php echo @$_POST["table_name"].",".$infoColumbs[$i]; ?>', 'Permissions: <?php echo @$_POST["table_name"].", ".$infoColumbs[$i] ?>' )"/>
        					</td>
                        </tr>
                    <?php } ?>
                </table>
             </div>
        <!-- -->
        <!-- Other info -->
            <input type="hidden" name="table_name" id="table_name" value="<?php echo $_POST["table_name"]; ?>" />
            <input type="hidden" name="id" id="id" value="<?php echo @$_POST["id"]; ?>" />
            <input type="hidden" name="option_panel" id="option_panel" value="" />
            <input type="hidden" name="include_file" id="include_file" value="" />
            <input type="hidden" name="tabs" id="tabs" value=""  />
        <!-- -->
    </form>
    <!-- Buttons -->
        <div style="margin: 20px 0px 20px; text-align: center;">
            <input class="button_gray" type="button" value="<?php echo @$language->add_option_panel ?>" onclick="edit_table.addOptionPanel()"/>
            <input class="button_gray" type="button" value="<?php echo @$language->add_include_file ?>" onclick="edit_table.addIncludeFile()"/>
            <input class="button_gray" type="button" value="<?php echo @$language->add_tab ?>" onclick="edit_table.addTab()" />
            
            <input class="button_gray" type="button" value="<?php echo @$language->table_permissions ?>" onclick="stage.loadPermissionsLightbox('2', '<?php echo @$_POST["table_name"] ?>', 'Permissions: <?php echo @$_POST["table_name"] ?>' )"/>
        </div>
    <!-- -->
    <!-- Option Panel -->
        <div class="option_panel" style="display: none;">
            <div class="section" style="margin: 20px 0px;"><div></div><span><?php echo @$language->option_panel_configuration ?></span></div>
            <div style="margin-top: 5px;">
                <ul>
                </ul>
            </div>
        </div>
    <!-- -->
    <!-- Import file -->
        <div class="include_file_pane" style="display: none;">
            <div class="section" style="margin: 20px 0px;"><div></div><span><?php echo @$language->include_file_configuration ?></span></div>
            <div style="margin-top: 5px;">
                <ul>
                </ul>
            </div>
        </div>
    <!-- -->
    <!-- Tab groups -->
        <div class="tabs" style="display: none;">
            <div class="section" style="margin: 20px 0px;"><div></div><span><?php echo @$language->tabs_configuration ?></span></div>
            <div style="margin-top: 5px;">
                <ul>
                </ul>
            </div>
        </div>
    <!-- -->
</div>
<div class="assets" style="display: none;">
    <!-- option pane template -->
        <li class="item option_panel_item" >
            <span><?php echo @$language->image ?></span>
            <input class="text_field image_src" id="image_src" style="width: 150px;" />    
            <span><?php echo @$language->url ?></span>
            <input class="url" id="url" style="width: 150px;" />
            <span><?php echo @$language->send_vars ?></span>
            <input class="var_name" id="var_name" style="width: 150px;" placeholder="var1=id&var2=column" />
            <span><?php echo @$language->tooltip ?></span>
            <input class="tooltip" id="tooltip" />
            <a onclick="stage.loadLightbox('<?php echo $language->language ?>', 'components/language_manager/html/files.php', '&width=80%&height=90%&mode=lightbox')"><img class="tooltip" src="templates/default/images/template/language_manager_16_black.png" title="<?php echo @$language->tooltip_langauge_manager ?>" /></a>
            <a class="button_delete"><img src="templates/default/images/template/close.png" /></a>
        </li>
    <!-- -->
    <!-- import file template -->
        <li class="item item_include_file">
            <span><?php echo @$language->file_path ?> </span>
            <img style="margin-right: 5px;" title="<?php echo @$language->file_path_message ?>" class="tooltip" src="templates/default/images/template/icon_help_12.png" />
            <input name="path" id="path" class="path" value="" />
            <span><?php echo @$language->include_to ?> </span>
            <select class="add_to" id="add_to" name="add_to" onchange="">
                <option value="list"><?php echo @$language->list ?></option>
                <option value="form"><?php echo @$language->form ?></option>
            </select>
            <span><?php echo @$language->position ?> </span>
            <select class="position" id="position" name="position">
                <option value="top"><?php echo @$language->top ?></option>
                <option value="end"><?php echo @$language->end ?></option>
                <option value="before_to_table"><?php echo @$language->before_to_table ?></option>
                <option value="after_to_table "><?php echo @$language->after_to_table ?></option>
            </select>
            <a class="button_delete"><img src="templates/default/images/template/close.png" /></a>
        </li>
    <!-- -->
    <!-- tab template -->
        <style> .item_tab *{ vertical-align: top !important; position: relative; }</style>
        <li class="item item_tab" >
            <span style="top: 7px;" ><?php echo @$language->name ?> </span>
            <input class="tab_name" value="" style="width: 100px;" style="top: 0px;"/>
            <span style="top: 7px;" ><?php echo @$language->fields ?> </span>
            <select class="tab_fields" multiple="multiple" size="7" style="top: 0px; width: 200px;" >
                <?php forEach($infoColumbs as $column){ ?>
                    <option value="<?php echo $column ?>"><?php echo $column ?></option>
                <?php } ?>
            </select>
            <a class="button_delete"><img src="templates/default/images/template/close.png" /></a>
        </li>
    <!-- -->
</div>