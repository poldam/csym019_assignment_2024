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
                    if($_POST['action'] == 'insert') {
                        $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO lessons (title, overview, `level`, `starting`, entryrequirementsforkeys, `location`, courseDetails, entryReqsFull, feesHeader, feesFooter, studentPerks, IFY) 
                                                VALUES (:title, :overview, :level, :starting, :entryrequirementsforkeys, :location, :courseDetails, :entryReqsFull, :feesHeader, :feesFooter, :studentPerks, :IFY) ");
                    } else {
                        $id = $_POST['id'];
                        // Row exists, perform an UPDATE
                        $stmt2 = $MYSQL_CONNECTION->prepare("UPDATE lessons SET title = :title, overview = :overview, `level` = :level, `starting` = :starting, entryrequirementsforkeys = :entryrequirementsforkeys, `location` = :location, courseDetails = :courseDetails, entryReqsFull = :entryReqsFull, feesHeader = :feesHeader, feesFooter = :feesFooter, studentPerks = :studentPerks, IFY = :IFY WHERE id = :id");

                        // Bind parameters
                        // id, title, overview, level, starting, entryrequirementsforkeys, location, courseDetails, entryReqsFull, feesHeader, feesFooter, studentPerks, IFY
                        $stmt2->bindParam(':id', $id);
                    }
                    
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

                    if($_POST['action'] == 'insert') {
                        $id = $MYSQL_CONNECTION->lastInsertId();
                    } else {
                        // debug($_POST);
                        ##### CODES
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
                    }

                    // CODES
                    if(count($_POST['codetype']) > 0) {
                        $counter = -1;
                        foreach($_POST['codetype'] as $codetype) {
                            $counter++;
                            if(empty($_POST['codevalue'][$counter]))
                                continue;
                            $value = substr($_POST['codevalue'][$counter], 0, 45);
                            $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO codes (lessonid, codetype, `value`) VALUES (:lessonid, :codetype, :value)");
                            $stmt2->bindParam(':lessonid', $id);   
                            $stmt2->bindParam(':codetype', $codetype);
                            $stmt2->bindParam(':value', $value);
                            $stmt2->execute();
                            
                        }
                    }

                    // Durations
                    if(count($_POST['durationtype']) > 0) {
                        $counter = -1;
                        foreach($_POST['durationtype'] as $durationtype) {
                            $counter++;
                            if(empty($_POST['durationvalue'][$counter]))
                                continue;
                            $value = substr($_POST['durationvalue'][$counter], 0, 45);
                            $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO durations (lessonid, durationtype, `value`) VALUES (:lessonid, :durationtype, :value)");
                            $stmt2->bindParam(':lessonid', $id);   
                            $stmt2->bindParam(':durationtype', $durationtype);
                            $stmt2->bindParam(':value', $value);
                            $stmt2->execute();
                        }
                    }

                    //Highlights
                    if(count($_POST['highlights']) > 0) {
                        foreach($_POST['highlights'] as $highlight) {
                            if(empty($highlight))
                                continue;
                            $highlight = substr($highlight, 0, 500);
                            $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO highlights (lessonid, `text`) VALUES (:lessonid, :highlight)");
                            $stmt2->bindParam(':lessonid', $id);   
                            $stmt2->bindParam(':highlight', $highlight);
                            $stmt2->execute();
                        }
                    }

                    //fees
                    if(count($_POST['feetype']) > 0) {
                        $counter = -1;
                        foreach($_POST['feetype'] as $feetype) {
                            $counter++;
                            if(empty($_POST['feevalue'][$counter]))
                                continue;
                            
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
                            
                        }
                    }

                    // FAQS
                    if(count($_POST['faqquestion']) > 0) {
                        $counter = -1;
                        foreach($_POST['faqquestion'] as $question) {
                            $counter++;
                            if(empty($question))
                                continue;
                            $q = substr($question, 0, 300);
                            $a = substr($_POST['faqanswer'][$counter], 0, 1000);

                            $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO faqs (lessonid, `question`, `answer`) VALUES (:lessonid, :q, :a)");
                            $stmt2->bindParam(':lessonid', $id);   
                            $stmt2->bindParam(':q', $q);
                            $stmt2->bindParam(':a', $a);
                            $stmt2->execute();
                            
                        }
                    }

                    //Modules
                    #########SUBJECTS

                    if(count($_POST['moduletitle']) > 0) {
                        $counter = -1;
                        foreach($_POST['moduletitle'] as $title) {
                            $counter++;
                            if(empty($title))
                                continue;
                            $code = substr($_POST['modulecode'][$counter], 0, 64);
                            $title = substr($title, 0, 200);
                            $status = $_POST['modulestatus'][$counter];
                            $stage = $_POST['modulestage'][$counter];
                            $credits = (int) $_POST['modulecredits'][$counter];
                            $description = substr($_POST['moduledescription'][$counter], 0, 1500);

                            $stmt2 = $MYSQL_CONNECTION->prepare("SELECT code FROM subjects WHERE lessonid = :lessonid AND code = :code");
                            $stmt2->bindParam(':lessonid', $id);
                            $stmt2->bindParam(':code', $code);
                            $stmt2->execute();
                            $row3 = $stmt2->fetch(PDO::FETCH_ASSOC);
        
                            if ($row3) {
                                $stmt2 = $MYSQL_CONNECTION->prepare("UPDATE subjects SET lessonid = :lessonid, title = :title, `status` = :status, code = :code, credits = :credits, stage = :stage, `description` = :description WHERE lessonid = :lessonid AND code = :code");
                                $stmt2->bindParam(':code', $code);   
                            } else {
                                $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO subjects (lessonid, title, `status`, code, credits, stage, `description`) VALUES (:lessonid, :title, :status, :code, :credits, :stage, :description)");
                            }
                           
                            $stmt2->bindParam(':lessonid', $id);   
                            $stmt2->bindParam(':title', $title);
                            $stmt2->bindParam(':status', $status);
                            $stmt2->bindParam(':code', $code);
                            $stmt2->bindParam(':credits', $credits);
                            $stmt2->bindParam(':stage', $stage);
                            $stmt2->bindParam(':description', $description);
                            $stmt2->execute();
                        }
                    }

                    if($_POST['action'] == 'insert') {
                        header("Location: ../list/?insertresult=success");
                    } else {
                        header("Location: ../list/?updateresult=success");
                    }

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
                <h2> Create Course</h2>

                <?php require_once("../modules/courseForm.php"); ?>
                
        <?php } else if (!empty($_GET['action']) && $_GET['action'] == 'edit' && !empty($_GET['id'])) { ?>

            <?php
                $lessonid = $_GET['id'];
                $stmt = $MYSQL_CONNECTION->prepare("SELECT * FROM lessons WHERE id = :id");
                $stmt->bindParam(':id', $lessonid);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($row) {
                    $id = $row['id'];
                }
            ?>
            <div>
                <h2> Edit Course (<?= $row['title'] ?>)  <span class="button button-edit button-sm mr-30"> <a href="../course?action=view&id=<?= $lessonid ?>"> View Course </a></span>
                <span class="button button-sm button-danger" id="deleteLesson"> <a href="../course?action=delete&id=<?= $lessonid ?>" onclick="return confirm('Είστε σίγουροι για την μόνιμη διαγραφή του Course και όλων των δεδομένων τους?')" >Delete Course</a></span></h2>

                <?php require_once("../modules/courseForm.php"); ?>

            </div>
        <?php } else if (!empty($_GET['action']) && $_GET['action'] == 'view' && !empty($_GET['id'])) { ?>

            <?php
                $lessonid = $_GET['id'];
                $stmt = $MYSQL_CONNECTION->prepare("SELECT * FROM lessons where id = :id");
                $stmt->bindParam(':id', $lessonid);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <div>
                <h2> Course View (<?= $row['title'] ?>) </h2>

                <div class="col50">
                    <?php
                        
    
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
                                3 => [],
                                4 => [],
                                5 => [],
                                6 => []
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

                    <?php if(!empty($row['entryReqsFull'])) { ?>
                        <button class="accordion">Entry Requirements</button>
                        <div class="panel"> <br>
                            <?= $row['entryReqsFull'] ?><br>
                        <br></div>
                    <?php } ?>

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
                    <span class="button button-danger" id="deleteLesson"> <a href="../course?action=delete&id=<?= $lessonid ?>" onclick="return confirm('Είστε σίγουροι για την μόνιμη διαγραφή του Course και όλων των δεδομένων τους?')">Delete Course</a></span>
                <div>
            </div>
        <?php } ?>
    </main>

    <footer><?php require_once($URLPREFIX."modules/footer.php"); ?></footer>
</body>
<script>
    function addListeners(name) {
        const button = document.getElementById('add-' + name + '-button');
        const container = document.getElementById(name + 's-container');
        const template = document.querySelector('.' + name + '-template');

        // MODULES
        button.addEventListener('click', function() {
            // Clone the module template
            const newElement = template.cloneNode(true);
            // Remove the template class from the new module
            newElement.classList.remove(name + '-template');
            // Add event listener to the remove button
            const removeButton = newElement.querySelector('.remove-' + name + '-button');
            removeButton.addEventListener('click', function() {
                container.removeChild(newElement);
            });
            // Append the new module to the container
            container.appendChild(newElement);
        });

        // Add event listener to the remove button of the initial module template
        const initialRemoveButton = template.querySelector('.remove-' + name + '-button');
        initialRemoveButton.addEventListener('click', function() {
            container.removeChild(template);
        });
    }

    function deleteTemplate(name, elem) {
        // console.log(name);
        // console.log(elem.parentNode);
        elem.parentNode.parentNode.remove(elem.parentNode)
    }
    <?php if (!empty($_GET['action']) && $_GET['action'] != 'view') { ?>
        document.addEventListener('DOMContentLoaded', function() {
            addListeners('module');
            addListeners('fee');
            addListeners('code');
            addListeners('duration');
            addListeners('highlight');
            addListeners('faq');
        });
    <?php } ?>

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