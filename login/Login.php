<?php


require '../Common/DbOperations.php';


function CreateUserAccount($con, $user_name, $password){
  if (empty($user_name) || empty($password)){
    echo "UserName and Password cannot be empty\n";
    return FALSE;
  }
  $user_name = $con->Quote($user_name);
  $password = $con->Quote($password);
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  try{
    $sql = "INSERT INTO Login (UserName, Password) VALUES (?,?)";
    $con->prepare($sql)->execute([$user_name, $hashed_password]);
    echo "Successfully created account for user:" . $user_name . "\n";
    return TRUE;
  } catch(PDOException $e){
    echo "Database exception while creating User Account :" . $user_name . ", Exception: " . $e->getMessage() . "\n";
  }
  echo "User account creation failed for user: " . $user_name . "\n";
  return FALSE;
}
  

function Login($con, $user_name, $password){
  if (empty($user_name) || empty($password)){
    echo "UserName and Password cannot be empty\n";
    return FALSE;
  }
  try{
    $user_name = $con->Quote($user_name);
    $password = $con->Quote($password);
    $sql = "SELECT Password FROM Login WHERE UserName=?";
    $stmt = $con->prepare($sql);
    $result = $stmt->execute([$user_name]);
    if ($result) {
      $entry = $stmt->fetch();
      if ($entry){
        if (password_verify($password, $entry['Password'])){
          //echo "User name " . $user_name . " successfully logged in\n";
          $_SESSION['login'] = $user_name;
          return TRUE;
        }
      }
    }
  } catch(PDOException $e){
    echo "Error: Database exception while Logging in to User Account :" . $user_name . ", Exception: " . $e->getMessage() . "\n<a href='../login.php'>Back to login page</a>";
  }
  echo "Error: Login failed for user: " . $user_name . "\n <a href='../login.php'>Back to login page</a>
  ";
  return FALSE;
}
      

function DeleteUserAccounts($con, &$user_name_array){
  echo "----------------------DeleteUserAcconts:[Start]----------------------\n";
  foreach ($user_name_array as $user_name){
    echo "Deleting User Account:" . $user_name . " From Db\n";
    try{
      $sql = "DELETE FROM Login WHERE UserName=?";
      $user_name = $con->Quote($user_name);
      $result = $con->prepare($sql)->execute([$user_name]);
      if ($result){
        echo "User Account: " . $user_name . " deleted from DB\n";
      }else{
        echo "Failed to remove User Account: " . $user_name . " from DB\n";
        return FALSE;
      }
    }catch(PDOException $e){
      echo "Failed to remove User Account :" . $user_name . " from DB, PDO Exception: " . $e->getMessage() . "\n";
      return FALSE;
    }
  }
  echo "----------------------DeleteUserAcconts:[End]----------------------\n";
  return TRUE;
}


function EmptyLoginTable($con){
  echo "----------------------EmptyLoginTable:[Start]----------------------\n";
  echo "Deleting all entries from Login table\n";
  $sql = "DELETE FROM Login";
  try{
    $result = $con->prepare($sql)->execute();
    if ($result){
      echo "All records deleted from Login table\n";
    }else{
      echo "Failed to delete all records from Login table\n";
    }
  }catch(PDOException $e){
    echo "Failed to delete all records from Login table " . ":PDO Exception: " . $e->getMessage() . "\n";
  }
  echo "----------------------EmptyLoginTable:[End]----------------------\n";
}

  
  