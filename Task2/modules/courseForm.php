<form method="POST" action="./">
    <div class="tabs">
        <div class="tab active" onclick="openTab(event, 'tab1')">Βασικές Πληροφορίες</div>
        <div class="tab" onclick="openTab(event, 'tab2')">Πρόσθετα</div>
        <div class="tab" onclick="openTab(event, 'tab3')">Course Modules</div>
    </div>

    <div id="tab1" class="tab-content active">
        <div class="col50">
            
            <label>* Course Title (έως 150 χαρακτήρες)</label>
            <div><input required type="text" name="title" placeholder="Εισάγετε τον Τίτλο του Μαθήματος" value="<?php if($_GET['action'] == 'edit') { echo $row['title']; } ?>"></div>

            <label>Overview (έως 500 χαρακτήρες) </label>
            <div><textarea type="text" name="overview" placeholder="Εισάγετε το Overview του Μαθήματος"><?php if($_GET['action'] == 'edit') { echo $row['overview']; } ?></textarea></div>

            <div class="col33">
                <label>Level </label>
                <div>
                    <select name="level">
                        <?php 
                            foreach($LESSON_LESSON_LEVELS as $k => $v) {
                                if($_GET['action'] == 'edit' && $k == $row['level']) { 
                                    echo "<option value='".$k."' selected>".$v."</option>";
                                } else
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
                                if($_GET['action'] == 'edit' && $k == $row['starting']) { 
                                    echo "<option value='".$k."' selected>".$v."</option>";
                                } else
                                    echo "<option value='".$k."'>".$v."</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col33">
                <label>Location </label>
                <div>
                    <input type="text" name="location" placeholder="Εισάγετε το Location του Μαθήματος" value="<?php if($_GET['action'] == 'edit') { echo $row['location']; } ?>">
                </div>
            </div>

            <label>Course Details (έως 2500 χαρακτήρες) </label>
            <div><textarea type="text" name="courseDetails" placeholder="Εισάγετε το Course Details του Μαθήματος"><?php if($_GET['action'] == 'edit') { echo $row['courseDetails']; } ?></textarea></div>

            <label>Entry Requirements (KEYS Section)</label>
            <div><input type="text" name="entryReqsKeys" placeholder="Εισάγετε τα Entry Requirements του Μαθήματος (KEYS Section)" value="<?php if($_GET['action'] == 'edit') { echo $row['entryrequirementsforkeys']; } ?>"></div>

            <label>Entry Requirements (έως 2500 χαρακτήρες) </label>
            <div><textarea type="text" name="entryReqs" placeholder="Εισάγετε τα Entry Requirements του Μαθήματος (FULL)"><?php if($_GET['action'] == 'edit') { echo $row['entryReqsFull']; } ?></textarea></div>
        </div>
        <div class="col50">
            <label>Fees Header (έως 500 χαρακτήρες) </label>
            <div><textarea type="text" name="feesHeader" placeholder="Εισάγετε το Fees Header του Μαθήματος"><?php if($_GET['action'] == 'edit') { echo $row['feesHeader']; } ?></textarea></div>

            <label>Fees Footer (έως 500 χαρακτήρες) </label>
            <div><textarea type="text" name="feesFooter" placeholder="Εισάγετε τα Fees Footer του Μαθήματος"><?php if($_GET['action'] == 'edit') { echo $row['feesFooter']; } ?></textarea></div>

            <label>Student Perks (έως 2500 χαρακτήρες) </label>
            <div><textarea type="text" name="studentPerks" placeholder="Εισάγετε τα Student perks του Μαθήματος"><?php if($_GET['action'] == 'edit') { echo $row['studentPerks']; } ?></textarea></div>

            <label>Integrated Foundation Year (IFY) (έως 2500 χαρακτήρες) </label>
            <div><textarea type="text" name="IFY" placeholder="Εισάγετε το IFY του Μαθήματος"><?php if($_GET['action'] == 'edit') { echo $row['IFY']; } ?></textarea></div>
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
                        <div><input type="number" step="1" name="feevalue[]" min="0" max="99999" value=""></div>
                    </div>
                    <div class="col24">
                        <label>Extras (έως 200 χαρακτήρες)</label>
                        <div><input type="text" name="feeextra[]" placeholder="Εισάγετε Extra πληροφορία για το Fee"></div>
                    </div>
                </div>
                <?php if ($_GET['action'] == 'edit') { 
                    $stmt2 = $MYSQL_CONNECTION->prepare("SELECT * FROM fees WHERE lessonid = :id");
                    $stmt2->bindParam(':id', $lessonid);
                    $stmt2->execute();
                    
                    $first = true;
                    while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) { ?>
                        <div class="fee-template">
                            <div class="text-right"><button type="button" class="remove-fee-button button-danger"> Διαγραφή </button></div>
                            <div class="col24">
                                <label>Region </label>
                                <div>
                                    <select name="feeregion[]">
                                        <?php 
                                            foreach($LESSON_FEE_REGIONS as $k => $v) {
                                                if(!$row2['region'] == $k)
                                                    echo "<option selected value='".$k."'>".$v."</option>";
                                                else
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
                                                if(!$row2['feesType'] == $k)
                                                    echo "<option selected value='".$k."'>".$v."</option>";
                                                else
                                                    echo "<option value='".$k."'>".$v."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col24">
                                <label>Fee (&pound;)</label>
                                <div><input type="number" step="1" name="feevalue[]" min="0" max="99999" value="<?= $row2['value'] ?>"></div>
                            </div>
                            <div class="col24">
                                <label>Extras (έως 200 χαρακτήρες)</label>
                                <div><input type="text" name="feeextra[]" placeholder="Εισάγετε Extra πληροφορία για το Fee" value="<?= $row2['extras'] ?>"></div>
                            </div>
                        </div>
                    <?php }
                } ?>
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
                <?php if($_GET['action'] == 'edit') {
                    $stmt2 = $MYSQL_CONNECTION->prepare("SELECT * FROM codes WHERE lessonid = :id");
                    $stmt2->bindParam(':id', $lessonid);
                    $stmt2->execute();
                    
                    $first = true;
                    while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) { ?>
                        <div class="code-template">
                            <div class="text-right"><button type="button" class="remove-code-button button-danger"> Διαγραφή </button></div>
                            <div class="col50">
                                <label>Code Type </label>
                                <div>
                                    <select name="codetype[]">
                                        <?php 
                                            foreach($LESSON_CODE_TYPES as $k => $v) {
                                                if($row['codeType'] == $k)
                                                    echo "<option selected value='".$k."'>".$v."</option>";
                                                else
                                                    echo "<option value='".$k."'>".$v."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col50">
                                <label>Code (έως 45 χαρακτήρες)</label>
                                <div><input type="text" name="codevalue[]" placeholder="Εισάγετε τον Κωδικό του Course" value="<?= $row2['value'] ?>"></div>
                            </div>
                        </div>
                    <?php }
                } ?>
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
                <?php if($_GET['action'] == 'edit') { 
                    $stmt2 = $MYSQL_CONNECTION->prepare("SELECT * FROM durations WHERE lessonid = :id");
                    $stmt2->bindParam(':id', $lessonid);
                    $stmt2->execute();
                    
                    $first = true;
                    while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) { ?>
                        <div class="duration-template">
                            <div class="text-right"><button type="button" class="remove-duration-button button-danger"> Διαγραφή </button></div>
                            <div class="col50">
                                <label>Duration Type </label>
                                <div>
                                    <select name="durationtype[]">
                                        <?php 
                                            foreach($LESSON_DURATION_TYPES as $k => $v) {
                                                if($row2['durationType'] == $k)
                                                    echo "<option selected value='".$k."'>".$v."</option>";
                                                else
                                                    echo "<option value='".$k."'>".$v."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col50">
                                <label>Διάρκεια (έως 45 χαρακτήρες)</label>
                                <div><input type="text" name="durationvalue[]" placeholder="Εισάγετε την διάρκεια του Course" value="<?= $row2['value'] ?>"></div>
                            </div>
                        </div>
                    <?php }
                } ?>
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
                <?php if($_GET['action'] == 'edit') { 
                    $stmt2 = $MYSQL_CONNECTION->prepare("SELECT * FROM highlights WHERE lessonid = :id");
                    $stmt2->bindParam(':id', $id);
                    $stmt2->execute();
                    while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                            <div class="highlight-template">
                                <div class="text-right"><button type="button" class="remove-highlight-button button-danger"> Διαγραφή </button></div>
                                <div class="">
                                    <label>Highlight </label>
                                    <div><input type="text" name="highlights[]" placeholder="Περιγραφή του highlight" value="<?= $row2['text'] ?>"></div>
                                </div>
                            </div>
                        <?php
                    }
                } ?>
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
                <?php if($_GET['action'] == 'edit') {
                    $stmt2 = $MYSQL_CONNECTION->prepare("SELECT * FROM faqs WHERE lessonid = :id");
                    $stmt2->bindParam(':id', $id);
                    $stmt2->execute();
                    
                    while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) { ?>
                        
                        <div class="faq-template">
                            <div class="text-right"><button type="button" class="remove-faq-button button-danger"> Διαγραφή </button></div>
                            <div class="">
                                <label>Question (έως 300 χαρακτήρες)</label>
                                <div><input type="text" name="faqquestion[]" placeholder="Ερώτηση" value="<?= $row2['question'] ?>"></div>
                            </div>
                            <div>
                                <label>Answer (έως 1000 χαρακτήρες) </label>
                                <div><textarea type="text" name="faqanswer[]" placeholder="Απάντηση"><?= $row2['answer'] ?></textarea></div>
                            </div>
                        </div>
                        
                    <?php }
                }?>
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
                    <label> Module Title (έως 200 χαρακτήρες)</label>
                    <div><input type="text" name="moduletitle[]" placeholder="Εισάγετε τον Τίτλο του Module"></div>
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
        <?php if($_GET['action'] == 'edit') {
            $stmt2 = $MYSQL_CONNECTION->prepare("SELECT * FROM subjects WHERE lessonid = :id");
            $stmt2->bindParam(':id', $lessonid);
            $stmt2->execute();

            while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="module-template">
                    <div class="text-right"><button type="button" class="remove-module-button button-danger"> Διαγραφή </button></div>
                    <div class="col64">
                        <label> Module Title (έως 200 χαρακτήρες)</label>
                        <div><input type="text" name="moduletitle[]" placeholder="Εισάγετε τον Τίτλο του Module" value="<?= $row2['title'] ?>"></div>
                    </div>
                    <div class="col33">
                        <label>Module Code (έως 64 χαρακτήρες)</label>
                        <div><input type="text" name="modulecode[]" placeholder="Εισάγετε τον Κωδικό του Module" value="<?= $row2['code'] ?>"></div>
                    </div>
                    <div class="col33">
                        <label>Stage </label>
                        <div>
                            <select name="modulestage[]">
                                <?php 
                                    foreach($LESSON_STAGES as $k => $v) {
                                        if($row2['stage'] == $k)
                                            echo "<option selected value='".$k."'>".$v."</option>";
                                        else
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
                                        if($row2['status'] == $k)
                                            echo "<option selected value='".$k."'>".$v."</option>";
                                        else
                                            echo "<option value='".$k."'>".$v."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col33">
                        <label>Credits</label>
                        <div><input type="number" step="1" name="modulecredits[]" min="0" max="100" value="<?= $row2['credits'] ?>"></div>
                    </div>
                    <div>
                        <label>Περιγραφή (έως 1500 χαρακτήρες) </label>
                        <div><textarea type="text" name="moduledescription[]" placeholder="Περιγραφή Module"><?= $row2['description'] ?></textarea></div>
                    </div>
                </div>
            <?php }
        }?>
        </div>
        <div>
            <button class="button-edit" type="button" id="add-module-button">Add Module</button>
        </div>
    </div>
                        
    <div class="text-center">
        <hr>
        <input type="hidden" name="action" value="<?= $_GET['action'] ?>" />
        <?php if($_GET['action'] == 'edit') { ?>
            <input type="hidden" name="id" value="<?= $_GET['id'] ?>" />
        <?php } ?>
        <input type="submit" class="button button-edit mt-30" value="<?php if($_GET['action'] == 'edit') { echo "Ανανέωση Course"; } else { echo "Εισαγωγή Course"; } ?> " />
    </div>
</form>

<?php 