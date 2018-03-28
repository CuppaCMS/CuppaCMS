<?php
	class MenuManager{
		private static $instance;
		private $database;
		private $user_validate_permissions = true;
        private $language;
        private $li_item = 0;
        public $dataBase_road_path = false; // If can't build the Road Path, try to get it from the dataBase.
        private $include_language = false;
        private $include_country = false;
		
		private function __construct(){
			$this->database = DataBase::getInstance();
		}
		public static function getInstance() {
			if (self::$instance == NULL) { self::$instance = new MenuManager(); } 
			return self::$instance;
		}
        // Return Array
            public function get($menu_name, $condition = "", $language_file_name = "administrator", $init_id_reference = 0, $include_init_reference = false){
                $cuppa = Cuppa::getInstance();
                if(is_string($language_file_name)){
                    $this->language = LanguageManager::getInstance()->load($language_file_name);
                }else{ 
                    $this->language = $language_file_name; 
                }
                $configuration = new Configuration();
                $sql = 	"SELECT mi.*, '' as table_name, m.name AS menu_name, mit.name AS menu_item_type
						FROM ".$configuration->table_prefix."menu_items AS mi 
						JOIN ".$configuration->table_prefix."menus AS m
                        JOIN ".$configuration->table_prefix."menu_item_type AS mit ON mi.menu_item_type_id = mit.id
						WHERE mi.menus_id = m.id AND m.name = '$menu_name' AND mi.enabled = 1 ";
                if($condition) $sql.= " AND ".$condition;
                $sql.= " AND (m.language = '' OR m.language = '". @$cuppa->language->getCurrentLanguage()."') ";
                $sql.= " AND (mi.language = '' OR mi.language = '". @$cuppa->language->getCurrentLanguage()."') ";
                $sql.= " ORDER BY mi.order ASC ";
                $data = $this->database->sql($sql); 
                if(!is_array($data)) return null;
                $data = $cuppa->utils->createTree($data, "id", "parent_id", "alias", true, $init_id_reference, $include_init_reference);
                $finish_array = array();
                for($i = 0; $i < count($data); $i++){
                    if($this->user_validate_permissions){
                        if($cuppa->permissions->getValue("3", $data[$i]["id"], 1)){
                            $data[$i]["real_alias"] = $data[$i]["alias"];
                            $data[$i]["title"] = @$cuppa->language->getValue(@$data[$i]["title"], $this->language);
                            $data[$i]["alias"] = @$cuppa->language->getValue(@$data[$i]["alias"], $this->language, true);
                            //++ Personal information
                                //++ Set params by Menu_item_type
                                    if($data[$i]["menu_item_type"] == "None"){
                                        $data[$i]["url"] = "";
                                        $data[$i]["target"] = "";
                                    }else if($data[$i]["menu_item_type"] == "URL"){
                                        $url = $cuppa->langValue($params->url, $this->language);
                                        $params = json_decode($data[$i]["menu_item_params"]);
                                        $data[$i]["url"] = $url;
                                        $data[$i]["target"] = $params->target;
                                    }else if($data[$i]["menu_item_type_id"] == "7"){
                                        $json_value = json_decode( $data[$i]["menu_item_params"] );
                                        $info = $this->getInfo($json_value->other_menu, $json_value->other_menu_item, $this->language, true);
                                        $url = $info->path;
                                        $data[$i]["url"] = $url;
                                        $data[$i]["target"] = "_self";
                                    }else{
                                        $data[$i]["url"] = @$cuppa->dataBase->getRoadPath($cuppa->configuration->table_prefix."menu_items", $data[$i]["id"], "id","parent_id", "alias", true);
                                        $data[$i]["target"] = "_self";
                                    }
                                    // translate
                                    $url_explode = explode("/", $data[$i]["url"]);
                                    for($z = 0; $z < count($url_explode); $z++){
                                        $url_explode[$z] = @$cuppa->language->getValue(@$url_explode[$z], $this->language, true);
                                    }
                                    $data[$i]["url"] = join("/", $url_explode);
                                //--
                            //--
                            array_push($finish_array, $data[$i]);
                        }
                    }else{
                        $data[$i]["real_alias"] = $data[$i]["alias"];
                        $data[$i]["title"] = $cuppa->language->getValue( @$data[$i]["title"], $this->language);
                        $data[$i]["alias"] = $cuppa->language->getValue(@$data[$i]["alias"], $this->language, true);
                        //++ Personal information
                            //++ Set params by Menu_item_type
                                if($data[$i]["menu_item_type"] == "URL"){
                                    $params = json_decode($data[$i]["menu_item_params"]);
                                    $data[$i]["url"] = $params->url;
                                    $data[$i]["target"] = $params->target;
                                }else{
                                    $data[$i]["url"] = $data[$i]["alias"];
                                    $data[$i]["target"] = "_self";
                                }
                            //--
                        //--
                        array_push($finish_array, $data[$i]);
                    }
                }
                if(!@$finish_array[0]) return null;
                return $finish_array;
            }
        // Get all info about a item
        // $menu = id,name
        // $reference = id, alias
            public function getInfo($menu, $reference, $language_file_reference = "administrator", $object_return = true){
                if(!$reference) return null;
                $cuppa = Cuppa::getInstance();
                if(is_string($language_file_reference) ){ $language_file_reference = $cuppa->language->load($language_file_reference); }
                
                if(is_numeric($menu)) $menu_data = $cuppa->dataBase->getRow("{$cuppa->configuration->table_prefix}menus", "id = '".$menu."'", true);
                else $menu_data = $cuppa->dataBase->getRow("{$cuppa->configuration->table_prefix}menus", "name = '".$menu."'", true);
                
                if(is_numeric($reference)){ 
                    $condition = "menus_id = '".@$menu_data->id."' AND id = '".$reference."' AND (language = '' OR language = '".$cuppa->language->current()."')";
                    $info = $cuppa->dataBase->getRow("{$cuppa->configuration->table_prefix}menu_items", $condition, false);
                }else{
                    $condition = "menus_id = '".@$menu_data->id."' AND alias = '".$reference."' AND (language = '' OR language = '".$cuppa->language->current()."')";
                    $info = $cuppa->dataBase->getRow("{$cuppa->configuration->table_prefix}menu_items", $condition, false);
                }
                if($info){
                    $info["real_alias"] = $info["alias"];
                    $info["title"] = @$cuppa->language->getValue(@$info["title"], $language_file_reference);
                    $info["alias"] = @$cuppa->language->getValue(@$info["alias"], $language_file_reference, true);  
                    $info["title_tab"] = @$cuppa->language->getValue(@$info["title_tab"], $language_file_reference); 
                    $info["path"] = @$cuppa->dataBase->getRoadPath($cuppa->configuration->table_prefix."menu_items", $info["id"], "id","parent_id", "alias", true, $language_file_reference, true);
                }
                if($object_return && $info) $info = (object) $info;
                return @$info;
            }
        // Get Childrens
            public function getChildrens($menu, $reference, $language_file_reference = "administrator", $object_return = true){
                $cuppa = Cuppa::getInstance();
                if(is_string($language_file_reference) ){ $language_file_reference = $cuppa->language->load($language_file_reference); }
                $parent_info = $this->getInfo($menu, $reference, true);
                $condition = "parent_id = '".$parent_info->id."' AND enabled = 1 AND (language = '' OR language = '".$cuppa->language->current()."')";
                $info = $cuppa->dataBase->getList("{$cuppa->configuration->table_prefix}menu_items", $condition, "", "`order` ASC", false);
                if($info){
                    for($i = 0; $i < count($info); $i++){
                        $info[$i]["real_alias"] = $info[$i]["alias"];
                        $info[$i]["title"] = @$cuppa->language->getValue(@$info[$i]["title"], $language_file_reference);
                        $info[$i]["alias"] = @$cuppa->language->getValue(@$info[$i]["alias"], $language_file_reference, true);  
                        $info[$i]["title_tab"] = @$cuppa->language->getValue(@$info[$i]["title_tab"], $language_file_reference); 
                        $info[$i]["path"] = @$cuppa->dataBase->getRoadPath($cuppa->configuration->table_prefix."menu_items", $info[$i]["id"], "id","parent_id", "alias", true, $language_file_reference, true);
                        if($object_return) $info[$i] = (object) $info[$i];
                    }
                }
                return @$info;
            }    
        // Return list ul li
			public function getList($menu_name, $language_file_name = "administrator", $include_language = false, $include_country = false, $item_start = 0){
                $this->include_language = $include_language;
                $this->include_country = $include_country;
                $cuppa = Cuppa::getInstance();
                if(is_string($language_file_name)){
                    $this->language = LanguageManager::getInstance()->load($language_file_name);
                }else{ 
                    $this->language = $language_file_name; 
                }
				$configuration = new Configuration();
				$sql = 	"SELECT mi.*, '' as table_name, m.name AS menu_name, mit.name AS menu_item_type
						FROM ".$configuration->table_prefix."menu_items AS mi 
						JOIN ".$configuration->table_prefix."menus AS m 
                        JOIN ".$configuration->table_prefix."menu_item_type AS mit ON mi.menu_item_type_id = mit.id
						WHERE mi.menus_id = m.id AND m.name = '$menu_name' AND mi.enabled = 1 ";     
                $sql.= " AND (m.language = '' OR m.language = '". @$cuppa->language->getCurrentLanguage()."') ";
                $sql.= " AND (mi.language = '' OR mi.language = '". @$cuppa->language->getCurrentLanguage()."') ";
                $sql.= " ORDER BY mi.order ASC ";
				$result = $this->database->sql($sql);
				if($result != 0 && $result != 1){
				    $menu = $this->createMenu($result, $item_start);
                    $no_menu = '<ul class="cuppa_menu_list" id="list_'.$menu_name.'" ><li class="vertical_divider vertical_last_divider"><div class="menu_divider menu_divider_10"></div></li></ul>';
                    if($menu != $no_menu) return $menu;
                    else return null;
				}
				return null;
			}
            
			private function createMenu($data, $item_start = 0){
                $menu_id = $data[0]["menus_id"];
                $cuppa = Cuppa::getInstance();
				$field = '<ul class="cuppa_menu_list" id="list_'.$menu_id.'" >';
                $parent_items = $item_start;
				for($i = 0; $i < count($data); $i++){
					if($data[$i]["parent_id"] == $item_start){
						// Permiisions validate
    						if($this->user_validate_permissions){
    							if( $cuppa->permissions->getValue("3", $data[$i]["id"], "1") ){
                                    $class_vertical_divider = ($parent_items == 0) ? "vertical_first_divider" : "vertical_divider_".$parent_items;
    								$field .= '<li class="vertical_divider '.$class_vertical_divider.'" ><div style="display:block" class="menu_divider menu_divider_'.$i.'"></div></li>';
    								$field .= $this->getItemType($i, $data, true);
    							}
    						}else{
    							$field .= '<li class="vertical_divider vertical_divider_'.$i.'" ><div style="display:block" class="menu_divider menu_divider_'.$i.'"></div></li>';
    							$field .= $this->getItemType($i, $data, true);
    						}
                        $parent_items++;
					}
				}
				$field .= '<li class="vertical_divider vertical_last_divider"><div class="menu_divider menu_divider_'.$i.'"></div></li>';
				$field .= '</ul>';
				return $field;
			}
			private function getSubMenu($id, $data){
                $cuppa = Cuppa::getInstance();
				$countItems = 0;
				for($j = 0; $j < count($data); $j++){
					// Permiisions validate          
    					if($this->user_validate_permissions){
    						if($cuppa->permissions->getValue("3", $data[$j]["id"], "1")){
    							if($data[$j]["parent_id"] == $id) $countItems++;
    						}
    					}else{
    						if($data[$j]["parent_id"] == $id) $countItems++;
    					}
				}
				$field = '<ul>';
				$itemAdded = 0;
				for($j = 0; $j < count($data); $j++){
					// Permiisions validate                                                                  
    					if($this->user_validate_permissions){
    						if($cuppa->permissions->getValue("3", $data[$j]["id"], "1")){
    							if($data[$j]["parent_id"] == $id){
    								$field .= $this->getItemType($j, $data, false);
    								$itemAdded++;
    								if($itemAdded < $countItems) $field .= '<li class="horizontal_divider horizontal_divider_'.$j.'"><div style="display:block" class="menu_divider_horizontal menu_divider_horizontal'.$data[$j]["id"].'"></div></li>';
    							}
    						}
    					}else{
    						if($data[$j]["parent_id"] == $id){
    							$field .= $this->getItemType($j, $data, false);
    							$itemAdded++;
    							if($itemAdded < $countItems) $field .= '<li class="vertical_divider vertical_divider_'.$j.'"><div style="display:block" class="menu_divider_horizontal menu_divider_horizontal'.$data[$j]["id"].'"></div></li>';
    						}
    					}
				}
				$field .= '</ul>';
				if($field != "<ul></ul>") return $field;
				else return 0;
			}
			private function getItemType($k, $data, $principal_menu = true){
                $cuppa = Cuppa::getInstance();
                $image_path = ($cuppa->getPath()) ? $cuppa->getPath() : "administrator/";
                $this->li_item++;
				$json_value = json_decode($data[$k]["menu_item_params"]);
				$class = "menu_button"; if(!$principal_menu) $class = "sub_menu_item";
                $subMenu = $this->getSubMenu($data[$k]["id"], $data); 
                
				$field = '<li class="item item_'.$data[$k]["alias"];
                if($subMenu) $field .= " arrow";
                $field .= ' " >';                   
                    $title = @$cuppa->language->getValue(@$data[$k]["title"], $this->language);
					if($data[$k]["menu_item_type_id"] == "1"){
						$field .= '<a style="display:block; cursor:default" class="'.$class.' '.$class."_".$data[$k]["id"].' no_link">';
                        if(@$data[$k]["image"]) $field .= '<img src="'.$image_path.@$data[$k]["image"].'">';
                        $field .= '<span class="text">'.$title.'</span>';
                        $field .= '</a>';
					}else if($data[$k]["menu_item_type_id"] == "2"){
						$field .= '<a title="'.$title.'" href="component/table_manager/view/'.@$json_value->table_name;
                        if(@$json_value->defined_task) $field .= "/".@$json_value->defined_task;
                        $field .= '" style="cursor:pointer; display:block" class="'.$class.' '.$class."_".$data[$k]["id"].'">';
                        if(@$data[$k]["image"]) $field .= '<img src="'.$image_path.@$data[$k]["image"].'">';
                        $field .= '<span class="text">'.$title.'</span>';
                        $field .= '</a>'; 
                        if($this->include_language && $this->include_country && strpos($field, 'href="http') === false ) $field = str_replace('href="', 'href="'.$cuppa->language->current()."-".$cuppa->country->current().'/', $field);
                        else if($this->include_language && strpos($field, 'href="http') === false ) $field = str_replace('href="', 'href="'.$cuppa->language->current().'/', $field);
                        else if($this->include_country && strpos($field, 'href="http') === false ) $field = str_replace('href="', 'href="'.$cuppa->country->current().'/', $field);
					}else if($data[$k]["menu_item_type_id"] == "3"){
						$field .= '<a title="'.$title.'" href="component/'.@$json_value->component_name;
                        $field .= '" style="cursor:pointer; display:block" class="'.$class.' '.$class."_".$data[$k]["id"].'">';
                        if(@$data[$k]["image"]) $field .= '<img src="'.$image_path.@$data[$k]["image"].'">';
                        $field .= '<span class="text">'.$title.'</span>';
                        $field .= '</a>';
                        if($this->include_language && $this->include_country && strpos($field, 'href="http') === false ) $field = str_replace('href="', 'href="'.$cuppa->language->current()."-".$cuppa->country->current().'/', $field);
                        else if($this->include_language && strpos($field, 'href="http') === false ) $field = str_replace('href="', 'href="'.$cuppa->language->current().'/', $field);
                        else if($this->include_country && strpos($field, 'href="http') === false ) $field = str_replace('href="', 'href="'.$cuppa->country->current().'/', $field);
					}else if($data[$k]["menu_item_type_id"] == "4"){
                        //$url = $cuppa->langValue($json_value->url, $this->language);
                        $url = $json_value->url;
						if(@$json_value->target == "iframe"){
							$field .= '<a title="'.$title.'" onclick="cuppa.blockade({\'zIndex\':\'9\', \'autoDeleteContent\':\'#new_content\' }); cuppa.setContent({\'url\':\'alerts/alertIFrame.php\', \'data\':\'url='.@$url.'&description='.$title.'\' })"  style="cursor:pointer; display:block" class="'.$class.' '.$class."_".$data[$k]["id"].'">';
                            if(@$data[$k]["image"]) $field .= '<img src="'.$image_path.@$data[$k]["image"].'">';
                            $field .= '<span class="text">'.$title.'</span>';
                            $field .= '</a>';
						}else{
							$field .= '<a title="'.$title.'" target="'.@$json_value->target.'" href="'.@$url.'" style="cursor:pointer; display:block" class="'.$class.' '.$class."_".$data[$k]["id"].'">';
                            if(@$data[$k]["image"]) $field .= '<img src="'.$image_path.@$data[$k]["image"].'">';
                            $field .= '<span class="text">'.$title.'</span>';
                            $field .= '</a>';
						}
					}else if($data[$k]["menu_item_type_id"] == "5"){
                        $field .= '<a title="'.$title.'" onclick="'.@$json_value->js_function.'" style="cursor:pointer; display:block" class="'.$class.' '.$class."_".$data[$k]["id"].'">';
                        if(@$data[$k]["image"]) $field .= '<img src="'.$image_path.@$data[$k]["image"].'">';
                        $field .= '<span class="text">'.$title.'</span>';
                        $field .= '</a>'; 
					}else if($data[$k]["menu_item_type_id"] == "6"){
					    $url = $this->database->getRoadPath($cuppa->configuration->table_prefix."menu_items", $data[$k]["id"], "id", "parent_id", "alias", true, $this->language, true);
                        $field .= '<a title="'.$title.'" href="'.@$url.'" style="cursor:pointer; display:block" class="'.$class.' '.$class."_".$data[$k]["id"].'">';
                        if(@$data[$k]["image"]) $field .= '<img src="'.$image_path.@$data[$k]["image"].'">';
                        $field .= '<span class="text">'.$title.'</span>';
                        $field .= '</a>'; 
                        if($this->include_language && $this->include_country && strpos($field, 'href="http') === false ) $field = str_replace('href="', 'href="'.$cuppa->language->current()."-".$cuppa->country->current().'/', $field);
                        else if($this->include_language && strpos($field, 'href="http') === false ) $field = str_replace('href="', 'href="'.$cuppa->language->current().'/', $field);
                        else if($this->include_country && strpos($field, 'href="http') === false ) $field = str_replace('href="', 'href="'.$cuppa->country->current().'/', $field);
					}else if($data[$k]["menu_item_type_id"] == "7"){
					   $info = $this->getInfo($json_value->other_menu, $json_value->other_menu_item, $this->language, true);
                       $url = $info->path;
                       $field .= '<a title="'.$title.'" href="'.@$url.'" style="cursor:pointer; display:block" class="'.$class.' '.$class."_".$data[$k]["id"].'">';
                       if(@$data[$k]["image"]) $field .= '<img src="'.$image_path.@$data[$k]["image"].'">';
                       $field .= '<span class="text">'.$title.'</span>';
                       $field .= '</a>'; 
                       if($this->include_language && $this->include_country && strpos($field, 'href="http') === false ) $field = str_replace('href="', 'href="'.$cuppa->language->current()."-".$cuppa->country->current().'/', $field);
                       else if($this->include_language && strpos($field, 'href="http') === false ) $field = str_replace('href="', 'href="'.$cuppa->language->current().'/', $field);
                       else if($this->include_country && strpos($field, 'href="http') === false ) $field = str_replace('href="', 'href="'.$cuppa->country->current().'/', $field);
					}
					if($subMenu) $field .= $subMenu;
				$field .= '</li>';
				return $field;
			}
	}
?>