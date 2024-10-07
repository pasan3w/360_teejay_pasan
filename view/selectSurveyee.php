<?php

include '../Common/DbOperations.php';

$_SESSION['surveyType'] = '360';

unset($_SESSION['surveyee']);
unset($_SESSION['internal']);
unset($_SESSION['external']);

if (!empty($_POST['submit'])) {
	if (!empty($_POST['employee_name'])) {
		$_SESSION['surveyee']['EID']   = $_POST['employee_id'];
		$_SESSION['surveyee']['Name'] = $_POST['employee_name'];
		$_SESSION['surveyee']['DepartmentName']        = $_POST['department_name'];
		$_SESSION['surveyee']['JobTitleName']    = $_POST['designation'];
		$_SESSION['surveyee']['EmailAddress']   = $_POST['email'];
		$_SESSION['surveyee']['type']   = 'Self';

		header('location: selectInternalSurveyers.php');
	} else {
		echo "<script>alert('Please Select an Employee to proceed...!!!')</script>";
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Select Surveyee</title>

	<?php 
        require_once 'includes/head.php';
    ?> 

	<script type="text/javascript">
		$(document).ready(function(){  
            var table = $('#employee_data').DataTable();

            $('#employee_data').on( 'click', 'tr', function (e) {
			    e.preventDefault();
				var table 		= $('#employee_data').DataTable();
				var eid 		= table.row( this ).data()[0];
				var ename 		= table.row( this ).data()[1];
				var branch 		= table.row( this ).data()[2];
				var department 	= table.row( this ).data()[3];
				var email	 	= table.row( this ).data()[4];
				var designation = table.row( this ).data()[5];
				
				$("#EmpID").val(eid);
				$("#EmpName").val(ename);
				$("#BranchName").val(branch);
				$("#DepartmentName").val(department);
				$("#Email").val(email);
				$("#DesignationName").val(designation);
			} );
        });  
	</script>

	<style rel="stylesheet">
		.floatRight {
			float: right;
		}
		.middle {
			width: 2%;
		}
	</style>


</head>
<body>
	<?php 
        include 'includes/navbar.php';
    ?>
	<div id="contentBox">
		<center>
			<h2 style="margin-top: 15px; margin-bottom: 15px;">Survey no : <?= $_SESSION['survey_id'] ?></h2>
			<hr>
			<form method="POST">
				<table>
					<tr>
						<td class="floatRight">
							<label for="employee_id">Employee ID : </label>					
						</td>
						<td class="middle"></td>
						<td>
							<input type="text" name="employee_id" id="EmpID" value="" readonly>
						</td>
					</tr>
					<tr>
						<td class="floatRight">
							<label for="employee_name">Employee Name : </label>					
						</td>
						<td class="middle"></td>
						<td>
							<input type="text" name="employee_name" id="EmpName" value="" readonly>
						</td>
					</tr>
					<tr>
						<td class="floatRight">
							<label for="branch_name">Branch : </label>
						</td>
						<td class="middle"></td>
						<td>
							<input type="text" name="branch_name" id="BranchName" value="" readonly>
						</td>
					</tr>
					<tr>
						<td class="floatRight">
							<label for="department_name">Department : </label>		
						</td>
						<td class="middle"></td>
						<td>
							<input type="text" name="department_name" id="DepartmentName" value="" readonly>
						</td>
					</tr>
					<tr>
						<td class="floatRight">
							<label for="designation">Designation : </label>
						</td>
						<td class="middle"></td>
						<td>
							<input type="text" name="designation" id="DesignationName" value="" readonly>
						</td>
					</tr>
					<tr>
						<td class="floatRight">
							<label for="designation">Email : </label>
						</td>
						<td class="middle"></td>
						<td>
							<input type="text" name="email" id="Email" value="" readonly>
						</td>
					</tr>
				</table>
				<br>
				<input type="submit" class="btn btn-primary" name="submit" value="Proceed with this employee">
			</form>
		</center>

		<?php
			GetAllEmployeeList($con, $result);
		?>

		<div class="container mt-5" style="margin-bottom: 50px;">
			<table id="employee_data" class="display" style="width:100%;">
		        <thead>
		            <tr>
		                <th>Emp. ID</th>
		                <th>Emp. Name</th>
		                <th>Branch</th>
		                <th>Department</th>
		                <th>Email</th>
		                <th>Designation</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php
		        		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
						    echo'<tr>  
		                            <td>'.$row["EID"].'</td>  
		                            <td>'.$row["Name"].'</td>  
		                            <td>'.$row["BranchName"].'</td>  
		                            <td>'.$row["DepartmentName"].'</td>  
		                            <td>'.$row["Email"].'</td>  
		                            <td>'.$row["JobTitleName"].'</td>  
		                        </tr>  
		                    ';  
						}
		        	?>
		        </tbody>
	    	</table>
		</div>
	</div>

</body>
</html>