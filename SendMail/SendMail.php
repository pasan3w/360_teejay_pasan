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
  <title>Multipoint Survey Invite</title>
</head>
<body>
<h2>Multipoint Survey Request for $surveyor_name</h2>

<h2>Dear $surveyor_name, </h2>
<p>
Welcome to the Hayleys Advantis Limited Multipoint Survey powered by 3W Consulting (Pvt) Ltd. <br><br>

Your feedback is invaluable in helping us understand and improve various aspects of our organization. This survey provides an opportunity for comprehensive feedback from multiple perspectives, including self-assessment and feedback from peers, managers and direct reports. <br><br>

Please carry out the Multipoint Evaluation as <b>$surveyor_type</b> of Employee:<b>$user_name</b>.<br><br>

Survey Link: $url <br><br>

<b>VERY IMPORTANT:</b></p>
<ul>
    <li>Simply click on the SURVEY LINK provided above to access the survey.</li>
    <li>Please use ONLY a desktop or a laptop to fill the survey (Refrain from using a mobile phone or a tablet).</li>
    <li>There are NO right or wrong answers.</li>
    <li>The questionnaire MAY take approximately 25 minutes to complete (Try to complete within 40 minutes).</li>
    <li>Please complete the questionnaire when you can do it WITHOUT INTERRUPTIONS OR DISTURBANCES.</li>
    <li>Your responses will remain CONFIDENTIAL and aggregated data will be solely for development purposes.</li>
    <li>If you encounter any errors when submitting the questionnaire</li>
    <ol>
        <li>Survey Page keeps Scrolling up without submitting - Please check if you have left any question(s) unanswered.</li>
        <li>If you have persisting errors when submitting, Please close the survey and reopen by clicking the link you recieved via email.</li>
    </ol>
</ul>

<b>Please be kind enough to fill the Multipoint Survey on or before 7<sup>th</sup> April 2024 12.00 p.m. (noon).</b>

<h4>This email is automatically generated. If you have any concerns, please contact:</h4>

<ul>
    <li>Hiran Wilathgamuwage - hiran@3rdwaveconsulting.com or Call - +94 777 601 706</li>
    <li>Sanuja Kobbekaduwe - sanuja@3rdwaveconsulting.com or Call - +94 76 870 5566</li>
</ul>

Thank You <br>
3W Consulting Pvt Ltd.
</body>
</html>
EOT;
  $mail->AltBody =<<<EOT
------Multipoint Request for $surveyor_name------
Please complete the $surveyor_type Multipoint Evaluation of Employee: $user_name.
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

function re_sendmail($user_name, $surveyor_name, $surveyor_type, $surveyor_email, $url){
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
  <title>Multipoint Survey Invite</title>
</head>
<body>
<h2>Multipoint Survey Request for $surveyor_name</h2>

<h2>Dear $surveyor_name, </h2>
<p>
Welcome to the Hayleys Advantis Limited Multipoint Survey powered by 3W Consulting (Pvt) Ltd. <br><br>

Your feedback is invaluable in helping us understand and improve various aspects of our organization. This survey provides an opportunity for comprehensive feedback from multiple perspectives, including self-assessment and feedback from peers, managers and direct reports. <br><br>

Please carry out the Multipoint Evaluation as <b>$surveyor_type</b> of Employee:<b>$user_name</b>.<br><br>

Survey Link: $url <br><br>

<b>VERY IMPORTANT:</b></p>
<ul>
    <li>Simply click on the SURVEY LINK provided above to access the survey.</li>
    <li>Please use ONLY a desktop or a laptop to fill the survey (Refrain from using a mobile phone or a tablet).</li>
    <li>There are NO right or wrong answers.</li>
    <li>The questionnaire MAY take approximately 25 minutes to complete (Try to complete within 40 minutes).</li>
    <li>Please complete the questionnaire when you can do it WITHOUT INTERRUPTIONS OR DISTURBANCES.</li>
    <li>Your responses will remain CONFIDENTIAL and aggregated data will be solely for development purposes.</li>
    <li>If you encounter any errors when submitting the questionnaire</li>
    <ol>
        <li>Survey Page keeps Scrolling up without submitting - Please check if you have left any question(s) unanswered.</li>
        <li>If you have persisting errors when submitting, Please close the survey and reopen by clicking the link you recieved via email.</li>
    </ol>
</ul>

<b>Please be kind enough to fill the Multipoint Survey on or before 19<sup>th</sup> April 2024 12.00 p.m. (noon).</b>

<h4>This email is automatically generated. If you have any concerns, please contact:</h4>

<ul>
    <li>Hiran Wilathgamuwage - hiran@3rdwaveconsulting.com or Call - +94 777 601 706</li>
    <li>Sanuja Kobbekaduwe - sanuja@3rdwaveconsulting.com or Call - +94 76 870 5566</li>
</ul>

Thank You <br>
3W Consulting Pvt Ltd.
</body>
</html>
EOT;
  $mail->AltBody =<<<EOT
------Multipoint Request for $surveyor_name------
Please complete the $surveyor_type Multipoint Evaluation of Employee: $user_name.
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

function sendmail_climate_survey($user_name, $surveyor_name, $date, $surveyor_email, $url){
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
  $mail->Subject = sprintf("Test : Climate Survey Request : %s", $date);
  $mail->Body =<<<EOT
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Climate Survey  Invite</title>
</head>
<body>
<h2>Climate Survey Request for $surveyor_name</h2>
<p>
Please carry out the Climate Survey evaluation.<br>
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
Please complete the Climate Survey evaluation.
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
