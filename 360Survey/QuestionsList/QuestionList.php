<?php


function DecodeQuestionaireFile($file_path){
  echo "Loading Questionaire from file:" . $file_path;
  try {
    $json_string = file_get_contents($file_path);
    $json_data = json_decode($json_string, true);
    //print_r($json_data);
    var_dump($json_data);
    return $json_data;
  } catch (Exception $e){
    echo "Error Loading Questionaire from JSON file: " . $file_path . ": " . $e->getMessage();
    return NULL;
    
  }
}


function PopulateQuestionaire($con, $question_list_id, $json_data){
  echo "*****************PopulateQuestionaire[start]***********************\n";
  foreach ($json_data["EvaluationCriteria"] as $category){
    echo "\t----------------CATEGORY:[start]------------------\n";
    //var_dump($category);
    echo "\tInserting Category Id=" . $category["id"] . ", Name=" . $category["category"] . " to DB\n";
    $sql = "INSERT INTO QuestionCategoryTable (QuestionListID, CategoryID, CategoryName) VALUES (?,?,?)";
    try{
      $con->prepare($sql)->execute([$question_list_id, $category["id"], $category["category"]]);
      
      echo "\tCategory Id=" . $category["id"] . ", Name=" . $category["category"] . " Inserted into DB\n";
      echo "\t\t...........QuestionList Insert[start]...............\n";
      foreach ($category["QuestionList"] as $question){
        $type = 0;
        if (array_key_exists("type", $question)){
          $type = $question["type"];
        }
        echo "\t\tInserting QuestionNumber=" . $question["number"] . ":" . $question["question"] . " Type:" . $type . "to DB\n";
        $qsql = "INSERT INTO QuestionsTable (QuestionListID, CategoryID, QuestionNumber, Question, Type) VALUES (?,?,?,?,?)";
        $con->prepare($qsql)->execute([$question_list_id, $category["id"],
          $question["number"], $question["question"], $type]);
        echo "\t\tQuestionNumber=" . $question["number"] . ":" . $question["question"] . " of Type:" . $type . "Inserted to DB\n";
      }
      echo "\t\t...........QuestionList[end]...............\n";
      echo "\t-----------------CATEGORY:[end]-------------\n";
    } catch (PDOException $e){
      echo "Failed to PopulateQuestionaire in DB:" . $e->getMessage() . "\n";
      exit("Failed to PopulateQuestionaire in DB");
    }
  }
  echo "*****************PopulateQuestionaire[end]***********************\n";
}


function AddQuestionList($con, $file_path){
  $sql = "INSERT INTO QuestionListTable (Date, FilePath) VALUES (Now(), :file_path)";
  try {
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':file_path', $file_path);
    $stmt->execute();
    $question_list_id = $con->lastInsertId('QuestionListID');
    echo "AddQuestionList:QuestionListId=" . $question_list_id . "\n";
    return $question_list_id;
  } catch (PDOException $e){
    echo "Failed to Add QuestionList to DB:" . $e->getMessage() . "\n";
    //exit("Failed to Add QuestionList to DB");
    return 0;    
  }  
}


function GetQuestionListIdDetails($con, &$question_list_id_details){
  echo "-------------------------GetQuestionListIdDetails:[start]-------------------------\n";
  $question_list_id_details = [];
  try {
    $question_list_id_stmt = $con->prepare("SELECT  QuestionListID, Date, FilePath FROM QuestionListTable ORDER BY QuestionListID ASC");
    $question_list_id_stmt->execute();
    while ($questionaire_details_row = $question_list_id_stmt->fetch(PDO::FETCH_ASSOC)) {
      //var_dump($questionaire_details_row);
      //print_r($questionaire_details_row);
      printf("Adding QuestionListId ID:%d, Date:%s, File Path:%s\n",
        $questionaire_details_row['QuestionListID'],
        $questionaire_details_row['Date'],
        $questionaire_details_row['FilePath']);
      array_push($question_list_id_details, [
        'ID' => $questionaire_details_row['QuestionListID'],
        'Date' => $questionaire_details_row['Date'],
        'FilePath' => $questionaire_details_row['FilePath']
      ]);
    }
  } catch (PDOException $e) {
    echo "GetQuestionListIdDetails:" . $e->getMessage() . "\n";
  }
  //var_dump($question_list_id_details);
  echo "-------------------------GetQuestionListIdDetails:[end]-------------------------\n";
}


function GetQuestionaireDetails($con, $questionaire_id, &$questionaire_details){
  echo "-------------------------GetQuestionaireDetails:[start]-------------------------\n";
  $questionaire_details = [];
  try {
    $category_list_stmt = $con->prepare("SELECT  CategoryID, CategoryName FROM QuestionCategoryTable WHERE QuestionListID =? ORDER BY CategoryID ASC");
    $category_list_stmt->execute([$questionaire_id]);
    while ($category_details_row = $category_list_stmt->fetch(PDO::FETCH_ASSOC)) {
      $category_id = $category_details_row['CategoryID'];
      $category_name = $category_details_row['CategoryName'];
      $category_details_array = ['CategoryID' => $category_id,
        'CategoryName' => $category_name,
        'QuestionList' => []];
      printf("Looking up questions for QuestionCategoryId ID:%d, CategoryName:%s\n", $category_id, $category_name);
      $question_list_stmt = $con->prepare("SELECT QuestionNumber, Question, Type FROM QuestionsTable WHERE QuestionListID =? AND CategoryID =? ORDER BY QuestionNumber ASC");
      $question_list_stmt->execute([$questionaire_id, $category_id]);
      while ($question_details_row = $question_list_stmt->fetch(PDO::FETCH_ASSOC)){
        printf("\tAdding question number=%d, Question=%s\n", $question_details_row['QuestionNumber'],
          $question_details_row['Question']);
        array_push($category_details_array['QuestionList'],
          ['QuestionNumber' => $question_details_row['QuestionNumber'],
            'Question' => $question_details_row['Question'],
            'Type' => $question_details_row['Type']
          ]);
      }
      array_push($questionaire_details, $category_details_array);
    }
  } catch (PDOException $e) {
    echo "GetQuestionaireDetails:" . $e->getMessage() . "\n";
    $questionaire_details = [];
  }
  //var_dump($questionaire_details);
  echo "-------------------------GetQuestionaireDetails:[end]-------------------------\n";
}

