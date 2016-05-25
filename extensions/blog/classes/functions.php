<?php
	//++ Functions
        function loadArticles(){
            include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["web_document_path"]."administrator/classes/Cuppa.php";
            $cuppa = Cuppa::getInstance();
            $data = $cuppa->jsonDecode($cuppa->POST("info"));
            $data->condition = $cuppa->jsonDecode($data->condition);
            $result = $cuppa->dataBase->getList("ex_blog_articles", $data->condition, $data->limit, "date DESC, id DESC", true);
            if($result){ forEach($result as $index => $item){
                $item->user = $cuppa->dataBase->getRow("cu_users", "id = ".$item->user, true);
                $cat_id = json_decode($item->categories); $cat_id = @$cat_id[0];
                $item->cat_alias = $cuppa->dataBase->getColumn("ex_blog_categories", "alias", "id = ".$cat_id);
            }}
            echo $cuppa->jsonEncode($result);
        }
	//--
	//++ Handler
		$nameFunction = htmlspecialchars(@$_POST["function"]);
		if($nameFunction == ""){ echo "function name no found"; exit();}
		@$nameFunction();
	//--
?>