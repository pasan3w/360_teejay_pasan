<?php

include '../Common/DbOperations.php';
//include '../control/selectExternalSurveyors.php';

if (empty($_SESSION['login'])) {
    header('location: ../login.php');
}

//unset($_SESSION['external']);

/*if(!isset($_SESSION['external'])) {
	GetAllExternalSurveyorDetails($con, $external_surveyor_details);
	$external_surveyors = [];

	foreach ($external_surveyor_details as $ext_list) {
		$ext_list['type'] 	= 'External';
		$ext_list['check'] 	= 'no';
		$external_surveyors[$ext_list['EID']] =  $ext_list;
	}

	$_SESSION['external'] = $external_surveyors;
}*/

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Select External Surveyors</title>

	<?php 
        require_once 'includes/head.php';
    ?> 

	<script type="text/javascript">
		$(document).ready(function(){ 
			$("#externalNext").click(function(){
				window.open('sendSurvey.php', "_self");
			});

			$("#externalSelectAll").click(function(){
			    $('.checkbox').each(function(){
	                this.checked = true;
	                var ischecked 	= $(this).is(':checked'); 
	                var row = $(this).val();
	                value = 'yes';
			    	$.ajax({
					    type: "POST",
					    url: "../session/external.php",
					    data: {
					    	row : row,
					    	ischecked : value
					    }, 
					    cache: false,
					    success: function(data){
					        //$(".result").html(data);
					    }
					});
	            });
			});

			$("#externalDeselectAll").click(function(){
			    $('.checkbox').each(function(){
	                this.checked = false;
	                var ischecked 	= $(this).is(':checked'); 
	                var row = $(this).val();
	                value = 'no';
			    	$.ajax({
					    type: "POST",
					    url: "../session/external.php",
					    data: {
					    	row : row,
					    	ischecked : value
					    }, 
					    cache: false,
					    success: function(data){
					        //$(".result").html(data);
					    }
					});
	            });
			});

			$("input:checkbox").change(function() {
			    var ischecked 	= $(this).is(':checked'); 
			    var row = $(this).val();
			    if(!ischecked) {
			    	value = 'no';
			    	$.ajax({
					    type: "POST",
					    url: "../session/external.php",
					    data: {
					    	row : row,
					    	ischecked : value
					    }, 
					    cache: false,
					    success: function(data){
					        //$(".result").html(data);
					    }
					});
			    }
			    if(ischecked){
			    	value = 'yes';
			    	$.ajax({
					    type: "POST",
					    url: "../session/external.php",
					    data: {
					    	row : row,
					    	ischecked : value
					    }, 
					    cache: false,
					    success: function(data){
					        //$(".result").html(data);
					    }
					});
			    }
			});

			$("#Email").on( "keydown", function() {
				$("#Email").removeClass("border border-danger");
			});
			$("#CompanyName").on( "keydown", function() {
				$("#CompanyName").removeClass("border border-danger");
			});
			$("#EmpName").on( "keydown", function() {
				$("#EmpName").removeClass("border border-danger");
			});
			$("#DepartmentName").on( "keydown", function() {
				$("#DepartmentName").removeClass("border border-danger");
			});
			$("#DesignationName").on( "keydown", function() {
				$("#DesignationName").removeClass("border border-danger");
			});

			function addNewRow(AddedEmployeeToTable) {
			    table.row
			        .add([
			            "<input type='checkbox' name='check' class='checkbox' value='"+AddedEmployeeToTable[0]+"-External'>",
			            AddedEmployeeToTable[0],
			            AddedEmployeeToTable[1],
			            AddedEmployeeToTable[2],
			            AddedEmployeeToTable[3],
			            AddedEmployeeToTable[4],
			            'External'
			        ])
			        .draw();
			        //location.reload();
			}

			$("#addExternalButton").click(function(e){
				//e.preventDefault();

				var email = $('#Email').val();
				var companyName = $('#CompanyName').val();
				var employeeName = $('#EmpName').val();
				var department = $('#DepartmentName').val();
				var designation = $('#DesignationName').val();

				var allFormDataNotEmpty = 1;

				if (email == '') {
					$("#Email").addClass("border border-danger");
					allFormDataNotEmpty = 0;
				}
				if (companyName == '') {
					$("#CompanyName").addClass("border border-danger");
					allFormDataNotEmpty = 0;
				}
				if (employeeName == '') {
					$("#EmpName").addClass("border border-danger");
					allFormDataNotEmpty = 0;
				}
				if (department == '') {
					$("#DepartmentName").addClass("border border-danger");
					allFormDataNotEmpty = 0;
				}
				if (designation == '') {
					$("#DesignationName").addClass("border border-danger");
					allFormDataNotEmpty = 0;
				}

				if (allFormDataNotEmpty) {
					$.ajax({
					    type: "POST",
					    url: "../session/external.php",
					    data: {
					    	email : email,
					    	companyName : companyName,
					    	employeeName : employeeName,
					    	department : department,
					    	designation : designation
					    },
					    success: function(data){
					    	console.log(data);
					    	location.reload();
					    }
					});
				}

			});

			var table  = $('#externalSurveyor').DataTable();
		});  
	</script>

	<style type="text/css">
		.floatRight {
			text-align: right;
		}
		.middle {
			width: 0.5%;
		}
		input.textBox {
			border: 0;
			width: 100%;
		}
		td {
			width: 10%;
		}
		button {
			margin-bottom: 15px;
		}
	</style>
</head>
<body>
	<?php 
        include 'includes/navbar.php';
    ?>
	<div id="contentBox">
		<center>
			<h2 style="margin-top: 15px; margin-bottom: 15px;">Add External Surveyors</h2>
			<hr>
			<table style="margin-left: auto; margin-right: auto;" class="display">
				<tr>
					<td class="floatRight">
						<label for="email">Email : </label>					
					</td>
					<td class="middle"></td>
					<td>
						<input type="text" name="emai" id="Email" value="" required>
					</td>
				</tr>
				<tr>
					<td class="floatRight">
						<label for="company">Company : </label>
					</td>
					<td class="middle"></td>
					<td>
						<input type="text" name="company" id="CompanyName" value="" required>
					</td>
				</tr>
				<tr>
					<td class="floatRight">
						<label for="employee_name">Employee Name : </label>					
					</td>
					<td class="middle"></td>
					<td>
						<input type="text" name="employee_name" id="EmpName" value="" required>
					</td>
				</tr>
				<tr>
					<td class="floatRight">
						<label for="department">Department : </label>		
					</td>
					<td class="middle"></td>
					<td>
						<input type="text" name="department" id="DepartmentName" value="" required>
					</td>
				</tr>
				<tr>
					<td class="floatRight">
						<label for="designation">Designation : </label>
					</td>
					<td class="middle"></td>
					<td>
						<input type="text" name="designation" id="DesignationName" value="" required>
					</td>
				</tr>
			</table>
			<br>
			<button class="btn btn-primary" id="addExternalButton" >Add Surveyor</button>
			<table class="display" id="externalSurveyor" width="100%">
				<thead>
					<tr>
						<th>Select</th>
						<th>E-mail</th>
						<th>Name</th>
						<th>Company</th>
						<th>Department</th>
						<th>Designation</th>
						<th>Type</th>
					</tr>
				</thead>
				<tbody>
					<?php

						foreach ($_SESSION['external'] as $list) {
							echo "<tr>";
							if ($list['check'] == 'yes') {
								echo "<td><input type='checkbox' class='checkbox' name='check' value='" . $list['EID'] . "-" . $list['type'] . "' checked></td>";
							} else {
								echo "<td><input type='checkbox' class='checkbox' name='check' value='" . $list['EID'] . "-" . $list['type'] . "'></td>";						
							}
							echo "<td>" . $list['EID'] . "</td>";
							echo "<td>" . $list['Name'] . "</td>";
							echo "<td>" . $list['CompanyName'] . "</td>";
							echo "<td>" . $list['Department'] . "</td>";
							echo "<td>" . $list['Designation'] . "</td>";
							echo "<td>" . $list['type'] . "</td>";
							echo "</tr>";
						}

						?>
				</tbody>
			</table>
			<hr>
			<button class="btn btn-primary" id="externalDeselectAll">Deselect All</button>
			<button class="btn btn-primary" id="externalSelectAll">Select All</button>
			<button class="btn btn-primary" id="externalNext">NEXT</button>	
		</center>
	</div>
</body>
</html>