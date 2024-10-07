<?php

include '../control/surveyPage.php';

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Survey Page</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- JQUERY CDNS -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>  

    <link rel="stylesheet" type="text/css" href="../view/css/survey_page.css">

    <!-- product sans cdn for fonts -->

    <link href="https://fonts.cdnfonts.com/css/product-sans" rel="stylesheet">

	<script type="text/javascript">
		$(document).ready(function(){
			$("#yes").click(function(){
				$("#beforeOK").css("display", "none");
				$("#afterOK").css("display", "block");
			});
			$("#back").click(function(){
				$("#afterOK").css("display", "none");
				$("#beforeOK").css("display", "block");
			});

			/*$("#survey_button").click(function(){
				$("#survey_button").css("display", "none");
				$("#loadingImg").css("display", "block");
			});*/			

		});
	</script>
</head>
<body>	
		<?php
			if (isset($_GET['eid'])) {
				$surveyeeID = $_SESSION['SurveyPageEID'] 		= $_GET['eid'];
				$surveyID 	=$_SESSION['SurveyPageSURVEYID'] 	= $_GET['surveyId'];
				$_SESSION['SurveyPageSelectedID'] 				= $_GET['selectedIds'];
				$_SESSION['SurveyPageTYPE'] 					= $_GET['type']; 

				//===========================================================
				//===============   CHECK IF THE SURVEY IS FILLED ===========
				//===========================================================

				GetOpenAssignmentsOfEmployeeInSurvey($con, $surveyID, $surveyeeID, $list_uncomplete);

				$complete = 1;

				foreach ($list_uncomplete as $key => $value) {
					if (in_array($_SESSION['SurveyPageSelectedID'], $value)) {
						$complete = 0;
					}
				}
			    if ($complete == 1){
			    	echo "<div id='contentBox' style='margin-top: 150px;'><center>";
				    echo "The survey is already filled!";
				    echo "</center></div>";
			      	die("");
			    }
		?>
	<!-- ====================================================================================== -->
	<!-- ==============================  BEFORE INTRODUCTION  ================================= -->
	<!-- ====================================================================================== -->
	<div id="beforeOK">
		<center><h2>360 Degree Survey by 3W Consulting</h2></center>
		<hr style="color: white;">
		<p>3W Consulting is pleased to extend it’s services to [Company Name] by way of
		designing and deploying the 360 degree performance evaluation, team building and the
		leadership program</p>
		<h4 style="margin-left: 20px;">Objective</h4>
		<p>The core objective of this survey is to gather feedback from the participants and identify
		the strengths and areas to improve to enhance individual and organizational performance. 
		Your candid / honest feedback is much appreciated to ensure a successful transformation in
		[Company Name]. We at 3W Consulting give you our assurance that all the data provided
		will be <b>strictly confidential.</b></p>
		<hr style="color: white;">
		<h4 style="margin-left: 20px;">How can I start the evaluation?</h4>
		<p>You can find the questionnaire and the rating scale in the next page.</p>
		<hr style="color: white;">


		<?php if ($_SESSION['SurveyPageTYPE'] == "Self") { ?>
			<h5 style="margin-left: 20px;">Dear <b><?= EmployeeId2EmployeeName($con, $_SESSION['SurveyPageSelectedID']) ?></b>,</h5>
			<p>This is a Survey conducted on you by the 360 Power solutions introduced by 3W Exotic</p>
			<p>Click on below button to Partisipate in this self evaluation Survey. </p>
			<center>
				<button class="btn btn-primary" id="yes"><span>Yes, Let's go</span></button>
			</center>
		<?php } else { ?>
			<h5 style="margin-left: 20px;">Dear <b><?= EmployeeId2EmployeeName($con, $_SESSION['SurveyPageSelectedID']) ?></b>,</h5>
			<p>This is a Survey conducted on <?= EmployeeId2EmployeeName($con, $surveyeeID) ?> by the 360 Power solutions introduced by 3W Exotic</p>
			<p>Click on below button to Partisipate in this Survey on <?= EmployeeId2EmployeeName($con, $surveyeeID). " as his/her " . $_SESSION['SurveyPageTYPE'] ?> surveyor. </p>
			<center>
				<button class="btn btn-primary" id="yes">Yes, I'm in</button>
			</center>
		<?php } ?>
	</div>
	<!-- ====================================================================================== -->
	<!-- ===============================  AFTER INTRODUCTION  ================================= -->
	<!-- ====================================================================================== -->
	<div id="afterOK">
		<div id="afterOK_header">
			<center style="padding-top: 20px; padding-bottom: 10px;">
					<h1><b>Survey For <?= EmployeeId2EmployeeName($con, $surveyeeID) ?></b></h1>
			</center>

			<table class="ratingtable" style="float: left;">
				<tr class="ratingtable">
					<th class="ratingtable" colspan="4">Follow the below rating scale</th>
				</tr>
				<tr class="ratingtable">
					<td class="ratingtable">1</td>
					<td class="left ratingtable">Not demonstrated it at all</td>
					<td class="ratingtable">2</td>
					<td class="left ratingtable">3 out of 10 times the individual has demonstrated it</td>
				</tr>
				<tr class="ratingtable">
					<td class="ratingtable">3</td>
					<td class="left ratingtable">4 - 7 times out of 10 the individual has demonstrated it</td>
					<td class="ratingtable">4</td>
					<td class="left ratingtable">8 - 9 times out of 10 the individual has demonstrated it</td>
				</tr>
				<tr class="ratingtable">
					<td class="ratingtable">5</td>
					<td colspan="3" class="left ratingtable">Individual has demonstrated in every interaction</td>
				</tr>
			</table>

			<button class="btn btn-primary" id="back" style="float: left; margin-left: 40px; vertical-align: bottom;">Back</button>
		</div>

			<!-- ====================================================================================== -->

		<div style="padding-left: 50px; padding-right: 50px;" id="question_list">
			
			<!-- ====================================================================================== -->
			<hr style="color: #004d4d; margin-top: 270px;"> 
			<form method="POST" action="surveyPage_validation.php" class="question_body" style="max-width: 100%;">
				<p>
			<?php 

					$question_list_id = GetSurveyQuestionaireId($con, $surveyID);

					$questionaire_details 	= [];
					GetQuestionaireDetails($con, $question_list_id, $questionaire_details);

					foreach ($questionaire_details as $key => $value) {
						echo "<h3><u>" . $value['CategoryName'] . "</u></h3>";
						echo "<br>";
						foreach ($value['QuestionList'] as $questions) {
							if ($questions['Type'] == 0) {
								echo "<h3>" . $questions['QuestionNumber'] . ") " . $questions['Question'] . "</h3>";
						        echo "<span> 1. <input type='radio' name=" . $value['CategoryID'] . "-" . $questions['QuestionNumber'] . "-" . $questions['Type'] . " value='1' required> </span>";
						        echo "<span> 2. <input type='radio' name=" . $value['CategoryID'] . "-" . $questions['QuestionNumber'] . "-" . $questions['Type'] . " value='2' required> </span>";
						        echo "<span> 3. <input type='radio' name=" . $value['CategoryID'] . "-" . $questions['QuestionNumber'] . "-" . $questions['Type'] . " value='3' required> </span>";
						        echo "<span> 4. <input type='radio' name=" . $value['CategoryID'] . "-" . $questions['QuestionNumber'] . "-" . $questions['Type'] . " value='4' required> </span>";
						        echo "<span> 5. <input type='radio' name=" . $value['CategoryID'] . "-" . $questions['QuestionNumber'] . "-" . $questions['Type'] . " value='5' required> </span>";
						        echo "<br><br><br>";
							} elseif ($questions['Type'] == 1) {
								echo "<h3>" . $questions['QuestionNumber'] . ") " . $questions['Question'] . "</h3>";
								echo "<span><textarea name=" . $value['CategoryID'] . "-" . $questions['QuestionNumber'] . "-" . $questions['Type'] . " rows='4' cols='50' required></textarea></span>";
							}
						}
					} ?>

				<?php
				} else { echo 
					"<script>
						alert('Please check the link you tried to get to the survey page !');
					</script>";
				} ?>
			</p>
			<center style="margin-bottom: 100px;">
				<input type="submit" name="submit" value="Submit" class="btn btn-primary" id="survey_button">
				<img src="../img/loading.gif" width="5%" id="loadingImg" style="display: none;" />
			</center>
			</form>
		</div>
	</div>

	<div id="footer">
		<center>
			<span style="color: white;">Copyright &#169; 2024 All rights reserved. 360&deg;Tool by 3W Consulting (Pvt) Ltd</span>			
		</center>
	</div>

</body>
</html>
