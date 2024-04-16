<?php
    $URLPREFIX = "../";
    
    session_name('CYM019'); 
    session_start();

    require_once($URLPREFIX.'lib.php');

    $_SESSION['loggedin'] = true;
    $_SESSION['email'] = "email@email.gr";

    header("Location: ".$URLPREFIX);
