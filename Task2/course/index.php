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
        <?php 
            if(!empty($_GET['action']) && $_GET['action'] == 'delete' && !empty($_GET['id'])) { 
                $id = $_GET['id'];

                $stmt = $MYSQL_CONNECTION->prepare("DELETE FROM lessons WHERE id = :id");
                $stmt->bindParam(':id', $id);
                $stmt->execute();

                ######### UCASCode
                $stmt2 = $MYSQL_CONNECTION->prepare("DELETE FROM codes WHERE lessonid = :lessonid");
                $stmt2->bindParam(':lessonid', $id);
                $stmt2->execute();

                ######### DURATIONS
                $stmt2 = $MYSQL_CONNECTION->prepare("DELETE FROM durations WHERE lessonid = :lessonid");
                $stmt2->bindParam(':lessonid', $id);
                $stmt2->execute();

                ######### HIGHLIGHTS
                $stmt2 = $MYSQL_CONNECTION->prepare("DELETE FROM highlights WHERE lessonid = :lessonid");
                $stmt2->bindParam(':lessonid', $id);
                $stmt2->execute();

                ######### FEES
                $stmt2 = $MYSQL_CONNECTION->prepare("DELETE FROM fees WHERE lessonid = :lessonid");
                $stmt2->bindParam(':lessonid', $id);
                $stmt2->execute();

                ##### QnA 
                $stmt2 = $MYSQL_CONNECTION->prepare("DELETE FROM faqs WHERE lessonid = :lessonid");
                $stmt2->bindParam(':lessonid', $id);
                $stmt2->execute();

                ##### LESSONS
                $stmt2 = $MYSQL_CONNECTION->prepare("DELETE FROM subjects WHERE lessonid = :lessonid");
                $stmt2->bindParam(':lessonid', $id);
                $stmt2->execute();

                header("Location: ../list?deleteresult=success");


            } else if(!empty($_GET['action']) && $_GET['action'] == 'insert') { ?>
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

                <span class="button button-edit mr-30"> <a href="../course?action=view&id=<?= $lessonid ?>"> View Course </a></span>
                <span class="button button-danger" id="deleteLesson"> <a href="../course?action=delete&id=<?= $lessonid ?>">Delete Course</a></span>
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
                    <hr>
                    <h3>COURSE CONTENT</h3>

                    <button class="accordion">Course Details</button>
                    <div class="panel"> 
                        <?= $row['courseDetails'] ?>
                        <?php
                            $stmt2 = $MYSQL_CONNECTION->prepare("SELECT * FROM subjects WHERE lessonid = :id");
                            $stmt2->bindParam(':id', $lessonid);
                            $stmt2->execute();

                            $subjects = [
                                1 => [],
                                2 => [],
                                3 => []
                            ];

                            while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                                $row2['status'] = $LESSON_STATUS[$row2['status']];
                                $subjects[$row2['stage']][] = $row2;
                            }

                            foreach($subjects as $k => $v) {
                                if(count($v) == 0)
                                    continue;
                                echo '<button class="accordion2">STAGE '.$k.'</button>';
                                echo '<div class="panel2"><br>'; 
                                foreach($v as $less) {
                                    echo '<button class="accordion3">'.$less['title'].' (Credits: '.$less['credits'].')</button>';
                                    echo '<div class="panel3"><br>'; 
                                    echo "<div class='gray'><small><span class='roboto-bold'>Module code:</span> ".$less['code']." <span class='roboto-bold'>Status:</span> ".$less['status']." </small></div> <br>".$less['description']."<br>";
                                    echo '<br></div>'; 
                                }
                                echo "<br></div>";
                            }
                        ?><br>
                    </div>
                    <button class="accordion">Entry Requirements</button>
                    <div class="panel"> <br>
                        <?= $row['entryReqsFull'] ?>
                    <br></div>

                    <button class="accordion">Fees & Funding</button>
                    <div class="panel"> <br>
                        <?= $row['feesHeader'] ?><br><br>
                        <?php
                        {
                            $stmt2 = $MYSQL_CONNECTION->prepare("SELECT * FROM fees WHERE lessonid = :id");
                            $stmt2->bindParam(':id', $lessonid);
                            $stmt2->execute();
                            
                            while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                                echo "<div><span class='roboto-bold'>".$LESSON_FEE_REGIONS[$row2['region']]." - ".$LESSON_FEE_TYPES[$row2['feestype']].": </span> &pound;".$row2['value']." ".$row2['extras']."</div>";
                            }

                        } ?>

                        <br><?= $row['feesFooter'] ?><br>
                    <br></div>
                    
                    <?php if(!empty($row['studentPerks'])) { ?>
                        <button class="accordion">Student Perks</button>
                        <div class="panel"> <br>
                        <?= $row['studentPerks'] ?>
                        <br></div>
                    <?php } ?>
                    
                    <?php if(!empty($row['IFY'])) { ?>
                        <button class="accordion"> Integrated Foundation Year (IFY) </button>
                        <div class="panel"> <br>
                        <?= $row['IFY'] ?>
                        <br></div>
                    <?php } ?>

                    
                        <?php 
                            {
                                $stmt2 = $MYSQL_CONNECTION->prepare("SELECT * FROM faqs WHERE lessonid = :id");
                                $stmt2->bindParam(':id', $lessonid);
                                $stmt2->execute();

                                $first = true;
                                
                                while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                                    if($first) {
                                        echo '<button class="accordion"> FAQs </button>';
                                        echo '<div class="panel"> <br>';
                                        $first = false;
                                    }

                                    echo '<button class="accordion2">'.$row2['question'].'</button>';
                                    echo '<div class="panel2"> <br>';
                                    echo $row2['answer'];
                                    echo '<br><br></div>';
                                }

                                if(!$first) {
                                    echo '<br></div>';
                                }
                            }
                        ?>
                </div>
                <div class="col50">
                    <?php if ($row) { 

                        echo "<h3>KEY FACTS</h3>";
                        
                        {
                            $stmt2 = $MYSQL_CONNECTION->prepare("SELECT * FROM codes WHERE lessonid = :id");
                            $stmt2->bindParam(':id', $lessonid);
                            $stmt2->execute();
                            
                            $first = true;
                            while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                                if($first) {
                                    echo "<div class='roboto-bold'>UCAS Code</div>";
                                    $first = false;
                                }
                                echo "<div><span class='roboto-bold'>".$LESSON_CODE_TYPES[$row2['codetype']].": </span> ".$row2['value']."</div>";
                            }

                            if(!$first)
                                echo "<hr>";
                        }

                        {
                            echo "<div><span class='roboto-bold'>Level: </span> ".$LESSON_LESSON_LEVELS[$row['level']]."</div>";
                            echo "<hr>";
                        }

                        {
                            $stmt2 = $MYSQL_CONNECTION->prepare("SELECT * FROM durations WHERE lessonid = :id");
                            $stmt2->bindParam(':id', $lessonid);
                            $stmt2->execute();
                            
                            $first = true;
                            while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                                if($first) {
                                    echo "<div class='roboto-bold'>Duration</div>";
                                    $first = false;
                                }
                                echo "<div><span class='roboto-bold'>".$LESSON_DURATION_TYPES[$row2['durationtype']].": </span> ".$row2['value']."</div>";
                            }

                            if(!$first)
                                echo "<hr>";
                        }         
                        
                        {
                            echo "<div><span class='roboto-bold'>Starting: </span> ".$LESSON_STARTING[$row['starting']]."</div>";
                            echo "<hr>";
                        }

                        if(!empty($row['entryrequirementsforkeys'])){
                            echo "<div><span class='roboto-bold'>Entry Requirements: </span> ".$row['entryrequirementsforkeys']."</div>";
                            echo "<hr>";
                        }

                        {
                            $stmt2 = $MYSQL_CONNECTION->prepare("SELECT * FROM fees WHERE lessonid = :id");
                            $stmt2->bindParam(':id', $lessonid);
                            $stmt2->execute();
                            
                            $first = true;
                            while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                                if($first) {
                                    echo "<div class='roboto-bold'>Fees</div>";
                                    $first = false;
                                }
                                echo "<div><span class='roboto-bold'>".$LESSON_FEE_REGIONS[$row2['region']]." - ".$LESSON_FEE_TYPES[$row2['feestype']].": </span> &pound;".$row2['value']." ".$row2['extras']."</div>";
                            }

                            if(!$first)
                                echo "<hr>";
                        }   

                        if(!empty($row['location'])){
                            echo "<div><span class='roboto-bold'>Location: </span> ".$row['location']."</div>";
                            echo "<hr>";
                        }
                        
                    } ?>
                </div>
                <div class="mt-30">
                    <span class="button button-edit mr-30"> <a href="../course?action=edit&id=<?= $lessonid ?>"> Edit Course </a></span> 
                    <span class="button button-danger" id="deleteLesson"> <a href="../course?action=delete&id=<?= $lessonid ?>">Delete Course</a></span>
                <div>
            </div>
        <?php } ?>
    </main>

    <footer><?php require_once($URLPREFIX."modules/footer.php"); ?></footer>
</body>
<script>
    var acc = document.querySelectorAll(".accordion");

    for (var i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function () {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.maxHeight) {
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = "10000px";
            }
        });
    }

    var acc2 = document.querySelectorAll(".accordion2");

    for (var j = 0; j < acc2.length; j++) {
        acc2[j].addEventListener("click", function () {
            this.classList.toggle("active");
            var panel2 = this.nextElementSibling;
            if (panel2.style.maxHeight) {
                panel2.style.maxHeight = null;
            } else {
                panel2.style.maxHeight = "10000px";
            }
        });
    }

    var acc3 = document.querySelectorAll(".accordion3");

    for (var k = 0; k < acc3.length; k++) {
        acc3[k].addEventListener("click", function () {
            this.classList.toggle("active");
            var panel3 = this.nextElementSibling;
            if (panel3.style.maxHeight) {
                panel3.style.maxHeight = null;
            } else {
                panel3.style.maxHeight = "10000px";
            }
        });
    }
</script>

</html>