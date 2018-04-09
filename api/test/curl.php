<?php
    include_once "../../classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $curl = 'https://int-server-one.info/kiptap/administrator/api/ \
               -H key=0Wu9tlj4A3cmP6lDOi57EQFUugMgG5pi \
               -d function=user/getUser \
               -d id=1 ';
    $result = $cuppa->curl($curl);
    echo "result: ";
    print_r($result);
?>