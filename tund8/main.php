<?php
	
	//et pääseks ligi sessioonile ja funktsioonidele
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
		
	
	$picDir = "../../pics/";
	$picFiles = [];
	$picFileTypes = ["jpg", "jpeg", "png", "gif", "jfif",];
	
	$allFiles = array_slice(scandir($picDir), 2);
	foreach ($allFiles as $file){
		$fileType = pathinfo($file, PATHINFO_EXTENSION);
		if (in_array($fileType, $picFileTypes) == true){
			array_push($picFiles, $file);
			
		}
			
	}
	
	//$allFiles = scandir($picDir);
	//var_dump($allFiles);
	//$picFiles = array_slice($allFiles, 2);
	//var_dump($picFiles);
	$picFileCount = count ($picFiles);
	$picNumber = mt_rand(0, $picFileCount - 1);
	$picFile = $picFiles [$picNumber];
	
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>
			<?php echo $_SESSION["firstname"] ." ".$_SESSION["lastname"]; ?>
	</title>
</head>
<body>
<body style="background-color:lightpink;">
	<h1><?php echo $_SESSION["firstname"] ." " .$_SESSION["lastname"]; ?></h1>
	<p>See veebileht on loodud õppetööraames ning ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
	<p><a href="?logout=1">Logi välja!</a></p>
	<p><a href="usersInfo.php">kasutajate info</a></p>
	<p><a href="usersideas.php">head mõtted</a></p>
	<p><a href="photoupload.php">Piltide üleslaadimine</a></p>
	
	<p>Lahedad pildid!</p>
	<img src="<?php echo $picDir .$picFile; ?>" alt="Auto">
	
	

</body>
</html>