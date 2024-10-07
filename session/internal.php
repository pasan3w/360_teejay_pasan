<?php

session_start();

if (!empty($_POST['ischecked'])) {
    if ($_POST['ischecked'] == 'no') {
        $array = explode("-", $_POST['row']);
        $_SESSION['internal'][$array[0]]['check'] = 'no';
    } elseif ($_POST['ischecked'] == 'yes') {
        $array = explode("-", $_POST['row']);
        $_SESSION['internal'][$array[0]]['check'] = 'yes';
    }
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
}

if (!empty($_POST['eid'])) {
    if (array_key_exists($_POST['eid'], $_SESSION['internal']) || $_POST['eid'] == $_SESSION['surveyee']['EID']) {
        echo "1";
    } else {
        $addedEmployee = [];
        $addedEmployee['EID'] = $_POST['data'][0];
        $addedEmployee['Name'] = $_POST['data'][1];
        $addedEmployee['DepartmentName'] = $_POST['data'][2];
        $addedEmployee['JobTitleName'] = $_POST['data'][3];
        $addedEmployee['EmailAddress'] = $_POST['data'][4];
        $addedEmployee['type'] = $_POST['data'][5];
        $addedEmployee['check'] = 'no';

        $_SESSION['internal'][$_POST['data'][0]] = $addedEmployee;
    }
}