<?php 
    include_once(realpath(__DIR__ . '/../..')."/classes/Cuppa.php");
    $cuppa = Cuppa::getInstance(); $cuppa->user->valid("admin_login");
    $language = $cuppa->language->load();
    $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    //++ Recover GET DATA
        if(@$_POST["path"]){
            $getData = @$cuppa->utils->getUrlVars($cuppa->POST("path"));
            $_REQUEST = array_merge((array) $getData, $_REQUEST);
        }
    //--
    if(@$path[2]){
        if(@$_REQUEST["task"] == "edit" && $_REQUEST["id"] == "profile"){
            $_REQUEST["id"] = $_POST["id"] =  $cuppa->user->getVar("id");
            include "html/edit_admin_table.php";
            return;
        }else if(@$_POST["task"] == "delete"){
            $view = $cuppa->POST("view");
            $_POST["ids"] = $cuppa->utils->jsonDecode($_POST["ids"]);
            $tableData = $cuppa->dataBase->getList($cuppa->configuration->table_prefix."tables", "table_name = '".$view."'");
            $field_types = @json_decode(base64_decode($tableData[0]["params"]));
            if(!@$field_types) $field_types = @json_decode($tableData[0]["params"]);
            for($i = 0; $i < count($_POST["ids"]); $i++){
              $cuppa->dataBase->delete($view, $field_types->primary_key."='".$_POST["ids"][$i]."'");
              //++ Update table log
                  $data = new stdClass(); 
                  $data->user_id_update = "'".$cuppa->user->getVar("id")."'";
                  $data->deleted = "1";
                  $data->date_update = "NOW()";
                  $cuppa->dataBase->update($cuppa->configuration->table_prefix."tables_log", $data, "reference_id ='".$_POST["ids"][$i]."' AND table_name = '".$view."'");
              //--
            }
            echo "<script> stage.toast('".@$language->information_has_been_deleted."'); </script>";
        }else if(@$_POST["task"] == "duplicate"){
            $view = $cuppa->POST("view");
            $id = $_POST["id"];
            $key = $cuppa->dataBase->getKeyFromTable($view);
            $result = $cuppa->dataBase->duplicate($view, $key." = '".$id."'");
            if($result) echo "<script> stage.toast('".@$language->information_has_been_duplicated."'); </script>";
        }
        include "html/list_admin_table.php";
    }else{
        if(@$_POST["task"] == "delete"){
            $_POST["ids"] = $cuppa->utils->jsonDecode($_POST["ids"]);
            for($i = 0; $i < count($_POST["ids"]); $i++){ 
                $info = $cuppa->dataBase->getRow($cuppa->configuration->table_prefix."tables", "id='".$_POST["ids"][$i]."'", true);
                $cuppa->dataBase->delete($cuppa->configuration->table_prefix."tables_log", "table_name='".$info->table_name."'");
                $cuppa->dataBase->delete($cuppa->configuration->table_prefix."tables", "id='".$info->id."'");
            }
        }
        include "html/list_table_manager.php";
    }
?>