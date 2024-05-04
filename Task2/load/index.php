<?php
    $URLPREFIX = "../";

    session_name('CYM019'); 
    session_start();

    $PAGE = "load";

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
        <h3>CSYM019 - TASK 2 - JSON load <span class="logoutLink roboto-bold"> <a href="<?= $URLPREFIX."scripts/logout.php" ?>"> Logout </a></span></h3>
        
    </header>

    <nav>
        <div class="navHeader roboto-bold">MAIN MENU</div>
        <hr>
        <?php require_once($URLPREFIX."modules/menu.php"); ?>
    </nav>

    <main id="main">
        <h2>Load Subjects from JSON/Task 1</h2>
        <?php
            $jsonFile = '../../Task1/course.json';

            // Read the JSON file content
            $jsonData = file_get_contents($jsonFile);
            
            // Parse the JSON data into a PHP array
            $lessons = json_decode($jsonData, true);

            foreach($lessons as $lesson) {
                $id = (int) $lesson['id'];
                print($lesson['title']."<br>");

                $title = substr($lesson['title'], 0, 150);
                $overview = substr($lesson['overview'], 0, 4000);
                $level = array_search($lesson['KEYFACTS']['Level'], $LESSON_LESSON_LEVELS); 
                $starting = array_search($lesson['KEYFACTS']['Starting'], $LESSON_STARTING);
                $entryrequirementsforkeys = substr($lesson['KEYFACTS']["EntryRequirements"], 0, 500);
                $location = substr($lesson['KEYFACTS']["Location"], 0, 45);
                $courseDetails = substr($lesson['COURSECONTENT']['CourseDetails']['html'], 0, 2500);
                $entryReqsFull = substr($lesson['COURSECONTENT']['EntryRequirements']['html'], 0, 2500);
                $feesHeader = substr($lesson['COURSECONTENT']['FeesandFunding']['text'], 0, 500);
                $feesFooter = substr($lesson['COURSECONTENT']['FeesandFunding']['AdditionalCosts'], 0, 500);
                $studentPerks = substr($lesson['COURSECONTENT']["StudentPerks"]['html'], 0, 2500);
                $IFY = substr($lesson['COURSECONTENT']["IntegratedFoundationYear"]['html'], 0, 2500);
                

                try {
                    $stmt = $MYSQL_CONNECTION->prepare("SELECT id FROM lessons WHERE id = :id");
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($row) {
                        // Row exists, perform an UPDATE
                        $stmt2 = $MYSQL_CONNECTION->prepare("UPDATE lessons SET title = :title, overview = :overview, `level` = :level, `starting` = :starting, entryrequirementsforkeys = :entryrequirementsforkeys, `location` = :location, courseDetails = :courseDetails, entryReqsFull = :entryReqsFull, feesHeader = :feesHeader, feesFooter = :feesFooter, studentPerks = :studentPerks, IFY = :IFY WHERE id = :id");

                        // Bind parameters
                        // id, title, overview, level, starting, entryrequirementsforkeys, location, courseDetails, entryReqsFull, feesHeader, feesFooter, studentPerks, IFY
                        $stmt2->bindParam(':id', $id);
                        
                    } else {
                        print("Row does not exist, perform an INSERT");
                        $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO lessons (title, overview, `level`, `starting`, entryrequirementsforkeys, `location`, courseDetails, entryReqsFull, feesHeader, feesFooter, studentPerks, IFY) 
                                                VALUES (:title, :overview, :level, :starting, :entryrequirementsforkeys, :location, :courseDetails, :entryReqsFull, :feesHeader, :feesFooter, :studentPerks, :IFY) ");
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

                    if(!$row) {
                        $id = $MYSQL_CONNECTION->lastInsertId();
                    }

                    ######### DURATIONS
                    $stmt2 = $MYSQL_CONNECTION->prepare("DELETE FROM codes WHERE lessonid = :lessonid");
                    $stmt2->bindParam(':lessonid', $id);
                    $stmt2->execute();

                    foreach($lesson['KEYFACTS']['UCASCode'] as $d) {
                        $codetype = array_search($d['type'], $LESSON_CODE_TYPES);
                        $value = substr($d['value'], 0, 45);
                        $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO codes (lessonid, codetype, `value`) VALUES (:lessonid, :codetype, :value)");
                        $stmt2->bindParam(':lessonid', $id);   
                        $stmt2->bindParam(':codetype', $codetype);
                        $stmt2->bindParam(':value', $value);
                        $stmt2->execute();
                    }

                    ######### DURATIONS
                    $stmt2 = $MYSQL_CONNECTION->prepare("DELETE FROM durations WHERE lessonid = :lessonid");
                    $stmt2->bindParam(':lessonid', $id);
                    $stmt2->execute();

                    foreach($lesson['KEYFACTS']['Duration'] as $d) {
                        $durationtype = array_search($d['type'], $LESSON_DURATION_TYPES);
                        $value = substr($d['value'], 0, 45);
                        $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO durations (lessonid, durationtype, `value`) VALUES (:lessonid, :durationtype, :value)");
                        $stmt2->bindParam(':lessonid', $id);   
                        $stmt2->bindParam(':durationtype', $durationtype);
                        $stmt2->bindParam(':value', $value);
                        $stmt2->execute();
                    }

                    ######### HIGHLIGHTS
                    $stmt2 = $MYSQL_CONNECTION->prepare("DELETE FROM highlights WHERE lessonid = :lessonid");
                    $stmt2->bindParam(':lessonid', $id);
                    $stmt2->execute();

                    foreach($lesson['highlights'] as $highlight) {
                        $highlight = substr($highlight, 0, 500);
                        $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO highlights (lessonid, `text`) VALUES (:lessonid, :highlight)");
                        $stmt2->bindParam(':lessonid', $id);   
                        $stmt2->bindParam(':highlight', $highlight);
                        $stmt2->execute();
                    }
                    #################################
                    ######### FEES
                    $stmt2 = $MYSQL_CONNECTION->prepare("DELETE FROM fees WHERE lessonid = :lessonid");
                    $stmt2->bindParam(':lessonid', $id);
                    $stmt2->execute();

                    //lessonid, region, feestype, value, extras

                    foreach($lesson['COURSECONTENT']['FeesandFunding']['Fees'] as $fee) {
                        $temp = explode("-", $fee['type']);
                        
                        $region = array_search(trim($temp[0]), $LESSON_FEE_REGIONS);
                        $feestype = array_search(trim($temp[1]), $LESSON_FEE_TYPES);
                        $extras = substr($fee['extra'], 0, 145);
                        $value = (float) $fee['value'];

                        $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO fees (lessonid, region, feestype, `value`, extras) VALUES (:lessonid, :region, :feestype, :value, :extras)");
                        $stmt2->bindParam(':lessonid', $id);   
                        $stmt2->bindParam(':region', $region);
                        $stmt2->bindParam(':feestype', $feestype);
                        $stmt2->bindParam(':value', $value);
                        $stmt2->bindParam(':extras', $extras);
                        $stmt2->execute();
                    }
                    #################################
                    ##### QnA 
                    $stmt2 = $MYSQL_CONNECTION->prepare("DELETE FROM faqs WHERE lessonid = :lessonid");
                    $stmt2->bindParam(':lessonid', $id);
                    $stmt2->execute();

                    foreach($lesson['COURSECONTENT']["FAQs"]['questions'] as $faq) {
                        $q = substr($faq['q'], 0, 300);
                        $a = substr($faq['a'], 0, 1000);

                        $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO faqs (lessonid, `question`, `answer`) VALUES (:lessonid, :q, :a)");
                        $stmt2->bindParam(':lessonid', $id);   
                        $stmt2->bindParam(':q', $q);
                        $stmt2->bindParam(':a', $a);
                        $stmt2->execute();
                    }
                    #################################
                    #########SUBJECTS

                    foreach($lesson['COURSECONTENT']['CourseDetails']['stages'] as $stage) {
                        $curstage = array_search($stage['name'], $LESSON_STAGES);
                        foreach($stage['modules'] as $subject) {
                            $code = substr($subject['code'], 0, 64);
                            $title = substr($subject['name'], 0, 200);
                            $status = array_search($subject['status'], $LESSON_STATUS);
                            $credits = (int) $subject['credits'];
                            $description = substr($subject['description'], 0, 1500);

                            $stmt2 = $MYSQL_CONNECTION->prepare("SELECT code FROM subjects WHERE lessonid = :lessonid AND code = :code");
                            $stmt2->bindParam(':lessonid', $id);
                            $stmt2->bindParam(':code', $code);
                            $stmt2->execute();
                            $row = $stmt2->fetch(PDO::FETCH_ASSOC);
        
                            if ($row) {
                                echo "Update Lesson <br>";
                                $stmt2 = $MYSQL_CONNECTION->prepare("UPDATE subjects SET lessonid = :lessonid, title = :title, `status` = :status, code = :code, credits = :code, stage = :stage, `description` = :description WHERE lessonid = :lessonid AND code = :code");
                                $stmt2->bindParam(':code', $code);   
                            } else {
                                $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO subjects (lessonid, title, `status`, code, credits, stage, `description`) VALUES (:lessonid, :title, :status, :code, :credits, :stage, :description)");
                            }

                            $stmt2->bindParam(':lessonid', $id);   
                            $stmt2->bindParam(':title', $title);
                            $stmt2->bindParam(':status', $status);
                            $stmt2->bindParam(':code', $code);
                            $stmt2->bindParam(':credits', $credits);
                            $stmt2->bindParam(':stage', $curstage);
                            $stmt2->bindParam(':description', $description);
                            $stmt2->execute();
                        }
                    }

                    #################################

                    echo "<br>Record updated/inserted successfully";
                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }

                break;
            }

            // var_dump($subjects);
        ?>
    </main>

    <footer><?php require_once($URLPREFIX."modules/footer.php"); ?></footer>
</body>
<script src="<?= $URLPREFIX ?>task2.js"></script>

</html>