<?php
    $URLPREFIX = "../";

    session_name('CYM019'); 
    session_start();

    require_once($URLPREFIX.'modules/lib.php');

    $login = strtolower($_POST['email']);
    $password = $_POST['password'];

    if($user = checkLogin($login, $password)) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user['email'];
    } 
    else {
        $_SESSION['errors'][] = "Ο συνδυασμός email/κωδικού δεν είναι σωστός.";
    }

    header("Location: ".$URLPREFIX);
