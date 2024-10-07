<?php


require "../Common/DbOperations.php";
require "QuestionList.php";



function PrintQuestionList($con, $question_list_id){
  echo "-----------------------PrintQuestionList:[start]----------------------------\n";
  printf("-------------Printing Question List Id=%d----------------\n", $question_list_id);
  $questionaire_details = [];
  GetQuestionaireDetails($con, $question_list_id, $questionaire_details);
  printf("Number of Category details returned=%d", Count($questionaire_details));
  foreach ($questionaire_details as $category_details){
    printf("\t\t\tCategory ID=%d, CategoryName=%s, Number-of-Questions=%d\n", $category_details['CategoryID'],
      $category_details['CategoryName'], Count($category_details['QuestionList']));
    echo "\t\t\t\t--Printing Question List for Category " . $category_details['CategoryName'] . "--\n";
    foreach ($category_details['QuestionList'] as $question_details){
      printf("\t\t\t\tQuestion Details: Id=%d, Question=%s, Type=%d\n", $question_details['QuestionNumber'],
        $question_details['Question'], $question_details['Type']);
    }
  }
  echo "------------------------PrintQuestionList:[end]-----------------------------\n";
}


function Usage(){
  print("Usage: printquestionslist.php <question_list_id>\n");
}

$hostname = "localhost";
$username = "root";
$password = "1qaz2wsx!@";
$dbname = "360_survey_schema";

printf("argc=%d\n", $argc);
if (2 != $argc){
  Usage();
  exit("This script only accepts one argument, the question list id to print.\n");
}
$question_list_id = $argv[1];
printf("Using question list Id:%d\n", $question_list_id);
$con = CreateDBOConnection($hostname, $username, $password, $dbname);
PrintQuestionList($con, $question_list_id);
$con = NULL;