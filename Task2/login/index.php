<?php
    $URLPREFIX = "../";
    session_name('CYM019'); 
    session_start();
    require_once($URLPREFIX.'modules/lib.php');
?>

<!DOCTYPE html>
<html>

<head>
    <title>CSYM019 - TASK 2 - LOGIN SCREEN - POLYVIOS DAMIANAKIS - 23858016</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CSYM019 - TASK 2 - LOGIN SCREEN - POLYVIOS DAMIANAKIS - 23858016">
    <meta name="author" content="Polyvios Damianakis">
    <link rel="stylesheet" href="../task2.css">
</head>

<body class="bodyLogin">
    <header>
        <h3>CSYM019 - TASK 2 - Login Screen</h3>
    </header>

    <main id="main" class="mainLogin text-center">
        <div class="loginForm text-left">
            <h3 class="text-center">Είσοδος Χρήστη</h3>
            <hr>
            <?= printMessage('errors', 'danger') ?> 
            <form id="loginform" class="user" method="post" action="<?= $URLPREFIX ?>scripts/login.php">
            
                <label>Διεύθυνση e-mail </label>
                <div><input type="email" name="email" placeholder="Εισάγετε το email σας"></div>
                
                <label>Κωδικός πρόσβασης </label>
                <div><input type="password" name="password" placeholder="Εισάγετε τον κωδικό σας"></div>

                
            
                <!-- <div class="g-recaptcha" data-sitekey="<?= $GOOGLE_RECAPTCHA['site_key'] ?>"></div>

                <span id="captcha_error" class="text-danger"></span> -->
                <br><br>
                
                <div class="text-center"><input type="submit" class="button" value="Είσοδος"></div>
            </form>
        </div>
    </main>

    <footer><?php require_once($URLPREFIX."modules/footer.php"); ?></footer>
</body>
<script src="<?= $URLPREFIX ?>task2.js"></script>

</html>