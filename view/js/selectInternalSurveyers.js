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
					    url: "session/internal.php",
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
					    url: "session/internal.php",
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
					    url: "session/internal.php",
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
				    url: "session/internal.php",
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