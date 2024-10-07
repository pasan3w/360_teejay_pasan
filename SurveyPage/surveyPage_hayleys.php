<?php

include '../control/surveyPage.php';

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Multipoint Survey - Hayleys Group</title>

    <link rel="stylesheet" type="text/css" href="../view/css/bootstrap1.css">

    <script src="../view/js/boottrap.js" ></script>

    <!-- JQUERY CDNS -->
    <script src="../view/js/jq.js" ></script>  

    <link rel="stylesheet" type="text/css" href="../view/css/survey_page_hayleys.css">

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
		<table style="width: 100%; max-height: 90vh;">
			<tr style="height: 15vh; margin-top: 20px;">
				<td style="width: 50%;" rowspan="2">
					<img src="../img/surveyPage/360_big_logo.png" style="max-width: 80%;margin-left: 40px;">
				</td>
				<td style="width: 50%;">
					<img src="../img/surveyPage/3wconsulting_logo.png" style="max-width: 50%; float: right; margin-right: 50px;">
				</td>
			</tr>
			<tr></tr>
			<tr style="height: 75vh;">
				<td style="width: 60%;vertical-align: top;">
					<p style="font-size: 16px; margin-top: 40px;" class="beforeTextArea">
					Dear <b><?= EmployeeId2EmployeeName($con, $_SESSION['SurveyPageSelectedID']) ?></b>,</p>

					<p style="font-size: 16px;" class="beforeTextArea">Welcome to the Hayleys Advantis Limited Multipoint Survey powered by 3W Consulting (Pvt) Ltd. 
					</p>

					<p style="font-size: 16px;" class="beforeTextArea">Your feedback is invaluable in helping us understand and improve various aspects of our organization. This survey provides an opportunity for comprehensive feedback from multiple perspectives, including self-assessment and feedback from peers, managers, and direct reports.</p>

					<p style="font-size: 16px;" class="beforeTextArea">Your responses will remain confidential, and aggregated data will be used solely for development purposes.</p>

					<p style="font-size: 16px;" class="beforeTextArea">Click the button to start the Survey.</p>
					<center>
						<div class="wrap">
						  <button class="button" id="yes">button</button>
						</div>
					</center>
				</td>
				<td style="width: 40%;" rowspan="2">
					<img src="../img/surveyPage/beforeok.png" style="max-width: 80%;">
				</td>
			</tr>
		</table>
	</div>
	<!-- ====================================================================================== -->
	<!-- ===============================  AFTER INTRODUCTION  ================================= -->
	<!-- ====================================================================================== -->
	<div id="afterOK">
		<div id="afterOK_header">
			<center style="padding-top: 10px; padding-bottom: 10px;">
				<h1><b>Survey For <?= EmployeeId2EmployeeName($con, $surveyeeID) ?></b></h1>
				<table id="ratingtableID" class="ratingtable">
					<tr class="ratingtable">
						<th class="ratingtable" colspan="6" style="text-align: center;">Follow The Below Rating Scale</th>
					</tr>
					<tr class="ratingtable">
						<td class="ratingtable"><b>1.</b></td>
						<td class="left ratingtable">Not demonstrated in behavior in every transaction</td>
						<td class="ratingtable"><b>2.</b></td>
						<td class="left ratingtable">Demonstrates in behavior 3 out of 10 times in every transaction</td>
						<td class="ratingtable"><b>3.</b></td>
						<td class="left ratingtable">Demonstrates in behavior 4 to 7 out of 10 times in every transaction</td>
					</tr>
					<tr class="ratingtable">
						<td class="ratingtable"><b>4.</b></td>
						<td class="left ratingtable">Demonstrates in behavior 8 to 9 out of 10 times in every transaction</td>
						<td class="ratingtable"><b>5.</b></td>
						<td class="left ratingtable">Demonstrates in behavior in every transaction (10/10)</td>
					</tr>
				</table>
			</center>
		</div>

		<div style="padding-left: 50px; padding-right: 50px;" id="question_list">
			
			<!-- ====================================================================================== -->
			<form method="POST" action="surveyPage_validation.php" style="max-width: 100%;margin-top: 220px;">
				<p>
			<?php 

					$question_list_id = GetSurveyQuestionaireId($con, $surveyID);

					$questionaire_details 	= [];
					GetQuestionaireDetails($con, $question_list_id, $questionaire_details);

					foreach ($questionaire_details as $key => $value) {
						if ($value['CategoryName'] != "Text Input") {
							echo "<h3><u><b>" . $value['CategoryName'] . "</b></u></h3>";
						} else {
							echo "<h3><u><b>Performance Assessment and Development Recommendations</b></u></h3>";
							echo "<h4>***Please limit your response to 500 characters.</h4>";
						}
						echo "<br>";
						foreach ($value['QuestionList'] as $questions) {
							if ($questions['Type'] == 0 || empty($questions['Type'])) {
								echo "<h4>" . $questions['QuestionNumber'] . ") " . $questions['Question'] . "</h4>";
						        echo "<span> 1. <input type='radio' name=" . $value['CategoryID'] . "-" . $questions['QuestionNumber'] . "-" . $questions['Type'] . " value='1' required> </span>";
						        echo "<span> 2. <input type='radio' name=" . $value['CategoryID'] . "-" . $questions['QuestionNumber'] . "-" . $questions['Type'] . " value='2' required> </span>";
						        echo "<span> 3. <input type='radio' name=" . $value['CategoryID'] . "-" . $questions['QuestionNumber'] . "-" . $questions['Type'] . " value='3' required> </span>";
						        echo "<span> 4. <input type='radio' name=" . $value['CategoryID'] . "-" . $questions['QuestionNumber'] . "-" . $questions['Type'] . " value='4' required> </span>";
						        echo "<span> 5. <input type='radio' name=" . $value['CategoryID'] . "-" . $questions['QuestionNumber'] . "-" . $questions['Type'] . " value='5' required> </span>";
						        echo "<br><br>";
							} elseif ($questions['Type'] == 1) {
								echo "<h4>" . $questions['QuestionNumber'] . ") " . $questions['Question'] . "</h4>";
								echo "<span><textarea maxlength='500' name=" . $value['CategoryID'] . "-" . $questions['QuestionNumber'] . "-" . $questions['Type'] . " rows='4' cols='50' required></textarea></span>";
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
				<div class="wrap">
					<button class="button" id="back" style="margin-right: 10px;">Back</button>
					<input type="submit" name="submit" value="Submit" class="button" id="survey_button">
				</div>
				<img src="../img/loading.gif" width="5%" id="loadingImg" style="display: none;" />
			</center>
			</form>
		</div>
	</div>

	<div id="footer">
		<center>
			<span style="color: white;"><b>Copyright &#169; 2024 All Rights Reserved. Multipoint Survey Tool powered by 3W Consulting (Pvt) Ltd.</b></span>			
		</center>
	</div>

</body>
</html>
