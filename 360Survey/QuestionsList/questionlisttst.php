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

function ParseQuestionListFileTst($questionaire_file){
  echo "-----------------------ParseQuestionListFileTst:[start]----------------------------";
  $json_data = DecodeQuestionaireFile($questionaire_file);
  echo "Printing JSON data loaded from file:" . $questionaire_file . "\n";  
  echo "Survey Type=" . $json_data["SurveySpecification"]["SurveyType"] . "\n";
  echo "-----------------------Category List----------------------------\n";
  foreach ($json_data["SurveySpecification"]["EvaluationCriteria"] as $category){
    echo "\tCategory Id=" . $category["id"] . ", Name=" . $category["category"] . "\n";
    echo "\t\t...........QuestionList [start]...............\n";
    foreach ($category["QuestionList"] as $question){
      $type = 0;
      if (array_key_exists("type", $question)){
        $type = $question["type"];
      }      
      echo "\t\tInserting QuestionNumber=" . $question["number"] . ":" . $question["question"] . "Type:" . $type .  "\n";
    }
  }
  echo "-----------------------Rating System----------------------------\n";
  $rating_system = $json_data["SurveySpecification"]["RatingSystem"];
  echo "\tDisplayOption=" . $rating_system["DisplayOption"] . "\n";
  foreach ($rating_system["RatingScale"] as $rating_scale){
    echo "\tNumber=" . $rating_scale["number"] . ", Text=" . $rating_scale["text"] . ", Rating=" .$rating_scale["rating"] . "\n";
  }
  echo "Successfully Parsed JSON File=" . $questionaire_file . "\n";
  echo "-----------------------ParseQuestionListFileTst:[end]----------------------------";
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
        printf("\t\t\t\tQuestion Details: Id=%d, Question=%s, Type=%d\n", $question_details['QuestionNumber'],
          $question_details['Question'], $question_details['Type']);
      }
    }
  }
  echo "------------------------FetchQuestionListTst:[end]-----------------------------";
}

$hostname = "localhost";
$username = "root";
$password = "1qaz2wsx!@";
$dbname = "360_survey_schema";


$questionaire_file = "questionlist.json";

function Usage(){
  print("Usage: questionlisttst.php [-h|--help] <question_list_file_path>\n");
}

$short_options = "h";
$long_options = ["help"];

$options = getopt($short_options, $long_options, $rest_index);
if (isset($options["h"]) || isset($options["help"])){
  Usage();
  exit();
}

if (isset($argv[$rest_index])){
  $questionaire_file = $argv[$rest_index];
}

if (!file_exists($questionaire_file)){
  Usage();
  exit("Question List File does not exist.\n");
}

printf("Yes, the question list file=%s exists, we can proceed\n", $questionaire_file);

//$con = CreateDBOConnection($hostname, $username, $password, $dbname);
//LoadQuestionListTst($con, $questionaire_file);
//FetchQuestionListTst($con);
//$con = NULL;
ParseQuestionListFileTst($questionaire_file);
