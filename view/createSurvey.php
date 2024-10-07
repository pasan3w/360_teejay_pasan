<?php

include '../Common/DbOperations.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <?php 
        require_once 'includes/head.php';
    ?> 

    <script type="text/javascript">
        $(document).ready(function(){

            // AJAX CALL FOR SELECTING AND SHOWING QUESTIONARE WHEN QUESTION LIST ID IS SELECTED FROM DROP DOWN

            $("#questionListId").change(function(){

            var questionListID=$("#questionListId").val();

                $.ajax({
                    url:'../control/getQuestions.php',
                    method:'POST',
                    data:{
                        questionListID:questionListID
                    },
                   success:function(data){
                       $(".result").html(data);
                   }
                });
            });

            // WHEN CREATE SURVEY BUTTON CLICKED

            $("#createSurveyButton").click(function(){

                var questionListID=$("#questionListId").val();

                $.ajax({
                    url:'../control/createSurvey.php',
                    method:'POST',
                    data:{
                        questionListID:questionListID
                    },
                    success:function(data){
                        alert(data);
                        if (data == 'Survey Created Successfully.') {
                            window.location.href = "surveySelection.php";
                        }
                    }
                });            
            });            
        });
    </script>

    <title>Create Survey Page</title>
</head>
<body>
    <?php 
        include 'includes/navbar.php';
    ?>
    <div id="contentBox">
        <center>
            <br>
            <h3>Create Survey</h3>
            <br><br>
            <form action="" method="post">
                <select name="QuestionListID" class="form-select" id="questionListId" style="width: 40%;">
                    <<option>Select a questionare from this list</option>
                    <?php 
                    $query  = "SELECT QuestionListID, FilePath FROM QuestionListTable";
                    $result = $con->query($query);

                    if ($result->rowCount() != 0) {
                        while ($optionData = $result->fetch(PDO::FETCH_ASSOC)) {
                            $selectedQuestionListID = "";
                            $Questions              = [];
                            $option                 = $optionData['FilePath'];
                            $id                     = $optionData['QuestionListID'];
                            $selected               = ($id === $selectedQuestionListID) ? 'selected' : ''; // Check if this option was selected
                            ?>
                            <option value="<?php echo $id; ?>" <?php echo $selected; ?>><?php echo $id; ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </form>
        </center>
            <p>
                <div class="result" style="font-size: 18px; margin-left:10px;"></div>            
            </p>
            <hr>
            
            <div class="surveyResult" style="margin-bottom: 5px; color: green;"></div>

        <button id="createSurveyButton" class="btn btn-primary" style="margin-left: 40px; margin-bottom: 40px;">Create Survey</button>

        <a href="surveySelection.php" id="nextButton" class="btn btn-primary" style="float: right; margin-right: 40px;">Next</a>
    </div>    
</body>
</html>
