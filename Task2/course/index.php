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
            if(!empty($_POST)) {
                debug($_POST);

                

                $title = substr($_POST['title'], 0, 150);
                $overview = substr($_POST['overview'], 0, 4000);
                $level = $_POST['level']; 
                $starting = $_POST['starting'];

                $entryrequirementsforkeys = substr($_POST['entryReqsKeys'], 0, 500);
                $entryReqsFull = substr($_POST['entryReqs'], 0, 2500);

                $location = substr($_POST["location"], 0, 45);
                $courseDetails = substr($_POST['courseDetails'], 0, 2500);

                    
                $feesHeader = substr($_POST['feesHeader'], 0, 500);
                $feesFooter = substr($_POST['feesFooter'], 0, 500);
                $studentPerks = substr($_POST['studentPerks'], 0, 2500);

                $IFY = substr($_POST['IFY'], 0, 2500);
                

                try {
                    $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO lessons (title, overview, `level`, `starting`, entryrequirementsforkeys, `location`, courseDetails, entryReqsFull, feesHeader, feesFooter, studentPerks, IFY) 
                                                VALUES (:title, :overview, :level, :starting, :entryrequirementsforkeys, :location, :courseDetails, :entryReqsFull, :feesHeader, :feesFooter, :studentPerks, :IFY) ");
                    
                    $stmt2->bindParam(':title', $title);
                    $stmt2->bindParam(':overview', $overview);
                    $stmt2->bindParam(':level', $level);   
                    $stmt2->bindParam(':starting', $starting);
                    $stmt2->bindParam(':entryrequirementsforkeys', $entryrequirementsforkeys);   
                    $stmt2->bindParam(':location', $location);
                    $stmt2->bindParam(':courseDetails', $courseDetails);   
                    $stmt2->bindParam(':entryReqsFull', $entryReqsFull);
                    $stmt2->bindParam(':feesHeader', $feesHeader);   
                    $stmt2->bindParam(':feesFooter', $feesFooter);
                    $stmt2->bindParam(':studentPerks', $studentPerks);   
                    $stmt2->bindParam(':IFY', $IFY);        
                          
                    // Execute the statement
                    $stmt2->execute();

                    $id = $MYSQL_CONNECTION->lastInsertId();



                    // FEES
                    if(count($_POST['codetype']) > 0) {
                        $counter = 0;
                        foreach($_POST['codetype'] as $codetype) {
                            $value = substr($_POST['codevalue'][$counter], 0, 45);
                            $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO codes (lessonid, codetype, `value`) VALUES (:lessonid, :codetype, :value)");
                            $stmt2->bindParam(':lessonid', $id);   
                            $stmt2->bindParam(':codetype', $codetype);
                            $stmt2->bindParam(':value', $value);
                            $stmt2->execute();
                            $counter++;
                        }
                    }

                    // Durations
                    if(count($_POST['durationtype']) > 0) {
                        $counter = 0;
                        foreach($_POST['durationtype'] as $durationtype) {
                            $value = substr($_POST['durationvalue'][$counter], 0, 45);
                            $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO durations (lessonid, durationtype, `value`) VALUES (:lessonid, :durationtype, :value)");
                            $stmt2->bindParam(':lessonid', $id);   
                            $stmt2->bindParam(':durationtype', $durationtype);
                            $stmt2->bindParam(':value', $value);
                            $stmt2->execute();
                            $counter++;
                        }
                    }

                    //Highlights
                    if(count($_POST['highlights']) > 0) {
                        foreach($_POST['highlights'] as $highlight) {
                            $highlight = substr($highlight, 0, 500);
                            $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO highlights (lessonid, `text`) VALUES (:lessonid, :highlight)");
                            $stmt2->bindParam(':lessonid', $id);   
                            $stmt2->bindParam(':highlight', $highlight);
                            $stmt2->execute();
                        }
                    }

                    //fees
                    if(count($_POST['feetype']) > 0) {
                        $counter = 0;
                        foreach($_POST['feetype'] as $feetype) {
                            $region = (int) $_POST['feeregion'][$counter];
                            $extras = substr($_POST['feeextra'][$counter], 0, 145);
                            $value = $_POST['feevalue'][$counter];

                            $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO fees (lessonid, region, feestype, `value`, extras) VALUES (:lessonid, :region, :feestype, :value, :extras)");
                            $stmt2->bindParam(':lessonid', $id);   
                            $stmt2->bindParam(':region', $region);
                            $stmt2->bindParam(':feestype', $feetype);
                            $stmt2->bindParam(':value', $value);
                            $stmt2->bindParam(':extras', $extras);
                            $stmt2->execute();
                            $counter++;
                        }
                    }

                    // FAQS
                    if(count($_POST['faqquestion']) > 0) {
                        $counter = 0;
                        foreach($_POST['faqquestion'] as $question) {
                            $q = substr($question, 0, 300);
                            $a = substr($_POST['faqanswer'][$counter], 0, 1000);

                            $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO faqs (lessonid, `question`, `answer`) VALUES (:lessonid, :q, :a)");
                            $stmt2->bindParam(':lessonid', $id);   
                            $stmt2->bindParam(':q', $q);
                            $stmt2->bindParam(':a', $a);
                            $stmt2->execute();
                            $counter++;
                        }
                    }

                    //Modules
                    #########SUBJECTS

                    if(count($_POST['moduletitle']) > 0) {
                        $counter = 0;
                        foreach($_POST['moduletitle'] as $title) {
                            $code = substr($_POST['modulecode'][$counter], 0, 64);
                            $title = substr($title, 0, 200);
                            $status = $_POST['modulestatus'][$counter];
                            $stage = $_POST['modulestage'][$counter];
                            $credits = (int) $_POST['modulecredits'][$counter];
                            $description = substr($_POST['moduledescription'][$counter], 0, 1500);

                            $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO subjects (lessonid, title, `status`, code, credits, stage, `description`) VALUES (:lessonid, :title, :status, :code, :credits, :stage, :description)");
                           
                            $stmt2->bindParam(':lessonid', $id);   
                            $stmt2->bindParam(':title', $title);
                            $stmt2->bindParam(':status', $status);
                            $stmt2->bindParam(':code', $code);
                            $stmt2->bindParam(':credits', $credits);
                            $stmt2->bindParam(':stage', $stage);
                            $stmt2->bindParam(':description', $description);
                            $stmt2->execute();

                            $counter++;
                        }
                    }



                    header("Location: ../list/?insertresult=success");

                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            }

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
                <h2> Insert Course</h2>
                <form method="POST" action="./">
                    <div class="tabs">
                        <div class="tab active" onclick="openTab(event, 'tab1')">Βασικές Πληροφορίες</div>
                        <div class="tab" onclick="openTab(event, 'tab2')">Πρόσθετα</div>
                        <div class="tab" onclick="openTab(event, 'tab3')">Course Modules</div>
                    </div>

                    <div id="tab1" class="tab-content active">
                        <div class="col50">
                            
                            <label>* Course Title (έως 150 χαρακτήρες)</label>
                            <div><input required type="text" name="title" placeholder="Εισάγετε τον Τίτλο του Μαθήματος"></div>

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

                            <label>Course Details (έως 2500 χαρακτήρες) </label>
                            <div><textarea type="text" name="courseDetails" placeholder="Εισάγετε το Course Details του Μαθήματος"></textarea></div>

                            <label>Entry Requirements (KEYS Section)</label>
                            <div><input type="text" name="entryReqsKeys" placeholder="Εισάγετε τα Entry Requirements του Μαθήματος (KEYS Section)"></div>

                            <label>Entry Requirements (έως 2500 χαρακτήρες) </label>
                            <div><textarea type="text" name="entryReqs" placeholder="Εισάγετε τα Entry Requirements του Μαθήματος (FULL)"></textarea></div>
                        </div>
                        <div class="col50">
                            <label>Fees Header (έως 500 χαρακτήρες) </label>
                            <div><textarea type="text" name="feesHeader" placeholder="Εισάγετε το Fees Header του Μαθήματος"></textarea></div>

                            <label>Fees Footer (έως 500 χαρακτήρες) </label>
                            <div><textarea type="text" name="feesFooter" placeholder="Εισάγετε τα Fees Footer του Μαθήματος"></textarea></div>

                            <label>Student Perks (έως 2500 χαρακτήρες) </label>
                            <div><textarea type="text" name="studentPerks" placeholder="Εισάγετε τα Student perks του Μαθήματος"></textarea></div>

                            <label>Integrated Foundation Year (IFY) (έως 2500 χαρακτήρες) </label>
                            <div><textarea type="text" name="IFY" placeholder="Εισάγετε το IFY του Μαθήματος"></textarea></div>
                        </div>
                    </div>
                    <div id="tab2" class="tab-content">
                        <div class="col50">
                            <h3>Fees </h3>
                            <div id="fees-container">
                                <div class="fee-template">
                                    <div class="text-right"><button type="button" class="remove-fee-button button-danger"> Διαγραφή </button></div>
                                    <div class="col24">
                                        <label>Region </label>
                                        <div>
                                            <select name="feeregion[]">
                                                <?php 
                                                    foreach($LESSON_FEE_REGIONS as $k => $v) {
                                                        echo "<option value='".$k."'>".$v."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col24">
                                        <label>Fee Type </label>
                                        <div>
                                            <select name="feetype[]">
                                                <?php 
                                                    foreach($LESSON_FEE_TYPES as $k => $v) {
                                                        echo "<option value='".$k."'>".$v."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col24">
                                        <label>Fee (&pound;)</label>
                                        <div><input type="number" step="1" name="feevalue[]" min="0" max="99999" value="1000"></div>
                                    </div>
                                    <div class="col24">
                                        <label>Extras (έως 200 χαρακτήρες)</label>
                                        <div><input type="text" name="feeextra[]" placeholder="Εισάγετε Extra πληροφορία για το Fee"></div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button class="button-edit" type="button" id="add-fee-button">Add Fee</button>
                            </div>
                            <hr>

                            <h3>Course Codes </h3>
                            <div id="codes-container">
                                <div class="code-template">
                                    <div class="text-right"><button type="button" class="remove-code-button button-danger"> Διαγραφή </button></div>
                                    <div class="col50">
                                        <label>Code Type </label>
                                        <div>
                                            <select name="codetype[]">
                                                <?php 
                                                    foreach($LESSON_CODE_TYPES as $k => $v) {
                                                        echo "<option value='".$k."'>".$v."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col50">
                                        <label>Code (έως 45 χαρακτήρες)</label>
                                        <div><input type="text" name="codevalue[]" placeholder="Εισάγετε τον Κωδικό του Course"></div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button class="button-edit" type="button" id="add-code-button">Add Code</button>
                            </div>
                            <hr>

                            <h3>Course Duration </h3>
                            <div id="durations-container">
                                <div class="duration-template">
                                    <div class="text-right"><button type="button" class="remove-duration-button button-danger"> Διαγραφή </button></div>
                                    <div class="col50">
                                        <label>Duration Type </label>
                                        <div>
                                            <select name="durationtype[]">
                                                <?php 
                                                    foreach($LESSON_DURATION_TYPES as $k => $v) {
                                                        echo "<option value='".$k."'>".$v."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col50">
                                        <label>Διάρκεια (έως 45 χαρακτήρες)</label>
                                        <div><input type="text" name="durationvalue[]" placeholder="Εισάγετε την διάρκεια του Course"></div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button class="button-edit" type="button" id="add-duration-button">Add Duration</button>
                            </div>
                            <hr>
                            
                            <h3>Highlights </h3>
                            <div id="highlights-container">
                                <div class="highlight-template">
                                    <div class="text-right"><button type="button" class="remove-highlight-button button-danger"> Διαγραφή </button></div>
                                    <div class="">
                                        <label>Highlight </label>
                                        <div><input type="text" name="highlights[]" placeholder="Περιγραφή του highlight"></div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button class="button-edit" type="button" id="add-highlight-button">Add Highlight</button>
                            </div>
                            
                        </div>
                        <div class="col50">

                            <h3>FAQs </h3>
                            <div id="faqs-container">
                                <div class="faq-template">
                                    <div class="text-right"><button type="button" class="remove-faq-button button-danger"> Διαγραφή </button></div>
                                    <div class="">
                                        <label>Question (έως 300 χαρακτήρες)</label>
                                        <div><input type="text" name="faqquestion[]" placeholder="Ερώτηση"></div>
                                    </div>
                                    <div>
                                        <label>Answer (έως 1000 χαρακτήρες) </label>
                                        <div><textarea type="text" name="faqanswer[]" placeholder="Απάντηση"></textarea></div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button class="button-edit" type="button" id="add-faq-button">Add QnA</button>
                            </div>
                        </div>
                    </div>
                    <div id="tab3" class="tab-content">
                        <h3>Modules</h3>
                        <div id="modules-container">
                            <div class="module-template">
                                <div class="text-right"><button type="button" class="remove-module-button button-danger"> Διαγραφή </button></div>
                                <div class="col64">
                                    <label>* Module Title (έως 200 χαρακτήρες)</label>
                                    <div><input required type="text" name="moduletitle[]" placeholder="Εισάγετε τον Τίτλο του Module"></div>
                                </div>
                                <div class="col33">
                                    <label>Module Code (έως 64 χαρακτήρες)</label>
                                    <div><input type="text" name="modulecode[]" placeholder="Εισάγετε τον Κωδικό του Module"></div>
                                </div>
                                <div class="col33">
                                    <label>Stage </label>
                                    <div>
                                        <select name="modulestage[]">
                                            <?php 
                                                foreach($LESSON_STAGES as $k => $v) {
                                                    echo "<option value='".$k."'>".$v."</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col33">
                                    <label>Module Status </label>
                                    <div>
                                        <select name="modulestatus[]">
                                            <?php 
                                                foreach($LESSON_STATUS as $k => $v) {
                                                    echo "<option value='".$k."'>".$v."</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col33">
                                    <label>Credits</label>
                                    <div><input type="number" step="1" name="modulecredits[]" min="0" max="100" value="20"></div>
                                </div>
                                <div>
                                    <label>Περιγραφή (έως 1500 χαρακτήρες) </label>
                                    <div><textarea type="text" name="moduledescription[]" placeholder="Περιγραφή Module"></textarea></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button class="button-edit" type="button" id="add-module-button">Add Module</button>
                        </div>
                    </div>
                                        
                    <div class="text-center">
                        <input type="submit" class="button button-edit mt-30" value="Εισαγωγή" />
                    </div>
                </form>
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
                    <div class="panel"> <br>
                        <?= $row['courseDetails'] ?><br>
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
                        ?><br><br>
                    </div>
                    <button class="accordion">Entry Requirements</button>
                    <div class="panel"> <br>
                        <?= $row['entryReqsFull'] ?><br>
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
                        <?= $row['studentPerks'] ?><br>
                        <br></div>
                    <?php } ?>
                    
                    <?php if(!empty($row['IFY'])) { ?>
                        <button class="accordion"> Integrated Foundation Year (IFY) </button>
                        <div class="panel"> <br>
                        <?= $row['IFY'] ?><br>
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

    document.addEventListener('DOMContentLoaded', function() {
        const addModuleButton = document.getElementById('add-module-button');
        const modulesContainer = document.getElementById('modules-container');
        const moduleTemplate = document.querySelector('.module-template');

        addModuleButton.addEventListener('click', function() {
            // Clone the module template
            const newModule = moduleTemplate.cloneNode(true);
            // Remove the template class from the new module
            newModule.classList.remove('module-template');
            // Add event listener to the remove button
            const removeButton = newModule.querySelector('.remove-module-button');
            removeButton.addEventListener('click', function() {
                modulesContainer.removeChild(newModule);
            });
            // Append the new module to the container
            modulesContainer.appendChild(newModule);
        });

        // Add event listener to the remove button of the initial module template
        const initialRemoveButton = moduleTemplate.querySelector('.remove-module-button');
        initialRemoveButton.addEventListener('click', function() {
            modulesContainer.removeChild(moduleTemplate);
        });

        /// Code for Dynamic Fees
        const addFeeButton = document.getElementById('add-fee-button');
        const feesContainer = document.getElementById('fees-container');
        const feeTemplate = document.querySelector('.fee-template');

        addFeeButton.addEventListener('click', function() {
            // Clone the module template
            const newModule2 = feeTemplate.cloneNode(true);
            // Remove the template class from the new module
            newModule2.classList.remove('fee-template');
            // Add event listener to the remove button
            const removeButton2 = newModule2.querySelector('.remove-fee-button');
            removeButton2.addEventListener('click', function() {
                feesContainer.removeChild(newModule2);
            });
            // Append the new module to the container
            feesContainer.appendChild(newModule2);
        });

        // Add event listener to the remove button of the initial module template
        const initialRemoveButton2 = feeTemplate.querySelector('.remove-fee-button');
        initialRemoveButton2.addEventListener('click', function() {
            feesContainer.removeChild(feeTemplate);
        });

        /// Code for Dynamic Codes
        const addCodeButton = document.getElementById('add-code-button');
        const codesContainer = document.getElementById('codes-container');
        const codeTemplate = document.querySelector('.code-template');

        addCodeButton.addEventListener('click', function() {
            // Clone the module template
            const newModule3 = codeTemplate.cloneNode(true);
            // Remove the template class from the new module
            newModule3.classList.remove('code-template');
            // Add event listener to the remove button
            const removeButton3 = newModule3.querySelector('.remove-code-button');
            removeButton3.addEventListener('click', function() {
                codesContainer.removeChild(newModule3);
            });
            // Append the new module to the container
            codesContainer.appendChild(newModule3);
        });

        // Add event listener to the remove button of the initial module template
        const initialRemoveButton3 = codeTemplate.querySelector('.remove-code-button');
        initialRemoveButton3.addEventListener('click', function() {
            codesContainer.removeChild(codeTemplate);
        });

        /// Code for Dynamic Durations
        const addDurationButton = document.getElementById('add-duration-button');
        const durationsContainer = document.getElementById('durations-container');
        const durationTemplate = document.querySelector('.duration-template');

        addDurationButton.addEventListener('click', function() {
            // Clone the module template
            const newModule4 = durationTemplate.cloneNode(true);
            // Remove the template class from the new module
            newModule4.classList.remove('duration-template');
            // Add event listener to the remove button
            const removeButton4 = newModule4.querySelector('.remove-duration-button');
            removeButton4.addEventListener('click', function() {
                durationsContainer.removeChild(newModule4);
            });
            // Append the new module to the container
            durationsContainer.appendChild(newModule4);
        });

        // Add event listener to the remove button of the initial module template
        const initialRemoveButton4 = durationTemplate.querySelector('.remove-duration-button');
        initialRemoveButton4.addEventListener('click', function() {
            durationsContainer.removeChild(durationTemplate);
        });

        /// Code for Dynamic Highlights
        const addHighlightButton = document.getElementById('add-highlight-button');
        const highlightsContainer = document.getElementById('highlights-container');
        const highlightTemplate = document.querySelector('.highlight-template');

        addHighlightButton.addEventListener('click', function() {
            // Clone the module template
            const newModule5 = highlightTemplate.cloneNode(true);
            // Remove the template class from the new module
            newModule5.classList.remove('highlight-template');
            // Add event listener to the remove button
            const removeButton5 = newModule5.querySelector('.remove-highlight-button');
            removeButton5.addEventListener('click', function() {
                highlightsContainer.removeChild(newModule5);
            });
            // Append the new module to the container
            highlightsContainer.appendChild(newModule5);
        });

        // Add event listener to the remove button of the initial module template
        const initialRemoveButton5 = highlightTemplate.querySelector('.remove-highlight-button');
        initialRemoveButton5.addEventListener('click', function() {
            highlightsContainer.removeChild(highlightTemplate);
        });

        /// Code for Dynamic FAQs
        const addFaqButton = document.getElementById('add-faq-button');
        const faqsContainer = document.getElementById('faqs-container');
        const faqTemplate = document.querySelector('.faq-template');

        addFaqButton.addEventListener('click', function() {
            // Clone the module template
            const newModule6 = faqTemplate.cloneNode(true);
            // Remove the template class from the new module
            newModule6.classList.remove('highlight-template');
            // Add event listener to the remove button
            const removeButton6 = newModule6.querySelector('.remove-faq-button');
            removeButton6.addEventListener('click', function() {
                faqsContainer.removeChild(newModule6);
            });
            // Append the new module to the container
            faqsContainer.appendChild(newModule6);
        });

        // Add event listener to the remove button of the initial module template
        const initialRemoveButton6 = faqTemplate.querySelector('.remove-faq-button');
        initialRemoveButton6.addEventListener('click', function() {
            faqsContainer.removeChild(faqTemplate);
        });
    });

    function openTab(event, tabId) {
        // Hide all tab contents
        var tabContents = document.getElementsByClassName('tab-content');
        for (var i = 0; i < tabContents.length; i++) {
            tabContents[i].classList.remove('active');
        }

        // Remove active class from all tabs
        var tabs = document.getElementsByClassName('tab');
        for (var i = 0; i < tabs.length; i++) {
            tabs[i].classList.remove('active');
        }

        // Show the current tab and add an "active" class to the button that opened the tab
        document.getElementById(tabId).classList.add('active');
        event.currentTarget.classList.add('active');
    }

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