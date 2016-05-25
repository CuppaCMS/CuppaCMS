<?php
    ini_set("session.gc_maxlifetime", 12*60*60); 
    ini_set('session.cookie_lifetime',12*60*60);
    session_set_cookie_params(2*7*24*60*60);
    @session_start();
    echo "1";
?>