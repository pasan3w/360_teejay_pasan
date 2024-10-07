<?php 

session_start();
require '../../SendMail/SendMail.php';

function mailHandler($surveyee, $surveyor, $surveyor_type, $surveyor_email, $url) {
  if ($surveyee == '' || $surveyor == '' || $surveyor_type == '' || $surveyor_email == '' || $url == '') {
    $_SESSION['message'] = "Some field data were empty!";
    echo "Some field data were empty!";
    return;
  } else {
    if (re_sendmail($surveyee, $surveyor, $surveyor_type, $surveyor_email, $url)){
      $_SESSION['message'] = "Survey successfully sent.\n";
      echo "Survey successfully sent.\n";
      return;
    } else {
      $_SESSION['message'] = "Email sending failed.\n";
      echo "Email sending failed.\n";
      return;
    }
  }
}

if (!empty($_POST['submit'])) {
    $surveyee       = $_POST['surveyee_name'];
    $surveyor       = $_POST['surveyor_name'];
    $surveyor_type  = $_POST['surveyor_type'];
    $surveyor_email = $_POST['surveyor_email'];
    $url            = $_POST['url']; 

    mailHandler($surveyee, $surveyor, $surveyor_type, $surveyor_email, $url);
}

?>