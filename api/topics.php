<?php
    function get(){
        $cuppa = Cuppa::getInstance();
        $result = $cuppa->db->getList("tu_topics", "", "", "id ASC", true);
        return $result;
    }
    function getTrending(){
        $cuppa = Cuppa::getInstance();
        $result = $cuppa->db->getList("tu_topics", "", "6", "id ASC", true);
        return $result;
    }
?>