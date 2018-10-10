<?php 
    include_once(realpath(__DIR__ . '/../..')."/classes/Cuppa.php");
    $cuppa = Cuppa::getInstance(); $cuppa->user->valid("admin_login");
    $language = $cuppa->language->load();
    if(@$_POST["path"]){
        $getData = @$cuppa->utils->getUrlVars($cuppa->POST("path"));
        $_REQUEST = array_merge((array) $getData, $_REQUEST);
    }
    if(@$_POST["task"] == "moveTop"){
        if(!@$_REQUEST["menu_filter"]) $_REQUEST["menu_filter"] = 1;
        $id = $cuppa->POST("id");
        $item = $cuppa->dataBase->getRow($cuppa->configuration->table_prefix."menu_items", "id = '".$id."'", true); 
		$data = $cuppa->dataBase->getList($cuppa->configuration->table_prefix."menu_items", "parent_id = '".$item->parent_id ."' AND menus_id = '".$_REQUEST["menu_filter"]."' ", "","`order` ASC");
        for($i = 0; $i < count($data); $i++){ if($data[$i]["id"] == $id) break; }
        //++ Update data
    		$updateData = array();
    		$updateData["order"] = $data[$i-1]["order"];
    		if(!$updateData["order"]) $updateData["order"] = "1";
    		$cuppa->dataBase->update($cuppa->configuration->table_prefix."menu_items", $updateData, "id='".$data[$i]["id"]."'");
    		$updateData = array();
    		$updateData["order"] = $data[$i]["order"];
    		if(!$updateData["order"]) $updateData["order"] = "1";
    		$cuppa->dataBase->update($cuppa->configuration->table_prefix."menu_items", $updateData, "id='".$data[$i-1]["id"]."'");
		//--
        echo "<script> stage.toast('".@$language->information_has_been_moved."', 'info'); </script>";
        if( !@$_REQUEST["menu_filter"] || @$_REQUEST["menu_filter"] == "1" ) echo '<script>menu.update();</script>';
        else if(@$_REQUEST["menu_filter"] == "2") echo '<script>menu.update("admin_settings",".menu_settings .buttons");</script>';
   }else if(@$_POST["task"] == "delete"){
        $ids = $cuppa->jsonDecode($_POST["ids"]);
        for($i = 0; $i < count($ids); $i++){
		    // Get info
		        $item = $cuppa->dataBase->getRow($cuppa->configuration->table_prefix."menu_items", "id = '".$ids[$i]."'", true);
            // delete
                $cuppa->dataBase->delete($cuppa->configuration->table_prefix."menu_items", "id='".$item->id."'");
                $cuppa->dataBase->delete($cuppa->configuration->table_prefix."menu_items_extra_data", "reference='".$item->id."'");
            // Order rest items
                $data = $cuppa->dataBase->getList($cuppa->configuration->table_prefix."menu_items", "parent_id = '".$item->parent_id ."'", "","`order` ASC");
                for($j = 0; $j < count($data); $j++){
                    $updateData = array();
                    $updateData["order"] = $j+1;
                    $cuppa->dataBase->update($cuppa->configuration->table_prefix."menu_items", $updateData, "id='".$data[$j]["id"]."'");
                }
        }
        echo "<script> stage.toast('".@$language->information_has_been_deleted."'); </script>";
        if( !@$_REQUEST["menu_filter"] || @$_REQUEST["menu_filter"] == "1" ) echo '<script>menu.update();</script>';
        else if(@$_REQUEST["menu_filter"] == "2") echo '<script>menu.update("admin_settings",".menu_settings .buttons");</script>';
    }
    include "html/list.php";
?>