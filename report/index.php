<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Report Template</title>

	<!-- Chart JS CDN -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

	<!-- GOOGLE FONTS CDNS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <!-- JQUERY CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>  

    <!-- BOOTSTRAP CDNS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- DATATABLE CDN S-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    
    <!-- CUSTOM STYLE SHEET -->
    <link rel="stylesheet" type="text/css" href="style.css">

	<script type="text/javascript">
		$(document).ready(function(){
			$('body').on( 'click', '#submit', function (e) {
				$("#submit").css("display", "none");
				$("#loadingImg").css("display", "block");
				e.preventDefault();
				var flag		= true;
				var surveyID 	= $("#surveyID").val();
				var employeeID 	= $("#employeeID").val();
				var submit 		= $("#submit").val();

				if (surveyID === '') {
					flag = false;
				}
				if (employeeID === '') {
					flag = false;					
				}

				console.log(surveyID + ' | ' + employeeID);

				if (flag) {
					$.ajax({
					    type: "POST",
					    url: "report.php",
					    data: {
					    	surveyID : surveyID,
					    	employeeID  : employeeID,
					    	submit  : submit
					    }, 
					    cache: false,
					    success: function(data){
							$("#loadingImg").css("display", "none");
					    	$("#submit").css("display", "block");
					    	$("#result").html(data);
					    	// alert(data);
					    	//OPEN THE RESULT IN A NEW WEBPAGE
					    	// var w = window.open();
							// $(w.document.body).html(data);
					    }
					});					
				}
			});
		});
	</script>

</head>
<body>
	<center style="padding-top: 30px;">
		<form method="POST">
			<input type="text" name="surveyID" id="surveyID" placeholder="Survey ID" required><br><br>
			<input type="text" name="employeeID" id="employeeID" placeholder="Employee ID" required><br><br>
			<select class="form-select" style="width: 10%;">
				<option>Report Type 1</option>
				<option>Report Type 2</option>
				<option>Report Type 3</option>
			</select><br>
			<button id="submit" value="submit" class="btn btn-primary">submit</button>
			<img src="../img/loading.gif" width="5%" id="loadingImg" style="display: none;" />
		</form>
		<button id="export" class="btn btn-primary" onclick="exportHTML();">Export to Doc</button>
	</center>
	<hr>
	<div id="result"></div>
	<script>
	    function exportHTML(){
	       var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' "+
	            "xmlns:w='urn:schemas-microsoft-com:office:word' "+
	            "xmlns='http://www.w3.org/TR/REC-html40'>"+
	            "<head><meta charset='utf-8'><title>Export HTML to Word Document with JavaScript</title></head><body>";
	       var footer = "</body></html>";
	       var sourceHTML = header+document.getElementById("result").innerHTML+footer;
	       
	       var source = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(sourceHTML);
	       var fileDownload = document.createElement("a");
	       document.body.appendChild(fileDownload);
	       fileDownload.href = source;
	       fileDownload.download = 'document.doc';
	       fileDownload.click();
	       document.body.removeChild(fileDownload);
	    }
	</script>
</body>
</html>