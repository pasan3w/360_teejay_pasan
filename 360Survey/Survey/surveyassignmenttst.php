<?php

require "../Common/DbOperations.php";
//require "Survey.php";
require "SurveyAssignment.php";


function CloseAndCreateSurveyTst($con, $question_list_id, $survey_date){
  echo "\n-----------------------------CreateSurvey:[start]-----------------------------------\n";
  printf("Checking for any open surveys\n");
  $open_survey_id = CheckForOpenSurveys($con);
  if (0 != $open_survey_id){
    printf("Found open SurveyId=%d, manually closing it\n", $open_survey_id);
    CompleteSurvey($con, $open_survey_id);
  }else{
    print("No open surveys found, Creating new Survey\n");
  }  
  printf("Creating new survey for question_list_id=%d, survey_date=%s\n", $question_list_id, $survey_date);
  $survey_id = CreateSurvey($con, $question_list_id, $survey_date);
  if (0 == $survey_id){
    printf("Failed to create survey due to unknown error, This should not happen.\n");
  }
  echo "\n-----------------------------CreateSurvey:[end]-----------------------------------\n";
  return $survey_id;
}


function AssignSelfSurveyTask($con, $survey_id, $surveyee_eid, $assignor_eid){
  echo "-----------------------------AssignSelfSurveyTask:[start]-----------------------------\n";
  global $SurveyorType2String;
  $assign_date = date("Y-m-d");
  printf("Generating Self Survey Task: survey_id=%d, assignor=%s, surveyee=%s, surveyor=%s, surveyor_type=%s, date=%s\n",
    $survey_id, $assignor_eid, $surveyee_eid, $surveyee_eid, $SurveyorType2String[SurveyorType::Self], $assign_date);  
  if (AssignSurvey($con, $survey_id, $assignor_eid, $surveyee_eid, $surveyee_eid, SurveyorType::Self, $assign_date)){
    print("AssignSelfSurveyTask: Self Survey Task generated successfully\n");
  }else{
    print("AssignSelfSurveyTask: Generation of Self Survey Task Failed\n");
  }
  echo "-----------------------------AssignSelfSurveyTask:[end]-------------------------------\n";  
}


function AssignSurveyTasksToReportingManagers($con, $survey_id, $surveyee_eid, $assignor_eid){
  echo "-----------------------------AssignSurveyTasksToReportingManagers:[start]-----------------------------\n";
  global $SurveyorType2String;
  $assign_date = date("Y-m-d");
  
  printf("Generating Reporting Manager Survey Tasks: survey_id=%d, assignor=%s, surveyee=%s, surveyor_type=%s, date=%s\n",
    $survey_id, $assignor_eid, $surveyee_eid, $SurveyorType2String[SurveyorType::ReportingManager], $assign_date);
  printf("----Obtaining Reporting Managers using:GetEmployeeReportingManagersForSurveyAssignment for eid=%s, name=%s----\n",
    $surveyee_eid, EmployeeId2EmployeeName($con, $surveyee_eid));
  $reporting_managers_ary_ref = [];
  GetEmployeeReportingManagersForSurveyAssignment($con, $surveyee_eid, $reporting_managers_ary_ref);
  printf("----Assigning Survey Tasks to Reporing Managers of Employee=%s---\n", $surveyee_eid);
  foreach ($reporting_managers_ary_ref as $rm_details) {
    printf("Assigning Survey to Reporting Manager EID:%s, Name:%s, Email:%s\n", $rm_details['EID'], 
      $rm_details['Name'], $rm_details['EmailAddress']);
    if (AssignSurvey($con, $survey_id, $assignor_eid, $surveyee_eid, $rm_details['EID'],
      SurveyorType::ReportingManager, $assign_date)){
      print("Reporting Manager Survey Task generated successfully\n");
    }else{
      print("Assignment of Reporting Manager Survey Task Failed\n");
    }
  }  
  echo "-----------------------------AssignSurveyTasksToReportingManagers:[end]-------------------------------\n";
}


function AssignSurveyTasksToDirectReports($con, $survey_id, $surveyee_eid, $assignor_eid){
  echo "-----------------------------AssignSurveyTasksToDirectReports:[start]-----------------------------\n";
  global $SurveyorType2String;
  $assign_date = date("Y-m-d");
  
  printf("Generating Direct Reports Survey Tasks: survey_id=%d, assignor=%s, surveyee=%s, surveyor_type=%s, date=%s\n",
    $survey_id, $assignor_eid, $surveyee_eid, $SurveyorType2String[SurveyorType::DirectReport], $assign_date);
  printf("----Obtaining Direct Reports using:GetEmployeeDirectReportsForSurveyAssignment for eid=%s, name=%s----\n",
    $surveyee_eid, EmployeeId2EmployeeName($con, $surveyee_eid));
  $direct_reports_ary_ref = [];
  GetEmployeeDirectReportsForSurveyAssignment($con, $surveyee_eid, $direct_reports_ary_ref);
  printf("----Assigning Survey Tasks to Direct Reports of Employee=%s---\n", $surveyee_eid);
  foreach ($direct_reports_ary_ref as $dr_details) {
    printf("Assigning Survey to Direct Report EID:%s, Name:%s, Email:%s\n", $dr_details['EID'],
      $dr_details['Name'], $dr_details['EmailAddress']);
    if (AssignSurvey($con, $survey_id, $assignor_eid, $surveyee_eid, $dr_details['EID'],
      SurveyorType::DirectReport, $assign_date)){
        print("Direct Report Survey Task generated successfully\n");
    }else{
      print("Assignment of Direct Report Survey Task Failed\n");
    }
  }
  echo "-----------------------------AssignSurveyTasksToDirectReports:[end]-------------------------------\n";
}


function AssignSurveyTasksToPeerList($con, $survey_id, $surveyee_eid, $assignor_eid){
  echo "-----------------------------AssignSurveyTasksToPeerList:[start]-----------------------------\n";
  global $SurveyorType2String;
  $assign_date = date("Y-m-d");
  
  printf("Generating Peer List Survey Tasks: survey_id=%d, assignor=%s, surveyee=%s, surveyor_type=%s, date=%s\n",
    $survey_id, $assignor_eid, $surveyee_eid, $SurveyorType2String[SurveyorType::Peer], $assign_date);
  printf("----Obtaining Peer List using:GetEmployeePeerListForSurveyAssignment for eid=%s, name=%s----\n",
    $surveyee_eid, EmployeeId2EmployeeName($con, $surveyee_eid));
  $peer_list_ary_ref = [];
  GetEmployeePeerListForSurveyAssignment($con, $surveyee_eid, $peer_list_ary_ref);
  printf("----Assigning Survey Tasks to Peer List of Employee=%s---\n", $surveyee_eid);
  foreach ($peer_list_ary_ref as $peer_details) {
    printf("Assigning Survey to Peer EID:%s, Name:%s, Email:%s\n", $peer_details['EID'],
      $peer_details['Name'], $peer_details['EmailAddress']);
    if (AssignSurvey($con, $survey_id, $assignor_eid, $surveyee_eid, $peer_details['EID'],
      SurveyorType::Peer, $assign_date)){
        print("Peer List Survey Task generated successfully\n");
    }else{
      print("Assignment of Peer List Survey Task Failed\n");
    }
  }
  echo "-----------------------------AssignSurveyTasksToPeerList:[end]-------------------------------\n";
}


function AssignSurveyTasksTst($con, $survey_id, $surveyee_eid, $assignor_eid){
  echo "-----------------------------AssignSurveyTst:[start]-----------------------------\n";
  printf("Assigning survey tasks by assignor=%s to evaluate employee=%s using servey id=%d\n",
    $assignor_eid, $surveyee_eid, $survey_id);
  print("Creating Self Survey Task\n");
  AssignSelfSurveyTask($con, $survey_id, $surveyee_eid, $assignor_eid);
  print("Self Survey tasks assignment complete\n");

  print("Creating Reporting Manager Survey Tasks\n");
  AssignSurveyTasksToReportingManagers($con, $survey_id, $surveyee_eid, $assignor_eid);
  print("Reporting Managers Survey task assignment complete\n");
  
  print("Creating Direct Reports Survey Tasks\n");
  AssignSurveyTasksToDirectReports($con, $survey_id, $surveyee_eid, $assignor_eid);
  print("Direct Reports Survey task assignment complete\n");
   
  print("Creating Peer List Survey Tasks\n");
  AssignSurveyTasksToPeerList($con, $survey_id, $surveyee_eid, $assignor_eid);
  print("Peer List Survey task assignment complete\n");
  
  echo "-----------------------------AssignSurveyTst:[end]-----------------------------\n";
}

function Usage(){
  print("Usage: surveyassignmenttst.php [-h|--help] [-a|--assignor <assignor_eid>] [-q|--qlid <question_list_id>] <surveyee_eid>\n");
  print("\tNote: All Employ Ids must be in quotes for eids containing quotes\n");
}


$hostname = "localhost";
$username = "root";
$password = "1qaz2wsx!@";
$dbname = "360_survey_schema";

$question_list_id = 100;
$assignor_eid = "94";
$survey_date = date("Y-m-d");

printf("argc=%d\n", $argc);
$short_options = "ha:q:";
$long_options = ["help", "assignor:", "qlid:"];

$options = getopt($short_options, $long_options, $rest_index);
print_r($options);
printf("rest_index=%d\n", $rest_index);
if (isset($options["h"]) || isset($options["help"])){
  Usage();
  exit();
}
if (isset($options["a"]) || isset($options["assignor"])){
  $assignor_eid = isset($options["a"])? $options["a"] : $options["assignor"];
}
if (isset($options["q"]) || isset($options["qlid"])){
  $question_list_id = isset($options["q"])?  $options["q"] : $options["qlid"];
}

$surveyee_eid = trim(strval($argv[$rest_index]));
printf("Eid=%s, QuestionListId=%d\n", $surveyee_eid, $question_list_id);
#exit("ill come back");
printf("Creating a survey assignment by employee=%s to evaluate employee=%s, using question list id=%d\n",
  $assignor_eid, $surveyee_eid, $question_list_id);
$con = CreateDBOConnection($hostname, $username, $password, $dbname);
$survey_id = CloseAndCreateSurveyTst($con, $question_list_id, $survey_date);
printf(" CreateSurvey returned survey id=%d\n",$survey_id);
AssignSurveyTasksTst($con, $survey_id, $surveyee_eid, $assignor_eid);
//AssignSurveyTst($con, $survey_id, $assignor_eid, $surveyee_eid, $surveyor_eid, $surveyor_type, $survey_date);
$con = null;