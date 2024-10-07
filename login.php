<?php

if (isset($_SESSION['error'])) {
	echo "<script> alert('error'); </script>";
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FONT CDNS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Agbalumo">

    <!-- JQUERY CDNS -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>  

    <title>Login</title>

    <style type="text/css">
        body {
          font-family: "Audiowide", sans-serif;
        }
    </style>

    <!-- BOOTSTRAP CDNS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- COMMON CSS FILE -->
    <link rel="stylesheet" type="text/css" href="view/css/backend_pages.css">
    <link rel="stylesheet" type="text/css" href="view/css/login.css">
</head>
<body>
	<div class="login-container">
		<form class="login-form" method="POST" action="login/get_login.php">
			<h1>Welcome to 360</h1>
			<p>Please login to your account</p>
			<div class="input-group">
				<input type="text" id="username" name="username" placeholder="Username" required>
			</div>
			<div class="input-group">
				<input type="password" id="password" name="password" placeholder="Password" required>
			</div>
			<input type="submit" name="login" class="btn btn-primary" value="Login">
		</form>
	</div>

</body>
</html>