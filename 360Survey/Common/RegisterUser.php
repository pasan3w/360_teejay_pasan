<?php

require 'Login.php';

$hostname = "localhost";
$db_username = "root";
$db_password = "1qaz2wsx!@";
$db_name = "360_survey_schema";


function Usage(){
  print("Usage: RegisterUser [-h|--help] <login_name>\n");
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

if (isset($argv[$rest_index])){
  $login_name = $argv[$rest_index];
}

if (!$login_name){
  echo "RegisterUser: login account name is mandatory for the script\n";
  exit(1);
}

echo "Please enter Password: ";
system('stty -echo');
$password = trim(fgets(STDIN));
system('stty echo');
// add a new line since the users CR didn't echo
echo "\n";
echo "Please re-enter Password: ";
system('stty -echo');
$password_copy =  trim(fgets(STDIN));
system('stty echo');
echo "\n";
if (0 != strcmp($password,  $password_copy)){
  echo "The passwords entered differ, please enter identical values.\n";
  exit(1);
}

$con = CreateDBOConnection($hostname, $db_username, $db_password, $db_name);
printf("Creating Login account for user: %s\n", $login_name);
$result = CreateUserAccount($con, $login_name, $password);
if ($result){
  printf("Successfully created account for user=%s\n",$login_name);
}else{
  printf("Failed to create account for user=%s\n",$login_name);
}


