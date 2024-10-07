<?php

include '../control/SurveyAssignment.php';

if (!empty($_POST['ischecked'])) {
    if ($_POST['ischecked'] == 'no') {
        $array = explode("-", $_POST['row']);
        $_SESSION['external'][$array[0]]['check'] = 'no';
    } elseif ($_POST['ischecked'] == 'yes') {
        $array = explode("-", $_POST['row']);
        $_SESSION['external'][$array[0]]['check'] = 'yes';
    }
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
}

if (!empty($_POST['email'])) {
    $addedEmployee = [];
    
    if (GetExternalSurveyorDetails($con, $_POST['email'], $external_surveyor_details)) {
        echo "yes";
        $addedEmployee['EID'] = $external_surveyor_details['EID'];
        $addedEmployee['Name'] = $external_surveyor_details['Name'];
        $addedEmployee['CompanyName'] = $external_surveyor_details['CompanyName'];
        $addedEmployee['Department'] = $external_surveyor_details['Department'];
        $addedEmployee['Designation'] = $external_surveyor_details['JobTitleName'];
        $addedEmployee['type'] = 'External';
        $addedEmployee['check'] = 'no';
        $_SESSION['external'][$_POST['email']] = $addedEmployee;

    } else {
        echo "no";
        $addedEmployee['EID'] = $_POST['email'];
        $addedEmployee['Name'] = $_POST['employeeName'];
        $addedEmployee['CompanyName'] = $_POST['companyName'];
        $addedEmployee['Department'] = $_POST['department'];
        $addedEmployee['Designation'] = $_POST['designation'];
        $addedEmployee['type'] = 'External';
        $addedEmployee['check'] = 'no';

        $_SESSION['external'][$_POST['email']] = $addedEmployee;
        AddExternalSurveyorDetails($con, $_POST['email'], $_POST['employeeName'], $_POST['companyName'], $_POST['department'], $_POST['designation']);

    }
}
