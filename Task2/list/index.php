<?php
    $URLPREFIX = "../";

    session_name('CYM019'); 
    session_start();

    $PAGE = "list";

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

    <!-- <script src="<?= $URLPREFIX ?>Chart.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
</head>

<body>
    <header>
        <h3>CSYM019 - TASK 2 - Course List <span class="logoutLink roboto-bold"> <a href="<?= $URLPREFIX."scripts/logout.php" ?>"> Logout </a></span></h3>
        
    </header>

    <nav>
        <div class="navHeader roboto-bold">MAIN MENU</div>
        <hr>
        <?php require_once($URLPREFIX."modules/menu.php"); ?>
    </nav>

    <main id="main">
        <h2>COURSE LIST</h2>
        <table>
            <tr>
                <th> <input type="checkbox" id="allcheckboxes" class="" /> </th>
                <th>ID</th>
                <th>TITLE</th>
                <th>LEVEL</th>
                <th>STARTING</th>
                <th>LOCATION</th>
                <th>OVERVIEW</th>
                <th>TOTAL CREDITS</th>
                <th>ACTIONS</th>
            <tr>
            <?php
                try {
                    $stmt = $MYSQL_CONNECTION->prepare("SELECT * FROM lessons;");
                    $stmt->execute();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $credits = "0";

                        $stmt2 =  $MYSQL_CONNECTION->prepare("SELECT SUM(credits) AS total_credits FROM subjects where lessonid = :id");
                        $stmt2->bindParam(':id', $row['id']);
                        $stmt2->execute();
                        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    
                        if ($row2) {
                            // var_dump($row2);
                            $credits = $row2['total_credits'];
                        }

                        echo "<tr>";
                            echo '<td> <input type="checkbox" id="checkbox-'.$row['id'].'" class="course-checkbox"/> </td>';
                            echo "<td>".$row['id']."</td>";
                            echo "<td class='roboto-bold'>".$row['title']."</td>";
                            echo "<td>".$LESSON_LESSON_LEVELS[$row['level']]."</td>";
                            echo "<td>".$LESSON_STARTING[$row['starting']]."</td>";
                            echo "<td>".$row['location']."</td>";
                            echo "<td><div title='".$row['overview']."'>".substr($row['overview'], 0, 100)."</div></td>";
                            echo "<td> ".$credits." </td>";
                            echo "<td>";
                                echo '<span class="buttonTable button-info"> <a href="../course/?action=view&id='.$row['id'].'" target="_blank"> VIEW </a> </span>';
                                echo '<span class="buttonTable button-edit"> <a href="../course/?action=edit&id='.$row['id'].'" target="_blank">EDIT </a></span>';
                                echo '<span class="buttonTable button-danger" id="course-'.$row['id'].'">DELETE</span>';
                            echo "</td>";
                        echo "</tr>";
                    }

                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }

            ?>
        </table>
    </main>

    <footer><?php require_once($URLPREFIX."modules/footer.php"); ?></footer>
</body>
<script src="<?= $URLPREFIX ?>task2.js"></script>

</html>