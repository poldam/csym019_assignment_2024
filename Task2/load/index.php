<?php
    $URLPREFIX = "../"; // define prefix

    session_name('CYM019');  //start session
    session_start();

    $PAGE = "load"; // page name

    require_once($URLPREFIX.'modules/lib.php'); // import lib.php file

    // if user is not loggedin then we redirect them to the login screen
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
        <h2>Load Courses/Modules from JSON/Task 1 to Test Task 2</h2>
        <?php
            $jsonFile = '../course.json';

            // Read the JSON file content
            $jsonData = file_get_contents($jsonFile);
            
            // Parse the JSON data into a PHP array
            $lessons = json_decode($jsonData, true);
            // Go through all teh courses
            foreach($lessons as $lesson) {
                $id = (int) $lesson['id']; // get course ID
                echo  "Processing: ".$lesson['title']."<br>";

                $title = substr($lesson['title'], 0, 150); //trim course title
                $overview = substr($lesson['overview'], 0, 4000); //trim course overview
                $level = array_search($lesson['KEYFACTS']['Level'], $LESSON_LESSON_LEVELS); // find the level from the lib.php > $LESSON_LESSON_LEVELS var 
                $starting = array_search($lesson['KEYFACTS']['Starting'], $LESSON_STARTING); // find the starting month from the lib.php > $LESSON_STARTING var 

                $entryrequirementsforkeys = ""; // initiate teh entryrequirements as blank
                if(!empty($lesson['KEYFACTS']["EntryRequirements"])) //if there are entry requirements in the KEYFACTS section of the json
                    $entryrequirementsforkeys = substr($lesson['KEYFACTS']["EntryRequirements"], 0, 500); // update the var after triming it to make sure it fits the MySQL column definition

                $location = substr($lesson['KEYFACTS']["Location"], 0, 45); // trim teh location field
                $courseDetails = substr($lesson['COURSECONTENT']['CourseDetails']['html'], 0, 2500); // trim the course detais html

                // setup course content entry requirements and trim the html
                $entryReqsFull = "";
                if(!empty($lesson['COURSECONTENT']['EntryRequirements']))
                    $entryReqsFull = substr($lesson['COURSECONTENT']['EntryRequirements']['html'], 0, 2500);

                // trim the fees header text
                $feesHeader = substr($lesson['COURSECONTENT']['FeesandFunding']['text'], 0, 500);

                // set up and trim the fees footer text
                $feesFooter = "";
                if(!empty($lesson['COURSECONTENT']['FeesandFunding']['AdditionalCosts']))
                    $feesFooter = substr($lesson['COURSECONTENT']['FeesandFunding']['AdditionalCosts'], 0, 500);
                // set up and trim the student perks text
                $studentPerks = "";
                if(!empty($lesson['COURSECONTENT']["StudentPerks"]))
                    $studentPerks = substr($lesson['COURSECONTENT']["StudentPerks"]['html'], 0, 2500);
                // set up and trim the IFY text
                $IFY = "";
                if(!empty($lesson['COURSECONTENT']["IntegratedFoundationYear"]))
                    $IFY = substr($lesson['COURSECONTENT']["IntegratedFoundationYear"]['html'], 0, 2500);
                

                try {
                    // select the course based on their ID
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
                        // Row doesn't exist -> we insert teh course
                        $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO lessons (title, overview, `level`, `starting`, entryrequirementsforkeys, `location`, courseDetails, entryReqsFull, feesHeader, feesFooter, studentPerks, IFY) 
                                                VALUES (:title, :overview, :level, :starting, :entryrequirementsforkeys, :location, :courseDetails, :entryReqsFull, :feesHeader, :feesFooter, :studentPerks, :IFY) ");
                    }

                    // parameters binding for insert/update
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
                          
                    $stmt2->execute(); // execute query

                    // if execution was successful we need to get teh latest inserted id in order to accossiate the course to their details 
                    if(!$row) {
                        $id = $MYSQL_CONNECTION->lastInsertId();
                    }

                    ######### UCASCode
                    // delete existing codes 
                    $stmt2 = $MYSQL_CONNECTION->prepare("DELETE FROM codes WHERE lessonid = :lessonid");
                    $stmt2->bindParam(':lessonid', $id);
                    $stmt2->execute();

                    // go through the list of codes and insert them using teh course id
                    if(!empty($lesson['KEYFACTS']['UCASCode'])) {
                        foreach($lesson['KEYFACTS']['UCASCode'] as $d) {
                            $codetype = array_search($d['type'], $LESSON_CODE_TYPES);
                            $value = substr($d['value'], 0, 45); // trim value
                            $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO codes (lessonid, codetype, `value`) VALUES (:lessonid, :codetype, :value)");
                            $stmt2->bindParam(':lessonid', $id);   
                            $stmt2->bindParam(':codetype', $codetype);
                            $stmt2->bindParam(':value', $value);
                            $stmt2->execute();
                        }
                    }

                    ######### DURATIONS
                    // delete all existing durations for that lesson
                    $stmt2 = $MYSQL_CONNECTION->prepare("DELETE FROM durations WHERE lessonid = :lessonid");
                    $stmt2->bindParam(':lessonid', $id);
                    $stmt2->execute();

                    // go through the list of durations and insert them using the course id
                    foreach($lesson['KEYFACTS']['Duration'] as $d) {
                        $durationtype = array_search($d['type'], $LESSON_DURATION_TYPES); // get teh type from the global var
                        $value = substr($d['value'], 0, 45); // trim value
                        $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO durations (lessonid, durationtype, `value`) VALUES (:lessonid, :durationtype, :value)");
                        $stmt2->bindParam(':lessonid', $id);   
                        $stmt2->bindParam(':durationtype', $durationtype);
                        $stmt2->bindParam(':value', $value);
                        $stmt2->execute();
                    }

                    ######### HIGHLIGHTS
                    // delete all existing highlights
                    $stmt2 = $MYSQL_CONNECTION->prepare("DELETE FROM highlights WHERE lessonid = :lessonid");
                    $stmt2->bindParam(':lessonid', $id);
                    $stmt2->execute();

                    // go through the list of highlights and insert them using teh course id
                    foreach($lesson['highlights'] as $highlight) {
                        $highlight = substr($highlight, 0, 500); // trim highlights text
                        $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO highlights (lessonid, `text`) VALUES (:lessonid, :highlight)");
                        $stmt2->bindParam(':lessonid', $id);   
                        $stmt2->bindParam(':highlight', $highlight);
                        $stmt2->execute();
                    }
                    #################################
                    ######### FEES
                    // delete existing fees of that course
                    $stmt2 = $MYSQL_CONNECTION->prepare("DELETE FROM fees WHERE lessonid = :lessonid");
                    $stmt2->bindParam(':lessonid', $id);
                    $stmt2->execute();

                    // go through the list of fees and insert them using teh course id
                    foreach($lesson['COURSECONTENT']['FeesandFunding']['Fees'] as $fee) {
                        $temp = explode("-", $fee['type']);
                        
                        // get region and type and trim their values
                        if($temp && count($temp) > 1) {
                            $region = array_search(trim($temp[0]), $LESSON_FEE_REGIONS);
                            $feestype = array_search(trim($temp[1]), $LESSON_FEE_TYPES);
                        } else {
                            $feestype = array_search(trim($temp[0]), $LESSON_FEE_TYPES);
                        }

                        $extras = substr($fee['extra'], 0, 145); // trim the extras
                        $value = (float) $fee['value']; // make sure the value is of type float

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
                    // delete existing QnA for the current course
                    $stmt2 = $MYSQL_CONNECTION->prepare("DELETE FROM faqs WHERE lessonid = :lessonid");
                    $stmt2->bindParam(':lessonid', $id);
                    $stmt2->execute();
                    // go through the list of Q/A and insert them using the course id
                    if(!empty($lesson['COURSECONTENT']["FAQs"]['questions'])) {
                        foreach($lesson['COURSECONTENT']["FAQs"]['questions'] as $faq) {
                            $q = substr($faq['q'], 0, 300); // trim question
                            $a = substr($faq['a'], 0, 1000); // trim answer

                            // insert query after binding params
                            $stmt2 = $MYSQL_CONNECTION->prepare("INSERT INTO faqs (lessonid, `question`, `answer`) VALUES (:lessonid, :q, :a)");
                            $stmt2->bindParam(':lessonid', $id);   
                            $stmt2->bindParam(':q', $q);
                            $stmt2->bindParam(':a', $a);
                            $stmt2->execute();
                        }
                    }
                    #################################
                    #########SUBJECTS
                    // go through the course stages
                    foreach($lesson['COURSECONTENT']['CourseDetails']['stages'] as $stage) {
                        $curstage = array_search($stage['name'], $LESSON_STAGES); // get the course stage

                        // go through the modules of the current stage
                        foreach($stage['modules'] as $subject) {
                            $code = substr($subject['code'], 0, 64); // trim code
                            $title = substr($subject['name'], 0, 200); // trim title
                            $status = array_search($subject['status'], $LESSON_STATUS); // get status
                            $credits = (int) $subject['credits']; // make sure the credits are of type int for using it in graphs
                            $description = substr($subject['description'], 0, 1500); //trim description

                            // chekc of module already exists
                            $stmt2 = $MYSQL_CONNECTION->prepare("SELECT code FROM subjects WHERE lessonid = :lessonid AND code = :code");
                            $stmt2->bindParam(':lessonid', $id);
                            $stmt2->bindParam(':code', $code);
                            $stmt2->execute();
                            $row = $stmt2->fetch(PDO::FETCH_ASSOC);
                            
                            // if module exists then perform an update
                            if ($row) {
                                $stmt2 = $MYSQL_CONNECTION->prepare("UPDATE subjects SET lessonid = :lessonid, title = :title, `status` = :status, code = :code, credits = :credits, stage = :stage, `description` = :description WHERE lessonid = :lessonid AND code = :code");
                                $stmt2->bindParam(':code', $code);   
                            } else {
                                // if module is not present then insert it
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
                    echo "Record updated/inserted successfully!<br><br>";
                } catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
        ?>
    </main>
    <!-- Import footer -->
    <footer><?php require_once($URLPREFIX."modules/footer.php"); ?></footer>
</body>

</html>