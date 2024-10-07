<?php


require 'SendMail.php';

$surveyee = 'Bill Gates';
$surveyor = 'Kanishka Dhanasekara';
$surveyor_type = 'Direct Report';
$surveyor_email = 'thalagoya@gmail.com';
$url = 'https://datatracker.ietf.org/doc/html/rfc5321';
if (sendmail($surveyee, $surveyor, $surveyor_type, $surveyor_email, $url)){
  echo "Survey request email sent to primary port\n";
}else{
  echo "Trying backup port";
  if (sendmail_backup_port($surveyee, $surveyor, $surveyor_type, $surveyor_email, $url)){
    echo "Survey request email sent by backup port\n";
  }else{
    echo "Failed to send out Survey Request\n";
  }
}

$surveyee = 'Bill Gates';
$surveyor = 'Pahani Gamage';
$surveyor_type = 'Reporting Manager';
$surveyor_email = 'pahani@3rdwaveconsulting.com';
$url = 'https://datatracker.ietf.org/doc/html/rfc5321';
echo "------------Sending email from Pahani-------------------------\n\n";
if (sendmail($surveyee, $surveyor, $surveyor_type, $surveyor_email, $url)){
  echo "Survey request email sent to primary port\n";
}else{
  echo "Trying backup port\n";
  if (sendmail_backup_port($surveyee, $surveyor, $surveyor_type, $surveyor_email, $url)){
    echo "Survey request email sent by backup port\n";
  }else{
    echo "Failed to send out Survey Request\n";
  }
}