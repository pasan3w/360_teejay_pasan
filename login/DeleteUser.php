<?php

require 'Login.php';

$hostname = "50.87.232.129";
$db_username = "thrwcons_root";
$db_password = "1rkLhgDMoY2n";
$db_name = "thrwcons_360_survey_schema";


function Usage(){
  print("Usage: DeleteUser [-h|--help] <login_name>\n");
}

$login_name = "";
$password = "";
$short_options = "h";
$long_options = ["help"];
$rest_index = 1;
$options = getopt($short_options, $long_options, $rest_index);
if (isset($options["h"]) || isset($options["help"])){
  Usage();
  exit();
}

$delete_user_accounts = [];
if (isset($argv[$rest_index])){
  $login_name = $argv[$rest_index];
}

if (!$login_name){
  echo "DeleteUser: login account name is mandatory for the script\n";
  exit(1);
}
//For the first release only one user account can be deleted
array_push($delete_user_accounts, $login_name);
//Disabling password check for now
// echo "Please enter Password: ";
// system('stty -echo');
// $password = trim(fgets(STDIN));
// system('stty echo');
// echo "\n";

$con = CreateDBOConnection($hostname, $db_username, $db_password, $db_name);
printf("Deleting Login account for user: %s\n", $login_name);
$result = DeleteUserAccounts($con, $delete_user_accounts);
if ($result){
  printf("Successfully deleted account for user=%s\n",$login_name);
}else{
  printf("Failed to delete account for user=%s\n",$login_name);
}
