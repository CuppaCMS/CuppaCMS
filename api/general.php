<?php
    // test
    function test($data){
        $cuppa = Cuppa::getInstance();
        return $cuppa->dataBase->getList("cu_menus");
    }
    // save form
    // info: Base64Encode(JSONEncode)
    function saveForm($table, $info){
        $cuppa = Cuppa::getInstance();
        $language = $cuppa->language->load("web");
        // save
            $data = $cuppa->jsonDecode($info);
            $data = $cuppa->dataBase->ajust($data, true);
            $data->date = "NOW()";
            $result = $cuppa->dataBase->insert($table, $data);
        // send mail
            if($result && $cuppa->configuration->forward){            
                $info = (array) $cuppa->jsonDecode($info);
                $body = "";
                foreach ($info as $key => $value) {
                    if($key == "country") $value = $cuppa->dataBase->getColumn($cuppa->configuration->table_prefix."countries", "name", "id = ".$value);
                    $body .= "<b>".ucfirst($key).":</b> ".$value."<br />";
                }
                $cuppa->mail->send($language->title, $cuppa->configuration->email_outgoing, "Form: ".$cuppa->POST("table"), $cuppa->configuration->forward,  $body);
            }
        return $result;
    }
?>