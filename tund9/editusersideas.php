<?php
	$database = "if17_sten";
	require ("../../../config.php");
	require("functions.php");
	require("editideafunctions.php");
	$notice = "";
	
	//kui pole sisseloginud, liigume login lehele
	if(!isset($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}
	//väljalogiimine
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: login.php");
		exit();
	}

	if(isset($_POST["ideaButton"])){
		updateIdea($_POST["id"], test_input($_POST["idea"]), $_POST["ideaColor"]);
		//jään siia samale lehele
		header("Location: ?id=" .$_POST["id"]);
		exit();
		
	}
	
	if(isset($_GET["delete"])){
		deleteIdea($_GET["id"]);
		header("Location: usersideas.php");
		exit();
		
	}
	$idea = getSingleIdea($_GET["id"]);
	
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Sten Piirsalu veebiprogrammeerimine</title>
</head>
<body>
<body style="background-color:lightblue;">
	<h1>Head mõtted</h1>
	<p>See veebileht on loodud õppetööraames ning ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
	<p><a href="?logout=1">Logi välja!</a></p>
	<p><a href="usersideas.php">tagasi mõtete lehele</a></p>
	<h2>Toimeta mõtet</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<input name="id" type="hidden" value="<?php echo $_GET["id"]; ?>"> 
		<label>Hea mõte: </label>
		<textarea name="idea"><?php echo $idea->text; ?></textarea>
		<br>
		<label>Mõttega seonduv värv: </label>
		<input name="ideaColor" type="color" value="<?php echo $idea->color; ?>">
		<br>
		<input name="ideaButton" type="submit" value="Salvesta muudetud mõte!">
		<span><?php echo $notice; ?></span>
	
	
	</form>
	<p><a href="?id=<?=$_GET['id']; ?>&delete=1">kustuta see mõte!</p>
	<!--<a href=?id=19&delete=1"> -->
	<hr>
	
	
</body>
</html>