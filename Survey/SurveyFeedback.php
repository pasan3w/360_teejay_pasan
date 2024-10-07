<?php

require "../Common/DbOperations.php";
require "SurveyAssignment.php";


function AddSurveyorFeedbackForEmployee($con, $survey_id, $surveyor_eid, $surveyor_type, $eid, &$feedback_array){
  global $SurveyorType2String;
  echo "---------------------------AddSurveyorFeedbackForEmployee:[start]--------------------------------\n";
  try {
    //$sql = "INSERT INTO SurveyFeedback (SurveyID, EID, SurveyorEID, SurveyorType, QuestionListID, QuestionCategoryID, ";
    //$sql .= "QuestionID, Rating) VALUES ($survey_id,$con->quote($eid), $con->quote($surveyor_eid),$surveyor_type,$question_list_id,?,?,?)";
    $sql = "INSERT INTO SurveyFeedback (SurveyID, EID, SurveyorEID, SurveyorType, QuestionCategoryID, ";
    $sql .= "QuestionID, Rating) VALUES (?,?,?,?,?,?,?)";
    $insert_feedback_stmt = $con->prepare($sql);
    printf("Updating Feedback Received SurveyId=%d, EmployeeId=%s, SurveyorId=%s, SurveyorType=%s\n",
      $survey_id, $eid, $surveyor_eid, $SurveyorType2String[$surveyor_type]);
    printf("SQL=[%s]\n", $sql);
    foreach ($feedback_array as $category_feedback_array){
      printf("\t\tCategoryId=%d, QuestionNumber=%d, Rating=%d\n",
        $category_feedback_array[0], $category_feedback_array[1], $category_feedback_array[2]);
      //if ($insert_feedback_stmt->execute([$category_feedback_array[0], $category_feedback_array[1], $category_feedback_array[2]])){
      if ($insert_feedback_stmt->execute([$survey_id, $eid, $surveyor_eid, $surveyor_type,
        $category_feedback_array[0], $category_feedback_array[1], $category_feedback_array[2]])){
        print("INSERT Feedback successful\n");
      }else{
        print("INSERT Feedback Failed\n");
      }
    }
    $response_date = date("Y-m-d");
    UpdateSurveyAssignmentResponseDate($con, $survey_id, $surveyor_eid, $eid, $response_date);
  }catch(PDOException $e) {
    echo "AddSurveyorFeedbackForEmployee: Failed due to DB Exception: " . $e->getMessage() . "\n";
  }
  echo "-------------------AddSurveyorFeedbackForEmployee:[end]------------------------------------\n";  
}

//Only for debugging purposes. Do not use this function
function AddSurveyorFeedbackForQuestion($con, $survey_id, $eid,
  $surveyor_eid, $surveyor_type, $category_id, $question_number, $rating){
    global $SurveyorType2String;
    echo "-----------------------AddSurveyorFeedbackForQuestion:[start]----------------------------\n";
    try {
      $sql = "INSERT INTO SurveyFeedback (SurveyID, EID, SurveyorEID, SurveyorType, QuestionCategoryID, ";
      $sql .= "QuestionID, Rating) VALUES (?,?,?,?,?,?,?)";
      $insert_feedback_stmt = $con->prepare($sql);
      printf("Updating Feedback Received: SurveyId=%d, EmployeeId=%s, SurveyorId=%s, SurveyorType=%s, CategoryId=%d, QuestionNumber=%d, Rating=%d\n",
        $survey_id, $eid, $surveyor_eid, $SurveyorType2String[$surveyor_type], $category_id, $question_number, $rating);
      printf("SQL=[%s]\n", $sql);
      if ($insert_feedback_stmt->execute([$survey_id, $eid, $surveyor_eid, $surveyor_type, $category_id, $question_number, $rating])){
        print("INSERT Feedback successful\n");
      }else{
        print("INSERT Feedback Failed\n");
      }
    }catch(PDOException $e) {
      echo "AddSurveyorFeedbackForQuestion: Failed due to DB Exception: " . $e->getMessage() . "\n";
    }
    echo "-------------------------AddSurveyorFeedbackForQuestion:[end]------------------------------\n";
}


function GetSurveyFeedbackForEmployeeByDepartment($con, $survey_id, $eid, &$feedback_array){
  global $SurveyorType2String;
  $feedback_array = [];
  $feedback_average_map = [];
  echo "-------------------GetSurveyFeedbackForEmployeeBySurveyorType:[start]------------------------\n";
  $open_assignment_count = GetOpenAssignmnetCountForEmployeeInSurvey($con, $survey_id, $eid);
  printf("OpenAssignments for survey_id=%d for Employee=%s is %d", $survey_id, $eid, $open_assignment_count);
  if ($open_assignment_count){
    printf("Survey Results for Employee=%s not available, response pending from %d surveyors\n", $eid, $open_assignment_count);
    return;
  }
  static $surveyor_sql = "SELECT SurveyAssignment.SurveyorEID, SurveyAssignment.SurveyorType, Employee.Name,";
  $surveyor_sql .= " Employee.DepartmentID FROM SurveyAssignment INNER JOIN Employee on SurveyAssignment.SurveyorEID = Employee.EID";
  $surveyor_sql .= " WHERE SurveyAssignment.SurveyID=? AND SurveyAssignment.EID=?";
  
  try{
    $survey_assignments_stmt = $con->prepare($surveyor_sql);
    $survey_assignments_stmt->execute([$survey_id, $eid]);
    //$surveyor_map = [];
    static $feedback_sql = "SELECT QuestionCategoryID, QuestionID, Rating";
    $feedback_sql .= " FROM SurveyFeedback WHERE SurveyID=? AND EID=? AND SurveyorEID=?";
    $feedbak_stmt = $con->prepare( $feedback_sql);
    while($row = $survey_assignments_stmt->fetch(PDO::FETCH_ASSOC)){
      //var_dump($row);
      $surveyor_eid = $row['SurveyorEID'];
      $surveyor_type_id = $row['SurveyorType'];
      $surveyor_name = $row['Name'];
      $surveyor_dept_id = $row['DepartmentID'];
      printf("Department Id=%d\n", $surveyor_dept_id);
      $surveyor_type = $SurveyorType2String[$surveyor_type_id];
      $surveyor_dept = "External";
      if ($surveyor_dept_id)
        $surveyor_dept = DepartmentId2DepartmentName($con, $surveyor_dept_id);
      printf("Survey Assignment Details: survey=%d, employeeId=%s, surveyorId=%s, surveyor name=%s, surveyor type=%s, surveyor departmen=%s\n",
        $survey_id, $eid, $surveyor_eid, $surveyor_name, $surveyor_type, $surveyor_dept);
      //$surveyor_map[$surveyor_eid] = ['Name' => $surveyor_name, 'Type' => $surveyor_type, 'Dept' => $surveyor_dept];
      printf("Obtaining Survey Assignment feedback\n");
      if (!array_key_exists($surveyor_type, $feedback_array)){
        printf("Surveyor Type key: %s does not exist, creating new array\n",$surveyor_type);
        $feedback_array[$surveyor_dept][$surveyor_eid] = ['Name' => $surveyor_name, 'Type' => $surveyor_type, 'Feedback' => []];
        $feedback_average_map[$surveyor_dept]=[];
      }
      printf("-------Obtaining Feedback Ratings for Survey=%d, by Surveyor=%s for Employee=%s---------\n",
        $survey_id, $surveyor_eid, $eid);
      $feedbak_stmt->execute([$survey_id, $eid, $surveyor_eid]);
      while ($feedback_row = $feedbak_stmt->fetch(PDO::FETCH_ASSOC)){
        //var_dump($feedback_row);
        $question_category_id = $feedback_row['QuestionCategoryID'];
        $question_id = $feedback_row['QuestionID'];
        $rating = $feedback_row['Rating'];
        $feedback_array[$surveyor_dept][$surveyor_eid]['Feedback'][$question_category_id][$question_id]=$rating;
        printf("\tQuestionCategoryID=%d, QuestionID=%d, Rating=%d\n",
          $question_category_id, $question_id, $rating);
        if (!array_key_exists($question_category_id, $feedback_average_map[$surveyor_dept])){
          printf("Adding category id:[%d] to average map surveyor_dept=%s, rating=[%d]\n",
            $question_category_id, $surveyor_dept, $rating);
          $feedback_average_map[$surveyor_dept][$question_category_id]=[];
          $feedback_average_map[$surveyor_dept][$question_category_id]['RatingTotal'] = $rating;
          $feedback_average_map[$surveyor_dept][$question_category_id]['RatingCount'] = 1;
          print("After initializing feedback_average_map:\n");
          //var_dump($feedback_average_map);
        }else {
          //printf("Current RatingTotal for surveyor_type=%s, category id:%d, RatingTotal=%d, RatingCount=%d\n",
          //$surveyor_type, $question_category_id,
          //$feedback_average_map[$surveyor_type][$question_category_id]['RatingTotal'],
          //$feedback_average_map[$surveyor_type][$question_category_id]['RatingCount']);
          printf("Adding rating to existing surveyor_dept=%s, question_category_id=%d\n",
            $surveyor_dept, $question_category_id);
          $feedback_average_map[$surveyor_dept][$question_category_id]['RatingTotal'] += $rating;
          $feedback_average_map[$surveyor_dept][$question_category_id]['RatingCount'] += 1;
          printf("After Add RatingTotal for surveyor_dept=%s, category id:%d, RatingTotal=%d, RatingCount=%d\n",
            $surveyor_dept, $question_category_id, $feedback_average_map[$surveyor_dept][$question_category_id]['RatingTotal'],
            $feedback_average_map[$surveyor_dept][$question_category_id]['RatingCount']);
        }
      }
    }
    print("Calculating Rating Averages per Surveyor Department\n");
    $surveyor_dept_keys = array_keys($feedback_average_map);
    foreach($surveyor_dept_keys as $surveyor_dept_key){
      $feedback_array[$surveyor_type]['AverageRatings'] = [];
      printf("Calculating average for Surveyor Dept=%s\n", $surveyor_dept_key);
      $category_id_keys = array_keys($feedback_average_map[$surveyor_dept_key]);
      foreach($category_id_keys as $category_id_key){
        printf("\tCategory Id=%d, RatingTotal=%d, Number-of-Ratings=%d\n",
          $category_id_key, $feedback_average_map[$surveyor_dept_key][$category_id_key]['RatingTotal'],
          $feedback_average_map[$surveyor_dept_key][$category_id_key]['RatingCount']);
        $rating_average = $feedback_average_map[$surveyor_dept_key][$category_id_key]['RatingTotal'] / $feedback_average_map[$surveyor_dept_key][$category_id_key]['RatingCount'];
        printf("\tRating Average=%f\n", $rating_average);
        $feedback_average_map[$surveyor_dept_key][$category_id_key]['Average'] = $rating_average;
        $feedback_array[$surveyor_dept_key]['AverageRatings'][$category_id_key] = $rating_average;
      }
    }
    //var_dump($feedback_array);
  }catch(PDOException $e) {
    echo "GetSurveyFeedbackForEmployeeBySurveyorType: Failed due to DB Exception: " . $e->getMessage() . "\n";
  }
  echo "-------------------GetSurveyFeedbackForEmployeeBySurveyorType:[end]------------------------\n";
}


function GetSurveyFeedbackForEmployeeBySurveyorType($con, $survey_id, $eid, &$feedback_array){
  global $SurveyorType2String;
  $feedback_array = [];
  $feedback_average_map = [];
  echo "-------------------GetSurveyFeedbackForEmployeeBySurveyorType:[start]------------------------\n";
  $open_assignment_count = GetOpenAssignmnetCountForEmployeeInSurvey($con, $survey_id, $eid);
  printf("OpenAssignments for survey_id=%d for Employee=%s is %d", $survey_id, $eid, $open_assignment_count);
  if ($open_assignment_count){
    printf("Survey Results for Employee=%s not available, response pending from %d surveyors\n", $eid, $open_assignment_count);
    return;
  }  
  static $surveyor_sql = "SELECT SurveyAssignment.SurveyorEID, SurveyAssignment.SurveyorType, Employee.Name,";
  $surveyor_sql .= " Employee.DepartmentID FROM SurveyAssignment INNER JOIN Employee on SurveyAssignment.SurveyorEID = Employee.EID";
  $surveyor_sql .= " WHERE SurveyAssignment.SurveyID=? AND SurveyAssignment.EID=?";
  try{
    $survey_assignments_stmt = $con->prepare($surveyor_sql);
    $survey_assignments_stmt->execute([$survey_id, $eid]);
    //$surveyor_map = [];
    static $feedback_sql = "SELECT QuestionCategoryID, QuestionID, Rating";
    $feedback_sql .= " FROM SurveyFeedback WHERE SurveyID=? AND EID=? AND SurveyorEID=?";
    $feedbak_stmt = $con->prepare( $feedback_sql);
    while($row = $survey_assignments_stmt->fetch(PDO::FETCH_ASSOC)){
      //var_dump($row);
      $surveyor_eid = $row['SurveyorEID'];
      $surveyor_type_id = $row['SurveyorType'];
      $surveyor_name = $row['Name'];
      $surveyor_dept_id = $row['DepartmentID'];
      printf("Department Id=%d\n", $surveyor_dept_id);
      $surveyor_type = $SurveyorType2String[$surveyor_type_id];
      $surveyor_dept = "External";
      if ($surveyor_dept_id)
        $surveyor_dept = DepartmentId2DepartmentName($con, $surveyor_dept_id);            
      printf("Survey Assignment Details: survey=%d, employeeId=%s, surveyorId=%s, surveyor name=%s, surveyor type=%s, surveyor departmen=%s\n",
        $survey_id, $eid, $surveyor_eid, $surveyor_name, $surveyor_type, $surveyor_dept);
      //$surveyor_map[$surveyor_eid] = ['Name' => $surveyor_name, 'Type' => $surveyor_type, 'Dept' => $surveyor_dept];      
      printf("Obtaining Survey Assignment feedback\n");
      if (!array_key_exists($surveyor_type, $feedback_array)){
        $feedback_array[$surveyor_type] = [];
        printf("Surveyor Type key: %s does not exist, creating new array\n",$surveyor_type);
        $feedback_array[$surveyor_type][$surveyor_eid] = ['Name' => $surveyor_name, 'Dept' => $surveyor_dept, 'Feedback' => []];
        $feedback_average_map[$surveyor_type]=[];
      }
      printf("-------Obtaining Feedback Ratings for Survey=%d, by Surveyor=%s for Employee=%s---------\n",
        $survey_id, $surveyor_eid, $eid);
      $feedbak_stmt->execute([$survey_id, $eid, $surveyor_eid]);
      while ($feedback_row = $feedbak_stmt->fetch(PDO::FETCH_ASSOC)){
        //var_dump($feedback_row);
        $question_category_id = $feedback_row['QuestionCategoryID'];
        $question_id = $feedback_row['QuestionID'];
        $rating = $feedback_row['Rating'];
        $feedback_array[$surveyor_type][$surveyor_eid]['Feedback'][$question_category_id][$question_id]=$rating;
        printf("\tQuestionCategoryID=%d, QuestionID=%d, Rating=%d\n",
          $question_category_id, $question_id, $rating);
        if (!array_key_exists($question_category_id, $feedback_average_map[$surveyor_type])){
          printf("Adding category id:[%d] to average map surveyor_type=%s, rating=[%d]\n", 
            $question_category_id, $surveyor_type, $rating);
          $feedback_average_map[$surveyor_type][$question_category_id]=[];
          $feedback_average_map[$surveyor_type][$question_category_id]['RatingTotal'] = $rating;
          $feedback_average_map[$surveyor_type][$question_category_id]['RatingCount'] = 1;
          print("After initializing feedback_average_map:\n");
          //var_dump($feedback_average_map);
        }else {
          //printf("Current RatingTotal for surveyor_type=%s, category id:%d, RatingTotal=%d, RatingCount=%d\n",
            //$surveyor_type, $question_category_id, 
            //$feedback_average_map[$surveyor_type][$question_category_id]['RatingTotal'],
            //$feedback_average_map[$surveyor_type][$question_category_id]['RatingCount']);
          printf("Adding rating to existing surveyor_type=%s, question_category_id=%d\n", 
            $surveyor_type, $question_category_id);
          $feedback_average_map[$surveyor_type][$question_category_id]['RatingTotal'] += $rating;
          $feedback_average_map[$surveyor_type][$question_category_id]['RatingCount'] += 1;
          printf("After Add RatingTotal for surveyor_type=%s, category id:%d, RatingTotal=%d, RatingCount=%d\n",
            $surveyor_type, $question_category_id, $feedback_average_map[$surveyor_type][$question_category_id]['RatingTotal'],
            $feedback_average_map[$surveyor_type][$question_category_id]['RatingCount']);
        }
      }
    }
    print("Calculating Rating Averages per Surveyor Type\n");
    $surveyor_type_keys = array_keys($feedback_average_map);
    foreach($surveyor_type_keys as $surveyor_type_key){
      $feedback_array[$surveyor_type]['AverageRatings'] = [];
      printf("Calculating average for Surveyor Type=%s\n", $surveyor_type_key);
      $category_id_keys = array_keys($feedback_average_map[$surveyor_type_key]);
      foreach($category_id_keys as $category_id_key){
        printf("\tCategory Id=%d, RatingTotal=%d, Number-of-Ratings=%d\n", 
          $category_id_key, $feedback_average_map[$surveyor_type_key][$category_id_key]['RatingTotal'],
          $feedback_average_map[$surveyor_type_key][$category_id_key]['RatingCount']);
        $rating_average = $feedback_average_map[$surveyor_type_key][$category_id_key]['RatingTotal'] / $feedback_average_map[$surveyor_type_key][$category_id_key]['RatingCount'];
        printf("\tRating Average=%f\n", $rating_average);
        $feedback_average_map[$surveyor_type_key][$category_id_key]['Average'] = $rating_average;
        $feedback_array[$surveyor_type_key]['AverageRatings'][$category_id_key] = $rating_average;
      }
    }
    //var_dump($feedback_array);
  }catch(PDOException $e) {
    echo "GetSurveyFeedbackForEmployeeBySurveyorType: Failed due to DB Exception: " . $e->getMessage() . "\n";
  }  
  echo "-------------------GetSurveyFeedbackForEmployeeBySurveyorType:[end]------------------------\n";  
}


function GetSurveyFeedbackForEmployeeByExternalSurveyorType($con, $survey_id, $eid, &$feedback_array){
  global $SurveyorType2String;
  $feedback_array = [];
  $feedback_average_map = [];
  echo "-------------------GetSurveyFeedbackForEmployeeByExternalSurveyorType:[start]------------------------\n";
  $open_assignment_count = GetOpenAssignmnetCountForEmployeeInSurvey($con, $survey_id, $eid);
  printf("OpenAssignments for survey_id=%d for Employee=%s is %d", $survey_id, $eid, $open_assignment_count);
  if ($open_assignment_count){
    printf("Survey Results for Employee=%s not available, response pending from %d surveyors\n", $eid, $open_assignment_count);
    return;
  }
  static $surveyor_sql = "SELECT SurveyAssignment.SurveyorEID, ExternalSurveyor.Name,";
  $surveyor_sql .= " ExternalSurveyor.CompanyName, ExternalSurveyor.Department, ExternalSurveyor.JobTitleName ";
  $surveyor_sql .= "  FROM SurveyAssignment INNER JOIN ExternalSurveyor on SurveyAssignment.SurveyorEID = ExternalSurveyor.EID";
  $surveyor_sql .= " WHERE SurveyAssignment.SurveyID=? AND SurveyAssignment.EID=? AND SurveyAssignment.SurveyorType=?";
  try{
    $survey_assignments_stmt = $con->prepare($surveyor_sql);
    $survey_assignments_stmt->execute([$survey_id, $eid, SurveyorType::ExternalSurveyor]);
    //$surveyor_map = [];
    static $feedback_sql = "SELECT QuestionCategoryID, QuestionID, Rating";
    $feedback_sql .= " FROM SurveyFeedback WHERE SurveyID=? AND EID=? AND SurveyorEID=?";
    $feedbak_stmt = $con->prepare( $feedback_sql);
    while($row = $survey_assignments_stmt->fetch(PDO::FETCH_ASSOC)){
      //var_dump($row);
      $surveyor_eid = $row['SurveyorEID'];
      $surveyor_name = $row['Name'];
      $surveyor_company = $row['CompanyName'];
      $surveyor_dept = $row['Department'];
      $surveyor_job_title = $row['JobTitleName'];      
      printf("Department=%s\n", $surveyor_dept);
      $surveyor_type = $SurveyorType2String[SurveyorType::ExternalSurveyor];
      printf("Survey Assignment Details: surveyId=%d, employeeId=%s, surveyorEId=%s, surveyor name=%s, surveyor company=%s, surveyor departmen=%s\n",
          $survey_id, $eid, $surveyor_eid, $surveyor_name, $surveyor_company, $surveyor_dept);
      //$surveyor_map[$surveyor_eid] = ['Name' => $surveyor_name, 'Type' => $surveyor_type, 'Dept' => $surveyor_dept];
      printf("Obtaining Survey Assignment feedback\n");
      if (!array_key_exists($surveyor_type, $feedback_array)){
          $feedback_array[$surveyor_type] = [];
          printf("Surveyor Type key: %s does not exist, creating new array\n",$surveyor_type);
          $feedback_array[$surveyor_type][$surveyor_eid] = ['Name' => $surveyor_name, 'Dept' => $surveyor_dept, 'Feedback' => []];
          $feedback_average_map[$surveyor_type]=[];
        }
        printf("-------Obtaining Feedback Ratings for Survey=%d, by Surveyor=%s for Employee=%s---------\n",
          $survey_id, $surveyor_eid, $eid);
        $feedbak_stmt->execute([$survey_id, $eid, $surveyor_eid]);
        while ($feedback_row = $feedbak_stmt->fetch(PDO::FETCH_ASSOC)){
          //var_dump($feedback_row);
          $question_category_id = $feedback_row['QuestionCategoryID'];
          $question_id = $feedback_row['QuestionID'];
          $rating = $feedback_row['Rating'];
          $feedback_array[$surveyor_type][$surveyor_eid]['Feedback'][$question_category_id][$question_id]=$rating;
          printf("\tQuestionCategoryID=%d, QuestionID=%d, Rating=%d\n",
            $question_category_id, $question_id, $rating);
          if (!array_key_exists($question_category_id, $feedback_average_map[$surveyor_type])){
            printf("Adding category id:[%d] to average map surveyor_type=%s, rating=[%d]\n",
              $question_category_id, $surveyor_type, $rating);
            $feedback_average_map[$surveyor_type][$question_category_id]=[];
            $feedback_average_map[$surveyor_type][$question_category_id]['RatingTotal'] = $rating;
            $feedback_average_map[$surveyor_type][$question_category_id]['RatingCount'] = 1;
            print("After initializing feedback_average_map:\n");
            //var_dump($feedback_average_map);
          }else {
            //printf("Current RatingTotal for surveyor_type=%s, category id:%d, RatingTotal=%d, RatingCount=%d\n",
            //$surveyor_type, $question_category_id,
            //$feedback_average_map[$surveyor_type][$question_category_id]['RatingTotal'],
            //$feedback_average_map[$surveyor_type][$question_category_id]['RatingCount']);
            printf("Adding rating to existing surveyor_type=%s, question_category_id=%d\n",
              $surveyor_type, $question_category_id);
            $feedback_average_map[$surveyor_type][$question_category_id]['RatingTotal'] += $rating;
            $feedback_average_map[$surveyor_type][$question_category_id]['RatingCount'] += 1;
            printf("After Add RatingTotal for surveyor_type=%s, category id:%d, RatingTotal=%d, RatingCount=%d\n",
              $surveyor_type, $question_category_id, $feedback_average_map[$surveyor_type][$question_category_id]['RatingTotal'],
              $feedback_average_map[$surveyor_type][$question_category_id]['RatingCount']);
          }
        }
    }
    //need more research to find a php framework for data analysis
    print("Calculating Rating Averages per Surveyor Type\n");
    $surveyor_type_keys = array_keys($feedback_average_map);
    foreach($surveyor_type_keys as $surveyor_type_key){
      $feedback_array[$surveyor_type]['AverageRatings'] = [];
      printf("Calculating average for Surveyor Type=%s\n", $surveyor_type_key);
      $category_id_keys = array_keys($feedback_average_map[$surveyor_type_key]);
      foreach($category_id_keys as $category_id_key){
        printf("\tCategory Id=%d, RatingTotal=%d, Number-of-Ratings=%d\n",
          $category_id_key, $feedback_average_map[$surveyor_type_key][$category_id_key]['RatingTotal'],
          $feedback_average_map[$surveyor_type_key][$category_id_key]['RatingCount']);
        $rating_average = $feedback_average_map[$surveyor_type_key][$category_id_key]['RatingTotal'] / $feedback_average_map[$surveyor_type_key][$category_id_key]['RatingCount'];
        printf("\tRating Average=%f\n", $rating_average);
        $feedback_average_map[$surveyor_type_key][$category_id_key]['Average'] = $rating_average;
        $feedback_array[$surveyor_type_key]['AverageRatings'][$category_id_key] = $rating_average;
      }
    }
    var_dump($feedback_array);
  }catch(PDOException $e) {
    echo "GetSurveyFeedbackForEmployeeByExternalSurveyorType: Failed due to DB Exception: " . $e->getMessage() . "\n";
  }
  echo "-------------------GetSurveyFeedbackForEmployeeByExternalSurveyorType:[end]------------------------\n";
}

