<!DOCTYPE html>
<html lang="en">
<head>

<?php

require_once '../control/createQuestionare.php';

if (isset($_POST["upload"])) {
    $file_type = pathinfo($_FILES['json_file']['name'])['extension'];
    if (isset($_FILES["json_file"]) && $_FILES["json_file"]["error"] == UPLOAD_ERR_OK && $file_type == 'json') {
        
        $UploadedFilePath = $_FILES["json_file"]["tmp_name"];
        $question_list_id = AddQuestionList($con, $UploadedFilePath);
        $JsonData         = DecodeQuestionaireFile($UploadedFilePath);
      
        PopulateQuestionaire($con, $question_list_id, $JsonData);

    } else {
        echo "<script>alert('File upload error. Please check if you selected a JSON file and try again.');</script>"; 
    }
}

?>
    <title>Upload and Store JSON Files</title>

    <?php 
        require_once 'includes/head.php';
    ?>

<body>
    <?php 
        include 'includes/navbar.php';
    ?>
    <div id="contentBox">
        <center>
            <h2 style="margin-top: 20px; margin-bottom: 20px;">Create Question List</h2> 
        </center>
        <div style="margin: auto; margin-left: 33%; margin-top: 0px; width: 33%;">
            <form action="" method="post" enctype="multipart/form-data" id="uploadForm">
                <input type="file" class="form-control" name="json_file" accept=".json" id="json_file" value="Browse">
                <br>
                <center>
                    <input type="submit" name="upload" class="btn btn-primary" value="Upload and Store">                
                </center>
                <br>
                <label for="question_list_id">Questionnaire List ID : </label>
                <input type="text" id="question_list_id" name="question_list_id" value="<?php echo isset($question_list_id) ? $question_list_id : ''; ?>" readonly>
                <br>            
                <br>
                <center>
                    <?php 
                        if (isset($question_list_id)) {
                            echo '<a href="createSurvey.php" class="btn btn-primary">Next</a>';
                        } else {
                            echo '<a href="createSurvey.php" class="btn btn-primary">Skip</a>';
                        }
                    ?>
                </center>
                <br>
           </form>
        </div>
    </div>
</body>

</html>
