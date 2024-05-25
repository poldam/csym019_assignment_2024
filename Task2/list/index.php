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
        <?php
            //deleteresult=success
            if(!empty($_GET) && !empty($_GET['deleteresult']) && $_GET['deleteresult'] == 'success') {
                echo '<div class="alert alert-success"> Course was successfully deleted. </div>';
            }

            if(!empty($_GET) && !empty($_GET['insertresult']) && $_GET['insertresult'] == 'success') {
                echo '<div class="alert alert-success"> Course was successfully inserted. </div>';
            } 

            if(!empty($_GET) && !empty($_GET['updateresult']) && $_GET['updateresult'] == 'success') {
                echo '<div class="alert alert-success"> Course was successfully updated. </div>';
            }
        ?>
        <h2>COURSE LIST</h2>
        <table>
            <tr>
                <th> <input type="checkbox" id="allcheckboxes" class="course-checkbox" /> </th>
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
                                echo '<button class="buttonTable button-info"> <a href="../course/?action=view&id='.$row['id'].'" target="_blank"> VIEW </a> </button>';
                                echo '<button class="buttonTable button-edit"> <a href="../course/?action=edit&id='.$row['id'].'" target="_blank">EDIT </a></button>';
                                echo '<button class="buttonTable button-danger" id="course-'.$row['id'].'"><a href="../course?action=delete&id='.$row['id'].'">DELETE</a></button>';
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('allcheckboxes');
        const rowCheckboxes = document.querySelectorAll('.course-checkbox');

        // Add event listener to the select-all checkbox
        selectAllCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                const row = checkbox.closest('tr');
                if (this.checked) {
                    row.classList.add('selected');
                } else {
                    row.classList.remove('selected');
                }
            });
        });

        // Add event listeners to each row checkbox
        rowCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const row = this.closest('tr');
                if (this.checked) {
                    row.classList.add('selected');
                } else {
                    row.classList.remove('selected');
                }

                // If any row checkbox is unchecked, uncheck the select-all checkbox
                if (!this.checked) {
                    selectAllCheckbox.checked = false;
                } else {
                    // If all row checkboxes are checked, check the select-all checkbox
                    const allChecked = Array.from(rowCheckboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                }
            });
        });
    });
</script>

</html>