<?php
    @session_start();
    require_once("../../classes/Cuppa.php");
    $cuppa = Cuppa::getInstance();
	// DataBase
        $tables = $cuppa->dataBase->getTables();
        for($i = 0; $i < count($tables); $i++){ $cuppa->dataBase->deleteTable($tables[$i]); }
        
        $file = 'script.sql';
        $host = $cuppa->configuration->host;
        $user = $cuppa->configuration->user;
        $password = $cuppa->configuration->password;
        $db = $cuppa->configuration->db;
        $command="mysql -h {$host} -u '{$user}' -p'{$password}' '{$db}' < '{$file}'";
        $output = shell_exec($command);
    //--
?>