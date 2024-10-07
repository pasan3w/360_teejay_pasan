<?php

include '../Common/DbOperations.php';

$_SESSION['surveyType'] = 'climate';

$surveyors 	= $tempSurveyors = array();
$survey_id 	= $_SESSION['survey_id'];

$sql    =  "SELECT * FROM Employee 
			JOIN Branch ON Employee.BranchID = Branch.BranchID 
			JOIN Department ON Employee.DepartmentID = Department.DepartmentID 
			JOIN JobTitle ON Employee.JobTitleID = JobTitle.JobTitleID";
$result = $con->query($sql);

while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	array_push($surveyors, $row);	 
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
			var SurveyID  = JSON.stringify(<?= json_encode($survey_id); ?>);
			$('body').on( 'click', '#send_email', function () {
				$("#send_email").css("display", "none");
				$("#loadingImg").css("display", "block");
				$.ajax({
				    type: "POST",
				    url: "../control/sendSurvey.php",
				    data: {
				    	surveyors : surveyors,
				    	SurveyID  : SurveyID
				    }, 
				    cache: false,
				    success: function(data){
				        console.log(data);
				        $("#loadingImg").css("display", "none");
				        $("#homePage").css("display", "block");
				    }
				});
			});

			$('body').on( 'click', '#homePage', function () {
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
			<table class="display" id="allSurveyors" style="color: white;">
				<thead>
					<tr>
						<th>Employee ID /<br> Email(External)</th>
						<th>Surveyor Name</th>
						<th>Department</th>
						<th>Designation</th>
						<th>Email</th>
					</tr>
				</thead>
				<tbody>
					<?php
						echo "<h1>Participants Details</h1>";

						foreach ($surveyors as $surveyors_list) {
							echo "<tr>";
							echo "<td>" . $surveyors_list['EID'] . "</td>";
							echo "<td>" . $surveyors_list['Name'] . "</td>";
							echo "<td>" . $surveyors_list['DepartmentName'] . "</td>";
							echo "<td>" . $surveyors_list['JobTitleName'] . "</td>";
							echo "<td>" . $surveyors_list['Email'] . "</td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
			<input type="submit" name="sendEmail" id="send_email" class="btn btn-primary my-3" value="Send" />
			<img src="../img/loading.gif" width="5%" id="loadingImg" style="display: none;" />
			<input type="submit" name="reAssign" id="homePage" style="display: none;" class="btn btn-primary my-3" value="Create New Assignment" />	
		</center>
	</div>
</body>
</html>