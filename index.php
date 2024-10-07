<?php

session_start();

if (!empty($_SESSION['login'])) {
	header('Location: view/createQuestionList.php');
} else {
	header('Location: login.php');
}

?>