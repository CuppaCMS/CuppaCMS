<?php
	ini_set("memory_limit","2000M");
	ini_set('max_execution_time', 0);
    set_time_limit(0);
    @session_start();
    include_once "Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load();
    if(!@$_SESSION["cuSession"]->sql_download) return;
    $sql = base64_decode($_SESSION["cuSession"]->sql_download);
    $table_params = json_decode(base64_decode($_SESSION["cuSession"]->table_params));
    // File
        $extension = ".csv";
        $name = "../media/files/file".$extension;
        $separator = $cuppa->configuration->csv_column_separator;
        $fp = @fopen($name,'w');
    //++ Header
        $result = $cuppa->dataBase->sql($sql." LIMIT 1");
        $header = array();
        foreach ($result[0] as $key => $value){
            if(@$table_params->{$key}->includeDownload != 0) array_push($header, $cuppa->langValue($key, $language));
        }
        @fwrite($fp, implode($separator,$header));
        @fwrite($fp,"\r");
    //--
    //++ Body
        $con = mysqli_connect($cuppa->configuration->db_host, $cuppa->configuration->db_user, $cuppa->configuration->db_password);
        mysqli_select_db($con, $cuppa->configuration->db_name);
        mysqli_query($con, "SET NAMES 'utf8'");
        $query = mysqli_query($con, $sql);
        $i = 1;
        while ($row = mysqli_fetch_object($query)){
            $row = (array) $row;
            foreach($row as $key=>$value){
                $value = trim($value);
                $value = preg_replace("/<.*?>/", " ", $value);
                $value = str_replace($separator, " ", $value);
                $value = preg_replace('/\s+/', ' ', $value);
                $value = trim($value);
                $value = str_replace(array("\r", "\r\n", "\n") , " ", $value);
                $row[$key] =  $cuppa->langValue($value, $language);
                //++ fixed expecial characters
                    $row[$key] = htmlentities($row[$key], ENT_QUOTES, 'utf-8');        
                    $row[$key] = html_entity_decode($row[$key], ENT_QUOTES , "Windows-1252");
                //--
                if(@$table_params->{$key}->includeDownload == 0) unset($row[$key]);
                
            }
            @fwrite($fp, implode($separator,$row));
            @fwrite($fp,"\r");
        }
    //--
    @fclose($fp);
    header('Content-Encoding: UTF-8');
    header('Content-type: text/csv; charset=UTF-8');
    header("Content-Disposition: attachment; filename=file".$extension);
	readfile($name);
?>