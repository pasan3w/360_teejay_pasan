<?php

// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

session_start();

// if (!empty($_SESSION['message'])) {
//     echo "<center>";
//     print_r($_SESSION['message']);
//     echo "</center>";
// }

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Multipoint Survey Resend</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript">
        $(document).ready(function(){

            $('body').on( 'click', '#sendMailButton', function (e) {
                e.preventDefault();

                var surveyeeName = $('input[name="surveyee_name"]').val();
                var surveyorName = $('input[name="surveyor_name"]').val();
                var surveyorType = $('input[name="surveyor_type"]').val();
                var surveyorEmail = $('input[name="surveyor_email"]').val();
                var surveyURL = $('input[name="url"]').val();
                var submit = $(this).val();

                console.log("Surveyee Name:", surveyeeName);
                console.log("Surveyor Name:", surveyorName);
                console.log("Surveyor Type:", surveyorType);
                console.log("Surveyor Email:", surveyorEmail);
                console.log("Survey URL:", surveyURL);

                $("#sendMailButton").css("display", "none");
                $("#loadingImg").css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "mailer.php",
                    data: {
                        surveyee_name : surveyeeName,
                        surveyor_name : surveyorName,
                        surveyor_type : surveyorType,
                        surveyor_email : surveyorEmail,
                        url : surveyURL,
                        submit : submit
                    }, 
                    cache: false,
                    success: function(data){
                        console.log(data);
                        $.ajax({
                            type: "POST",
                            url: "../../important/console_log/console_log.php",
                            data: {
                                logMessage : data,
                                type : 'resend'
                            }, 
                            cache: false,
                            success: function(data){
                                alert(data);
                            }
                        });
                        $("#loadingImg").css("display", "none");
                        $("#sendMailButton").css("display", "block");
                    }
                });
            });
        });
    </script>
</head>
<body>
    <center>
        <form method="post" style="width: 60%; border: 1px solid black; border-radius: 25px;">
            <h2>Survey Mail Resender</h2>
            <hr>
            <br>
            <input type="text" name="surveyee_name" placeholder="Surveyee Name" required><br><br>
            <input type="text" name="surveyor_name" placeholder="Surveyor Name" required><br><br>
            <input type="text" name="surveyor_type" placeholder="Surveyor Type" required><br><br>
            <input type="email" name="surveyor_email" placeholder="Surveyor Email" required><br><br>
            <input type="url" name="url" placeholder="Survey URL | https://example.com" required><br><br>
            <input type="submit" name="submit" value="Send Email" id="sendMailButton"><br><br>
            <img src="../../img/loading.gif" width="5%" id="loadingImg" style="display: none;" />
        </form>
    </center>
</body>
</html>