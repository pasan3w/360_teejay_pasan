<?php

require_once '../control/SurveyFeedback.php';
require_once '../QuestionsList/QuestionList.php';

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Get Feedback</title>

	<!-- JAVASCRIPT CDN -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

	<!-- GOOGLE FONTS CDNS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Agbalumo">

    <!-- JQUERY CDNS -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>  

    <!-- BOOTSTRAP CDNS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- DATATABLE CDN -->

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />

    <!-- GET FEEDBACK CSS -->

    <link rel="stylesheet" type="text/css" href="css/get_feedbcak.css">
    <link rel="stylesheet" type="text/css" href="css/backend_pages.css">
    <link rel="stylesheet" type="text/css" href="css/navbar.css">
  
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

    <!-- GET FEEDBACK RADAR GRAPH JS -->

</head>
<body>
	<?php 
        include 'includes/navbar.php';
    ?>
	<div id="contentBox">
		<div style="padding: 10px; color: red;">
			<?php

			if (!empty($_POST['submit'])) {
				$survey_id 		= $_POST['survey_id'];
				$eid 			= $_POST['eid'];
				$feedback_array = [];

				if (empty($survey_id)) {
					echo "Enter Survey ID<br>";
				}
				if (empty($eid)) {
					echo "Enter Employee ID<br>";
				}
				$QuestionareID  = SurveyID2questionareID($con, $survey_id);
				//echo "<center>";
				//GetSurveyFeedbackForEmployeeByDepartment($con, $survey_id, $eid, $internal_feedback_array);
				//GetSurveyFeedbackForExternalByCompany($con, $survey_id, $eid, $external_feedback_array);

				GetSurveyFeedbackForEmployeeBySurveyorType($con, $survey_id, $eid, $feedback_array_internal);
				//GetSurveyFeedbackForEmployeeByExternalSurveyorType($con, $survey_id, $eid, $feedback_array_external);
				GetSurveyTextFeedbackForEmployeeBySurveyorType($con, $survey_id, $eid, $feedback_array_text);

				/*
				echo "<pre>";
				print_r($feedback_array_internal);
				echo "<br>==============================================================================<br>";
				print_r($feedback_array_external);
				echo "<br>==============================================================================<br>";
				print_r($feedback_array_text);
				echo "<hr>";
				*/
			}
			?>			
		</div>
		<form method="POST" style="padding: 20px;">
			<label>SurveyID &nbsp;&nbsp;: &nbsp;&nbsp;</label>
			<input type="text" name="survey_id"><br><br>
			<label>Empl. ID &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;</label>
			<input type="text" name="eid"><br><br>
			<input type="submit" name="submit" class="btn btn-primary">
		</form>
		<hr>
		<div style="padding: 20px; margin-top: -20px; color: white;">
			<?php
				GetQuestionaireDetails($con, $QuestionareID, $questionaire_details);

				foreach ($questionaire_details as $key => $value) {
				  echo "<h2>" . $value['CategoryName'] . "</h2>";
				  foreach ($value['QuestionList'] as $questions) {
				    echo "<p>" . $questions['QuestionNumber'] . ") " . $questions['Question'] . "</p>";
				  }
				}
			?>
		</div>

		<!-- COMPLETE FEEDBACK DATA -->

		<h3 align="center" class="subtopic">Numerical Feedback by Surveyor Type</h3>

		<table style="border: 1px solid black;" class="table full" id="numericalReport">
			<thead>
				<tr>					
					<th rowspan="2">Surveyor Type</th>
					<th rowspan="2">Surveyor EID</th>
					<th rowspan="2">Surveyor Name</th>
					<?php
						foreach ($questionaire_details as $key => $value) {
							//output category tiitle for table
							if ($value['CategoryName'] != 'Text Input') {
								echo "<th colspan='" . count($value['QuestionList']) . "'>" . $value['CategoryName'] . "</th>";
							} 
						}
					?>
				</tr>
				<tr>
					<?php
						foreach ($questionaire_details as $key => $value) {
						  	//echo "<th colspan='" . count($value['QuestionList']) . "'>" . $value['CategoryName'] . "</th>";
						  	if ($value['CategoryName'] != 'Text Input') {
								foreach ($value['QuestionList'] as $questions) {
									echo "<th>" . $questions['QuestionNumber'] . "</th>";
								}
							}
						}
					?>					
				</tr>
			</thead>
			<tbody>
				<?php

					if (!empty($feedback_array_internal)) {
					    foreach ($feedback_array_internal as $key => $surveyor) {
					        $count_for_type = count($surveyor);
					        $count_for_type--;
					        echo "<tr><td rowspan='" . $count_for_type . "'>" . $key . "</td>";

					        foreach ($surveyor as $emp_id => $value) {
					        	if ($emp_id != 'AverageRatings') {
						            echo "<td>$emp_id</td><td>" . EmployeeId2EmployeeName($con, $emp_id) . "</td>";

						            foreach ($value['Feedback'] as $category => $scores) {
						            	foreach ($scores as $scores) {
						                	echo "<td>" . $scores . "</td>";
						            	}
						            }

						            echo "</tr>";
					        	}
					        }

					    }
					}

					if (!empty($feedback_array_external)) {
					    foreach ($feedback_array_external as $key => $surveyor) {
					        $count_for_type = count($surveyor);
					        $count_for_type--;
					        echo "<tr><td rowspan='" . $count_for_type . "'>" . $key . "</td>";

					        foreach ($surveyor as $emp_id => $value) {
					        	if ($emp_id != 'AverageRatings') {
						            echo "<td>$emp_id</td><td>" . EmployeeId2EmployeeName($con, $emp_id) . "</td>";

						            foreach ($value['Feedback'] as $category => $scores) {
						            	foreach ($scores as $scores) {
						                	echo "<td>" . $scores . "</td>";
						            	}
						            }

						            echo "</tr>";
					        	}
					        }

					    }
					}
				?>
			</tbody>
		</table>

		<!-- CATEGORY AVERAGE ONLY -->

		<h3 align="center" class="subtopic">Text Feedback by Surveyor Type</h3>

		<table style="border: 1px solid black;" class="table averageBySurveyor" id="textualReport">
			<thead>
				<tr>					
					<th rowspan="2">Surveyor Type</th>
					<th rowspan="2">Surveyor EID</th>
					<th rowspan="2">Surveyor Name</th>
					<?php
						foreach ($questionaire_details as $key => $value) {
							//output category tiitle for table
							if ($value['CategoryName'] == 'Text Input') {
								echo "<th colspan='" . count($value['QuestionList']) . "'>" . $value['CategoryName'] . "</th>";
							} 
						}
					?>
				</tr>
				<tr>
					<?php
						foreach ($questionaire_details as $key => $value) {
						  	//echo "<th colspan='" . count($value['QuestionList']) . "'>" . $value['CategoryName'] . "</th>";
						  	if ($value['CategoryName'] == 'Text Input') {
								foreach ($value['QuestionList'] as $questions) {
									echo "<th>" . $questions['QuestionNumber'] . "</th>";
								}
							}
						}
					?>					
				</tr>
			</thead>
			<tbody>
				<?php


					if (!empty($feedback_array_text)) {
					    foreach ($feedback_array_text as $key => $surveyor) {
					        $count_for_type = count($surveyor);
					        echo "<tr><td rowspan='" . $count_for_type . "'>" . $key . "</td>";

					        foreach ($surveyor as $emp_id => $value) {
					            echo "<td>$emp_id</td><td>" . EmployeeId2EmployeeName($con, $emp_id) . "</td>";

					            foreach ($value['Feedback'] as $category => $scores) {
					            	foreach ($scores as $scores) {
					                	echo "<td>" . $scores . "</td>";
					            	}
					            }

					            echo "</tr>";
					        }

					    }
					}
					//when textfeedback for external surveyors is implemented
					//until then this is a placeholder
					if (!empty($feedback_array_text_external)) {
					    foreach ($feedback_array_text_external as $key => $surveyor) {
					        $count_for_type = count($surveyor);
					        $count_for_type--;
					        echo "<tr><td rowspan='" . $count_for_type . "'>" . $key . "</td>";

					        foreach ($surveyor as $emp_id => $value) {
					        	if ($emp_id != 'AverageRatings') {
						            echo "<td>$emp_id</td><td>" . EmployeeId2EmployeeName($con, $emp_id) . "</td>";

						            foreach ($value['Feedback'] as $category => $scores) {
						            	foreach ($scores as $scores) {
						                	echo "<td>" . $scores . "</td>";
						            	}
						            }

						            echo "</tr>";
					        	}
					        }

					    }
					}

					echo "<h1>" . $textQuestionsCount . "</h1>";
				?>
			</tbody>
		</table>
		<hr>
		<center>
			<button id="exportButton" class="btn btn-danger" onclick="exportToExcel('numericalReport')">Get Numerical Report</button>			
			<button id="exportButton" class="btn btn-danger" onclick="exportToExcel('textualReport')">Get Textual Report</button>	
			<button id="exportButton" class="btn btn-danger" onclick="showGraph()">Radar Graph</button>			
		</center>
		<canvas id="radarChart" width="400" height="400"></canvas>	
	</div>

		<script>
			// export table to
		    function exportToExcel(tableID) {
		        const table = document.getElementById(tableID);
		        const html 	= table.outerHTML;
		        const blob 	= new Blob([html], { type: 'application/vnd.ms-excel' });
		        const url 	= URL.createObjectURL(blob);
		        const a 	= document.createElement('a');
		        a.href 		= url;
		        a.download 	= tableID + '.xlsx';
		        document.body.appendChild(a);
		        a.click();
		        document.body.removeChild(a);
		        URL.revokeObjectURL(url);
		    }

		    function showGraph() {
		    	alert("Radar graphs are still under construction ;/");
		    }
		</script>

</body>
</html>