<?php
    // Setup page helper vars and start session
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

    <!-- Import Chart.js -->
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
            //Print messages for delete/add/update course
            if(!empty($_GET) && !empty($_GET['deleteresult']) && $_GET['deleteresult'] == 'success') {
                echo '<div class="alert alert-success"> Course was successfully deleted. </div>';
            }

            if(!empty($_GET) && !empty($_GET['insertresult']) && $_GET['insertresult'] == 'success') {
                echo '<div class="alert alert-success"> Course was successfully inserted. </div>';
            } 

            if(!empty($_GET) && !empty($_GET['updateresult']) && $_GET['updateresult'] == 'success') {
                echo '<div class="alert alert-success"> Course was successfully updated. </div>';
            }
        
        // Show back to list button in case of a report
        if(!empty($_GET['action']) && $_GET['action'] == 'report') {
            echo '<h2>REPORT RESULTS <button class="button button-sm button-edit"> <a href="../list/">Επιστροφή στη λίστα</a> </button>  </h2>';
        } else {
            // show create report button
            echo '<h2>COURSE LIST  <button class="button button-sm button-info" onclick="createReport()"> Generate Report </button>  </h2>';        
        }
        ?>
        <!-- Table setup -->
        <table>
            <tr>
                <?php if(empty($_GET['action']) || $_GET['action'] != 'report') { 
                    $STATISTICS = [];
                    ?>
                    <th> <input type="checkbox" id="allcheckboxes" class="course-checkbox" /> </th>
                <?php } ?>
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
                // get all selected courses from GET['ids'] in case of a report
                try {
                    if(!empty($_GET['action']) && $_GET['action'] == 'report') {
                        $stmt = $MYSQL_CONNECTION->prepare("SELECT * FROM lessons WHERE id IN(".$_GET['ids'].") ORDER BY title asc");
                    } else {
                        // if no report is selected then show in a table all teh courses
                        $stmt = $MYSQL_CONNECTION->prepare("SELECT * FROM lessons ORDER BY title asc");
                        
                    }

                    $stmt->execute();
                    // go through all the courses
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $credits = "0";
                        // query to get the total credits based on teh course modules
                        $stmt2 =  $MYSQL_CONNECTION->prepare("SELECT SUM(credits) AS total_credits FROM subjects where lessonid = :id");
                        $stmt2->bindParam(':id', $row['id']);
                        $stmt2->execute();
                        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

                        // if there are modules with credits assign teh SUM to $credits var
                        if ($row2) {
                            // var_dump($row2);
                            $credits = $row2['total_credits'];
                        }

                        // in case of a report
                        if(!empty($_GET['action']) && $_GET['action'] == 'report') {
                            
                            //get all the modules of teh current course
                            $stmt3 = $MYSQL_CONNECTION->prepare("SELECT * FROM subjects WHERE lessonid = :id");
                            $stmt3->bindParam(':id', $row['id']);
                            $stmt3->execute();

                            // store modules per stage
                            $subjects = [
                                1 => [],
                                2 => [],
                                3 => [],
                                4 => [],
                                5 => [],
                                6 => []
                            ];

                            $subjectLabels = []; // save in array module labels for carts
                            $subjectCredits = []; // save in array module credits for carts
                            $subjectColors = []; // save in array module colors for carts

                            // $subjects[$row3['stage']][0] = 0;
                            // go through modules
                            while ($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {
                                $row3['status'] = $LESSON_STATUS[$row3['status']]; // get status

                                // initiate stage
                                if(empty($subjects[$row3['stage']][0]))
                                    $subjects[$row3['stage']][0] = 0;

                                // sum up credits per stage
                                $subjects[$row3['stage']][0] += $row3['credits'];
                                // save module per stage
                                $subjects[$row3['stage']][1][] = $row3;

                                $subjectLabels[] = $row3['title']." (".$row3['code'].")"; // update labels
                                $subjectCredits[] = $row3['credits']; // update credits
                                $subjectColors[] = generateRandomColor(); //create a random color
                            }
                        }

                        // print table rows, one row per module
                        echo "<tr>";
                            // in case of no report include a checkbox 
                            if(empty($_GET['action']) || $_GET['action'] != 'report')  
                                echo '<td> <input type="checkbox" id="checkbox-'.$row['id'].'" class="course-checkbox"/> </td>';
                            echo "<td>".$row['id']."</td>";
                            echo "<td class='roboto-bold'>".$row['title']."</td>";
                            echo "<td>".$LESSON_LESSON_LEVELS[$row['level']]."</td>";
                            echo "<td>".$LESSON_STARTING[$row['starting']]."</td>";
                            echo "<td>".$row['location']."</td>";
                            echo "<td><div title='".$row['overview']."'>".substr($row['overview'], 0, 100)."</div></td>";
                            echo "<td> ".$credits." </td>";
                            // print action buttons
                            echo "<td>";
                                echo '<button class="buttonTable button-info"> <a href="../course/?action=view&id='.$row['id'].'" target="_blank"> VIEW </a> </button>';
                                echo '<button class="buttonTable button-edit"> <a href="../course/?action=edit&id='.$row['id'].'" target="_blank">EDIT </a></button>';
                                ?>
                                
                                <button class="buttonTable button-danger" id="course-<?= $row['id'] ?>"><a href="../course?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Είστε σίγουροι για την μόνιμη διαγραφή του Course και όλων των δεδομένων τους?')" >DELETE</a></button>
                            
                            <?php echo "</td>";
                        echo "</tr>";

                        // in case of report save all the course info in teh STATISTICS array
                        if(!empty($_GET['action']) && $_GET['action'] == 'report') {
                            $STATISTICS[$row['id']] = [$row['id'], $row['title'], $credits, $subjects, $subjectLabels, $subjectCredits, $subjectColors];
                        }
                    }

                } catch(PDOException $e) {
                    // print erorr in case of mysql error
                    echo "Error: " . $e->getMessage();
                }

            ?>
        </table>
        
        <?php if(!empty($_GET['action']) && $_GET['action'] == 'report') { ?>
            <div class="mt-30 text-center">
                <?php
                // in case of report setup the graph data
                    $COURSESIDS = [];
                    $COURSESMODULES = [];
                    $COURSESCREDITS = [];
                    $COURSESCOLORS = [];
                    $COURSESNAMES = [];
                ?>
                <?php foreach($STATISTICS as $id => $data) { 
                    // gor through courses with $id => $data
                    if(empty($data[2]))
                        $data[2] = 0;

                    // $COURSESIDS[] = [$id, count($data[4]), $data[2]];

                    // setup graph info
                    $COURSESIDS[] = $id;
                    $COURSESMODULES[] = count($data[4]);
                    $COURSESCREDITS[] = $data[2];
                    $COURSESCOLORS[] = generateRandomColor();
                    $COURSESNAMES[] = "'".$data[1]."'";

                    // add the modules title
                    ?>
                    <h3> <?= $data[1] ?> (Modules: <?= count($data[4]) ?> | Total Credits: <?= $data[2] ?>) <button class="buttonTable button-edit"> <a href="../course/?action=edit&id=<?= $id ?>" target="_blank"> EDIT </a></button> </h3>
                    <div class="col50 badge">
                    <!-- Charts placeholder -->
                        <canvas id="reportChart-<?= $data[0] ?>" class="mychart"></canvas>
                        <script>
                        // charts options setup
                            var options = {
                                legend: { display: false },
                                title: { display: true,  text: "<?= $data[1] ?> - Credits per Module" },
                                tooltips: { enabled: true },
                                plugins: {
                                    datalabels: {
                                        color: 'white',
                                        font: {
                                            size: 14,
                                            weight: 'bold'
                                        },
                                        formatter: (value, ctx) => {
                                            return ctx.chart.data.
                                                labels[ctx.dataIndex] + ': ' + value;
                                        }
                                    }
                                }
                            };

                            // setup values
                            var xValues = ["<?= implode("\",\"", $data[4]) ?>"];
                            var yValues = [<?= implode(",", $data[5]) ?>];
                            var colors = [<?= implode(",", $data[6]) ?>];

                            // create new chart
                            new Chart("reportChart-<?= $data[0] ?>", {
                                type: "pie",
                                data: {
                                    labels: xValues,
                                    datasets: [{
                                        label: '# Credits',
                                        backgroundColor: colors,
                                        data: yValues
                                    }]
                                },
                                options: options
                            });
                        </script>
                    </div>
                    <div class="col50 badge">
                    <canvas id="reportChartStage-<?= $data[0] ?>" class="mychart"></canvas>
                        <?php
                            $labels = [];
                            $stagedata = [];
                            $colors = [];
                            $i = 1;
                            foreach($data[3] as $stage) {
                                if(count($stage) > 0) {
                                    $labels[] = "'Stage ".$i."'";
                                    $stagedata[] = $stage[0];
                                    $colors[] = generateRandomColor();
                                }
                                $i++;
                            }
                        ?>
                        <script>
                            var options = {
                                legend: { display: false },
                                title: { display: true,  text: "<?= $data[1] ?> - Credits per Stage" },
                                tooltips: { enabled: true }
                            };

                            new Chart("reportChartStage-<?= $data[0] ?>", {
                                type: "pie",
                                data: {
                                    labels: [<?= implode(",", $labels) ?>],
                                    datasets: [{
                                        backgroundColor: [<?= implode(",", $data[6]) ?>],
                                        data: [<?= implode(",", $stagedata) ?>]
                                    }]
                                },
                                options: options
                            });
                        </script>
                    </div>
                <?php } ?>
            </div>
            
            <?php if(count($COURSESIDS) > 1) { ?>
                <div class="mt-30 text-center">
                    <h3>ΣΥΝΟΛΙΚΑ ΣΤΑΤΙΣΤΙΚΑ</h3>
                    <div class="col50 badge">
                        <canvas id="coursesCredits" class="mychart"></canvas>
                        <script>
                            var xValues = [<?= implode(",", $COURSESNAMES) ?>];
                            var yValues = [<?= implode(",", $COURSESCREDITS) ?>];
                            var barColors = [<?= implode(",", $COURSESCOLORS) ?>];

                            new Chart("coursesCredits", {
                            type: "bar",
                            data: {
                                labels: xValues,
                                datasets: [{
                                    label: "Credits",
                                    backgroundColor: barColors,
                                    data: yValues
                                }]
                            },
                            options: {
                                legend: {display: false},
                                title: {
                                    display: true,
                                    text: "Total Credits per Course"
                                },
                                scales: {
                                    yAxes: [{
                                        display: true,
                                        ticks: {
                                                beginAtZero: true
                                            }
                                    }],
                                    xAxes: [{
                                        display: true,
                                    }],
                                }
                            }
                            });
                        </script>
                    </div>
                    <div class="col50 badge">
                        <canvas id="coursesModules" class="mychart"></canvas>
                        <script>
                            var xValues = [<?= implode(",", $COURSESNAMES) ?>];
                            var yValues = [<?= implode(",", $COURSESMODULES) ?>];
                            var barColors = [<?= implode(",", $COURSESCOLORS) ?>];

                            new Chart("coursesModules", {
                                type: "bar",
                                data: {
                                    labels: xValues,
                                    datasets: [{
                                        label: "Credits",
                                        backgroundColor: barColors,
                                        data: yValues
                                    }]
                                },
                                options: {
                                    legend: {display: false},
                                    title: {
                                        display: true,
                                        text: "Total Modules per Course"
                                    },
                                    scales: {
                                        yAxes: [{
                                            display: true,
                                            ticks: {
                                                    beginAtZero: true
                                                }
                                        }],
                                        xAxes: [{
                                            display: true,
                                        }],
                                    }
                                }
                            });
                        </script>
                    </div>

                    <div class="badge">
                        <canvas id="coursesStages" class="mychart"></canvas>
                        <script>
                            var xValues = ["Stage 1", "Stage 2", "Stage 3"];
                            var barColors = [<?= implode(",", $COURSESCOLORS) ?>];

                            new Chart("coursesStages", {
                                type: "bar",
                                data: {
                                    labels: xValues,
                                    datasets: [
                                        <?php foreach($STATISTICS as $id => $data) { 
                                            if(empty($data[3][1][0]))
                                                $data[3][1][0] = 0;
                                            if(empty($data[3][2][0]))
                                                $data[3][2][0] = 0;
                                            if(empty($data[3][3][0]))
                                                $data[3][3][0] = 0;
                                            $color = generateRandomColor();
                                            ?>
                                            {
                                                label: "<?= $data[1] ?>",
                                                backgroundColor: [<?= $color ?>,<?= $color ?>,<?= $color ?> ],
                                                data: [<?= $data[3][1][0] ?>,<?= $data[3][2][0] ?>,<?= $data[3][3][0] ?>]
                                            },
                                        <?php } ?>
                                    ]
                                },
                                options: {
                                    legend: {display: false},
                                    title: {
                                        display: true,
                                        text: "Total Credits per Course per Stage"
                                    },
                                    scales: {
                                        yAxes: [{
                                            display: true,
                                            ticks: {
                                                    beginAtZero: true
                                                }
                                        }],
                                        xAxes: [{
                                            display: true,
                                        }],
                                    }
                                }
                            });
                        </script>
                    </div>

                    <div class="badge">
                        <canvas id="coursesModulesStages" class="mychart"></canvas>
                        <script>
                            var xValues = ["Stage 1", "Stage 2", "Stage 3"];
                            var barColors = [<?= implode(",", $COURSESCOLORS) ?>];

                            new Chart("coursesModulesStages", {
                                type: "bar",
                                data: {
                                    labels: xValues,
                                    datasets: [
                                        <?php foreach($STATISTICS as $id => $data) { 
                                            if(empty($data[3][1][1]))
                                                $data[3][1][1] = [];
                                            if(empty($data[3][2][1]))
                                                $data[3][2][1] = [];
                                            if(empty($data[3][3][1]))
                                                $data[3][3][1] = [];
                                            
                                            $color = generateRandomColor();

                                            ?>
                                            {
                                                label: "<?= $data[1] ?>",
                                                backgroundColor: [<?= $color ?>,<?= $color ?>,<?= $color ?> ],
                                                data: [<?= count($data[3][1][1]) ?>,<?= count($data[3][2][1]) ?>,<?= count($data[3][3][1]) ?>]
                                            },
                                        <?php } ?>
                                    ]
                                },
                                options: {
                                    legend: {display: false},
                                    title: {
                                        display: true,
                                        text: "Total Modules per Course per Stage"
                                    },
                                    scales: {
                                        yAxes: [{
                                            display: true,
                                            ticks: {
                                                    beginAtZero: true
                                                }
                                        }],
                                        xAxes: [{
                                            display: true,
                                        }],
                                    }
                                }
                            });
                        </script>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </main>

    <footer><?php require_once($URLPREFIX."modules/footer.php"); ?></footer>
</body>
<script>
    function createReport() {
        // console.log("clicked");
        const rowCheckboxes = document.querySelectorAll('.course-checkbox:checked');
        var ids = []
        rowCheckboxes.forEach(checkbox => {
            // console.log(checkbox.id)
            var temp = checkbox.id.split("-")[1]
            if(temp)
                ids.push(temp);
        });

        if(ids.length == 0) {
            alert("Πρέπει να επιλέξετε Courses για την δημιουργία του report");
        } else {
            // console.log(ids);
            window.location.href = "./?action=report&ids=" + ids;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('allcheckboxes');
        const rowCheckboxes = document.querySelectorAll('.course-checkbox');

        // Add event listener to the select-all checkbox
        if(selectAllCheckbox) {
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
        }
    });
</script>

</html>