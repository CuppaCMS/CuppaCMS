<?php
	//++ Functions
		function save(){
            include_once(realpath(__DIR__ . '/../../..')."/classes/Cuppa.php");
            $cuppa = Cuppa::getInstance(); $cuppa->user->valid("admin_login");
            // Get data
                $data["id"] = "'".$cuppa->dataBase->escape($_POST["id"])."'";
    			$data["title"] = "'".$cuppa->dataBase->escape($_POST["title_field"])."'";
                $data["alias"] = "'".$cuppa->dataBase->escape($_POST["alias_field"])."'";
                $data["title_tab"] = "'".$cuppa->dataBase->escape($_POST["title_tab_field"])."'";
                $data["image"] = "'".$cuppa->dataBase->escape($_POST["image_field"])."'";
                $data["view"] = "'".$cuppa->dataBase->escape($_POST["view_field"])."'";
                $data["description"] = "'".$cuppa->dataBase->escape($_POST["description_field"])."'";
    			$data["menu_item_type_id"] = "'".$cuppa->dataBase->escape($_POST["menu_item_type_id"])."'";
    			$data["menu_item_params"] = "'".str_replace("\\", "",$_POST["menu_item_params"])."'";
    			$data["parent_id"] = ($_POST["parent_field"]) ? "'".$cuppa->dataBase->escape($_POST["parent_field"])."'" : '0';
    			$data["menus_id"] = "'".$cuppa->dataBase->escape($_POST["menu_field"])."'";
                $data["language"] = "'".$cuppa->dataBase->escape($_POST["language_field"])."'";
    			$data["enabled"] = "'".$cuppa->dataBase->escape($_POST["enabled_field"])."'";
                $data["tracking_codes"] = "'".$cuppa->dataBase->escape($_POST["tracking_codes"])."'";
                $data["default_page"] = "'".$cuppa->dataBase->escape($_POST["default_page_field"])."'";
                $data["error_page"] = "'".$cuppa->dataBase->escape($_POST["error_page_field"])."'";
            // validate neworder
				$itemsGroup = $cuppa->dataBase->getList("".$cuppa->configuration->table_prefix."menu_items", "parent_id = ".$data["parent_id"], "","`order` ASC");
				$order = $itemsGroup[count($itemsGroup)-1]["order"] + 1;
					$newItem = true;
					if($data["id"] != "'0'"){
						$currentItem = $cuppa->dataBase->getRow($cuppa->configuration->table_prefix."menu_items", "id=".$data["id"]);
						if($currentItem["parent_id"] == $data["parent_id"]) $newItem = false;
					}
                if($newItem) $data["order"] = "'".$order."'";
				$result = $cuppa->dataBase->add("".$cuppa->configuration->table_prefix."menu_items", $data, true, true);
            // Order old rest items
                $data = @$cuppa->dataBase->getList($cuppa->configuration->table_prefix."menu_items", "parent_id = '".$_POST["parent_field"] ."' AND menus_id = '".$_REQUEST["menu_filter"]."'", "","`order` ASC");
                for($j = 0; $j < count($data); $j++){
                    $updateData = array();
                    $updateData["order"] = $j+1;
                    $cuppa->dataBase->update($cuppa->configuration->table_prefix."menu_items", $updateData, "id='".$data[$j]["id"]."'");
                }
            echo @$result->id;
        }
	//--
	//++ Handler
		$nameFunction = htmlspecialchars($_POST["function"]);
		if($nameFunction == ""){ echo "function name no found"; exit();}
		$nameFunction();
	//--
?>