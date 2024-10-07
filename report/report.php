<?php

require '../control/SurveyFeedback.php';
require '../QuestionsList/QuestionList.php';

// if (!empty($_POST['submit'])) {
if (true) {
    // $survey_id   = $_POST['surveyID'];
    // $eid         = $_POST['employeeID'];

    $survey_id  = 101;
    $eid        = 'H008';

    GetSurveyFeedbackForEmployeeBySurveyorType($con, $survey_id, $eid, $feedback_array_numerical);
    GetSurveyTextFeedbackForEmployeeBySurveyorType($con, $survey_id, $eid, $feedback_array_text);

    $questionare_id = SurveyID2questionareID($con, $survey_id);

    GetQuestionaireDetails($con, $questionare_id, $questionaire_details);

    $questionare_category_list = [];
    $average_by_surveyor_type  = [];
    $average_without_self      = [];
    $questions_list 	       = [];
    $question_avg_by_surveyor_type = [];

    foreach ($questionaire_details as $key => $value) {
        if ($value['CategoryName'] != 'Text Input') {
            array_push($questionare_category_list, $value['CategoryName']);
            $questions_list[$value['CategoryID']] = [];
            foreach ($value['QuestionList'] as $question_index => $question_details) {
            	array_push($questions_list[$value['CategoryID']], $question_details['Question']);
            }
        }
    }

    foreach ($feedback_array_numerical as $key => $value) {

        // CALCULATE AVERAGE FOR REFERENCE GROUP - CATEGORY WISE

        if ($key != 'Self') {
            foreach ($value['AverageRatings'] as $avg_key => $avg_value) {
                $avg_key--;
                if (array_key_exists($avg_key, $average_without_self)) {
                    $average_without_self[$avg_key] = $average_without_self[$avg_key] + $avg_value;
                } elseif (!array_key_exists($avg_key, $average_without_self)) {
                    $average_without_self[$avg_key] = $avg_value;
                }
            }
        }

        // CALCULATE QUESTION BASED AVERAGE - SURVEYOR TYPE BASED    

        foreach ($value as $employee_id => $employee_details) {
            if ($employee_id != 'AverageRatings') {
                if (!array_key_exists($key, $question_avg_by_surveyor_type)) {
                    $question_avg_by_surveyor_type[$key] = $employee_details['Feedback'];
                } elseif (array_key_exists($key, $question_avg_by_surveyor_type)) {
                    foreach ($employee_details['Feedback'] as $category => $questions) {
                        foreach ($questions as $question_number => $rating) {
                            $question_avg_by_surveyor_type[$key][$category][$question_number] = $question_avg_by_surveyor_type[$key][$category][$question_number] + $rating;
                        }
                    }
                }
            }
        }

        $average_by_surveyor_type[$key] = array_values($value['AverageRatings']);
    }

    foreach ($average_without_self as $key => $value) {
        $average_without_self[$key] = $value / 3;
    }

    foreach ($question_avg_by_surveyor_type as $surveyor_type => $category) {
    	if ($surveyor_type != "Self" || $surveyor_type != "ReportingManager") {
    		foreach ($category as $category_id => $feedback) {
    			foreach ($feedback as $question_number => $rating) {
    				$count_s = count($feedback_array_numerical[$surveyor_type]);
    				$count_s--;
    				$question_avg_by_surveyor_type[$surveyor_type][$category_id][$question_number] = $rating / $count_s;
    			}
    		}
    	}
    }

    // echo "<pre>";
    // // echo "-------------------------feedback_array_numerical---------------------------------<br>";
    // // print_R($feedback_array_numerical);
    // echo "-------------------------question_avg_by_surveyor_type---------------------------------<br>";
    // print_R($question_avg_by_surveyor_type);
    // echo "-------------------------average_by_surveyor_type---------------------------------";
    // print_r($average_by_surveyor_type);
    // echo "-------------------------questionare_category_list---------------------------------";
    // print_r($questionare_category_list);
    // echo "--------------------------average_without_self--------------------------------";
    // print_r($average_without_self);
    // echo "--------------------------questions_list--------------------------------";
    // print_r($questions_list);
    // echo "----------------------------------------------------------";
    // echo "</pre>";
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Multipoint Report</title>

	<!-- Chart JS CDN -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.4.1/chart.min.js" integrity="sha512-5vwN8yor2fFT9pgPS9p9R7AszYaNn0LkQElTXIsZFCL7ucT8zDCAqlQXDdaqgA1mZP47hdvztBMsIoFxq/FyyQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	<!-- GOOGLE FONTS CDNS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <!-- JQUERY CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>  

    <!-- BOOTSTRAP CDNS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- DATATABLE CDNS-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    
    <!-- CUSTOM STYLE SHEET -->
    <link rel="stylesheet" type="text/css" href="style.css">

	<script type="text/javascript">
		$(document).ready(function(){
			var category_list 	  	 = <?= json_encode($questionare_category_list); ?>;
			var questions_list		 = <?= json_encode($questions_list); ?>;
			var reporting_manager 	 = <?= json_encode($average_by_surveyor_type['ReportingManager']); ?>;
			var self 			  	 = <?= json_encode($average_by_surveyor_type['Self']); ?>;
			var peer 			  	 = <?= json_encode($average_by_surveyor_type['Peer']); ?>;
			var direct_reports    	 = <?= json_encode($average_by_surveyor_type['DirectReport']); ?>;
			var reference_group    	 = <?= json_encode($average_without_self); ?>;
			var question_avg_by_surveyor_type = <?= json_encode($question_avg_by_surveyor_type); ?>;

			var category_count = category_list.length;

			console.log(category_list);
			console.log(reporting_manager);
			console.log(self);
			console.log(peer);
			console.log(direct_reports);
			console.log(reference_group);
			console.log(question_avg_by_surveyor_type);
			console.log(questions_list);

			// Evaluation Summery Page 3

			new Chart(document.getElementById("summery_radar"), {
			    type: 'radar',
			    data: {
			      labels: category_list,
			      datasets: [
						{
							label: "Reporting Manager",
							fill: false,
							borderColor: "rgba(255, 195, 0,1)",
							pointBorderColor: "#FFCC00",
							pointBackgroundColor: "rgba(255, 195, 0,1)",
							data: reporting_manager
						}, {
							label: "Self",
							fill: false,
							borderColor: "rgba(199, 0, 57,1)",
							pointBorderColor: "#C70039",
							pointBackgroundColor: "rgba(199, 0, 57,1)",
							data: self
						},
						{
							label: "Peer",
							fill: false,
							borderColor: "rgba(57, 189, 18,1)",
							pointBorderColor: "#39BD12",
							pointBackgroundColor: "rgba(57, 189, 18,1)",
							data: peer
						}, {
							label: "Direct Report",
							fill: false,
							borderColor: "rgba(11, 54, 160,1)",
							pointBorderColor: "#0B36A0",
							pointBackgroundColor: "rgba(11, 54, 160,1)",
							data: direct_reports
						}
			      ]
			    },
			    options: {
			      title: {
			        display: true,
			        text: 'Evaluation Summery - Radar Graph',
			        fontSize: 40
			      },
			      scales: {
			      	r: {
			      		beginAtZero: true,
			      		ticks: {
				      		min: 0,
				      		stepSize: 1
					    }
			      	}
			      }
			    }
			});

			// Evaluation Summery (Self vs Reference Group) Page 4

			new Chart(document.getElementById("summery_bar_self_vs_reference"), {
			    type: 'bar',
			    data: { 
			    	labels: category_list,
				    datasets: [
				        {
				            label: "Self",
				            backgroundColor: "rgb(199, 0, 57)",
				            data: self
				        },
				        {
				            label: "Reference Group",
				            backgroundColor: "rgb(25, 140, 4)",
				            data: reference_group
				        }
				    ]
				},
			    options: {
			    	indexAxis: 'y',
			        barValueSpacing: 20,
			        scales: {
			            y: {
			                ticks: {
				                min: 0, // Start the axis at zero
				                max: 5, // Set maximum rating to 5
			                    stepSize: 1
			                }
			            }
			        }
			    }
			});

			// Evaluation Summery (Self vs Reference Group) Page 5

			new Chart(document.getElementById("summery_bar_self_vs_all_types"), {
			    type: 'bar',
			    data: { 
			    	labels: category_list,
				    datasets: [
				    	{
				          label: "Self",
				          backgroundColor: "rgb(199, 0, 57)",
				          data: self
				        },
				        {
				          label: "Reporting Manager",
				          backgroundColor: "rgb(255, 195, 0)",
				          data: reporting_manager
				        },
				        {
				          label: "Peer",
				          backgroundColor: "rgb(57, 189, 18)",
				          data: peer
				        }, 
				        {
				          label: "Direct Report",
				          backgroundColor: "rgb(11, 54, 160)",
				          data: direct_reports
				        }
				    ]
				},
			    options: {
			    	indexAxis: 'y',
			        barValueSpacing: 20,
			        scales: {
			            y: {
			                ticks: {
			                    min: 0,
			                    max: 5, // Set maximum rating to 5
			                    stepSize: 1
			                }
			            }
			        }
			    }
			});

			// Question wise rating breakdown from Page 6 onwards

			console.log("-------------------------------");
			$.each( category_list, function( key, value ) {
				key++;
				var elementID = "category_no_" + key;

				var self_ratings = $.map(question_avg_by_surveyor_type['Self'][key], function(v) {
				    return v;
				});
				var rm_ratings = $.map(question_avg_by_surveyor_type['ReportingManager'][key], function(v) {
				    return v;
				});
				var dr_ratings = $.map(question_avg_by_surveyor_type['DirectReport'][key], function(v) {
				    return v;
				});
				var peer_ratings = $.map(question_avg_by_surveyor_type['Peer'][key], function(v) {
				    return v;
				});

				console.log("Self" + JSON.stringify(self_ratings));
				console.log("Reporting Managers" + JSON.stringify(rm_ratings));
				console.log("Direct Reporting" + JSON.stringify(dr_ratings));
				console.log("Peer" + JSON.stringify(peer_ratings));
				console.log("-------------------------------");

				new Chart(document.getElementById(elementID), {
				    type: 'bar',
				    data: { 
				    	labels: questions_list[key],
					    datasets: [
					    	{
					          label: "Self",
					          backgroundColor: "rgb(199, 0, 57)",
					          data: self_ratings
					        },
					        {
					          label: "Reporting Manager",
					          backgroundColor: "rgb(255, 195, 0)",
					          data: reporting_manager
					        },
					        {
					          label: "Peer",
					          backgroundColor: "rgb(57, 189, 18)",
					          data: peer
					        }, 
					        {
					          label: "Direct Report",
					          backgroundColor: "rgb(11, 54, 160)",
					          data: direct_reports
					        }
					    ]
					},
				    options: {
				    	indexAxis: 'y',
				        barValueSpacing: 20,
				        scales: {
				            y: {
				                ticks: {
				                    min: 0,
				                    max: 5, // Set maximum rating to 5
				                    stepSize: 1
				                }
				            }
				        }
				    }
				});				
			});
		});
	</script>
</head>
<body>
	<h1 style="text-align: center;"><?= EmployeeId2EmployeeName($con, $eid) ?></h1>

	<!-- -------------------------------------------------------------- -->

	<h1>Evaluation Summery</h1>
	<p class="paragraph">Summery of the ratings of the four surveyor types (Self, Reporting Manager, Peers, Direct Reports) for the seven rating questions. (Excluding text questions)</p>
	<center style="padding: 0px 40px;">
		<div style="width: 100%; border: 1px solid black;">
			<canvas id="summery_radar"></canvas>
		</div>		
	</center>

	<!-- -------------------------------------------------------------- -->


	<h1>Evaluation Summery (Self vs Reference Group)</h1>
	<p class="paragraph">Reference Group - Average rating of the Reporting Manager(s), Peer(s), Direct Report(s)</p>
	<center>
		<div style="width: 100%; border: 1px solid black;">
			<canvas id="summery_bar_self_vs_reference"></canvas>
		</div>		
	</center>

	<!-- -------------------------------------------------------------- -->

	<h1>Evaluation Summery (Self vs Reporting Manager, Peers, Direct Reports)</h1>
	<center>
		<div style="width: 100%; border: 1px solid black;">
			<canvas id="summery_bar_self_vs_all_types"></canvas>
		</div>		
	</center>

	<!-- -------------------------------------------------------------- -->

	<h1><u>Category Deep Dive</u></h1>

	<?php 

	foreach ($questionaire_details as $key => $value) {
        if ($value['CategoryName'] != 'Text Input') {
            $questions_list[$value['CategoryID']] = [];
            echo "<h4>" . $value['CategoryName'] . "</h4>";
            foreach ($value['QuestionList'] as $question_index => $question_details) {
            	$question_index++;
            	echo "<h6>" . $question_index . " ) " . $question_details['Question'] . "</h6>"; ?>

		<center style="padding: 20px 0px;">
			<div style="width: 100%; border: 1px solid black; padding: 40px 0px;">
				<canvas id="<?= $question_index . "category_no_" . $category_index; ?>"></canvas>
			</div>		
		</center>

    <?php   
    		echo "<br>";
			}
    	echo "<br>";
        }
    } ?>

	<!-- -------------------------------------------------------------- -->

</body>
</html>
