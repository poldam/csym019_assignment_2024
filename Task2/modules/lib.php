<?php

// Credentials and MySQL connection credentials
$MYSQL_HOST = 'localhost'; // MySQL Server name/IP
$MYSQL_PORT = '3306'; //MySQL Server Port
$MYSQL_DATABASE = 'c019'; // App Database
$MYSQL_USERNAME = 'root'; // User that has access to our database
$MYSQL_PASSWORD = ''; //Password used for that authentication

$MYSQL_CONNECTION = null;

try {
    // Establishing a connection
    $MYSQL_CONNECTION = new PDO("mysql:host=$MYSQL_HOST;dbname=$MYSQL_DATABASE", $MYSQL_USERNAME, $MYSQL_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
} 
catch (PDOException $pe) {
    //If the connection fails the app exits
    die("Could not connect to the database $MYSQL_DATABASE :" . $pe->getMessage());
}
//Dropdown values for the course status
$LESSON_STATUS = [
    1 => "Compulsory",
    2 => "Optional",
    3 => "Designate"
];

//Dropdown values for the course code types
$LESSON_CODE_TYPES = [
    1 => "BA",
    2 => "BA with Foundation",
    3 => "BSc",
    4 => "BSc with Foundation",
    5 => ""
];

//Dropdown values for the course duration types
$LESSON_DURATION_TYPES = [
    1 => "Full Time",
    2 => "Full Time Foundation",
    3 => "Part Time",
    4 => "Distance Learning"
];

//Dropdown values for the course regions for the fees
$LESSON_FEE_REGIONS = [
    1 => "UK",
    2 => "International"
];
//Dropdown values for the course level
$LESSON_LESSON_LEVELS = [
    1 => "Undergraduate",
    2 => "Postgraduate"
];
//Dropdown values for the course starting month
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
//Dropdown values for the course fee types
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
//Dropdown values for the course stages
$LESSON_STAGES = [
    1 => "STAGE 1",
    2 => "STAGE 2",
    3 => "STAGE 3",
    4 => "STAGE 4",
    5 => "STAGE 5"
];

// Creates a random rgb color used for the charts
function generateRandomColor() {
    $red = mt_rand(0, 255); // get random color for red
    $green = mt_rand(0, 255); // get random color for green
    $blue = mt_rand(0, 255); // get random color for blue

    return "'rgb($red, $green, $blue)'"; // return the randomly created rgb color
}

// Function that checks if a user can login using teh login form
function checkLogin($email, $password) {
  
    global $MYSQL_CONNECTION; // global connection string
    
    // uery that selects a user based on the email
    $stmt = $MYSQL_CONNECTION->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->execute(['email' => $email]); 
    $user = $stmt->fetch();
    
    // if no user is returned then the login fails -> access to app denied
    if(!$user)
      return false;
    
    // if user is found we have to validate the password using validate_pw function
    if(validate_pw($password, $user['password'])) // if passes match
      return $user; // user is authenticated and we return their information
  
    return false; // login failed
}

// Function for printing messages as alerts
// $whereFrom: list of messages
// $type: message type that defines the css class of teh alert
function printMessage($whereFrom, $type) {
    $msgs = "";

    if(!empty($_SESSION[$whereFrom])) { // if there is a list of messages
        $msgs .= "<div class='alert alert-".$type."'> <ul>"; //create an alert with a list 
        foreach($_SESSION[$whereFrom] as $e) { // go through the list and print each message as a list item
            $msgs .= "<li>".$e."</li>";
        }
        $msgs .= "</ul></div>"; // close the list

        unset($_SESSION[$whereFrom]); // unset the $_SESSION var that was holding teh messages
    }

    return $msgs; // return the html alert
}

// Function that validates $password against $hash using the php function crypt()
// returns true if the $hash was created from the $password
function validate_pw($password, $hash) {
    return crypt($password, $hash) == $hash;
}

// Function to generate a hash for a given password using bcrypt algorithm
// Default cost is set to 11
// Generates a random salt and combines with cost to create hash parameter
// Returns the hashed password generated using bcrypt algorithm and provided parameters
function generate_hash($password, $cost = 11) {
    $salt = substr(base64_encode(openssl_random_pseudo_bytes(17)), 0, 22);
    $salt = str_replace("+", ".", $salt);
    $param = '$'.implode('$',array("2y",str_pad($cost, 2, "0", STR_PAD_LEFT), $salt));
    return crypt($password, $param);
}

// Create hash for 'password'
// var_dump(generate_hash("password"));

//Helper function used for debugging
function debug($data) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}