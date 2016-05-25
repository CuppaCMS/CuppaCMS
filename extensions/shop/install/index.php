<?php
    include_once "../../../classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $administrator_path = $cuppa->utils->getStringPart(__DIR__, "administrator\\", true);
    $cuppa->setDocumentPath($administrator_path);
    $cuppa->init();
    $token = $cuppa->security->token;
    // Create image folder
        @mkdir($cuppa->getDocumentPath()."/media/shop");
    //++ Script 1 -  create tables 
        $sql = file_get_contents('tables.sql');
    	$sql_array = explode(";", $sql);
    	for($i = 0; $i < count($sql_array); $i++){
    		if(trim($sql_array[$i])){
    			$result = $cuppa->dataBase->personalSql($sql_array[$i], $token);
                echo "<b>SQL:</b> ".$cuppa->utils->cutText("", $sql_array[$i], 50, "...");
                echo " | Result:</b> ".$result;
                echo "<br />";
            }
    	}
     //--
     //++ Script 2 - insert {cu_tables}
        $sql = file_get_contents('cu_tables.sql');
    	$sql_array = explode(";", $sql);
        //++ set id = 0
            for($i = 0; $i < count($sql_array); $i++){
                $current_id = explode("VALUES (", @$sql_array[$i]);
                $current_id = explode(",", @$current_id[1]);
                $current_id = $current_id[0];
                if($current_id){
                    for($j = 0; $j < count($sql_array); $j++){
                        $sql_array[$j] = str_replace($current_id.",", "0,", $sql_array[$j]);
                    }
                }
            }
        //--
        //++ insert on table: cu_tables
        	for($i = 0; $i < count($sql_array); $i++){
        		if(trim($sql_array[$i])){
        			$result = $cuppa->dataBase->personalSql($sql_array[$i], $token);
                    echo "<b>SQL:</b> ".$cuppa->utils->cutText("", $sql_array[$i], 50, "...");
                    echo " | Result:</b> ".$result;
                    echo "<br />";
                }
        	}
        //--
     //--
     //++ Script 3 -  permissions {cu_permissions_data}
        $sql = file_get_contents('cu_permissions_data.sql');
    	$sql_array = explode(";", $sql);
        //++ set id = 0
            for($i = 0; $i < count($sql_array); $i++){
                $current_id = explode("VALUES (", @$sql_array[$i]);
                $current_id = explode(",", @$current_id[1]);
                $current_id = $current_id[0];
                if($current_id){
                    for($j = 0; $j < count($sql_array); $j++){
                        $sql_array[$j] = str_replace($current_id.",", "0,", $sql_array[$j]);
                    }
                }
            }
        //--
        //++ insert on table: cu_permissions_data
        	for($i = 0; $i < count($sql_array); $i++){
        		if(trim($sql_array[$i])){
        			$result = $cuppa->dataBase->personalSql($sql_array[$i], $token);
                    echo "<b>SQL:</b> ".$cuppa->utils->cutText("", $sql_array[$i], 50, "...");
                    echo " | Result:</b> ".$result;
                    echo "<br />";
                }
        	}
         //--
     //--
     //++ Script 4 -  create menu instances and permission_data {cu_menu_items, cu_permissions_data}
        $sql = file_get_contents('cu_menu_items.sql');
    	$sql_array = explode(";", $sql);
        //++ parse values
            $id_start = 100000;
            for($i = 0; $i < count($sql_array); $i++){
                if(trim($sql_array[$i])){
                    $current_id = explode("VALUES (", @$sql_array[$i]);
                    $current_id = explode(",", @$current_id[1]);
                    $current_id = $current_id[0];
                    if($current_id){
                        for($j = 0; $j < count($sql_array); $j++){
                            $sql_array[$j] = str_replace($current_id.",", $id_start.",", $sql_array[$j]);
                        }
                    }
                    $id_start++;
                }
            }
        //--
        //++ set real values
          $id_start = $cuppa->dataBase->getColumnValueOnTable("cu_menu_items", $token, "", "", "id","", "1", "id DESC");
          $id_start++;  
              for($i = 0; $i < count($sql_array); $i++){
                if(trim($sql_array[$i])){
                    $current_id = explode("VALUES (", @$sql_array[$i]);
                    $current_id = explode(",", @$current_id[1]);
                    $current_id = $current_id[0];
                    if($current_id){
                        for($j = 0; $j < count($sql_array); $j++){
                            $sql_array[$j] = str_replace($current_id.",", $id_start.",", $sql_array[$j]);
                        }
                    }
                    $id_start++;
                }
            }
        //--
        //++ set correct order
            $last_menu_items = $cuppa->dataBase->getList("cu_menu_items", $token, "menus_id = 1 AND parent_id = 0", 1, "`order` DESC", true);
            $order = (float) $last_menu_items[0]->order; 
            $order++;
            $sql_array[0] = str_replace("6,", $order.",", $sql_array[0]);
        //--
        //++ insert on table: cu_menu_items
            for($i = 0; $i < count($sql_array); $i++){
        		if(trim($sql_array[$i])){
        			$result = $cuppa->dataBase->personalSql($sql_array[$i], $token);
                    echo "<b>SQL:</b> ".$cuppa->utils->cutText("", $sql_array[$i], 50, "...");
                    echo " | Result:</b> ".$result;
                    echo "<br />";
                }
        	}
        //--
     //--
?>