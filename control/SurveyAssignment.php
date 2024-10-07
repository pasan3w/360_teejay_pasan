<?php

require "Survey.php";

function ValidateAssignment($con, $survey_id, $surveyee_eid, $surveyor_eid){
  echo "-------------------------ValidateAssignment:[start]-----------------------------\n";
  $survey_state = GetSurveyState($con, $survey_id);
  echo "Survey State=" . $survey_state . "\n";
  if ($survey_state != SurveyState::Assign){
    printf("SurveyId:%d, SurveyState:%d Current State is not valid for for new Assignment\n",
      $survey_id, $survey_state);
    return false;
  }
  try {
    //Check for duplicate assignments
    $validate_assign_stmt = $con->prepare("SELECT COUNT(*) FROM SurveyAssignment WHERE SurveyID=? AND EID=? AND SurveyorEID=?");
    $validate_assign_stmt->execute([$survey_id, $surveyee_eid, $surveyor_eid]);
    //if ($result){
    $count = $validate_assign_stmt->fetchColumn();
    if ($count){
      printf("SurveyID: %d already contains a SurveyAssignment for employee:%s by surveyor %s, number of duplicate assignments=%d\n",
        $survey_id, $surveyee_eid, $surveyor_eid, $count);
      return false;
    }else{
      printf("No duplicate assignments found for survey_id=%d, employee=%s by surveyor=%s\n", 
        $survey_id, $surveyee_eid, $surveyor_eid);
      return true;
    }
  } catch (PDOException $e) {
    echo "ValidateAssignment Failed due to exception:" . $e->getMessage() . "\n";
    return false;
  }
  echo "-------------------------ValidateAssignment:[end]-----------------------------\n";
  return true;
}


function GetExternalSurveyorDetails($con, $external_surveyor_eid, &$external_surveyor_details){
  echo "-------------------------GetExternalSurveyorDetails:[end]-----------------------------\n";  
  $external_surveyor_details = [];
  try {
    echo "Looking for details of External Surveyor EID(email)=" . $external_surveyor_eid . "\n";
    $ext_surveyor_details_stmt = $con->prepare("SELECT Name, CompanyName, Department, JobTitleName FROM ExternalSurveyor WHERE EID =?");
    $ext_surveyor_details_stmt->execute([
      $external_surveyor_eid
    ]);
    $surveyor_details_row = $ext_surveyor_details_stmt->fetch(PDO::FETCH_ASSOC);
    if ($surveyor_details_row) {
      $external_surveyor_details['EID'] = $external_surveyor_eid;
      $external_surveyor_details['Name'] = $surveyor_details_row['Name'];
      $external_surveyor_details['CompanyName'] = $surveyor_details_row['CompanyName'];
      $external_surveyor_details['Department'] = $surveyor_details_row['Department'];
      $external_surveyor_details['JobTitleName'] = $surveyor_details_row['JobTitleName'];
      printf("Adding Details of External Surveyor EID:%s, Name:%s, Company:%s, Department:%s, JobTitle:%s\n", 
        $external_surveyor_details['EID'], $external_surveyor_details['Name'],
        $external_surveyor_details['CompanyName'], $external_surveyor_details['Department'],
        $external_surveyor_details['JobTitleName']);
      return true;
    }
  } catch (PDOException $e) {
    echo "GetExternalSurveyorDetails Failed for External Surveyor:" .  $external_surveyor_eid . ":" . $e->getMessage() . "\n";
  }
  echo "-------------------------GetExternalSurveyorDetails:[end]-----------------------------\n";
  return false;
}


function AddExternalSurveyorDetails($con, $surveyor_eid, $surveyor_name, $company, $department, $designation){
  echo "-------------------------AddExternalSurveyorDetails:[start]-----------------------------\n";
  $surveyor_details = [];
  if (GetExternalSurveyorDetails($con, $surveyor_eid, $surveyor_details)){
    printf("Entry already exists for external Surveyor EID:%s Name:%s of Company:%s\n",
      $surveyor_details['EID'], $surveyor_details['Name'], $surveyor_details['CompanyName']);
    return true;
  }
  try {
    $insert_ext_surveyor_details_stmt = $con->prepare("INSERT INTO ExternalSurveyor (EID, Name, CompanyName, Department, JobTitleName) VALUES (? , ?, ?, ?, ?)");
    if ($insert_ext_surveyor_details_stmt->execute([$surveyor_eid, $surveyor_name, $company, $department,$designation])){
      printf("AddExternalSurveyorDetails: Successfully Added entry for External Surveyor EID=%d, Name=%s Company=%s, Department=%s, JobTitle=%s\n",
        $surveyor_eid, $surveyor_name, $company, $department, $designation);
      return true;
    }else {
      printf("AddExternalSurveyorDetails: Failed to Create entry for External Surveyor EID=%s, Name:%s of Company=%s\n",
        $surveyor_eid, $surveyor_name, $company);
    }
  }catch (PDOException $e) {
    echo "AddExternalSurveyorDetails Failed due to Exception:" . $e->getMessage() . "\n";
  }
  echo "-------------------------AddExternalSurveyorDetails:[end]-----------------------------\n";
  return false;  
}

function AssignSurvey($con, $survey_id, $assigner_eid, $surveyee_eid, $surveyor_eid, $surveyor_type, $date){
  echo "-------------------------AssignSurvey:[start]-----------------------------\n";
  global $SurveyorType2String;
  if (!ValidateAssignment($con, $survey_id, $surveyee_eid, $surveyor_eid)){
    echo "Error: Illegal Survey Assignment\n";
    return false;
  }
  try {
    $create_assignment_stmt = $con->prepare("INSERT INTO SurveyAssignment (SurveyID, AssignorEID, SurveyorEID, SurveyorType, EID, AssignedDate) VALUES (? , ?, ?, ?, ?, ?)");
    if ($create_assignment_stmt->execute([$survey_id, $assigner_eid, $surveyor_eid, $surveyor_type, $surveyee_eid, $date])){
      printf("AssignSurvey:Successful SurveyId=%d, AssignorEID=%s SurveyorEID=%s, SurveyorType=%s to survey EID=%s, Date=%s\n",
        $survey_id, $assigner_eid, $surveyor_eid, $SurveyorType2String[$surveyor_type], $surveyee_eid, $date);
      return true;
    }else {
      printf("AssignSurvey: Failed Assignment Survey_id=%s, Assignor=%s  to Surveyor=%d to survey employee=%s\n",
        $survey_id, $assigner_eid, $surveyor_eid, $surveyee_eid);
    }
  }catch (PDOException $e) {
    echo "AssignSurvey Failed due to Exception:" . $e->getMessage() . "\n";
  }
  echo "-------------------------AssignSurvey:[end]-----------------------------\n";
  return false;
}


function UpdateSurveyAssignmentResponseDate($con, $survey_id, $surveyor_eid, $surveyee_eid, $date){
  //echo "-------------------------UpdateSurveyAssignmentResponseDate:[start]-----------------------------\n";  
  try{
    $survey_response_date_stmt= $con->prepare("UPDATE SurveyAssignment SET ResponseDate=? WHERE SurveyID=? AND SurveyorEID=? AND EID=?");
    $survey_response_date_stmt->execute([$date, $survey_id, $surveyor_eid, $surveyee_eid]);
    CloseSurveyIfComplete($con, $survey_id);
  }catch (PDOException $e) {
    echo "UpdateSurveyAssignmentResponseDate: Failed due to exception: " . $e->getMessage() . "\n";
  }
  //echo "-------------------------UpdateSurveyAssignmentResponseDate:[end]-----------------------------\n";
  return false;
}


function GetOpenAssignmentsOfSurvey($con, $survey_id, &$assignment_list){
  echo "-------------------------GetOpenAssignmentsOfSurvey:[start]-----------------------------\n";
  global $SurveyorType2String;
  $assignment_list = [];
  try{
    $survey_assignment_stmt = $con->prepare("SELECT SurveyorEID, SurveyorType, EID FROM SurveyAssignment WHERE SurveyID=? AND ResponseDate IS NULL");
    $survey_assignment_stmt->execute([$survey_id]);
    while ($assignment_row = $survey_assignment_stmt->fetch(PDO::FETCH_ASSOC)) {
      printf("Incomplete Survey Assignment: surveyor=%s, surveyor_type=%s, surveyee=%s\n", 
        $assignment_row['SurveyorEID'], $SurveyorType2String[$assignment_row['SurveyorType']], $assignment_row['EID']);
      array_push($assignment_list, [$assignment_row['SurveyorEID'], $assignment_row['SurveyorType'], $assignment_row['EID']]);
    }
  }catch(PDOException $e) {
    echo "GetOpenAssignmentsOfSurvey: Failed due to exception: " . $e->getMessage() . "\n";
  }
  echo "-------------------------GetOpenAssignmentsOfSurvey:[end]-----------------------------\n";
}


function GetOpenAssignmnetCountOfSurvey($con, $survey_id){
  try {
    $sql = "SELECT COUNT(*) FROM SurveyAssignment WHERE SurveyID=? AND ResponseDate IS NULL";
    $count_open_assignments_stmt = $con->prepare($sql);
    $count_open_assignments_stmt->execute([$survey_id]);
    $open_assignments_count = $count_open_assignments_stmt->fetchColumn();
    //printf("Number of open Assignments for Survey=%d is=%d\n", $survey_id, $open_assignments_count);
    return $open_assignments_count;
  }catch (PDOException $e) {
    echo "GetOpenAssignmnetCountOfSurvey: Failed due to exception: " . $e->getMessage() . "\n";
  }
  return 1;
  
}


function GetOpenAssignmnetCountForEmployeeInSurvey($con, $survey_id, $eid){
  try {
    $sql = "SELECT COUNT(*) FROM SurveyAssignment WHERE SurveyID=? AND EID=? AND ResponseDate IS NULL";
    $count_open_assignments_stmt = $con->prepare($sql);
    $count_open_assignments_stmt->execute([$survey_id, $eid]);
    $open_assignments_count = $count_open_assignments_stmt->fetchColumn();
    //printf("Number of open Assignments for Survey=%d of Employee=%s is=%d\n", $survey_id, $eid, $open_assignments_count);
    return $open_assignments_count;
  }catch (PDOException $e) {
    echo "GetOpenAssignmnetCountForEmployeeInSurvey: Failed due to exception: " . $e->getMessage() . "\n";
  }
  return 1;
}


function GetOpenAssignmentsOfEmployeeInSurvey($con, $survey_id, $eid, &$assignment_list){
  //echo "-------------------------GetOpenAssignmentsOfEmployeeInSurvey:[start]-----------------------------\n";
  global $SurveyorType2String;
  $assignment_list = [];
  try{
    $survey_assignment_stmt = $con->prepare("SELECT SurveyorEID, SurveyorType FROM SurveyAssignment WHERE SurveyID=? AND ResponseDate IS NULL AND EID=?");
    $survey_assignment_stmt->execute([$survey_id, $eid]);
    while ($assignment_row = $survey_assignment_stmt->fetch(PDO::FETCH_ASSOC)) {
      //printf("Incomplete Survey Assignment: survey=%d, surveyor=%s, surveyor type=%s, surveyee=%s\n", 
        //$survey_id, $assignment_row['SurveyorEID'], $SurveyorType2String[$assignment_row['SurveyorType']], $eid);
      array_push($assignment_list, [$assignment_row['SurveyorEID'], $assignment_row['SurveyorType']]);
    }
  }catch(PDOException $e) {
    echo "GetOpenAssignmentsOfEmployeeInSurvey: Failed due to exception: " . $e->getMessage() . "\n";
  }
  //echo "-------------------------GetOpenAssignmentsOfEmployeeInSurvey:[end]-----------------------------\n";
}


function GetOpenAssignmnetCountForSurveyorInSurvey($con, $survey_id, $surveyor_eid){
  echo "-------------------GetOpenAssignmnetCountForSurveyorInSurvey:[start]------------------------\n";
  try {
    $sql = "SELECT COUNT(*) FROM SurveyAssignment WHERE SurveyID=? AND SurveyorEID=? AND ResponseDate IS NULL";
    $count_open_assignments_stmt = $con->prepare($sql);
    $count_open_assignments_stmt->execute([$survey_id, $surveyor_eid]);
    $open_assignments_count = $count_open_assignments_stmt->fetchColumn();
    printf("Number of open Assignments for Survey=%d for Surveyor=%s is=%d\n", $survey_id, $surveyor_eid, $open_assignments_count);
    return $open_assignments_count;
  }catch (PDOException $e) {
    echo "GetOpenAssignmnetCountForSurveyorInSurvey: Failed due to exception: " . $e->getMessage() . "\n";
  }
  echo "-------------------GetOpenAssignmnetCountForSurveyorInSurvey:[end]------------------------\n";
  return 1;
}


function GetOpenAssignmentsForSurveyorInSurvey($con, $survey_id, $surveyor_eid, &$assignment_list){
  echo "-------------------------GetOpenAssignmentsForSurveyorInSurvey:[start]-----------------------------\n";
  global $SurveyorType2String;
  $assignment_list = [];
  try{
    $survey_assignment_stmt = $con->prepare("SELECT SurveyorType, EID FROM SurveyAssignment WHERE SurveyID=? AND SurveyorEID=? AND ResponseDate IS NULL");
    $survey_assignment_stmt->execute([$survey_id, $surveyor_eid]);
    while ($assignment_row = $survey_assignment_stmt->fetch(PDO::FETCH_ASSOC)) {
      printf("Incomplete Survey Assignment: survey=%d, surveyor=%s, surveyor type=%s, surveyee=%s\n",
        $survey_id, $surveyor_eid, $SurveyorType2String[$assignment_row['SurveyorType']], $assignment_row['EID']);
      array_push($assignment_list, [$assignment_row['SurveyorType'], $assignment_row['EID']]);
    }
  }catch(PDOException $e) {
    echo "GetOpenAssignmentsForSurveyorInSurvey: Failed due to exception: " . $e->getMessage() . "\n";
  }
  echo "-------------------------GetOpenAssignmentsForSurveyorInSurvey:[end]-----------------------------\n";
}


//Technically this should return a list of 1 element or empty 
function GetOpenAssignmentsForSurveyorOfEmployeeInSurvey($con, $survey_id, $surveyor_eid, $eid, &$assignment_list){
  echo "-------------------------GetOpenAssignmentsForSurveyorOfEmployeeInSurvey:[start]-----------------------------\n";
  global $SurveyorType2String;
  $assignment_list = [];
  try{
    $survey_assignment_stmt = $con->prepare("SELECT SurveyorType FROM SurveyAssignment WHERE SurveyID=? AND SurveyorEID=? AND EID=? AND ResponseDate IS NULL");
    $survey_assignment_stmt->execute([$survey_id, $surveyor_eid, $eid]);
    while ($assignment_row = $survey_assignment_stmt->fetch(PDO::FETCH_ASSOC)) {
      printf("Incomplete Survey Assignment: survey=%d, surveyor=%s, surveyor type=%s, surveyee=%s\n",
        $survey_id, $surveyor_eid, $SurveyorType2String[$assignment_row['SurveyorType']], $eid);
      array_push($assignment_list, [$assignment_row['SurveyorType']]);
    }
  }catch(PDOException $e) {
    echo "GetOpenAssignmentsForSurveyorOfEmployeeInSurvey: Failed due to exception: " . $e->getMessage() . "\n";
  }
  echo "-------------------------GetOpenAssignmentsForSurveyorOfEmployeeInSurvey:[end]-----------------------------\n";
  
}

