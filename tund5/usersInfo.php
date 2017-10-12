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


 

		
	//andmebaasiühendus
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	//käsk serverile
	$stmt = $mysqli->prepare("SELECT id, firstname, lastname, email, birthday, gender FROM vpusers");
	$result = mysqli_fetch_array("SELECT id, firstname, lastname, email, birthday, gender from vpusers");
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
	
	<table width="500" cellpadding=5celspacing=5 border=1>
	<tr>
	<th>ID#</th>
	<th>eesnimi</th>
	<th>perekonnanimi</th>
	<th>email</th>
	<th>sünnipäev</th>
	<th>sugu</th>
	</tr>
	<?php while($row=mysqli_fetch_array($result)):?>
	<tr>
	<td><?php echo $row['id'];?></td>
	<td><?php echo $row['firstname'];?></td>
	<td><?php echo $row['lastname'];?></td>
	<td><?php echo $row['email'];?></td>
	<td><?php echo $row['birthdate'];?></td>
	<td><?php echo $row['gender'];?></td>
	</tr>
	<?php endwhile;?> 
	</table>
	
	
	
</body>
</html>