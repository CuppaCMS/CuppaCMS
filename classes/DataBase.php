<?php
	class DataBase{
		private static $instance;
		public $db = "";
		public $host = "";
		public $user = "";
		public $password = "";
		private $con;
		
		public function DataBase($db = "", $host = "", $user = "", $password = ""){
			if($db) $this->db = $db; if($host) $this->host = $host; if($user) $this->user = $user; if($password) $this->password = $password;
            if(!$this->db || !$this->user) return "No database data";
            $this->con = mysqli_connect($this->host, $this->user, $this->password, $this->db);
			if (!$this->con){ return "Error de Conexion: ".mysqli_error();}
			mysqli_query($this->con, "SET NAMES 'utf8'");
		}
		public static function getInstance($db = "", $host = "", $user = "", $password = "") {
			if (self::$instance == NULL) { self::$instance = new DataBase($db, $host, $user, $password); } 
			return self::$instance;
		}
		// Functions
            // This function try Insert, if can't do it, try Update.
            public function add($table, $data, $return_row = false, $object_return = false){
				$data = (array) $data;
                $query = $this->insert($table, $data, true);
                if(!$query){
                    $primary_key_name = $this->getKeyFromTable($table);
                    $condition = "`".@$primary_key_name."` = ".@$data[$primary_key_name];
                    if(@$primary_key_name && @$data[$primary_key_name]){
                        unset($data[$primary_key_name]);
                        $query = $this->update($table, $data, $condition, true);
                    }
                }
                if($query){
                    if($return_row){
                        if($object_return) return (object) $query;
                        else return $query;
                    }else{
                        return 1;
                    }
                }
				return 0;
			}
			public function add2($table, $data, $return_row = false, $object_return = false){
				$data = (array) $data;
				for($i = 0; $i < count($data); $i++){ unset($data[$i]); }
				$sql = "INSERT INTO $table ( `";
				$sql .= implode("` , `", array_keys($data));
				$sql .=  "` ) VALUES ( ";
				$sql .= implode(" , ", $data);
				$sql .= " ) ";
				$sql .= "ON DUPLICATE KEY UPDATE ";
				$current_key = 0;
				foreach ($data as $key => $value) {
                    if($current_key  == count($data)-1) $sql .= "`" . $key . "`=" . $value . "";
					else $sql .= "`" .$key . "`=" . $value . ", ";
					$current_key++;
				}
				$sql = trim($sql);
				$query = @mysqli_query($this->con, $sql);
				if($query){ 
					if($return_row){
                       $primary_key_name = $this->getKeyFromTable($table);
                       $id = $this->sql("SELECT LAST_INSERT_ID() as id", true); $id = @$id[0]->id;
                       if(!$id) $id = @$data[$primary_key_name];
                       if($id){
                            return $this->getRow($table, $primary_key_name." = ".$id, $object_return);
                       } else return 1;
					} else return 1;
				}
				return 0;
			}
			public function insert($table, $data, $return_row = false, $object_return = false){ 
				$data = (array) $data;
				for($i = 0; $i < count($data); $i++){ unset($data[$i]); }
				$sql = "INSERT INTO $table ( `";
				$sql .= implode("` , `", array_keys($data));
				$sql .=  "` ) VALUES ( ";
				$sql .= implode(" , ", $data);
				$sql .= " ) ";
				$sql = trim($sql);
				$query = @mysqli_query($this->con, $sql);
				if($query){
					if($return_row){
					   $id = mysqli_insert_id($this->con);
                       if($id){
                            $primary_key_name = $this->getKeyFromTable($table);
                            return $this->getRow($table, $primary_key_name." = $id", $object_return);
                       } else return 1; 
                    }
					else return 1;
				}
				return 0;
			}
			public function update($table, $data, $condition, $return_row = false, $object_return = false){
				$data = (array) $data;
				for($i = 0; $i < count($data); $i++){ unset($data[$i]); }
				$sql = "UPDATE $table SET ";
				$current_key = 0;
                foreach ($data as $key => $value) {
                    if($current_key  == count($data)-1) $sql .= "`" . $key . "`=" . $value . "";
					else $sql .= "`" .$key . "`=" . $value . ", ";
					$current_key++;
				}
				$sql .= " WHERE $condition";
				$sql = trim($sql);
				$query = @mysqli_query($this->con, $sql);
				if($query){
				    if($return_row){
                        return $this->getRow($table, $condition, $object_return); 
				    }else return 1; 
                }
				return 0;
			}
			public function getRow($table, $condition, $object_return = false, $order_by = ''){
				$sql = "SELECT * FROM $table WHERE $condition";
                if($order_by) $sql .= " ORDER BY $order_by ";
				$query = @mysqli_query($this->con, $sql);
                $row = @mysqli_fetch_object($query);
                if(!$row) return 0;
                if(!$object_return) $row = (array) $row;
				if($row != ""){ return $row; }
				return null;
			}
			public function getList($table, $condition = '', $limit = '', $order_by = '', $object_return = false){
				$sql = "SELECT * FROM $table ";
				if($condition) $sql .= " WHERE $condition ";
				if($order_by) $sql .= " ORDER BY $order_by ";
				if($limit) $sql .= " LIMIT $limit";
				$query = @mysqli_query($this->con, $sql);
				$totalRows = @mysqli_num_rows($query);
				if($totalRows > 0){
					$result = array();
                    if($object_return){
					   while ($row = @mysqli_fetch_object($query)){ array_push($result, $row); }
                    }else{
                        while ($row = @mysqli_fetch_object($query)){ array_push($result, (array) $row); }
                    }
					return $result;
				}
				return null;
			}
			public function delete($table, $condition){
                if(!$condition) return;
				$sql = "DELETE FROM $table WHERE $condition";
				$query = @mysqli_query($this->con, $sql);
				if($query){ return 1; }
				return 0;
			}
            public function duplicate($table, $condition){
                if(!$condition) return;
                $data = $this->getRow($table, $condition, true);
                $key = $this->getKeyFromTable($table);
                unset($data->{$key}); $data = $this->ajust($data, true, false);
                return $this->insert($table, $data);
            }
            public function deleteTable($table){
                $sql = "DROP TABLE $table";
                $query = @mysqli_query($this->con, $sql);
				if($query){ return 1; }
				return 0;
            }
			public function getTotalRows($table, $condition = '',  $limit = '', $order_by = '' ){
				$sql = "SELECT count(*) as total FROM $table ";
				if($condition)  $sql .= " WHERE $condition";
                if($order_by) $sql .= " ORDER BY $order_by ";
                if($limit){
				    $sql = "SELECT count(*) as total FROM ( ";
                    $sql .= "SELECT *  FROM $table ";
                    if($condition)  $sql .= " WHERE $condition";
                    if($order_by) $sql .= " ORDER BY $order_by ";
                    $sql .= " LIMIT $limit";
                    $sql .= " ) AS data";
                }
				$query = @mysqli_query($this->con, $sql);
				$row = @mysqli_fetch_array($query);
				if($row != ""){ return $row["total"]; }
				return 0;
			}
			public function getError(){
                $data = new stdClass();
                $data->code = mysqli_errno($this->con);
                $data->message = mysqli_error($this->con);
				return $data;
			}
			public function getColums($table){
				$sql = "SHOW COLUMNS FROM $table";
				$query = @mysqli_query($this->con, $sql);
				if($query){
					$colums = array();
					while ($row = mysqli_fetch_assoc($query)) { 	array_push($colums, $row["Field"]); }
					return $colums;
				}
				return 0;
			}
			public function getTables(){
				$sql = "SHOW TABLES";
				$query = @mysqli_query($this->con, $sql);
				if($query){
					$tables = array();
					while ($row = mysqli_fetch_assoc($query)) { 	array_push($tables, $row["Tables_in_".$this->db]); }
					return $tables;
				}
				return 0;
			}
            public function getKeyFromTable($table, $key = 'PRIMARY'){
				$sql = "SHOW KEYS FROM $table WHERE Key_name = '$key'";
                $query = @mysqli_query($this->con, $sql);
                $row = @mysqli_fetch_object($query);
				if($row){
					return $row->Column_name;
				}
				return 0;
            }
			public function sql($sql, $object_return = false){
				$query = @mysqli_query($this->con, $sql);
				if($query){ 
					$totalRows = @mysqli_num_rows($query);
					$result = array();
                    if($object_return){
					   if($totalRows){
                            while ($row = @mysqli_fetch_object($query)){ array_push($result, (object) $row); }
                        }else {
                            $result = null; 
                        }
                    }else{
                        if($totalRows){
                            while ($row = @mysqli_fetch_object($query)){ array_push($result, (array) $row); }
                        }else {
                            $result = null;
                        }
                    }
					return $result;
				}
				return null;
			} 
            public function getColumn($table, $return_column, $condition = "", $limit = "", $order_by = ""){
                $info = $this->getList($table, $condition, $limit, $order_by, true);
                return @$info[0]->{$return_column};
            }
            public function getColumnValueOnTable($table, $validation_column, $value, $return_column, $condition = "", $limit = "", $order_by = ""){
                $info = $this->getList($table, $condition, $limit, $order_by, true);
                if(!$validation_column || ! $value){
                    return @$info[0]->{$return_column};
                }
                return $this->getColumnValue2($info, $validation_column, $value, $return_column);
            }
                public function getColumnValue2($info, $validation_column, $value, $return_column = ""){
                    $info = (array) $info;
                    for($i = 0; $i < count($info); $i++){
                        $row = (array) $info[$i];
                        if($row[$validation_column] == $value){
                            if(@$return_column) return @$row[$return_column];
                            else return @$row;
                        }
                    }
                    return null;
                }
            //++ Ajust data to save, add to the string '' in all data to the object - example: name => 'name'
            // $no_scape = 'field1, field2' (string separated by , )
                function ajust($object, $object_return = false, $escape = true, $no_scape = ""){
                    $object = (array) $object;
                    foreach ($object as $key => $value) {
                        if($value){
                            $object[$key] = "'".mysqli_real_escape_string($this->con, $value)."'";
                            $object[$key] = str_replace("''","'", $object[$key]);
                            if($escape){ 
                                if(strpos($key, $no_scape) === false){ $object[$key] = htmlspecialchars(trim($object[$key])); }
                            }
                            else $object[$key] = trim($object[$key]);
                        }else{
                            $object[$key] = "''";
                        }
                    }
                    if($object_return) $object = (object) $object;
                    return $object;
                }
            // Get last id
                function lastId($table, $id_column = "id"){
                    return $this->getColumnValueOnTable($table, $id_column, "", $id_column, "", 1, $id_column . " DESC");
                }
            /* Get road path
                Example: getRoadPath('')
            */
                function getRoadPath($table, $data, $id_column, $parent_column, $return_column, $return_string = false, $language = null, $frienly_convertion = false, $road_path = null){
                    if(!$data) return;
                    if(!$road_path) $road_path = array();
                    $data = $this->getRow($table, $id_column."='".$data."'", true);
                    array_unshift($road_path, $data->{$return_column});
                    $data = $data->{$parent_column};
                    if($data){
                        return $this->getRoadPath($table, $data, $id_column, $parent_column, $return_column, $return_string, $language, $frienly_convertion, $road_path); 
                    }
                    if($language && $road_path){
                        $languageClass = LanguageManager::getInstance();
                        for($z = 0; $z < count($road_path); $z++){
                            $road_path[$z] = @$languageClass->getValue(@$road_path[$z], $language, $frienly_convertion);
                        }
                    }
                    if($return_string) $road_path = implode("/",$road_path);
                    return $road_path;
                }
                function getPathRoad($table, $data, $id_column, $parent_column, $return_column, $return_string = false, $language = null, $frienly_convertion = false, $road_path = null){
                    return $this->getRoadPath($table, $data, $id_column, $parent_column, $return_column, $return_string, $language, $frienly_convertion, $road_path);
                }
            // scape
                public function scape($string){
                    return mysqli_real_escape_string($this->con, $string);
                }
                public function escape($string){ return $this->scape($string); }
			//++ Personal functions
				public function getTablesNoRegistered(){
					$configuration = new Configuration();
					$registeredTables = $this->getList("".$configuration->table_prefix."tables");
					$registeredTablesSQL = "";
					for($i = 0; $i< count($registeredTables); $i++){
						if($i == count($registeredTables) -1 ) $registeredTablesSQL .= "'".$registeredTables[$i]["table_name"]."'";
						else $registeredTablesSQL .= "'".$registeredTables[$i]["table_name"]."',";
					}
					$sql = "SHOW TABLES WHERE Tables_in_".$this->db." NOT IN ($registeredTablesSQL)";
					$query = @mysqli_query($this->con, $sql);
					if($query){
						$tables = array();
						while ($row = mysqli_fetch_assoc($query)) { array_push($tables, $row["Tables_in_".$this->db]); }
						return $tables;
					}
					return 0;
				}
	}
?>