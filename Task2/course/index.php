<?php
    $URLPREFIX = "../";

    session_name('CYM019'); 
    session_start();

    $PAGE = "course";

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
        <h3>CSYM019 - TASK 2 - Course Page<span class="logoutLink roboto-bold"> <a href="<?= $URLPREFIX."scripts/logout.php" ?>"> Logout </a></span></h3>
        
    </header>

    <nav>
        <div class="navHeader roboto-bold">MAIN MENU</div>
        <hr>
        <?php require_once($URLPREFIX."modules/menu.php"); ?>
    </nav>

    <main id="main">
        <?php if(!empty($_GET['action']) && $_GET['action'] == 'insert') { ?>
            <div>
                <h2> Insert Course</h2>
                <form>
                    <label>Course Title (έως 150 χαρακτήρες)</label>
                    <div><input type="text" name="title" placeholder="Εισάγετε τον Τίτλο του Μαθήματος"></div>

                    <label>Overview (έως 500 χαρακτήρες) </label>
                    <div><textarea type="text" name="overview" placeholder="Εισάγετε το Overview του Μαθήματος"></textarea></div>

                    <div class="col33">
                        <label>Level </label>
                        <div>
                            <select name="level">
                                <?php 
                                    foreach($LESSON_LESSON_LEVELS as $k => $v) {
                                        echo "<option value='".$k."'>".$v."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col33">
                        <label>Starting </label>
                        <div>
                            <select name="starting">
                                <?php 
                                    foreach($LESSON_STARTING as $k => $v) {
                                        echo "<option value='".$k."'>".$v."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col33">
                        <label>Location </label>
                        <div>
                            <input type="text" name="location" placeholder="Εισάγετε το Location του Μαθήματος">
                        </div>
                    </div>

                    <label>Course Details (έως 500 χαρακτήρες) </label>
                    <div><textarea type="text" name="courseDetails" placeholder="Εισάγετε το Course Details του Μαθήματος"></textarea></div>

                    <label>Entry Requirements (KEYS Section)</label>
                    <div><input type="text" name="entryReqsKeys" placeholder="Εισάγετε τα Entry Requirements του Μαθήματος (KEYS Section)"></div>

                    <label>Entry Requirements (έως 500 χαρακτήρες) </label>
                    <div><textarea type="text" name="entryReqs" placeholder="Εισάγετε τα Entry Requirements του Μαθήματος (FULL)"></textarea></div>

                    <label>Fees Header (έως 500 χαρακτήρες) </label>
                    <div><textarea type="text" name="feesHeader" placeholder="Εισάγετε το Fees Header του Μαθήματος"></textarea></div>

                    <label>Fees Footer (έως 500 χαρακτήρες) </label>
                    <div><textarea type="text" name="feesFooter" placeholder="Εισάγετε τα Fees Footer του Μαθήματος"></textarea></div>

                    <label>Student Perks (έως 500 χαρακτήρες) </label>
                    <div><textarea type="text" name="studentPerks" placeholder="Εισάγετε τα Student perks του Μαθήματος"></textarea></div>

                    <label>Integrated Foundation Year (IFY) (έως 500 χαρακτήρες) </label>
                    <div><textarea type="text" name="IFY" placeholder="Εισάγετε το IFY του Μαθήματος"></textarea></div>
            
                </form>
            </div>
        <?php } else if (!empty($_GET['action']) && $_GET['action'] == 'edit' && !empty($_GET['id'])) { ?>

            <?php
                $lessonid = $_GET['id'];
            ?>
            <div>
                <h2> Course Edit (<?= $lessonid ?>) </h2>

                <span class="button button-info mr-30"> <a href="../course?action=view&id=<?= $lessonid ?>"> View Course </a></span>
                <span class="button button-danger" id="deleteLesson"> Delete Course</span>
            </div>
        <?php } else if (!empty($_GET['action']) && $_GET['action'] == 'view' && !empty($_GET['id'])) { ?>

            <?php
                $lessonid = $_GET['id'];
            ?>
            <div>
                <h2> Course View (<?= $lessonid ?>) </h2>

                <div class="col50">
                    <?php
                        $stmt =  $MYSQL_CONNECTION->prepare("SELECT * FROM lessons where id = :id");
                        $stmt->bindParam(':id', $lessonid);
                        $stmt->execute();
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
                        if ($row) { ?>
                            <h3><?= $row['title']?></h3>
                            <p><?= $row['overview'] ?></p>

                            <?php
                                $stmt2 = $MYSQL_CONNECTION->prepare("SELECT * FROM highlights WHERE lessonid = :id");
                                $stmt2->bindParam(':id', $lessonid);
                                $stmt2->execute();
                                echo "<h3>HIGHLIGHTS</h3>";
                                echo "<ul>";
                                while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<li>".$row2['text']."</li>";
                                }
                                echo "</ul>";
                            ?>
                        <?php
                        } else {
                            echo "<div class='alert alert-danger'>COURSE NOT FOUND!</div>";
                        }
                    ?>
                    
                </div>
                <div class="col50">

                </div>
                <div class="mt-30">
                    <span class="button button-edit mr-30"> <a href="../course?action=edit&id=<?= $lessonid ?>"> Edit Course </a></span> 
                    <span class="button button-danger" id="deleteLesson"> Delete Course</span>
                <div>
            </div>
        <?php } ?>
    </main>

    <footer><?php require_once($URLPREFIX."modules/footer.php"); ?></footer>
</body>
<script src="<?= $URLPREFIX ?>task2.js"></script>

</html>