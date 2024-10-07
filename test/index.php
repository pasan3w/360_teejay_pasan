<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Get feedback</title>

    <!-- JQUERY CDNS -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <!-- DATATABLE CDNS -->

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

    <!-- BOOTSTRAP CDNS -->

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <style type="text/css">
    	body {
    		padding: 20px;
    	}
        table, td, th {
            border: 1px solid black;
        }
        td, th {
        	padding: 5px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
			$('body').on( 'click', '#loadData', function () {
				$("#loadData").css("display", "none");
				$("#loadingImg").css("display", "block");

				$.ajax({
					type: "POST",
					url: "inc.php",
					cache: false,
					success: function(data){
						$("#loadingImg").css("display", "none");
						$("#table_body").html(data);
						$("#loadData").css("display", "block");
						$("#table").DataTable();
					}
				});
			});
        });
    </script>
</head>
<body>
	<br>
	<center style="padding: 10px;">
		<button class="btn btn-primary" id="loadData">Click to load data</button>
		<img src="../img/loading.gif" width="5%" id="loadingImg" style="display: none;" />
	    <table style="border: 1px solid black;width: 100%; margin-top: 20px;" id="table">
	        <thead>
	            <tr>
	                <th>Surveyee</th>
	                <th>Surveyor</th>
	                <th>Surveyor Type</th>
	                <th>Status</th>
	                <th>Response Date</th>
	            </tr>
	        </thead>
	        <tbody id="table_body">
	        </tbody>
	    </table>
	</center>

</body>
</html>