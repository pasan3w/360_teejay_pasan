<?php

require '../control/SurveyFeedback.php';

if (!empty($_POST['submit'])) {
  $feedback_array = [];
  echo "<pre>";
  foreach ($_POST as $key => $value) {
    if ($key != 'submit') {
      $arr = explode("-", $key);
      array_push($arr, $value);
      array_push($feedback_array, $arr);
    }
  }

  /*echo "<pre>";
  print_r($feedback_array);
  echo "</pre>";
*/
  //print_r($_SESSION);
  //echo $SurveyorType2Int['External'];
  echo "<div id='contentBox' style='margin-bottom: 20px; color: white;'><center><h3>";
  $response = AddSurveyorFeedbackForEmployee($con, $_SESSION['SurveyPageSURVEYID'], $_SESSION['SurveyPageSelectedID'], $SurveyorTypeString2Enum[$_SESSION['SurveyPageTYPE']], $_SESSION['SurveyPageEID'], $feedback_array);
  echo "</h3></center></div>";

  //echo $response;
  
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thank you</title>
    <link rel="stylesheet" type="text/css" href="../view/css/survey_page_hayleys.css">
    <!-- product sans cdn for fonts -->
    <link href="https://fonts.cdnfonts.com/css/product-sans" rel="stylesheet">

</head>
<body>
    <center>
        <div style="position: absolute; top: 0; width: 98%; display: block; font-size: 20px; white-space: normal; ">
            <?php
                if ($response == 'error') {
                    echo "<div id='contentBox' style='margin-top: 20px;'><center><h3>There was an error in submitting you response.<br> Please close this window and try again with the link you got.</h3></center></div>";
                } elseif ($response == 'success') {
                    echo "<div id='contentBox' style='margin-top: 20px;max-width: 60%;'>
                            <center><img src='../img/surveyPage/360_big_logo.png' style='max-width: 80%;'></center><br><br><br>";

                    echo "Your input is crucial in providing a comprehensive view of performance. We appreciate your time and thoughtful responses in our Multipoint Survey. <br>

                        Your feedback contributes to fostering a culture of continuous improvement and development. If you have any additional comments or insights to share, please don't hesitate to reach out.  Your input is essential to our collective success. <br>

                        Thank you for your commitment to excellence! <br>

                        3W Consulting Team</div>";
                } else {
                    echo "<div id='contentBox' style='margin-top: 20px;'><center><h3>There was an error in submitting you response.<br> Please close this window and try again with the link you got.</h3></center></div>";
                }
            ?>
        </div>
    </center>
    <div id="Validation_footer" style="bottom: 0; width: 100%; position: fixed; color: white; background: rgba(0, 0, 255, 1);font-size: 16px; text-align: center;">
            <b>Copyright &#169; 2024 All Rights Reserved. Multipoint Survey Tool powered by 3W Consulting (Pvt) Ltd.</b>
    </div>
</body>
</html>