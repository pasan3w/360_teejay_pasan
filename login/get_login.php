<?php

require "Login.php";

if (!empty($_POST['login'])) {

	unset($_SESSION['login']);

	$username = $_POST['username'];
	$password = $_POST['password'];

	// $hostname = "50.87.232.129";
	// $db_username = "thrwcons_root";
	// $db_password = "1rkLhgDMoY2n";
	// $db_name = "thrwcons_360_survey_schema";

	$hostname = "localhost";
	$db_username = "root";
	$db_password = "MySql@123";
	$db_name   = "360_survey_schema";

	$conn  = CreateDBOConnection($hostname, $db_username, $db_password, $db_name);
	$login = Login($conn, $username, $password);

	if ($login == 1) {
		header("Location: https://3wexotic.com/view/createQuestionList.php");
	}
}