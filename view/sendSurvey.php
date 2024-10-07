<?php

session_start();

if (empty($_SESSION['login'])) {
    header('location: ../login.php');
}

$surveyors 	= $tempSurveyors = array();
$survey_id 	= $_SESSION['survey_id'];

foreach ($_SESSION['internal'] as $internal) {
	if ($internal['check'] == 'yes') {
		array_push($surveyors, $internal);
	}
}

foreach ($_SESSION['external'] as $external) {
	if ($external['check'] == 'yes') {
		$tempSurveyors['EID'] =  $external['EID'];
		$tempSurveyors['Name'] = $external['Name'];
		$tempSurveyors['DepartmentName'] = $external['Department'];
		$tempSurveyors['JobTitleName'] = $external['Designation'];
		$tempSurveyors['EmailAddress'] = $external['EID'];
		$tempSurveyors['type'] = $external['type'];
		$tempSurveyors['check'] = $external['check'];

		array_push($surveyors, $tempSurveyors);
	}
}

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Send Survey</title>

	<?php 
        require_once 'includes/head.php';
    ?> 

	<script type="text/javascript">
		$(document).ready(function(){
			$('#allSurveyors').DataTable();
			var surveyors = JSON.stringify(<?= json_encode($surveyors); ?>);
			var surveyee  = JSON.stringify(<?= json_encode($_SESSION['surveyee']); ?>);
			var SurveyID  = JSON.stringify(<?= json_encode($survey_id); ?>);
			$('body').on( 'click', '#send_email', function () {
				$("#send_email").css("display", "none");
				$("#loadingImg").css("display", "block");
				$.ajax({
				    type: "POST",
				    url: "../control/sendSurvey.php",
				    data: {
				    	surveyors : surveyors,
				    	surveyee  : surveyee,
				    	SurveyID  : SurveyID
				    }, 
				    cache: false,
				    success: function(data){
				        console.log(data);
				        $.ajax({
						    type: "POST",
						    url: "../important/console_log/console_log.php",
						    data: {
						    	logMessage : data
						    }, 
						    cache: false,
						    success: function(data){
						        alert(data);
						    }
						});
				        $("#loadingImg").css("display", "none");
				        $("#reassign").css("display", "block");
				    }
				});
			});

			$('body').on( 'click', '#reassign', function () {
				window.open('selectSurveyee.php', '_SELF');
			});
		});
	</script>
	<style type="text/css">
		td {
			width: 10%;
		}
	</style>
</head>
<body style="padding-left: 20px; padding-right: 20px;">
	<?php 
        include 'includes/navbar.php';
    ?>
	<div id="contentBox">
		<center>
			<table class="display" id="allSurveyors">
				<thead>
					<tr>
						<th>Employee ID /<br> Email(External)</th>
						<th>Surveyor Name</th>
						<th>Department</th>
						<th>Designation</th>
						<th>Email</th>
						<th>Surveyor Type</th>
					</tr>
				</thead>
				<tbody>
					<?php
						echo "<h1>Surveyee Details</h1>";
						echo "<tr>";
						foreach ($_SESSION['surveyee'] as $key => $value) {
							echo "<td>" . $value . "</td>";
						}
						echo "</tr>";

						foreach ($surveyors as $surveyors_list) {
							echo "<tr>";
							echo "<td>" . $surveyors_list['EID'] . "</td>";
							echo "<td>" . $surveyors_list['Name'] . "</td>";
							echo "<td>" . $surveyors_list['DepartmentName'] . "</td>";
							echo "<td>" . $surveyors_list['JobTitleName'] . "</td>";
							echo "<td>" . $surveyors_list['EmailAddress'] . "</td>";
							echo "<td>" . $surveyors_list['type'] . "</td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
			<input type="submit" name="sendEmail" id="send_email" class="btn btn-primary my-3" value="Send" />
			<img src="../img/loading.gif" width="5%" id="loadingImg" style="display: none;" />
			<input type="submit" name="reAssign" id="reassign" style="display: none;" class="btn btn-primary my-3" value="Create Assignments" />	
		</center>
	</div>
</body>
</html>