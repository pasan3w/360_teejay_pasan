<?php

include '../Common/DbOperations.php';

$stmt                   = $con->prepare("SELECT MAX(SurveyID) AS max_id FROM Survey");
$stmt -> execute();
$invNum                 = $stmt -> fetch(PDO::FETCH_ASSOC);
$max_id                 = $invNum['max_id'];
$_SESSION['survey_id']  = $max_id;

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Select Surveyee</title>

    <?php 
        require_once 'includes/head.php';
    ?>

    <script type="text/javascript">
        $(document).ready(function(){ 

            $("#survey360").click(function(){
                window.open('selectSurveyee.php', "_self");
            });

            $("#surveyClimate").click(function(){
                window.open('climateSurvey.php', "_self");
            });

        });
    </script>
</head>
<body>
    <?php 
        include 'includes/navbar.php';
    ?>
    <div id="contentBox" style="height: 60vh;">
        <center>
            <h2 style="margin-top: 15px; margin-bottom: 15px;">Survey no : <?= $_SESSION['survey_id'] ?></h2>
            <hr>

            <button class="btn btn-primary" id="survey360">360 Survey</button>
            <button class="btn btn-primary" id="surveyClimate">Climate Survey</button>
            
        </center>
    </div>

</body>
</html>