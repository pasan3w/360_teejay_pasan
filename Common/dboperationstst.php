<?php

require "DbOperations.php";


function BranchName2IdTst($con, $branch_name){
  echo "--------------------BranchName2IdTst:[start]--------------------";
  $branch_id =  BranchName2BranchId($con, $branch_name);
  printf("BranchName=%s ==> BranchId=%d\n", $branch_name, $branch_id);
  $branch_name2 = BranchId2BranchName($con, $branch_id);
  printf("BranchId=%d ==> BranchName=%s\n", $branch_id, $branch_name);
  if (0 == strcmp($branch_name, $branch_name2)){
    print("Test successfull\n");
  }else {
    printf("Test Failed, the Branch names differ: branch[initial]=%s, branch[final]=%s\n",
      $branch_name, $branch_name2);
  }
  echo "--------------------BranchName2IdTst:[end]--------------------";  
}


function DepartmentName2IdTst($con, $department_name){
  echo "--------------------DepartmentName2IdTst:[start]--------------------";
  $department_id =  DepartmentName2DepartmentId($con, $department_name);
  printf("DepartmentName=%s ==> DepartmentId=%d\n", $department_name, $department_id);
  $department_name2 = DepartmentId2DepartmentName($con, $department_id);
   printf("DepartmentId=%d ==> DepartmentName=%s\n", $department_id, $department_name2);
  if (0 == strcmp($department_name, $department_name2)){
    print("Test successfull\n");
  }else {
    printf("Test Failed, the Department names differ: department[initial]=%s, department[final]=%s\n",
      $department_name, $department_name2);
  }
  echo "--------------------DepartmentName2IdTst:[end]--------------------";
}


function JobTitleName2IdTst($con, $jobtitle_name){
  echo "--------------------JobTitleName2IdTst:[start]--------------------";
  $jobtitle_id =  JobTitleName2JobTitleId($con, $jobtitle_name);
  printf("JobTitleName=%s ==> JobTitleId=%d\n", $jobtitle_name, $jobtitle_id);
  $jobtitle_name2 = JobTitleId2JobTitleName($con, $jobtitle_id);
  printf("JobTitleId=%d ==> JobTitleName=%s\n", $jobtitle_id, $jobtitle_name);  
  if (0 == strcmp($jobtitle_name, $jobtitle_name2)){
    print("Test successfull\n");
  }else {
    printf("Test Failed, the JobTitle names differ: jobtitle[initial]=%s, jobtitle[final]=%s\n",
      $jobtitle_name, $jobtitle_name2);
  echo "--------------------JobTitleName2IdTst:[end]--------------------";
  }
}


function GetEmployeeDetailsTst($con, $employee_eid){
  echo "--------------------GetEmployeeDetailsTst:[start]--------------------";
  printf("----Obtaining Employee Details using:GetEmployeeDetails for eid=%s, name=%s----\n",
    $employee_eid, EmployeeId2EmployeeName($con, $employee_eid));
  $employee_details = [];
  GetEmployeeDetails($con, $employee_eid, $employee_details);
  printf("----Printing Employee Details of employee id=%s----\n", $employee_eid);
  printf("EID:%s, Name:%s, Department:%s, Title:%s, PhoneNumber:%s, Email:%s\n",
    $employee_details['EID'], $employee_details['Name'], $employee_details['DepartmentName'], 
    $employee_details['JobTitleName'], $employee_details['PhoneNumber'], $employee_details['EmailAddress']);
  echo "--------------------GetEmployeeDetailsTst:[end]--------------------\n";  
}


function GetEmployeeReportingManagersTst($con, $employee_eid)
{
  echo "--------------------GetEmployeeReportingManagersTst:[start]--------------------";
  printf("----Obtaining Reporting Managers using:GetEmployeeReportingManagers for eid=%s, name=%s----\n",
    $employee_eid, EmployeeId2EmployeeName($con, $employee_eid));
  $reporting_managers_ary_ref = [];
  GetEmployeeReportingManagers($con, $employee_eid, $reporting_managers_ary_ref);
  echo "----Printing Reporing Managers obtained using reference list----\n";
  foreach ($reporting_managers_ary_ref as $employee_details) {
    printf("Reporting Manager EID:%s, Name:%s, Department:%s, Title:%s, PhoneNumber:%s, Email:%s\n", $employee_details['EID'], $employee_details['Name'], $employee_details['DepartmentName'], $employee_details['JobTitleName'], $employee_details['PhoneNumber'], $employee_details['EmailAddress']);
  }
  echo "--------------------GetEmployeeReportingManagersTst:[end]---------------------\n";
}


function GetEmployeeDirectReportsTst($con, $employee_eid)
{
  echo "--------------------GetEmployeeDirectReportsTst:[start]--------------------";
  printf("----Obtaining Direct Reports using:GetEmployeeDirectReports for eid=%s, name=%s----\n",
    $employee_eid, EmployeeId2EmployeeName($con, $employee_eid));
  $direct_reports_ary_ref = [];
  GetEmployeeDirectReports($con, $employee_eid, $direct_reports_ary_ref);
  echo "----Printing  Direct Reports using reference list----\n";
  foreach ($direct_reports_ary_ref as $employee_details) {
    printf("Direct Report EID:%s, Name:%s, Department:%s, Title:%s, PhoneNumber:%s, Email:%s\n", $employee_details['EID'], $employee_details['Name'], $employee_details['DepartmentName'], $employee_details['JobTitleName'], $employee_details['PhoneNumber'], $employee_details['EmailAddress']);
  }
  echo "--------------------GetEmployeeDirectReportsTst:[end]--------------------\n";
}


function GetEmployeePeerListTst($con, $employee_eid)
{
  echo "--------------------GetEmployeePeerListTst:[start]--------------------";
  printf("----Obtaining Peer List using:GetEmployeePeerList for eid=%s, name=%s----\n",
  $employee_eid, EmployeeId2EmployeeName($con, $employee_eid));
  $peer_list_ary_ref = [];
  GetEmployeePeerList($con, $employee_eid, $peer_list_ary_ref);
  echo "----Printing  Peer List using reference list----\n";
  foreach ($peer_list_ary_ref as $employee_details) {
    printf("Peer EID:%s, Name:%s, Department:%s, Title:%s, PhoneNumber:%s, Email:%s\n", $employee_details['EID'], $employee_details['Name'], $employee_details['DepartmentName'], $employee_details['JobTitleName'], $employee_details['PhoneNumber'], $employee_details['EmailAddress']);
  }
  echo "--------------------GetEmployeePeerListTst:[end]--------------------\n";
}


function EmployeeSearchByNameTst($con)
{
  echo "--------------------EmployeeSearchByNameTst:[start]--------------------";
  echo "--------------Testing Search/Filter capability------------------\n";
  echo "----Obtaining Employee List using:EmployeeSearchRegex----\n";
  $employee_list_ary = [];
  $search_str = "Das";
  EmployeeSearchByName($con, $search_str, $employee_list_ary);
  echo "----Printing  Employee List returned for string:[" . $search_str . "]----\n";
  foreach ($employee_list_ary as $employee_details) {
    printf("Employee EID:%s, Name:%s, Department:%s, Title:%s, PhoneNumber:%s, Email:%s\n", $employee_details['EID'], $employee_details['Name'], $employee_details['DepartmentName'], $employee_details['JobTitleName'], $employee_details['PhoneNumber'], $employee_details['EmailAddress']);
  }
  echo "-------------------------------------------------------------------\n";
  $search_str = "Thivy";
  EmployeeSearchByName($con, $search_str, $employee_list_ary);
  echo "----Printing  Employee List returned for string:[" . $search_str . "]----\n";
  foreach ($employee_list_ary as $employee_details) {
    printf("Employee EID:%s, Name:%s, Department:%s, Title:%s, PhoneNumber:%s, Email:%s\n", $employee_details['EID'], $employee_details['Name'], $employee_details['DepartmentName'], $employee_details['JobTitleName'], $employee_details['PhoneNumber'], $employee_details['EmailAddress']);
  }
  echo "-------------------------------------------------------------------\n";
  $search_str = "Iqbal";
  EmployeeSearchByName($con, $search_str, $employee_list_ary);
  echo "----Printing  Employee List returned for string:[" . $search_str . "]----\n";
  foreach ($employee_list_ary as $employee_details) {
    printf("Employee EID:%s, Name:%s, Department:%s, Title:%s, PhoneNumber:%s, Email:%s\n", $employee_details['EID'], $employee_details['Name'], $employee_details['DepartmentName'], $employee_details['JobTitleName'], $employee_details['PhoneNumber'], $employee_details['EmailAddress']);
  }
  echo "-------------------------------------------------------------------\n";
  $search_str = "hulk";
  EmployeeSearchByName($con, $search_str, $employee_list_ary);
  echo "----Printing  Employee List returned for string:[" . $search_str . "]----\n";
  foreach ($employee_list_ary as $employee_details) {
    printf("Employee EID:%s, Name:%s, Department:%s, Title:%s, PhoneNumber:%s, Email:%s\n", $employee_details['EID'], $employee_details['Name'], $employee_details['DepartmentName'], $employee_details['JobTitleName'], $employee_details['PhoneNumber'], $employee_details['EmailAddress']);
  }
  echo "--------------------EmployeeSearchByNameTst:[end]--------------------\n";
}


function EmployeeSearchByDepartmentTst($con)
{
  echo "--------------------EmployeeSearchByDepartment:[start]--------------------";  
  $search_str = "3W Centre";
  $employee_list_ary = [];
  EmployeeSearchByDepartment($con, $search_str, $employee_list_ary);
  echo "----Printing  Employee List returned for Department String:[" . $search_str . "]----\n";
  foreach ($employee_list_ary as $employee_details) {
    printf("Employee EID:%s, Name:%s, Department:%s, Title:%s, PhoneNumber:%s, Email:%s\n", $employee_details['EID'], $employee_details['Name'], $employee_details['DepartmentName'], $employee_details['JobTitleName'], $employee_details['PhoneNumber'], $employee_details['EmailAddress']);
  }
  echo "-------------------------------------------------------------------\n";
  $search_str = '3W Centre (IT)';
  EmployeeSearchByDepartment($con, $search_str, $employee_list_ary);
  echo "----Printing  Employee List returned for Department String:[" . $search_str . "]----\n";
  foreach ($employee_list_ary as $employee_details) {
    printf("Employee EID:%s, Name:%s, Department:%s, Title:%s, PhoneNumber:%s, Email:%s\n", $employee_details['EID'], $employee_details['Name'], $employee_details['DepartmentName'], $employee_details['JobTitleName'], $employee_details['PhoneNumber'], $employee_details['EmailAddress']);
  }
  echo "-------------------------------------------------------------------\n";
  $search_str = '3W Centre \(IT\)';
  EmployeeSearchByDepartment($con, $search_str, $employee_list_ary);
  echo "----Printing  Employee List returned for Department String:[" . $search_str . "]----\n";
  foreach ($employee_list_ary as $employee_details) {
    printf("Employee EID:%s, Name:%s, Department:%s, Title:%s, PhoneNumber:%s, Email:%s\n", $employee_details['EID'], $employee_details['Name'], $employee_details['DepartmentName'], $employee_details['JobTitleName'], $employee_details['PhoneNumber'], $employee_details['EmailAddress']);
  }
  echo "-------------------------------------------------------------------\n";
  $search_str = "3W";
  EmployeeSearchByDepartment($con, $search_str, $employee_list_ary);
  echo "----Printing  Employee List returned for Department String:[" . $search_str . "]----\n";
  foreach ($employee_list_ary as $employee_details) {
    printf("Employee EID:%s, Name:%s, Department:%s, Title:%s, PhoneNumber:%s, Email:%s\n", $employee_details['EID'], $employee_details['Name'], $employee_details['DepartmentName'], $employee_details['JobTitleName'], $employee_details['PhoneNumber'], $employee_details['EmailAddress']);
  }
  echo "-------------------------------------------------------------------\n";
  $search_str = "3W Design";
  EmployeeSearchByDepartment($con, $search_str, $employee_list_ary);
  echo "----Printing  Employee List returned for Department String:[" . $search_str . "]----\n";
  foreach ($employee_list_ary as $employee_details) {
    printf("Employee EID:%s, Name:%s, Department:%s, Title:%s, PhoneNumber:%s, Email:%s\n", $employee_details['EID'], $employee_details['Name'], $employee_details['DepartmentName'], $employee_details['JobTitleName'], $employee_details['PhoneNumber'], $employee_details['EmailAddress']);
  }
  echo "--------------------EmployeeSearchByDepartment:[end]--------------------\n";
}



$hostname = "localhost";
$username = "root";
$password = "MySql@123";
$dbname = "360_survey_schema";

$con = CreateDBOConnection($hostname, $username, $password, $dbname);
$employee_eid = "69";
GetEmployeeDetailsTst($con, $employee_eid);
GetEmployeeReportingManagersTst($con, $employee_eid);
GetEmployeeDirectReportsTst($con, $employee_eid);
GetEmployeePeerListTst($con, $employee_eid);
EmployeeSearchByNameTst($con);
EmployeeSearchByDepartmentTst($con);
$branch_name = "Colombo";
BranchName2IdTst($con, $branch_name);
$department_name = "3W Centre (HR)";
DepartmentName2IdTst($con, $department_name);
$jobtitle_name = "HR & Compliance Officer";
JobTitleName2IdTst($con, $jobtitle_name);
$con = null;
echo "\n\n";
