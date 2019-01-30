<?php
    class FileManager{
        private static $instance;
        public function __construct(){ }
        public static function getInstance() {
			if (self::$instance == NULL) { self::$instance = new FileManager(); } 
			return self::$instance;
		}
        // Generate File
            function createFile($data, $name, $path = ""){
                $fp = @fopen($path.$name,'w');
                $result = @fwrite($fp,$data);
                @fclose($fp);
                return $result;
            }
        // Create String File
            function createStringFile($data, $name = "file", $random_name = false, $path = "", $extension = "txt"){
                // Generate Name
                    if($random_name){ $random_number = rand(); $name = $name."_".date("Ymd-His").".".$extension;
                    }else{ $name = $name.".".$extension;}
                // Save File
                    $result = $this->createFile($data,$name, $path);
                    if($result) return $name;
                    else return 0;
            }
        // Create CSV File
            function createCSVFile($data, $name = "file", $random_name = false, $path = "", $separator_column = ",", $separator_row = "\r\n", $extension = "csv"){
                $info_to_save = "";
                for($i = 0; $i < count($data); $i++){
                    $info_to_save .= implode($separator_column, $data[$i]);
                    $info_to_save .= $separator_row;
                }
                // Generate Name
                    if($random_name){ $random_number = rand(); $name = $name."_".date("Ymd-His").".".$extension;
                    }else{ $name = $name.".".$extension;}
                // Save File
                    $result = $this->createFile($info_to_save,$name, $path);
                    if($result) return $name;
                    else return 0;
            }
        // Get List of file in folder
             function getList($path = "", $remove_extension = false){
        		$files = @scandir($path);
                if(!$files) return null;
                $file_list = array();
        		for($i = 0; $i < count($files); $i++){
        			if($files[$i] != "." && $files[$i] != ".."){
        			     if($remove_extension){ 
        			         $files[$i] = explode(".", $files[$i]);
                             $files[$i] = $files[$i][0];
                         }
       				     array_push($file_list, $files[$i]);
        			}
        		}
                return $file_list;
            }
        // Get List extend
            function getListExtend($path = "", $removePath = true){
                $path = $path."/";
                $path = str_replace("\\","/",$path);
                $path = str_replace("//","/",$path);

                // extract relative path
                $urlLF = explode("/", CU_FM_ROOT_URL);
                $urlLF = array_filter($urlLF);
                $urlLF = array_pop($urlLF);
                $pathArray = explode("/",$path);
                $index = array_search($urlLF, $pathArray);
                $pathArray = array_slice($pathArray, $index+1);
                $pathRelative = join("/",$pathArray);

                $files = @scandir($path);
                if(!$files) return null;
                $file_list = array();

                for($i = 0; $i < count($files); $i++){
                    if($files[$i] != "." && $files[$i] != ".."){
                        $file = $files[$i];
                        $item = new stdClass();
                        $item->file = $file;
                        if(is_dir($path.$file)){
                            $item->name = $file;
                            $item->type = "folder";
                            $item->path = $path.$file."/";
                            $item->date = filemtime($path.$file);
                        }else{
                            $item->name = explode(".", $file);
                            $item->ext = array_pop($item->name);
                            $item->name = join(".", $item->name);
                            $item->type = "file";
                            if(in_array(strtolower($item->ext), ["jpg", "jpeg", "png","apng","svg","bmp","gif","ico"], true)){
                                $dimensions = getimagesize($path.$file);
                                $item->dimensions = new stdClass();
                                $item->dimensions->width = $dimensions[0];
                                $item->dimensions->height = $dimensions[1];
                                $item->type2 = "image";
                            }else if(strtolower($item->ext) == "pdf"){
                                $item->type2 = "pdf";
                            }
                            $size = filesize($path.$file);
                            $item->date = filemtime($path.$file);
                            $item->size = $size;
                            if(!$removePath) $item->path = $path."/".$file;
                        }
                        array_push($file_list, $item);
                        $item->url = CU_FM_ROOT_URL."/".$pathRelative.$file;
                        $item->url = str_replace("///","/", $item->url);
                        $item->url = explode("://", $item->url);
                        $item->url[1] = str_replace("//","/", $item->url[1]);
                        $item->url = join("://", $item->url);
                    }
                }
                return $file_list;
            }
        // delete File
            function deleteFile($file){
                unlink($file); 
            }
        // create foder
            function createFolder($name, $path, $permissions = 0755){
                return @mkdir($path.$name, $permissions);
            }
        // delete Folder
            function deleteFolder($folder, $keep_folder = false) {
        		$dir_handle = @opendir($folder);
        		if (!@$dir_handle){ return false; }
        		while($file = readdir($dir_handle)) { 
        			if ($file != "." && $file != "..") {
        				if (!is_dir($folder."/".$file)) 
        					unlink($folder."/".$file);
        				else
        					$this->deleteFolder($folder.'/'.$file);
        			}
        		}
        		closedir($dir_handle);
        		if(!$keep_folder){ rmdir($folder); }
        		return true;
        	}
        // Load File
            function loadFile($file){
                if(!$file) return;
                $data = @file_get_contents($file);
                return $data;
            }
        // Copy File
            function copyFile($source, $dest){
                if(!$source) return;
                return @copy($source,$dest);
            }
        // Get extensio
            function getExtension($name){
                $extension = explode(".", $name);
                $extension = strtolower($extension[count($extension) - 1]);
                return $extension;
            }
        // Get description
            function getDescription($file){
                $file = explode("/", @$file);
                $file = explode(".", @$file[count($file)-1]);
                $data = new stdClass();
                $data->name = @$file[0];
                $data->ext = @$file[1];
                $data->type = @$file[1];
                return $data;
            }
        // rename
            function rename($from, $to){
                @rename($from, $to);
                return 1;
            }
        // Copy Folder
            function copyFolder($src,$dst) { 
                $dir = opendir($src); 
                @mkdir($dst); 
                while(false !== ( $file = readdir($dir)) ) { 
                    if (( $file != '.' ) && ( $file != '..' )) { 
                        if ( is_dir($src . '/' . $file) ) { 
                            $this->copyFolder($src . '/' . $file,$dst . '/' . $file);
                        } 
                        else {
                            $this->copyFile($src . '/' . $file,$dst . '/' . $file);
                        } 
                    } 
                }
                closedir($dir); 
            } 
    }
?>