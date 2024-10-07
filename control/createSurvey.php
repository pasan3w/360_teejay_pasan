<?php

require "Survey.php";

if (!empty($_POST['questionListID']) && is_numeric($_POST['questionListID'])) {
  $question_list_id = $_POST['questionListID'];
  $date             = date("Y-m-d");

  CreateSurvey($con, $question_list_id, $date);
} else {
  echo "Questionare ID is NOT selected.";
}

?>