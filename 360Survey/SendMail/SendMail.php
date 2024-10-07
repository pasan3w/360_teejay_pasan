<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


require 'vendor/autoload.php';

$AppConfig = include('SendMailConfig.php');

date_default_timezone_set($AppConfig['time_zone']);


function sendmail($user_name, $surveyor_name, $surveyor_type, $surveyor_email, $url){
  global $AppConfig;
  $mail = new PHPMailer();
  $mail->isSMTP();
  $mail->Host = $AppConfig['smtp_server'];
  $mail->SMTPDebug = $AppConfig['debug_level'];
  $mail->SMTPAuth = true;
  $mail->Username = $AppConfig['user_email'];
  $mail->Password = $AppConfig['password'];
  $mail->Port = $AppConfig['primary_port'];
  $mail->Timeout = $AppConfig['time_out'];
  $mail->setFrom($AppConfig['user_email'], $AppConfig['user_name']);
  $mail->addAddress($surveyor_email, $surveyor_name);
  $mail->isHTML(true);
  $mail->Subject = sprintf("%s Survey Request for Employee: %s", $surveyor_type, $user_name);
  $mail->Body =<<<EOT
<!DOCTYPE html>
<html lang="en">
<head>
  <title>360 Survey  Invite</title>
</head>
<body>
<h2>360Survey Request for $surveyor_name</h2>
<p>
<b>THIS IS A TEST EMAIL, IF YOU RECEIVE, PLEASE LET PASAN KNOW</b>
Please carry out the 360Survey evaluation as <b>$surveyor_type</b> of Employee:<b>$user_name</b>.<br>
Provide your feedback at: $url<br>
Once complete, please press the <b>Submit</b> button to upload your Feedback.<br>
Appreciate your help in completing this survey at your earliest convenience.<br>
<br>
Thank You
</body>
</html>
EOT;
  $mail->AltBody =<<<EOT
------360Survey Request for $surveyor_name------
Please complete the $surveyor_type 360Survey evaluation of Employee: $user_name.
Provide your feedback at: $url
Once complete, please press the Submit button to forward your Feedback.
Appreciate your help in completing this survey at your earliest convenience.

Thank You
EOT;
  if($mail->send()){
    echo 'Email sent successfully through the primary port:' . $AppConfig['primary_port'] . "\n";
    return true;
  }else{
    printf("Failed to send Message through primary port: %s\n", $mail->ErrorInfo);
    return false;
  }
   
}

function sendmail_backup_port($user_name, $surveyor_name, $surveyor_type, $surveyor_email, $url){
  global $AppConfig;
  $mail = new PHPMailer();
  $mail->isSMTP();
  $mail->Host = $AppConfig['smtp_server'];
  $mail->SMTPDebug = $AppConfig['debug_level'];
  $mail->SMTPAuth = true;
  $mail->Username = $AppConfig['user_email'];
  $mail->Password = $AppConfig['password'];
  $mail->Port = $AppConfig['secondary_port'];
  $mail->Timeout = $AppConfig['time_out'];
  $mail->setFrom($AppConfig['user_email'], $AppConfig['user_name']);
  $mail->addAddress($surveyor_email, $surveyor_name);
  $mail->isHTML(true);
  $mail->Subject = sprintf("%s Survey Request for Employee: %s", $surveyor_type, $user_name);
  $mail->Body =<<<EOT
<!DOCTYPE html>
<html lang="en">
<head>
  <title>360 Survey  Invite</title>
</head>
<body>
<h2>360Survey Request for $surveyor_name</h2>
<p>
Please carry out the 360Survey evaluation as <b>$surveyor_type</b> of Employee:<b>$user_name</b>.<br>
Provide your feedback at: $url<br>
Once complete, please press the <b>Submit</b> button to upload your Feedback.<br>
Appreciate your help in completing this survey at your earliest convenience.<br>
<br>
Thank You
</body>
</html>
EOT;
  $mail->AltBody =<<<EOT
------360Survey Request for $surveyor_name------
Please complete the $surveyor_type 360Survey evaluation of Employee: $user_name.
Provide your feedback at: $url
Once complete, please press the Submit button to forward your Feedback.
Appreciate your help in completing this survey at your earliest convenience.

Thank You
EOT;
  if($mail->send()){
    echo 'Email sent successfully through the backup port:' . $AppConfig['secondary_port'] . "\n";
    return true;
  }else{
    printf("Failed to send Message through backup port: %s\n", $mail->ErrorInfo);
    return false;
  }
  
}
