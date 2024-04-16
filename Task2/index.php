<?php
    $URLPREFIX = "./";

    session_name('CYM019'); 
    session_start();

    require_once($URLPREFIX.'lib.php');

    if(!$_SESSION['loggedin']) {
        header("Location: ".$URLPREFIX."login/");
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>CSYM019 - TASK 2 - POLYVIOS DAMIANAKIS - 23858016</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CSYM019 - ΛΙΣΤΑ ΜΕ ΠΑΝΕΠΙΣΤΗΜΙΑΚΑ ΜΑΘΗΜΑΤΑ">
    <meta name="author" content="Polyvios Damianakis">
    <link rel="stylesheet" href="./task2.css">
</head>

<body>
    <header>
        <h3>CSYM019 - TASK 2 <span class="logoutLink roboto-bold"> <a href="<?= $URLPREFIX."scripts/logout.php" ?>"> Logout </a></span></h3>
        
    </header>

    <nav>
        <div class="navHeader roboto-bold">MAIN MENU</div>
        <hr>
        <ul id="menu"> 
            <li>Menu</li>
        </ul>
    </nav>

    <main id="main">
        
    </main>

    <footer>&copy; CSYM019 2024 - TASK 2 - POLYVIOS DAMIANAKIS - 23858016</footer>
</body>
<script src="./task2.js"></script>

</html>