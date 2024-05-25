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

$LESSON_STATUS = [
    1 => "Compulsory",
    2 => "Optional",
    3 => "Designate"
];

$LESSON_CODE_TYPES = [
    1 => "BA",
    2 => "BA with Foundation",
    3 => "BSc",
    4 => "BSc with Foundation",
    5 => ""
];

$LESSON_DURATION_TYPES = [
    1 => "Full Time",
    2 => "Full Time Foundation",
    3 => "Part Time",
    4 => "Distance Learning"
];

$LESSON_FEE_REGIONS = [
    1 => "UK",
    2 => "International"
];

$LESSON_LESSON_LEVELS = [
    1 => "Undergraduate",
    2 => "Postgraduate"
];

$LESSON_STARTING = [
    1 => "January",
    2 => "February",
    3 => "March",
    4 => "April",
    5 => "May",
    6 => "June",
    7 => "July",
    8 => "August",
    9 => "September",
    10 => "October",
    11 => "November",
    12 => "December"
];

$LESSON_FEE_TYPES = [
    1 => "Full Time",
    2 => "Part Time",
    3 => "Integrated Foundation Year",
    4 => "Distance Learning",
    5 => "PGCert",
    6 => "PGDip",
    7 => "MSc (Top Up)",
    8 => "MSc: Year 1",
    9 => "MSc: Year 2",
    10 => "MSc: Year 3",
    11 => "Year 1",
    12 => "Year 2",
    13 => "Postgraduate Certificate in Advanced Clinical Practice",
    14 => "Postgraduate Diploma in Advanced Clinical Practice",
    15 => "MSc Advanced Clinical Practice",
    16 => "MSc Advanced Clinical Practice (Top Up)",
    17 => "Part Time / Year 1",
    18 => "Part Time / Year 2",
    19 => "Year 2 Placement Fee"
];

$LESSON_STAGES = [
    1 => "STAGE 1",
    2 => "STAGE 2",
    3 => "STAGE 3",
    4 => "STAGE 4",
    5 => "STAGE 5"
];

function generateRandomColor() {
    $red = mt_rand(0, 255);
    $green = mt_rand(0, 255);
    $blue = mt_rand(0, 255);

    return "'rgb($red, $green, $blue)'";
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

function debug($data) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}