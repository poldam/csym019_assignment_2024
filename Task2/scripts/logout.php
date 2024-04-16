<?php
    $URLPREFIX = "../";

    session_name('CYM019'); 
    session_start();
    
    require_once($URLPREFIX.'lib.php');

    session_destroy();

    header("Location: ".$URLPREFIX."login/");
