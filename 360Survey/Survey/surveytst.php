<?php

require "../Common/DbOperations.php";
require "Survey.php";


function CreateSurveyTst($con, $question_list_id){
  echo "\n-----------------------------CreateSurveyTst:[start]-----------------------------------\n";
  global $SurveyState2String;
  $survey_date = date("Y-m-d");
  printf("Checking for any open surveys\n");
  $open_survey_id = CheckForOpenSurveys($con);
  if (0 != $open_survey_id){
    printf("Found open SurveyId=%d, manually closing it\n", $open_survey_id);
    CompleteSurvey($con, $open_survey_id);
  }else{
    print("No open surveys found, Creating new Survey\n");
  }
  
  printf("Creating survey for question_list_id=%d, survey_date=%s\n", $question_list_id, $survey_date);
  $survey_id = CreateSurvey($con, $question_list_id, $survey_date);
  if (0 == $survey_id){
    printf("Failed to create survey due to open surveys, This should not happen.\n");
    $open_survey_id = CheckForOpenSurveys($con);
    printf("CheckForOpenSurveys: returned open survey Id=%d, This cannot happen, Stop testing\n", $open_survey_id);
    return;
  }
  
  printf("Created new survey, survey id=%d, question_list_id=%d, date=%s\n", $survey_id, $question_list_id, $survey_date);
  $ret_question_list_id = GetSurveyQuestionaireId($con, $survey_id);
  printf("Question List Id returned by Survey=%d, should be the same as the Id used to create the survey=%d\n",
    $ret_question_list_id, $question_list_id);
  $survey_state = GetSurveyState($con, $survey_id);
  printf("Newly created Survey state=%d => %s\n", $survey_state, $SurveyState2String[$survey_state]);
  printf("Trying to create another Survey before closing the existing survey, this should Fail\n");
  $new_survey_id = CreateSurvey($con, $question_list_id, $survey_date);
  printf("Id of the survey created (with a still open survey): %d\n", $new_survey_id);
  if (0 == $new_survey_id){
    print("Success: Failed to create the second Survey with a survey still open\n");
  }
  
  print("Updating the Survey State to InProgress\n");
  UpdateSurveyState($con, $survey_id, SurveyState::InProgress);
  $survey_state = GetSurveyState($con, $survey_id);
  printf("Updated Survey state=%d => %s\n", $survey_state, $SurveyState2String[$survey_state]);
  printf("Trying to create another Survey before completing the current survey, this should Fail\n");
  $new_survey_id = CreateSurvey($con, $question_list_id, $survey_date);
  printf("Id of the survey created (with a still open survey): %d\n", $new_survey_id);
  if (0 == $new_survey_id){
    print("Success: Failed to create the second Survey with a survey still open\n");
  }
  
  print("Updating the Survey State to Complete\n");
  UpdateSurveyState($con, $survey_id, SurveyState::Complete);
  $survey_state = GetSurveyState($con, $survey_id);
  printf("Updated Survey state=%d => %s\n", $survey_state, $SurveyState2String[$survey_state]);
  printf("Trying to create another Survey after completing the current survey, this should Succeed.\n");
  $new_survey_id = CreateSurvey($con, $question_list_id, $survey_date);
  printf("Id of the survey created (after Completing the survey): %d\n", $new_survey_id);
  if (0 == $new_survey_id){
    print("Failure: Failed to create second Survey after Completing the open survey\n");
  }else{
    $survey_id = $new_survey_id;
  }
  $new_question_list_id = 101;
  print("Trying to create new survey with different question list while the previous survey state is open, this should fail\n");
  $new_survey_id = CreateSurvey($con, $question_list_id, $survey_date);
  printf("Id of the survey created (with open surveys): %d, Question List Id=%d\n", $new_survey_id, $new_question_list_id);
  if (0 == $new_survey_id){
    print("Success: Failed to create new Survey with current survey in Open state\n");
  }else{
    print("Error: Successfully created new Survey with different question list id while current survey in Open state\n");
  }
  printf("Completing the survey Id=%d\n", $survey_id);
  UpdateSurveyState($con, $survey_id, SurveyState::Complete);
  CompleteSurvey($con, $survey_id);
  $survey_state = GetSurveyState($con, $survey_id);
  printf("Updated Survey state=%d => %s\n", $survey_state, $SurveyState2String[$survey_state]);
  printf("Trying to create another Survey after completing the current survey, with new question list id\n");
  $survey_id = CreateSurvey($con, $new_question_list_id, $survey_date); 
  printf("Id of the new survey created: %d\n", $survey_id, $new_question_list_id);
  if (0 == $survey_id){
    print("Error: Failed to create new Survey with current survey in Closed state: The question list id must be invalid\n");
  }else{
    print("Success: New survey created with different question list id after Completing current survey\n");
  }    
  echo "-----------------------------CreateSurveyTst:[end]-----------------------------\n";
  return $survey_id;
}


function AssignSurveyTst($con, $survey_id, $assignor_eid, $surveyee_eid, $surveyor_eid, $surveyor_type, $survey_date){
  echo "-----------------------------AssignSurveyTst:[start]-----------------------------\n";
  printf("Assigning survey task to: %s to carryout survey of: %s as the surveyee's: %d\n",
    $surveyor_eid, $surveyee_eid, $surveyor_type);
  if (AssignSurvey($con, $survey_id, $assignor_eid, $surveyee_eid, $surveyor_eid, $surveyor_type, $survey_date)){
    printf("Survey task assigned by=%s to survey employee=%s by surveyor=%s by answering survey id=%d\n",
      $assignor_eid, $surveyee_eid, $surveyor_eid, $survey_id);
  }else{
    print("Failed to Assign Survey\n");
  }
  print("Trying to create a duplicate Survey Assignment\n");
  if (AssignSurvey($con, $survey_id, $assignor_eid, $surveyee_eid, $surveyor_eid, $surveyor_type, $survey_date)){
    printf("Survey task assigned by=%s to survey employee=%s by surveyor=%s by answering survey id=%d\n",
      $assignor_eid, $surveyee_eid, $surveyor_eid, $survey_id);
  }else{
    print("Failed to Assign Survey\n");
  }  
  echo "-----------------------------AssignSurveyTst:[end]-----------------------------\n";  
}



$hostname = "localhost";
$username = "root";
$password = "1qaz2wsx!@";
$dbname = "360_survey_schema";


$question_list_id = 0;

function Usage(){
  print("Usage: surveytst [-h|--help] <question_list_id>\n");
}

$hostname = "localhost";
$username = "root";
$password = "1qaz2wsx!@";
$dbname = "360_survey_schema";


$short_options = "h";
$long_options = ["help"];

$options = getopt($short_options, $long_options, $rest_index);
if (isset($options["h"]) || isset($options["help"])){
  Usage();
  exit();
}
if (isset($argv[$rest_index])){
  $question_list_id = $argv[$rest_index];
}

if (!$question_list_id ){
  Usage();
  exit("Question List Id is mandatory.\n");
}

$con = CreateDBOConnection($hostname, $username, $password, $dbname);
$survey_id = CreateSurveyTst($con, $question_list_id);
printf(" CreateSurveyTst returned survey id=%d\n",$survey_id);

$con = null;
