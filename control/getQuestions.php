<?php

require 'Survey.php';
require '../QuestionsList/QuestionList.php';

$question_list_id = $_POST['questionListID'];

$questionaire_details = [];


GetQuestionaireDetails($con, $question_list_id, $questionaire_details);

foreach ($questionaire_details as $key => $value) {
  echo "<h2>" . $value['CategoryName'] . "</h2>";
  foreach ($value['QuestionList'] as $questions) {
    echo "<p>" . $questions['QuestionNumber'] . ") " . $questions['Question'] . "</p>";
  }
}
