<?php
$MYSQL_HOST = 'localhost';
$MYSQL_PORT = '';
$MYSQL_DATABASE = 'c019';
$MYSQL_USERNAME = 'root';
$MYSQL_PASSWORD = 'root';

$MYSQL_CONNECTION = null;

try {
    $MYSQL_CONNECTION = new PDO("mysql:host=$MYSQL_HOST;dbname=$MYSQL_DATABASE", $MYSQL_USERNAME, $MYSQL_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    // echo "Connected to $MYSQL_DATABASE at $MYSQL_HOST successfully.";
} 
catch (PDOException $pe) {
    die("Could not connect to the database $MYSQL_DATABASE :" . $pe->getMessage());
}

function checkLogin($email, $password) {
  
    global $MYSQL_CONNECTION;
  
    $stmt = $MYSQL_CONNECTION->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->execute(['email' => $email]); 
    $user = $stmt->fetch();
    
    if(!$user)
      return false;
  
    if(validate_pw($password, $user['password']))
      return $user;
  
    return false;
}

function printMessage($whereFrom, $type) {
    $msgs = "";

    if(!empty($_SESSION[$whereFrom])) {
        $msgs .= "<div class='alert alert-".$type."'> <ul>";
        foreach($_SESSION[$whereFrom] as $e) {
        $msgs .= "<li>".$e."</li>";
        }
        $msgs .= "</ul></div>";

        unset($_SESSION[$whereFrom]);
    }

    return $msgs;
}

function validate_pw($password, $hash) {
    return crypt($password, $hash) == $hash;
}

function generate_hash($password, $cost = 11) {
    $salt = substr(base64_encode(openssl_random_pseudo_bytes(17)), 0, 22);
    $salt = str_replace("+", ".", $salt);
    $param = '$'.implode('$',array("2y",str_pad($cost, 2, "0", STR_PAD_LEFT), $salt));
    return crypt($password, $param);
}

// var_dump(generate_hash("password"));