<?phparray_key_exists($value['CategoryID'], $questions_list)

require_once '../SendMail/SendMail.php';
require_once 'SurveyAssignment.php';

//------------------------------------------
//     TEST - When the survey is 360
//------------------------------------------

if ($_SESSION['surveyType'] == '360') {
	if (!empty($_POST['surveyee']) && !empty($_POST['surveyors']) && !empty($_POST['SurveyID'])) {
		$surveyors = json_decode($_POST['surveyors'], true);
		$surveyee  = json_decode($_POST['surveyee'], true);
		$surveyID  = json_decode($_POST['SurveyID']);

		echo "<pre><h2>Survey ID</h2>";
		echo $surveyID;

		echo "<pre><h2>Surveyee Details</h2>";
		print_r($surveyee);
		echo "<pre><h2>Surveyors Details</h2>";
		print_r($surveyors);
		echo "<hr>";

		$surveyee_id = $surveyee['EID'];
		$survyee_name = $surveyee['Name'];

		$assigner_eid = 'test';

		$date = date('Y-m-d');

		$url = 'https://3wexotic.com/SurveyPage/surveyPage_hayleys.php?eid=' . urlencode($surveyee_id) . 
				'&type=' . urlencode('Self') . 
				'&selectedIds=' . urlencode($surveyee_id) . 
				'&surveyId=' . urlencode($surveyID);
		//echo $url;

		echo "<script>alert('" . $surveyee['type'] . "');</script>";
		//echo "value: " . $value['type'] . "<br>After Mapping: " . $SurveyorTypeString2Enum[$value['type']];
		
		if (AssignSurvey($con, $surveyID, $assigner_eid, $surveyee_id, $surveyee_id, $SurveyorTypeString2Enum[$surveyee['type']], $date)) {
			sendmail($survyee_name, $survyee_name, 'Self', $surveyee['EmailAddress'], $url);

			foreach ($surveyors as $key => $value) {
				$url = 'https://3wexotic.com/SurveyPage/surveyPage_hayleys.php?eid=' . urlencode($surveyee_id) . 
						'&type=' . urlencode($value['type']) . 
						'&selectedIds=' . urlencode($value['EID']) . 
						'&surveyId=' . urlencode($surveyID);

				echo $url;
				
				if (AssignSurvey($con, $surveyID, $assigner_eid, $surveyee_id, $value['EID'], $SurveyorTypeString2Enum[$value['type']], $date)) {
					sendmail($survyee_name, $value['Name'], $value['type'], $value['EmailAddress'], $url);	
				}
				
			}
		}

	} else {
		echo "<script>alert('Error in sending the survey');</script>";
	}	

//------------------------------------------
//     TEST - When the survey is climate
//------------------------------------------
} elseif ($_SESSION['surveyType'] == 'climate') {
	$surveyors = json_decode($_POST['surveyors'], true);
	$surveyID  = json_decode($_POST['SurveyID']);
	$date 	   = date('Y-m-d');

	foreach ($surveyors as $surveyors_list) {
		$url = 'http://3.138.184.14/SurveyPage/surveyPage.php?eid=' . urlencode($surveyors_list['EID']) . 
				'&type=climate&selectedIds=' . urlencode($surveyors_list['EID']) . 
				'&surveyId=' . urlencode($surveyID);

		echo $url;

		$assigner_eid = 'admin';
		
		if (AssignSurvey($con, $surveyID, $assigner_eid, $surveyors_list['EID'], $surveyors_list['EID'], $SurveyorType2Int['Self'], $date)) {
			sendmail_climate_survey($surveyors_list['Name'], $surveyors_list['Name'], $date, $surveyors_list['Email'], $url);	
		}
	}
}

?>