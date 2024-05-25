<?php
    $URLPREFIX = "./";

    session_name('CYM019'); 
    session_start();

    $PAGE = "dashboard";

    require_once($URLPREFIX.'modules/lib.php');

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
    <meta name="description" content="CSYM019 - TASK 2 - POLYVIOS DAMIANAKIS - 23858016">
    <meta name="author" content="Polyvios Damianakis">
    <link rel="stylesheet" href="<?= $URLPREFIX ?>task2.css">
</head>

<body>
    <header>
        <h3>CSYM019 - TASK 2 - Dashboard<span class="logoutLink roboto-bold"> <a href="<?= $URLPREFIX."scripts/logout.php" ?>"> Logout </a></span></h3>
        
    </header>

    <nav>
        <div class="navHeader roboto-bold">MAIN MENU</div>
        <hr>
        <?php require_once($URLPREFIX."modules/menu.php"); ?>
    </nav>

    <main id="main">
        <!-- <canvas id="demoChart" style="width:100%;max-width:700px;height: 300px;"></canvas> -->
        <div class="col24 text-center badge">
            <h3>Courses</h3>
            <span class="roboto-bold">
            <?php 
                $stmt2 =  $MYSQL_CONNECTION->prepare("SELECT COUNT(id) AS total_courses FROM lessons");
                $stmt2->execute();
                $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

                if ($row2) {
                    echo "<a href='./list/'>".$row2['total_courses']."</a>";
                } else {
                    echo 0;
                }
            ?>
            </span>
        </div>

        <div class="col24 text-center badge">
            <h3>Modules</h3>
            <span class="roboto-bold">
            <?php 
                $stmt2 =  $MYSQL_CONNECTION->prepare("SELECT COUNT(id) AS total_modules FROM subjects");
                $stmt2->execute();
                $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

                if ($row2) {
                    echo $row2['total_modules'];
                } else {
                    echo 0;
                }
            ?>
            </span>
        </div>

        <div class="col24 text-center badge">
            <h3>Total Credits</h3>
            <span class="roboto-bold">
            <?php 
                $stmt2 =  $MYSQL_CONNECTION->prepare("SELECT SUM(credits) AS total_credits FROM subjects");
                $stmt2->execute();
                $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

                if ($row2) {
                    echo $row2['total_credits'];
                } else {
                    echo 0;
                }
            ?>
            </span>
        </div>

        <div class="mt-30">
            <button class="button button-edit"><a href="./course/?action=insert"> Insert Course</a></button>
            <button class="button button-info"><a href="./list/"> Create Report</a></button>
        </div>
    </main>

    <footer><?php require_once($URLPREFIX."modules/footer.php"); ?></footer>
</body>
<script>
    // var xValues = ["Italy", "France", "Spain", "USA", "Argentina"];
    // var yValues = [55, 49, 44, 24, 15];
    // var barColors = ["red", "green","blue","orange","brown"];

    // new Chart("demoChart", {
    // type: "bar",
    // data: {
    //     labels: xValues,
    //     datasets: [{
    //     backgroundColor: barColors,
    //     data: yValues
    //     }]
    // },
    // options: {
    //     legend: {display: false},
    //     title: {
    //     display: true,
    //     text: "World Wine Production 2018"
    //     }
    // }
    // });
</script>

</html>