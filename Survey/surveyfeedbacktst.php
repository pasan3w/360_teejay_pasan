<?php

require "../QuestionsList/QuestionList.php";
require "SurveyFeedback.php";


function GetRandomMD5String(){
  return(md5(rand()));
}


//Not the most efficient but introduces a little randomness
function GetRandomString($max_len){
  static $string_base = 'Kabc12TU. ZdeY3XWfgh4VU.TSijkT5SRl;QuP,lmnONoMpqr67LKstu8. JIvwHG9xFEyz0DCBA!';
  $actual_len = rand(1, $max_len);
  printf("----MakeRandomString: maximum length=%d, actual length=%d------\n", $max_len, $actual_len);
  $base_len = strlen($string_base);
  #printf("Length of the string_base=%d\n", $base_len);
  $slen = 0;
  $max_word_len = 10;
  $start_idx = 0;
  $word_array = [];
  while($slen < $actual_len){
    $start_idx = rand($start_idx, $base_len - 1);
    $wlen = rand(1, $max_word_len);
    if (count($word_array))
      $slen += 1;
      #printf("actual_len=%d, wordlen=%d, start_idx =%d, slen=%d\n", $actual_len, $wlen, $start_idx, $slen);
      if (($slen + $wlen) > $actual_len)
        $wlen = $actual_len - $slen;
        if (($start_idx + $wlen) > $base_len)
          $wlen = $base_len - $start_idx;
          //$wlen = min([$actual_len - $slen, $base_len - $start_idx, $wlen]);
          #printf("Creating word of len=%d\n", $wlen);
          $word = substr($string_base, $start_idx, $wlen);
          $word_len = strlen($word);
          $slen += $word_len;
          array_push($word_array, $word);
          
          #printf("Created word=%s, word length=%d, total string length=%d\n", $word, $word_len, $slen);
          #printf("Current length of generated string=%d, number of words=%d\n", $slen, count($word_array));
          if (($start_idx + $word_len) >= $base_len){
            #printf("Resetting start_idx to zero\n");
            $start_idx = 0;
          }
          #printf("\n");
  }
  #printf("Generating string of length=%d using array of lenght=%d\n", $actual_len, count($word_array));
  $gen_string = implode(' ', $word_array);
  printf("Created string:[%s] of length=%d, calculated string length=%d\n", $gen_string,
    strlen($gen_string), $slen);
  return $gen_string;
}


function AddTestSurveyFeedbackForEmployee($con, $survey_id, $surveyor_eid, $surveyor_type, $eid){
  echo "------------------------AddTestSurveyFeedbackForEmploye:[start]-------------------------------\n";
  $question_list_id = GetSurveyQuestionaireId($con, $survey_id);
  $feedback_array = [];
  $questions_list = [];
  $max_response_length = 255;
  GetQuestionaireDetails($con, $question_list_id, $questions_list);
  foreach ($questions_list as $category_details){
    $category_id = $category_details['CategoryID'];
    foreach ($category_details['QuestionList'] as $question_details){
      $question_number = $question_details['QuestionNumber'];
      $type = $question_details['Type'];
      if (1 == $type){
        $rating_str = GetRandomString($max_response_length);
        printf("CategoryId=%d, CatName=%s, QuestionNumber=%d, Question=%s, Type=%d, Rating=%s\n",
          $category_id, $category_details['CategoryName'], $question_number, $question_details['Question'], $type, $rating_str);
        array_push($feedback_array, [$category_id, $question_number, $type, $rating_str]);
      }else{
        $rating = rand(1, 5);
        printf("CategoryId=%d, CatName=%s, QuestionNumber=%d, Question=%s, Type=%d, Rating=%d\n",
          $category_id, $category_details['CategoryName'], $question_number, $question_details['Question'], $type, $rating);
        array_push($feedback_array, [$category_id, $question_number, $type, $rating]);
      }
    }
  }
  AddSurveyorFeedbackForEmployee($con, $survey_id, $surveyor_eid, $surveyor_type, $eid, $feedback_array);
  echo "-------------------------AddTestSurveyFeedbackForEmploye:[end]------------------------------------\n";
}


function ProvideTestFeedbackBySurveyorForEmployeeInSurveyAssignments($con, $surveyor_eid, $eid, $survey_id){
  echo "------------------------ProvideTestFeedbackBySurveyorForEmployeeInSurveyAssignments:[start]-------------------------------\n";
  $open_survey_assignments = [];
  global $SurveyorType2String;
  GetOpenAssignmentsForSurveyorOfEmployeeInSurvey($con, $survey_id, $surveyor_eid, $eid, $open_survey_assignments);
  foreach ($open_survey_assignments as $open_survey_assignment){
    $surveyor_type = $open_survey_assignment[0];
    printf("Generating Feedback for SurveyAssignment: survey=%d, surveyor=%s, surveyor_type=%s, employee=%s\n",
      $survey_id, $surveyor_eid, $SurveyorType2String[$surveyor_type], $eid);
    AddTestSurveyFeedbackForEmployee($con, $survey_id, $surveyor_eid, $surveyor_type, $eid);
  }
  echo "------------------------ProvideTestFeedbackBySurveyorForEmployeeInSurveyAssignments:[start]-------------------------------\n";
}


function ProvideTestFeedbackBySurveyorInSurveyAssignments($con, $surveyor_eid, $survey_id){
  echo "------------------------ProvideTestFeedbackBySurveyorInSurveyAssignments:[start]-------------------------------\n";
  global $SurveyorType2String;
  $open_survey_assignments = [];
  GetOpenAssignmentsForSurveyorInSurvey($con, $survey_id, $surveyor_eid, $open_survey_assignments);
  foreach ($open_survey_assignments as $open_survey_assignment){
    $surveyor_type = $open_survey_assignment[0];
    $eid = $open_survey_assignment[1];
    printf("Generating Feedback for SurveyAssignment: survey=%d, surveyor=%s, surveyor_type=%s, employee=%s\n",
      $survey_id, $surveyor_eid, $SurveyorType2String[$surveyor_type], $eid);
    AddTestSurveyFeedbackForEmployee($con, $survey_id, $surveyor_eid, $surveyor_type, $eid);
  }
  echo "------------------------ProvideTestFeedbackBySurveyorInSurveyAssignments:[end]-------------------------------\n";
}


function ProvideTestFeedbackForEmployeeInSurveyAssignments($con, $eid, $survey_id){
  echo "------------------------ProvideTestFeedbackForEmployeeInSurveyAssignments:[start]-------------------------------\n";
  global $SurveyorType2String;
  $open_survey_assignments = [];
  GetOpenAssignmentsOfEmployeeInSurvey($con, $survey_id, $eid, $open_survey_assignments);
  foreach ($open_survey_assignments as $open_survey_assignment){
    $surveyor_eid = $open_survey_assignment[0];
    $surveyor_type = $open_survey_assignment[1];
    printf("Generating Feedback for SurveyAssignment: survey=%d, surveyor=%s, surveyor_type=%s, employee=%s\n",
      $survey_id, $surveyor_eid, $SurveyorType2String[$surveyor_type], $eid);
    AddTestSurveyFeedbackForEmployee($con, $survey_id, $surveyor_eid, $surveyor_type, $eid);
  }
  echo "------------------------ProvideTestFeedbackForEmployeeInSurveyAssignments:[end]-------------------------------\n";
}


function ProvideTestFeedbackForAllSurveyAssignments($con, $survey_id){
  echo "------------------------ProvideTestFeedbackForAllSurveyAssignments:[start]-------------------------------\n";
  global $SurveyorType2String;
  $open_survey_assignments = [];
  GetOpenAssignmentsOfSurvey($con, $survey_id, $open_survey_assignments);
  foreach ($open_survey_assignments as $open_survey_assignment){
    $surveyor_eid = $open_survey_assignment[0];
    $surveyor_type = $open_survey_assignment[1];
    $eid = $open_survey_assignment[2];
    printf("Generating Feedback for SurveyAssignment: survey=%d, surveyor=%s, surveyor_type=%s, employee=%s\n",
      $survey_id, $surveyor_eid, $SurveyorType2String[$surveyor_type], $eid);
    AddTestSurveyFeedbackForEmployee($con, $survey_id, $surveyor_eid, $surveyor_type, $eid);
  }
  echo "------------------------ProvideTestFeedbackForAllSurveyAssignments:[end]-------------------------------\n";  
}


function GetFeedbackForEmployee($con, $survey_id, $eid){
  print("------------------------------GetFeedbackForEmployee:[start]-----------------------------------------\n");
  $feedback_array = [];
  GetSurveyFeedbackBySurveyorType($con, $survey_id, $eid, $feedback_array);
  print("------------------------------GetFeedbackForEmployee:[end]-----------------------------------------\n");
}


function Usage(){
  print("Usage: surveyfeedbacktst.php [-h|--help] [-s|--surveyor <surveyor_id>] [-e|--eid <employee_id>] <survey_id>\n");
  print("\tWhere <surveyor_id> is the Employee Id of the surveyor and <employee_id> is the Id of the employee being surveyed.\n");
  print("\t<survey_id> is the Id of the Survey to use. This is a mandatory parameter. Only a single Survey can be processed at a time");
  print("\tNote: All Employee Ids must be in quotes for eids containing quotes\n");
}


$hostname = "localhost";
$username = "root";
$password = "1qaz2wsx!@";
$dbname = "360_survey_schema";

$survey_id = 0;
$surveyor_id = null;
$eid = null;
$assignor_eid = "96";
$survey_completion_date = date("Y-m-d");

printf("argc=%d\n", $argc);
$short_options = "he:s:";
$long_options = ["help", "surveyor:", "eid:"];

$options = getopt($short_options, $long_options, $rest_index);
print_r($options);
printf("rest_index=%d\n", $rest_index);

if (isset($options["h"]) || isset($options["help"])){
  Usage();
  exit();
}
if (isset($options["e"]) || isset($options["eid"])){
  $eid = trim(strval(isset($options["e"])?  $options["e"] : $options["eid"]));
}
if (isset($options["s"]) || isset($options["surveyor"])){
  $surveyor_id = trim(strval(isset($options["s"])? $options["s"] : $options["surveyor"]));
}
if (isset($argv[$rest_index])){
  $survey_id = $argv[$rest_index];
}

printf("Eid=%s, SurveyorId=%s, SurveyId=%d\n", $eid, $surveyor_id, $survey_id);

if (0 == $survey_id){
  print("The Survey Id is mandatory for the script.");
  Usage();
  exit("Run again with required arguments\n");
}

$con = CreateDBOConnection($hostname, $username, $password, $dbname);


if ($eid && $surveyor_id){
  printf("Providing feedback for survey assignments for surveyor=%s of employee=%s for survey=%d\n",
    $surveyor_id, $eid, $survey_id);
  ProvideTestFeedbackBySurveyorForEmployeeInSurveyAssignments($con, $surveyor_id, $eid, $survey_id);
}elseif ($surveyor_id){
  printf("Providing feedback for survey assignments of surveyor=%s for survey=%d\n", $surveyor_id, $survey_id);
  ProvideTestFeedbackBySurveyorInSurveyAssignments($con, $surveyor_id, $survey_id);  
}elseif ($eid){
  printf("Providing feedback for survey assignments for an employee=%s for survey=%d\n", $eid, $survey_id);
  ProvideTestFeedbackForEmployeeInSurveyAssignments($con, $eid, $survey_id);
}else{
  printf("Providing feedback for all open survey assignments for survey=%d\n", $survey_id);
  ProvideTestFeedbackForAllSurveyAssignments($con, $survey_id);  
}

$con = null;