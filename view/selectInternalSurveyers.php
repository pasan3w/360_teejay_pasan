<?php

include '../Common/DbOperations.php';

if (empty($_SESSION['login'])) {
    header('location: ../login.php');
}

if (empty($_SESSION['surveyee']['EID'])) {
	echo "<script>alert('You have not selected an Employee...!!!')</script>";
	echo "<a style='margin: 30px;' class='btn btn-primary' href='selectSurveyee.php'>Go back</a>";
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Select Internal Surveyors</title>

	<?php 
        require_once 'includes/head.php';
    ?> 

	<script type="text/javascript">
		$(document).ready(function(){

			$("#internalNext").click(function(){
				window.open('selectExternalSurveyors.php', "_self");
			});

			$("#internalSelectAll").click(function(){
			    $('.checkbox').each(function(){
	                this.checked = true;
	                var ischecked 	= $(this).is(':checked'); 
	                var row = $(this).val();
	                value = 'yes';
			    	$.ajax({
					    type: "POST",
					    url: "../session/internal.php",
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
					    url: "../session/internal.php",
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
					    url: "../session/internal.php",
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

            //add new row function

			function addNewRow(data) {
			    table.row
			        .add([
			            "<input type='checkbox' name='check' class='checkbox' value='"+data[0]+"-"+data[5]+"'>",
			            data[0],
			            data[1],
			            data[2],
			            data[3],
			            data[4],
			            data[5]
			        ])
			        .draw();
			        location.reload();
			}

            var table  = $('#internalSurveyors').DataTable();
            var table2 = $('#employee_data').DataTable();

            //add new row event catcher

            $('#employee_data tbody').on( 'click', 'tr', function () {
			    var data = table2.row( this ).data() ;
			    console.log(data);
			    $.ajax({
				    type: "POST",
				    url: "../session/internal.php",
				    data: {
				    	eid : data[0],
				    	data : data
				    }, 
				    cache: false,
				    success: function(validation){
				    	console.log(validation);
				        if (validation == '1') {
				        	alert('The Employee is already a surveyor !');
				        } else {
			    			addNewRow(data);
				        }
				    }
				});
			} );
        });  
	</script>
	<style type="text/css">
		td {
			width: 100%;
			overflow-y:hidden;
		}
	</style>
</head>
<body style="padding: 10px;">
	<?php 
        include 'includes/navbar.php';
    ?>
	<div class="result"></div>
<?php

	$eid = $_SESSION['surveyee']['EID'];

	if (!isset($_SESSION['internal'])) {
		GetEmployeeReportingManagersForSurveyAssignment($con, $eid, $rm_list);
		GetEmployeeDirectReportsForSurveyAssignment($con, $eid, $dm_list);
		GetEmployeePeerListForSurveyAssignment($con, $eid, $peer_list);

		$internal_surveyors = [];

		foreach ($rm_list as $rm_lists) {
			unset($rm_lists['PhoneNumber']);
			$rm_lists['type'] = 'Reporting Manager';
			$rm_lists['check'] = 'yes';
			$internal_surveyors[$rm_lists['EID']] =  $rm_lists;
		}

		foreach ($dm_list as $dm_lists) {
			unset($dm_lists['PhoneNumber']);
			$dm_lists['type'] = 'Direct Reporting';
			$dm_lists['check'] = 'yes';
			$internal_surveyors[$dm_lists['EID']] =  $dm_lists;
		}

		foreach ($peer_list as $peer_lists) {
			unset($peer_lists['PhoneNumber']);
			$peer_lists['type'] = 'Peer';
			$peer_lists['check'] = 'yes';
			$internal_surveyors[$peer_lists['EID']] =  $peer_lists;
		}

		$_SESSION['internal'] = $internal_surveyors;
	}

	GetAllEmployeeList($con, $result);
?>
	<div id="contentBox">
		<center>
			<hr><h3>Select More Internal Surveyors to ADD</h3><hr>
		</center>
		<!-- INTERNAL SURVEYORS PAGE -->
		<table id="employee_data" class="display" style="width:100%;table-layout: fixed;">
	        <thead>
	            <tr>
	                <th>Emp. ID</th>
	                <th>Emp. Name</th>
	                <th>Department</th>
	                <th>Designation</th>
	                <th>Email</th>
	                <th>Surveyor Type</th>
	                <th>Options</th>
	            </tr>
	        </thead>
	        <tbody>
	        	<?php
	        		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	        			if ($_SESSION['surveyee']['EID'] != $row["EID"]) {
	        				echo'<tr>
		                            <td>'.$row["EID"].'</td>  
		                            <td>'.$row["Name"].'</td> 
		                            <td>'.$row["DepartmentName"].'</td>  
		                            <td>'.$row["JobTitleName"].'</td>
		                            <td>'.$row["Email"].'</td>
		                            <td>Internal</td>
		                            <td><input type="submit" class="add btn btn-success" value="Add to surveyors" ></td>
		                        </tr>  
		                    '; 	
	        			}	 
					}
	        	?>
	        </tbody>
		</table>
		<!-- INTERNAL SURVEYORS PAGE -->
		<center>
			<hr><h3>Internal Surveyors List</h3><hr>
			<table id="internalSurveyors" class="display" style="width:100%;table-layout: fixed;">
				<thead>
					<tr>
						<th >select</th>
						<th >Emp. ID</th>
						<th >Emp. Name</th>
						<th >Department</th>
						<th >Job Title</th>
						<th >Email</th>
						<th >Surveyor Type</th>
					</tr>			
				</thead>
				<tbody>

					<?php

					foreach ($_SESSION['internal'] as $list) {
						echo "<tr>";
						if ($list['check'] == 'yes') {
							echo "<td><input type='checkbox' class='checkbox' name='check' value='" . $list['EID'] . "-" . $list['type'] . "' checked></td>";
						} else {
							echo "<td><input type='checkbox' class='checkbox' name='check' value='" . $list['EID'] . "-" . $list['type'] . "'></td>";						
						}
						echo "<td>" . $list['EID'] . "</td>";
						echo "<td>" . $list['Name'] . "</td>";
						echo "<td>" . $list['DepartmentName'] . "</td>";
						echo "<td>" . $list['JobTitleName'] . "</td>";
						echo "<td>" . $list['EmailAddress'] . "</td>";
						echo "<td>" . $list['type'] . "</td>";
						echo "</tr>";
					}

					?>
				</tbody>
			</table>
			<hr>
			<button class="btn btn-primary" id="internalSelectAll">Select All</button>
			<button class="btn btn-primary" id="internalNext">NEXT</button>
		</center>
	</div>
</body>
</html>