<?php

include '../Common/DbOperations.php';

enum SurveyState
{
  public const InvalidState = 0;
  public const Assign = 1;
  public const InProgress = 2;
  public const Complete = 3;
}

$SurveyState2String = [
  SurveyState::InvalidState => "Invalid",
  SurveyState::Assign => "Assign",
  SurveyState::InProgress => "InProgress",
  SurveyState::Complete => "Complete"  
];

enum SurveyorType
{
  public const Self = 0;
  public const ReportingManager = 1;
  public const DirectReport = 2;
  public const Peer = 3;
  public const InternalSurveyor = 4;
  public const ExternalSurveyor = 5;
}

$SurveyorType2String = [
  SurveyorType::Self              => "Self",
  SurveyorType::ReportingManager  => "ReportingManager",
  SurveyorType::DirectReport      => "DirectReport",
  SurveyorType::Peer              => "Peer",
  SurveyorType::InternalSurveyor  => "InternalSurveyor",
  SurveyorType::ExternalSurveyor  => "ExternalSurveyor"  
];

//================================================================================
//================================ ADDED BY PASAN ================================
//================================================================================

$SurveyorTypeString2Enum = [
  "Self"              => SurveyorType::Self,
  "Reporting Manager" => SurveyorType::ReportingManager,
  "Direct Reporting"  => SurveyorType::DirectReport,
  "Peer"              => SurveyorType::Peer,
  "Internal"          => SurveyorType::InternalSurveyor,
  "External"          => SurveyorType::ExternalSurveyor
];

//================================================================================


function CheckForOpenSurveys($con){
  $open_survey_id = 0;
  try {
    $check_open_surveys_stmt = $con->prepare("SELECT SurveyID FROM Survey WHERE State <> ?");
    $check_open_surveys_stmt->execute([SurveyState::Complete]);
    if ($result = $check_open_surveys_stmt->fetch()){
      $open_survey_id = $result['SurveyID'];
      echo "CheckForOpenSurveys: Found at least one open SurveyID=" . $open_survey_id . "\n";
    }
  }catch (PDOException $e) {
    echo "CheckForOpenSurveys: Failed due to exception: " . $e->getMessage() . "\n";
  }
  return $open_survey_id;
}


function CreateSurvey($con, $question_list_id, $date){
  //echo "-------------------------------------CreateSurvey:[start]--------------------------------------------\n";
  $open_survey_id = 0;
  if ($open_survey_id = CheckForOpenSurveys($con)){
    printf("CreateSurvey: Failed. At least one open Survey exist Id=%d, Close all open Surveys before creating new Survey\n", 
      $open_survey_id);
    return 0;    
  }
  //printf("CreateSurvey: Creating new Survey using question list id=%s, date=[%s]\n", $question_list_id, $date);
  $survey_id = 0;
  try {
    $create_survey_stmt = $con->prepare("INSERT INTO Survey (QuestionaireID, Date) VALUES (? , ?)");
    if ($create_survey_stmt->execute([$question_list_id, $date])){
      $survey_id = $con->lastInsertId('SurveyID');
      echo "CreateSurvey: Successfully created new SurveyID=" . $survey_id . "\n";
    }
  }catch (PDOException $e) {
    echo "CreateSurvey Failed:" . $e->getMessage() . "\n";
  }
  //echo "-------------------------------------CreateSurvey:[end]--------------------------------------------\n";
  return $survey_id;
}


function UpdateSurveyState($con, $survey_id, $state){
  //echo "------------------------------UpdateSurveyState:[start]--------------------------------------------\n";
  if (SurveyState::Assign > $state || SurveyState::Complete < $state){
    printf("Invalid Survey State: %d\n", $state);
    return;
  }
  try {
    $update_survey_state_stmt = $con->prepare("UPDATE Survey SET State =? WHERE  SurveyId=?");
    if ($update_survey_state_stmt->execute([$state, $survey_id])){
      //printf("UpdateSurveyState: Updated State of SurveyId:%d to %d\n", $survey_id, $state);
    }
  }catch (PDOException $e) {
    echo "UpdateSurveyState: Failed due to Exception: " . $e->getMessage() . "\n";
  }  
  //echo "------------------------------------UpdateSurveyState:[end]------------------------------------------------\n";  
}


function GetSurveyQuestionaireId($con, $survey_id){
  //echo "-------------------GetSurveyQuestionaireId:[start]---------------------------------\n";
  try {
    $survey_state_stmt = $con->prepare("SELECT QuestionaireID FROM Survey WHERE SurveyID = ?");
    $survey_state_stmt->execute([$survey_id]);
    $result = $survey_state_stmt->fetch();
    if ($result){
      $questionaire_id = $result['QuestionaireID'];
      //printf("Survey SurveyID=%d, QuestionaireIDe=%d\n", $survey_id, $questionaire_id);
      return $questionaire_id;
    }
  } catch (PDOException $e) {
    echo "GetSurveyQuestionaireId Failed due to exception:" . $e->getMessage() . "\n";
  }
  //echo "-------------------GetSurveyQuestionaireId:[end]----------------------------------\n";
  return 0;  
}


function GetSurveyState($con, $survey_id){
  //echo "-----------------------------GetSurveyState:[start]-------------------------------\n";
  try {
    $survey_state_stmt = $con->prepare("SELECT State FROM Survey WHERE SurveyID = ?");
    $survey_state_stmt->execute([$survey_id]);
    $result = $survey_state_stmt->fetch();
    if ($result){
      $survey_state = $result['State'];
      //printf("Survey: SurveyID=%d, State=%d\n", $survey_id, $survey_state);
      return $survey_state;
    }
  } catch (PDOException $e) {
    echo "GetSurveyState Failed due to exception:" . $e->getMessage() . "\n";
  }
  //echo "-------------------------------GetSurveyState:[end]-------------------------------\n";
  return SurveyState::InvalidState;
}


function CloseSurveyIfComplete($con, $survey_id){
  //echo "---------------------------CloseSurveyIfComplete:[start]---------------------------\n";
  try {
    $count_open_assignments_stmt = $con->prepare("SELECT COUNT(*) FROM SurveyAssignment WHERE SurveyID=? AND ResponseDate IS NULL");
    $count_open_assignments_stmt->execute([$survey_id]);
    $open_assignments_count = $count_open_assignments_stmt->fetchColumn();
    //printf("Number of open Assignments for Survey=%d is=%d\n", $survey_id, $open_assignments_count);
    if (0 == $open_assignments_count){
      //print("No open Assignments found, closing survey\n");
      $update_survey_state_stmt = $con->prepare("UPDATE Survey SET State =? WHERE  SurveyId=?");
      return $update_survey_state_stmt->execute([SurveyState::Complete, $survey_id]);
    }
  }catch (PDOException $e) {
    echo "CloseSurveyIfComplete: Failed due to exception: " . $e->getMessage() . "\n";
  }
  //echo "----------------------------CloseSurveyIfComplete:[end]-----------------------------\n";
  return false;
  
}


function CompleteSurvey($con, $survey_id){
  //echo "-------------------------------CompleteSurvey:[start]--------------------------------\n";
  try {
    $update_survey_state_stmt = $con->prepare("UPDATE Survey SET State =? WHERE  SurveyId=?");
    return $update_survey_state_stmt->execute([SurveyState::Complete, $survey_id]);
  }catch (PDOException $e) {
    echo "CompleteSurvey: Failed due to exception: " . $e->getMessage() . "\n";
  }
  //echo "------------------------------CompleteSurvey:[end]-----------------------------------\n";
  return false;
}