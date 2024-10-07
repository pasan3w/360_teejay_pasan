<?php

require "../Common/DbOperations.php";
require "QuestionList.php";


function LoadQuestionListTst($con, $questionaire_file){
  echo "-----------------------LoadQuestionListTst:[start]----------------------------";
  $json_data = DecodeQuestionaireFile($questionaire_file);
  echo "JSON file loaded from file:" . $questionaire_file . "\n";
  $question_list_id =  AddQuestionList($con, $questionaire_file);
  echo "JsonFilePath=" . $questionaire_file . ", QuestionListId Created=" . $question_list_id . "\n";
  PopulateQuestionaire($con, $question_list_id, $json_data);
  echo "Successfully Added JSON File=" . $questionaire_file . " To DB QuestionListId=" . $question_list_id . "\n";
  echo "-----------------------LoadQuestionListTst:[end]----------------------------";
}
  

function FetchQuestionListTst($con){
  echo "-----------------------FetchQuestionListTst:[start]----------------------------";
  $question_list_id_details = [];
  GetQuestionListIdDetails($con, $question_list_id_details);
  printf("\t--------------------Printing Details of Question Lists defined in the DB---------------------\n");
  printf("Number of entries in the question list details array=%dn", count($question_list_id_details));
  foreach ( $question_list_id_details as $question_list_details){
    printf("\n\t\t--------QuestionList  ID=%d, Date=%s, FilePath=%s--------\n", $question_list_details['ID'],
    $question_list_details['Date'], $question_list_details['FilePath']);
    echo "\t\t\t--Printing Question List for each Category--\n";
    $questionaire_details = [];
    GetQuestionaireDetails($con, $question_list_details['ID'], $questionaire_details);
    printf("Number of Category details returned=%d", Count($questionaire_details));
    foreach ($questionaire_details as $category_details){
      printf("\t\t\tCategory ID=%d, CategoryName=%s, Number-of-Questions=%d\n", $category_details['CategoryID'],
        $category_details['CategoryName'], Count($category_details['QuestionList']));
      echo "\t\t\t\t--Printing Question List for Category " . $category_details['CategoryName'] . "--\n";
      foreach ($category_details['QuestionList'] as $question_details){
        printf("\t\t\t\tQuestion Details: Id=%d, Question=%s\n", $question_details['QuestionNumber'],
          $question_details['Question']);
      }
    }
  }
  echo "------------------------FetchQuestionListTst:[end]-----------------------------";
}

$hostname = "localhost";
$username = "root";
$password = "MySql@123";
$dbname = "360_survey_schema";

$questionaire_file = "questionlist.json";
$con = CreateDBOConnection($hostname, $username, $password, $dbname);
LoadQuestionListTst($con, $questionaire_file);
FetchQuestionListTst($con);
$con = NULL;
