<?php
    include_once "../../classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $curl = 'http://localhost/projects/refineddata/repositories/vp-console/www/administrator/api/ \
               -H key:qGkZ2WEd1OWbKEWz17yFOOCiek3pyIvs \
               -d function=diagnostic/getDiagnosticResult \
               -d id=8';
    $result = $cuppa->curl($curl);
    print_r($result);
/*
  Regular CURL
    $header = array('key:qGkZ2WEd1OWbKEWz17yFOOCiek3pyIvs');
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "http://localhost/projects/refineddata/repositories/vp-console/www/administrator/api/");
    curl_setopt($curl, CURLOPT_HEADER, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_HTTPHEADER, );
    curl_setopt($curl, CURLOPT_POSTFIELDS, "function=diagnostic/getDiagnosticResult&id=8");
    $result = curl_exec($curl);
    curl_close($curl);
    print_r($result);
*/
?>