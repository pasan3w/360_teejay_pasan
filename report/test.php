<?php

require '../control/SurveyFeedback.php';
require '../QuestionsList/QuestionList.php';

// if (!empty($_POST['submit'])) {
if (true) {
    // $survey_id   = $_POST['surveyID'];
    // $eid         = $_POST['employeeID'];

    $survey_id  = 101;
    $eid        = 'H002';

    GetSurveyFeedbackForEmployeeBySurveyorType($con, $survey_id, $eid, $feedback_array_numerical);
    GetSurveyTextFeedbackForEmployeeBySurveyorType($con, $survey_id, $eid, $feedback_array_text);

    $questionare_id = SurveyID2questionareID($con, $survey_id);

    GetQuestionaireDetails($con, $questionare_id, $questionaire_details);

    $questionare_category_list = [];
    $average_by_surveyor_type  = [];
    $average_without_self      = [];
    $questions_list            = [];
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
        if ($key != "Self" || $key != "ReportingManager") {
            foreach ($category as $category_id => $feedback) {
                foreach ($feedback as $question_number => $rating) {
                    $question_avg_by_surveyor_type[$surveyor_type][$category_id][$question_number] = $rating / count($feedback_array_numerical[$surveyor_type]);
                }
            }
        }
    }

    echo "<pre>";
    // echo "-------------------------feedback_array_numerical---------------------------------<br>";
    // print_R($feedback_array_numerical);
    // echo "-------------------------question_avg_by_surveyor_type---------------------------------<br>";
    // print_R($question_avg_by_surveyor_type);
    echo "-------------------------average_by_surveyor_type---------------------------------";
    print_r($average_by_surveyor_type);
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