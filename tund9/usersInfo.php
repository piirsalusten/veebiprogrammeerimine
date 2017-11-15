<?php
	$database = "if17_sten";
	require ("../../../config.php");
	require("functions.php");
	
	//kui pole sisseloginud, liigume login lehele
	if(!isset($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}
	
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: login.php");
		exit();
	}


	
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Sten Piirsalu veebiprogrammeerimine</title>
</head>
<body>
<body style="background-color:lightblue;">
	<h1>Kasutajad</h1>
	<p>See veebileht on loodud õppetööraames ning ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
	<p><a href="?logout=1">Logi välja!</a></p>
	<p><a href="main.php">pealeht</a></p>
	<h2>Kasutajate loend</h2>
	
	<table border="1" style="border: 1px solid black; border-collapse: collapse">
		<?php echo listUsers(); ?>
	</table>
	
	
	
	
</body>
</html>