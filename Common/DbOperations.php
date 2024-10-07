<?php

session_start();

function CreateDBOConnection($host_name, $user_name, $password, $db_name)
{
  try {
    $conn = new PDO("mysql:host=$host_name;dbname=$db_name", $user_name, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
  } catch (PDOException $e) {
    echo "Failed to connect to DB:$db_name on host:$host_name: " . $e->getMessage() . "\n";
    exit("Db connection failed");
  }
}


function DepartmentId2DepartmentName($con, $department_id)
{
  $department_name = NULL;
  try {
    $dep_stmt = $con->prepare("SELECT DepartmentName FROM Department WHERE DepartmentID =?");
    $dep_stmt->execute([
      $department_id
    ]);
    $result = $dep_stmt->fetch();
    if ($result)
      $department_name = $result['DepartmentName'];
  } catch (PDOException $e) {
    echo "DepartmentId2DepartmentName Failed for DepartmentID:" . $department_id . ":" . $e->getMessage() . "\n";
  }
  return $department_name;
}


function DepartmentName2DepartmentId($con, $department_name){
  $department_id = 0;
  try {
    $dep_stmt = $con->prepare("SELECT DepartmentID FROM Department WHERE DepartmentName =?");
    $dep_stmt->execute([
      $department_name
    ]);
    $result = $dep_stmt->fetch();
    if ($result)
      $department_id = $result['DepartmentID'];
  } catch (PDOException $e) {
    echo "DepartmentName2DepartmentName Failed for DepartmentName:" . $department_name . ":" . $e->getMessage() . "\n";
  }
  return $department_id;
}


function JobTitleId2JobTitleName($con, $job_title_id)
{
  $job_title_name = NULL;
  try {
    $job_title_stmt = $con->prepare("SELECT JobTitleName FROM JobTitle WHERE JobTitleID =?");
    $job_title_stmt->execute([
      $job_title_id
    ]);
    $result = $job_title_stmt->fetch();
    if ($result)
      $job_title_name = $result['JobTitleName'];
  } catch (PDOException $e) {
    echo "JobTitleId2JobTitleName Failed for JobTitleID:" . $job_title_id . ":" . $e->getMessage() . "\n";
  }
  return $job_title_name;
}


function JobTitleName2JobTitleId($con, $job_title_name)
{
  $job_title_id = 0;
  try {
    $job_title_stmt = $con->prepare("SELECT JobTitleID FROM JobTitle WHERE JobTitleName =?");
    $job_title_stmt->execute([
      $job_title_name
    ]);
    $result = $job_title_stmt->fetch();
    if ($result)
      $job_title_id = $result['JobTitleID'];
  } catch (PDOException $e) {
    echo "JobTitleName2JobTitleId Failed for JobTitleName:" . $job_title_name . ":" . $e->getMessage() . "\n";
  }
  return $job_title_id;
}


function BranchId2BranchName($con, $branch_id)
{
  $branch_name = NULL;
  try {
    $branch_stmt = $con->prepare("SELECT BranchName FROM Branch WHERE BranchID =?");
    $branch_stmt->execute([
      $branch_id
    ]);
    $result = $branch_stmt->fetch();
    if ($result)
      $branch_name = $result['BranchName'];
  } catch (PDOException $e) {
    echo "BranchId2BranchName Failed for BranchID:" . $branch_id . ":" . $e->getMessage() . "\n";
  }
  return $branch_name;
}


function BranchName2BranchId($con, $branch_name)
{
  $branch_id = 0;
  try {
    $branch_stmt = $con->prepare("SELECT BranchID FROM Branch WHERE BranchName =?");
    $branch_stmt->execute([
      $branch_name
    ]);
    $result = $branch_stmt->fetch();
    if ($result)
      $branch_id = $result['BranchID'];
  } catch (PDOException $e) {
    echo "BranchName2BranchId Failed for BranchID:" . $branch_name . ":" . $e->getMessage() . "\n";
  }
  return $branch_id;
}

//Note: the reverse of this function is not valid as there could be multiple
//employees with the same name
function EmployeeId2EmployeeName($con, $employee_eid){
  $employee_name = NULL;
  try {
    $employee_name_stmt = $con->prepare("SELECT Name FROM Employee WHERE EID =?");
    $employee_name_stmt->execute([
      $employee_eid
    ]);
    $result = $employee_name_stmt->fetch();
    if (!empty($result['Name']))
      $employee_name = $result['Name'];
    else {
      $external_name_stmt = $con->prepare("SELECT Name FROM ExternalSurveyor WHERE EID =?");
      $external_name_stmt->execute([
        $employee_eid
      ]);
      $result = $external_name_stmt->fetch();
      $employee_name = $result['Name'];
    }
  } catch (PDOException $e) {
    echo "EmployeeId2EmployeeName Failed for BranchID:" . $employee_eid . ":" . $e->getMessage() . "\n";
  }
  return $employee_name;  
}


function GetEmployeeDetails($con, $eid, &$employee_details)
{
  $employee_details = [];
  try {
    echo "Looking for details of Employee EID=" . $eid . "\n";
    $employee_details_stmt = $con->prepare("SELECT Name, DepartmentID, JobTitleID, Email FROM Employee WHERE EID =?");
    $employee_details_stmt->execute([
      $eid
    ]);
    $employee_details_row = $employee_details_stmt->fetch();
    if ($employee_details_row) {
      $employee_details['EID'] = $eid;
      $employee_details['Name'] = $employee_details_row['Name'];
      $employee_details['DepartmentName'] = DepartmentId2DepartmentName($con, $employee_details_row['DepartmentID']);
      $employee_details['JobTitleName'] = JobTitleId2JobTitleName($con, $employee_details_row['JobTitleID']);
      $employee_details['EmailAddress'] = $employee_details_row['Email'];
      printf("Adding Details of Employee EID:%s, Name:%s,JobTitleId:%s, EmailAddress:%s", $eid, $employee_details['Name'], $employee_details['JobTitleName'], $employee_details['EmailAddress']);
    }
  } catch (PDOException $e) {
    echo "GetEmployeeReportingManagers for EmployeeId:" . $eid . ":" . $e->getMessage() . "\n";
  }
}

function GetEmployeeReportingManagersForSurveyAssignment($con, $eid, &$rm_list)
{
  $rm_list = [];
  try {
    $rm_stmt = $con->prepare("SELECT SEID FROM Supervisor WHERE EID =?");
    $rm_stmt->execute([
      $eid
    ]);
    while ($row = $rm_stmt->fetch()) {
      $rm_eid = $row['SEID'];
      echo "Looking for details of Reporting Manager EID=" . $rm_eid . "\n";
      $rm_details_stmt = $con->prepare("SELECT Name, DepartmentID, JobTitleID, PhoneNumber, Email FROM Employee WHERE EID =?");
      $rm_details_stmt->execute([$rm_eid]);
      $rm_details_row = $rm_details_stmt->fetch();
      if ($rm_details_row) {
        $rm_name = $rm_details_row['Name'];
        $rm_dept_id = $rm_details_row['DepartmentID'];
        $rm_job_title_id = $rm_details_row['JobTitleID'];
        $rm_phone_num = $rm_details_row['PhoneNumber'];
        $rm_email = $rm_details_row['Email'];
        array_push($rm_list, [
          'EID' => $rm_eid,
          'Name' => $rm_name,
          'DepartmentName' => DepartmentId2DepartmentName($con, $rm_dept_id),
          'JobTitleName' => JobTitleId2JobTitleName($con, $rm_job_title_id),
          'PhoneNumber' => $rm_phone_num,
          'EmailAddress' => $rm_email
        ]);
      }
    }
  } catch (PDOException $e) {
    echo "Error: GetEmployeeReportingManagersForSurveyAssignment Failed for EmployeeId:" . $eid . ":" . $e->getMessage() . "\n";
  }
}


function GetEmployeeDirectReportsForSurveyAssignment($con, $eid, &$dr_list)
{
  $dr_list = [];
  try {
    $dr_stmt = $con->prepare("SELECT  DirectReportEID FROM DirectReports WHERE EID =?");
    $dr_stmt->execute([$eid]);
    while ($row = $dr_stmt->fetch()) {
      $dr_eid = $row['DirectReportEID'];
      echo "DirectReport EID=" . $dr_eid . "\n";
      $dr_details_stmt = $con->prepare("SELECT Name, DepartmentID, JobTitleID, PhoneNumber, Email FROM Employee WHERE EID =?");
      $dr_details_stmt->execute([$dr_eid]);
      $dr_details_row = $dr_details_stmt->fetch();
      if ($dr_details_row) {
        $dr_name = $dr_details_row['Name'];
        $dr_dept_id = $dr_details_row['DepartmentID'];
        $dr_job_title_id = $dr_details_row['JobTitleID'];
        $dr_phone_num = $dr_details_row['PhoneNumber'];
        $dr_email = $dr_details_row['Email'];
        array_push($dr_list, [
          'EID' => $dr_eid,
          'Name' => $dr_name,
          'DepartmentName' => DepartmentId2DepartmentName($con, $dr_dept_id),
          'JobTitleName' => JobTitleId2JobTitleName($con, $dr_job_title_id),
          'PhoneNumber' => $dr_phone_num,
          'EmailAddress' => $dr_email
        ]);
      }
    }
  } catch (PDOException $e) {
    echo "Error: GetEmployeeDirectReportsForSurveyAssignment Failed for EmployeeId:" . $eid . ":" . $e->getMessage() . "\n";
  }
}

function GetEmployeePeerListForSurveyAssignment($con, $eid, &$peer_list)
{
  $peer_list = [];
  try {
    $peer_list_stmt = $con->prepare("SELECT  PeerID FROM Peers WHERE EID =?");
    $peer_list_stmt->execute([$eid]);
    while ($row = $peer_list_stmt->fetch()) {
      $peer_eid = $row['PeerID'];
      echo "Peer EID=" . $peer_eid . "\n";
      $peer_details_stmt = $con->prepare("SELECT Name, DepartmentID, JobTitleID, PhoneNumber, Email FROM Employee WHERE EID =?");
      $peer_details_stmt->execute([$peer_eid]);
      $peer_details_row = $peer_details_stmt->fetch();
      if ($peer_details_row) {
        $peer_name = $peer_details_row['Name'];
        $peer_dept_id = $peer_details_row['DepartmentID'];
        $peer_job_title_id = $peer_details_row['JobTitleID'];
        $peer_phone_num = $peer_details_row['PhoneNumber'];
        $peer_email = $peer_details_row['Email'];
        array_push($peer_list, [
          'EID' => $peer_eid,
          'Name' => $peer_name,
          'DepartmentName' => DepartmentId2DepartmentName($con, $peer_dept_id),
          'JobTitleName' => JobTitleId2JobTitleName($con, $peer_job_title_id),
          'PhoneNumber' => $peer_phone_num,
          'EmailAddress' => $peer_email
        ]);
      }
    }
  } catch (PDOException $e) {
    echo "Error: GetEmployeePeerListForSurveyAssignment Failed for EmployeeId:" . $eid . ":" . $e->getMessage() . "\n";
  }
}


function EmployeeSearchByName($con, $employee_name_part, &$employee_list){
  $employee_list = [];
  try {
    echo "Using regex=[" . $employee_name_part . "]\n";
    $employee_list_stmt = $con->prepare("SELECT EID, Name, DepartmentID, JobTitleID, PhoneNumber, Email FROM Employee WHERE REGEXP_LIKE(Name, ?)");
    $employee_list_stmt->execute([$employee_name_part]);
    $matching_rows = $employee_list_stmt->fetchall();
    foreach ($matching_rows as $employee_details_row) {
      $employee_eid = $employee_details_row['EID'];
      $employee_name = $employee_details_row['Name'];
      $employee_dept_id = $employee_details_row['DepartmentID'];
      $employee_job_title_id = $employee_details_row['JobTitleID'];
      $employee_phone_num = $employee_details_row['PhoneNumber'];
      $employee_email = $employee_details_row['Email'];
      printf("EmployeeSearchByName: Found Employee=%s, EID=%s, DepartmentId=%d, JobTitleId=%d, PhoneNumber=%s, Email=%s\n",
        $employee_name, $employee_eid, $employee_dept_id, $employee_job_title_id, $employee_phone_num, $employee_email);
      array_push($employee_list, [
          'EID' => $employee_eid,
          'Name' => $employee_name,
          'DepartmentName' => DepartmentId2DepartmentName($con, $employee_dept_id),
          'JobTitleName' => JobTitleId2JobTitleName($con, $employee_job_title_id),
          'PhoneNumber' => $employee_phone_num,
          'EmailAddress' => $employee_email
        ]);
    }
  } catch (PDOException $e) {
    echo "EmployeeSearchByName Failed for string:" . $employee_name_part . ":" . $e->getMessage() . "\n";
  }  
}


function EmployeeSearchByDepartment($con, $department_name, &$employee_list){
  $employee_list = [];
  try {
    echo "Searching for emploees from Department=[" . $department_name . "]\n";
    $department_name_stmt = $con->prepare("SELECT DepartmentID FROM Department WHERE REGEXP_LIKE(DepartmentName, ?)");
    $department_name_stmt->execute([$department_name]);
    $matching_department_rows = $department_name_stmt->fetchall();
    foreach ($matching_department_rows as $department_row){
      $employee_list_stmt = $con->prepare("SELECT EID, Name, JobTitleID, PhoneNumber, Email FROM Employee WHERE DepartmentID = ?");
      $employee_list_stmt->execute([$department_row['DepartmentID']]);
      $matching_rows = $employee_list_stmt->fetchall();
      foreach ($matching_rows as $employee_details_row) {
        $employee_eid = $employee_details_row['EID'];
        $employee_name = $employee_details_row['Name'];
        $employee_dept_id = $department_row['DepartmentID'];
        $employee_job_title_id = $employee_details_row['JobTitleID'];
        $employee_phone_num = $employee_details_row['PhoneNumber'];
        $employee_email = $employee_details_row['Email'];
        printf("EmployeeSearchByDepartment: Found Employee=%s, EID=%s, DepartmentId=%d, JobTitleId=%d, PhoneNumber=%s, Email=%s\n",
          $employee_name, $employee_eid, $employee_dept_id, $employee_job_title_id, $employee_phone_num, $employee_email);
        array_push($employee_list, [
          'EID' => $employee_eid,
          'Name' => $employee_name,
          'DepartmentName' => DepartmentId2DepartmentName($con, $employee_dept_id),
          'JobTitleName' => JobTitleId2JobTitleName($con, $employee_job_title_id),
          'PhoneNumber' => $employee_phone_num,
          'EmailAddress' => $employee_email
        ]);
      }
    }
  } catch (PDOException $e) {
    echo "EmployeeSearchByDepartment Failed for string:" . $department_name . ":" . $e->getMessage() . "\n";
  }
}



//****************************ADDED BY PASAN*****************************

//$hostname = "50.87.232.129";
$hostname = "localhost";
$password = "MySql@123";
$username = "root";
$dbname   = "thrwcons_hayleys_360";

/*
//godaddy
$hostname = "160.153.129.239";
$password = "2FE!U$d(wQw}";
$username = "root_360";
$dbname   = "360_survey_schema";

//bluehost
$hostname = "50.87.232.129";
$password = "1rkLhgDMoY2n";
$username = "thrwcons_root";
$dbname   = "thrwcons_hayleys_360";

$hostname = "localhost";
$password = "MySql@123";
$username = "root";
$dbname   = "360_survey_schema";

*/

$con = CreateDBOConnection($hostname, $username, $password, $dbname);

// to get the questionare ID for feedback page

function SurveyID2questionareID($con, $surveyID)
{
  $questionareID = 0;
  try {
    $questionare_stmt = $con->prepare("SELECT QuestionaireID FROM Survey where SurveyID = ?");
    $questionare_stmt->execute([
      $surveyID
    ]);
    $result = $questionare_stmt->fetch();
    if ($result)
      $questionareID = $result['QuestionaireID'];
  } catch (PDOException $e) {
    echo "Questionare ID getting failed:" . $surveyID . ":" . $e->getMessage() . "\n";
  }
  return $questionareID;
}

function GetAllEmployeeList($con, &$result)
{
  $result = [];
  try {
    $sql    =  "SELECT * FROM Employee 
          JOIN Branch ON Employee.BranchID = Branch.BranchID 
          JOIN Department ON Employee.DepartmentID = Department.DepartmentID 
          JOIN JobTitle ON Employee.JobTitleID = JobTitle.JobTitleID";
    $result = $con->query($sql);
  } catch (PDOException $e) {
    echo "Error: Get all employee list failed! :" . $e->getMessage() . "\n";
  }
}
