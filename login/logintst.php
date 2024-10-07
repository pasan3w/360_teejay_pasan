<?php

require 'Login.php';


$hostname = "localhost";
$db_username = "root";
$db_password = "1qaz2wsx!@";
$db_name = "360_survey_schema";

$user1 = "User1";
$user1_pass = 'User1xPass1';
$empty_user = "";
$empty_pass = "";
$generic_user = "GenericUser";
$generic_pass = '<aBcD1234>';
$user2 = "User2";
$user2_pass = 'User2xPass2';





function AccountCreationTest($con, &$user_account_list){
  echo "----------------------AccountCreationTest:[Start]----------------------\n";
  global $user1, $user1, $user2, $generic_user, $empty_user, $user1_pass, $user2_pass, $generic_pass, $empty_pass;
  printf("Creating Login account for user:[%s], password:[%s]\n", $user1, $user1_pass);
  $result = CreateUserAccount($con, $user1, $user1_pass);
  if ($result){
    printf("Successfully created account for user=%s\n", $user1);
    array_push($user_account_list, $user1);
  }else{
    printf("Failed to create account for user=%s\n", $user1);
  }
  printf("Creating duplicate Login account for user:[%s], password:[%s]\n", $user1, $user1_pass);
  $result = CreateUserAccount($con, $user1, $user1_pass);
  if ($result){
    printf("Successfully created account for user=%s, Failed test\n", $user1);
    array_push($user_account_list, $user1);
  }else{
    printf("Failed to create account for user=%s\n", $user1);
  }
  printf("Creating second Login account for user:[%s], password:[%s]\n", $user2, $user2_pass);
  $result = CreateUserAccount($con, $user2, $user2_pass);
  if ($result){
    printf("Successfully created account for user=%s\n", $user2);
    array_push($user_account_list, $user2);
  }else{
    printf("Failed to create account for user=%s\n", $user2);
  }
  echo "------Creating 5 User Accounts in sequence---------\n";
  for ($i=0; $i < 5; $i++){
    $user = $generic_user . $i;
    $pass = $generic_pass . $i;
    $result = CreateUserAccount($con, $user, $pass);
    if ($result){
      printf("Successfully created account for user=%s\n", $user);
      array_push($user_account_list, $user);
    }else{
      printf("Failed to create account for user=%s\n", $user);
    }    
  }
  echo "------Creating User Account with empty user name: should fail---------\n";
  $result = CreateUserAccount($con, $empty_user, $generic_pass);
  if ($result){
    printf("Successfully created account for user=%s Shoud not happen\n", $empty_user);
  }else{
    printf("Failed to create account for empty user=%s, Success\n", $empty_user);
  }
  echo "------Creating User Account with empty password: should fail---------\n";
  $result = CreateUserAccount($con, $generic_user, $empty_pass);
  if ($result){
    printf("Successfully created account for user=%s, with empty password=%s, Should not happen\n", 
      $generic_user, $empty_pass);
  }else{
    printf("Failed to create account for user=%s, with empty password=%s, Success\n", $generic_user, $empty_pass);
  }  
  echo "----------------------AccountCreationTest:[End]----------------------\n";  
}

function LoginTest($con, &$user_account_list){
  echo "----------------------LoginTest:[Start]----------------------\n";
  global $user1, $user1, $user2, $generic_user, $empty_user, $user1_pass, $user2_pass, $generic_pass, $empty_pass;
  printf("-------Login in with valid user account=%s with valid password=%s-----------\n", $user1, $user1_pass);
  $result = Login($con, $user1, $user1_pass);
  if ($result){
    printf("User=%s,Successfully logged in with valid password[Success]\n", $user1);
  }else{
    printf("User=%s Failed to login with valid password\n", $user1);
  }
  
  printf("-------Login in with valid user account=%s with invalid password=%s-----------\n", $user1, $user2_pass);
  $result = Login($con, $user1, $user2_pass);
  if ($result){
    printf("User=%s,Successfully logged in with invalid password=%s[Should not happend]\n", $user1, $user2_pass);
  }else{
    printf("User=%s Failed to login with invalid password[Success]\n", $user1);
  }
  
  printf("-------Login in with invalid user account=%s with valid password=%s-----------\n", $user2, $user1_pass);
  $invalid_user = "Invalid" . $user1;
  $result = Login($con, $invalid_user, $user1_pass);
  if ($result){
    printf("User=%s,Successfully logged in with invalid password=%s[Should not happen]\n", $user1, $user2_pass);
  }else{
    printf("User=%s Failed to login with invalid password[Success]\n", $user1);
  }
  
  printf("-------Login in with valid user account=%s with empty password=%s-----------\n", $user1, $empty_pass);
  $result = Login($con, $user1, $empty_pass);
  if ($result){
    printf("User=%s,Successfully logged in with empty password=%s[Should not happen]\n", $user1, $empty_pass);
  }else{
    printf("User=%s Failed to login with empty password[Success]\n", $user1);
  }
  
  $user3 = "user3";
  $user3_pass = '123Xyz$%';
  printf("-------Login in with invalid user account=%s with password=%s-----------\n", $user3, $user3_pass);
  $result = Login($con, $user3, $user3_pass);
  if ($result){
    printf("User=%s,Successfully logged in with password=%s[Should not happen]\n", $user3, $user3_pass);
  }else{
    printf("User=%s Failed to login with password[Success]\n", $user3, $user3_pass);
    printf("Registering user=%s with password=%s\n", $user3, $user3_pass);
    $result = CreateUserAccount($con, $user3, $user3_pass);
    if ($result){
      printf("Successfully created account for user=%s with password=%s\n", $user3, $user3_pass);
      array_push($user_account_list, $user3);
      printf("Trying to Login with User=%s with password=%s Should succeed now\n", $user3, $user3_pass);
      $result = Login($con, $user3, $user3_pass);
      if ($result){
        printf("Success: User=%s, logged in with password=%s\n", $user3, $user3_pass);
      }else {
        printf("User=%s Failed to login with password after registering  the user[Should Not Happen]\n", $user3, $user3_pass);
      }
    }else{
      printf("Failed to create account for user=%s[Should Not Happen]\n", $user3);
    }    
  } 
  echo "----------------------LoginTest:[End]----------------------\n"; 
}


function Usage(){
  print("Usage: logintst [-h|--help] [-c|--clean]\n");
}

$short_options = "hc";
$long_options = ["help", "clean"];
$rest_index = 1;
$options = getopt($short_options, $long_options, $rest_index);
if (isset($options["h"]) || isset($options["help"])){
  Usage();
  exit();
}

$con = CreateDBOConnection($hostname, $db_username, $db_password, $db_name);
if (isset($options["c"]) || isset($options["clean"])){
  echo "Deleting all entries from Login table\m";
  EmptyLoginTable($con);
  exit();
}


$user_account_list = [];
AccountCreationTest($con, $user_account_list);
printf("Number of accounts created after AccountCreationTest:%d\n", count($user_account_list));
LoginTest($con, $user_account_list);
printf("Number of accounts created after LoginTest:%d\n", count($user_account_list));
DeleteUserAccounts($con, $user_account_list);

$con = null;