<?php

require '../Common/DbOperations.php';

function surveyorType2String($surveyType){
    switch ($surveyType) {
        case 0:
            return "Self";
            break;
        case 1:
            return "Reporting Manager";
            break;
        case 2:
            return "Direct Report";
            break;
        case 3:
            return "Peer";
            break;
        case 4:
            return "Internal Surveyor";
            break;

        default:
            return "Invalid Surveyor Type";
            break;
    }
}

function getStatus($responseDate){
    if ($responseDate == NULL) {
        return 'Pending';
    } elseif ($responseDate != NULL) {
        return 'Completed';
    }
}

function getFeedbackReport($con) {
    try {
        $stmt = $con->prepare("SELECT * FROM SurveyAssignment WHERE SurveyID = '101'");
        $stmt->execute();
        
        while ($feedback_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (getStatus($feedback_row['ResponseDate']) == 'Completed') {
                echo "<tr style='background: #5EFF51;'>";
            } elseif (getStatus($feedback_row['ResponseDate']) == 'Pending') {
                echo "<tr style='background: #FFDE47;'>";
            }
            echo
                "<td>" . EmployeeId2EmployeeName($con, $feedback_row['EID']) . "</td>
                <td>" . EmployeeId2EmployeeName($con, $feedback_row['SurveyorEID']) . "</td>
                <td>" . surveyorType2String($feedback_row['SurveyorType']) . "</td>
                <td>" . getStatus($feedback_row['ResponseDate']) . "</td>
                <td>" . $feedback_row['ResponseDate'] . "</td>
            </tr>";
        }
    } catch (PDOException $e) {
        echo "getFeedbackReport: Failed due to exception: " . $e->getMessage() . "\n";
    }
}

getFeedbackReport($con);

?>