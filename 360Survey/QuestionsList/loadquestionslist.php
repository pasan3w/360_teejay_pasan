<?php


require "../Common/DbOperations.php";
require "QuestionList.php";


function LoadQuestionList($con, $questionaire_file){
  echo "-----------------------LoadQuestionList:[start]----------------------------\n";
  $json_data = DecodeQuestionaireFile($questionaire_file);
  if (!$json_data){
    printf("Failed to load question list from file:%s\n", $questionaire_file);
    exit();
  }
  echo "Question List loaded from Json file:" . $questionaire_file . "\n";
  $question_list_id =  AddQuestionList($con, $questionaire_file);
  echo "JsonFilePath=" . $questionaire_file . ", QuestionListId Created=" . $question_list_id . "\n";
  if (!$question_list_id){
    exit("Failed to AddQuestionList\n");
  }
  PopulateQuestionaire($con, $question_list_id, $json_data);
  echo "Successfully Added JSON File=" . $questionaire_file . " To DB QuestionListId=" . $question_list_id . "\n";
  echo "-----------------------LoadQuestionList:[end]----------------------------\n";
  return $question_list_id;
}


function PrintQuestionList($con, $question_list_id){
  echo "-----------------------PrintQuestionList:[start]----------------------------\n";
  printf("------Printing Question List Id=%d---------\n", $question_list_id);
  $questionaire_details = [];
  GetQuestionaireDetails($con, $question_list_id, $questionaire_details);
  printf("Number of Category details returned=%d", Count($questionaire_details));
  foreach ($questionaire_details as $category_details){
    printf("\t----Category ID=%d, CategoryName=%s, Number-of-Questions=%d----\n", $category_details['CategoryID'],
      $category_details['CategoryName'], Count($category_details['QuestionList']));
    foreach ($category_details['QuestionList'] as $question_details){
      printf("\t\tQuestionId=%d, Question=%s, Type=%d\n", $question_details['QuestionNumber'],
        $question_details['Question'], $question_details['Type']);
    }
  }
  echo "------------------------PrintQuestionList:[end]-----------------------------\n";
}


function Usage(){
  print("Usage: loadquestionslist [-h|--help] [-p|--print] <question_list_file_path>\n");
}

$hostname = "localhost";
$username = "root";
$password = "1qaz2wsx!@";
$dbname = "360_survey_schema";


$short_options = "hp";
$long_options = ["help", "print"];
$opt_print = false;
$questionaire_file = null;

$options = getopt($short_options, $long_options, $rest_index);
if (isset($options["h"]) || isset($options["help"])){
  Usage();
  exit();
}
if (isset($options["p"]) || isset($options["print"])){
  $opt_print = true;
}
if (isset($argv[$rest_index])){
  $questionaire_file = trim($argv[$rest_index]);
}

if (!$questionaire_file){
  Usage();
  exit("Question List file path is mandatory.\n");
}

printf("Using question list file:%s\n", $questionaire_file);

$con = CreateDBOConnection($hostname, $username, $password, $dbname);
$question_list_id = LoadQuestionList($con, $questionaire_file);
if ($opt_print){
  PrintQuestionList($con, $question_list_id);
}
printf("Successfully loaded question list from file=%s, question_list_id=%d\n", $questionaire_file, $question_list_id);
$con = NULL;