<?php
	require("functions.php");
	$notice = "";
	
	//kui pole sisseloginud, siis sisselogimise lehele
	if(!isset($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}
	
	//kui logib välja
	if (isset($_GET["logout"])){
		//lõpetame sessiooni
		session_destroy();
		header("Location: login.php");
	}
	
	//Algab foto laadimise osa
	$target_dir = "../../pics/";
	$target_file = "";
	$uploadOk = 1;
	//määran maximum pildi suurused
	$maxWidth = 600;
	$maxHeight = 400;
	$marginVer = 10;
	$marginHor = 10;
	
	//kas vajutati laadimise nuppu
	if(isset($_POST["submit"])) {
		
		//kas fail on valitud
		if(!empty($_FILES["fileToUpload"]["name"])){
				
			//fikseerin failinime
			
			// muudan faili extensionid koik vaikesteks tahtedeks
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]))["extension"]);
			$timeStamp = microtime(1) *10000;
			// muudan faili nimeks uue nime, kus sees on ka timestamp, et teha sama nimedega piltidel vahet
			$target_file = $target_dir . pathinfo(basename($_FILES["fileToUpload"]["name"]))["filename"] ."_" .$timeStamp ."." .$imageFileType;
			
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				$notice .= "Fail on pilt - " . $check["mime"] . ". ";
				$uploadOk = 1;
			} else {
				$notice .= "See pole pildifail. ";
				$uploadOk = 0;
			}
			
			//Kas selline pilt on juba üles laetud
			if (file_exists($target_file)) {
				$notice .= "Kahjuks on selle nimega pilt juba olemas. ";
				$uploadOk = 0;
			}
			//Piirame faili suuruse
			if ($_FILES["fileToUpload"]["size"] > 1000000) {
				$notice .= "Pilt on liiga suur! ";
				$uploadOk = 0;
			}
				
			//Piirame failitüüpe
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
				$notice .= "Vabandust, vaid jpg, jpeg, png ja gif failid on lubatud! ";
				$uploadOk = 0;
			}
				
			//Kas saab laadida?
			if ($uploadOk == 0) {
				$notice .= "Vabandust, pilti ei laetud üles! ";
			//Kui saab üles laadida
			} else {		
				// kommenteerin faili üleslaadimise valja sest muudan pildi enne sobivaks suuruseks
				/*if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					$notice .= "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti üles! ";
				} else {
					$notice .= "Vabandust, üleslaadimisel tekkis tõrge! ";
				}*/
				
				//saab salvestada lõppeb
			
				//sõltuvalt failitüübist, loon pildiobjekti(objekti panen uute muutujasse, et see ei laheks vaga suurel kujul andmebaasi. Saan seda hiljem töödelda.)
				if($imageFileType == "jpg" or $imageFileType == "jpeg"){
					$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
			
				}
			
				if($imageFileType == "png"){
					$myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
				}
				
				if($imageFileType == "gif"){
					$myTempImage = imagecreatefromgif($_FILES["fileToUpload"]["tmp_name"]);
				}
				
				//suuruse muutmine
				//küsin esmalt pildi suurused eelnevat muutujat kasutades
				$imageWidth = imagesx($myTempImage);
				$imageHeight = imagesy($myTempImage);
			
				//arvutan suuruse suhte 
				$sizeRatio = 1;
				//kui on landscape pilt või siis portree, ja siis vähendan pildi suurust
				if($imageWidth > $imageHeight){
					$sizeRatio = $imageWidth / $maxWidth;
				} else {
					$sizeRatio = $imageHeight / $maxHeight;
				}
				//tekitame ära uue sobiva suurusega pildikogumi!
				$myImage = resizeImage($myTempImage, $imageWidth, $imageHeight, round($imageWidth / $sizeRatio), round($imageHeight / $sizeRatio));
			
				//lisan vesimärgi
				
				$stamp = imagecreatefrompng("../../graphics/hmv_logo.png");
				//määrab jälle kõrguse ja laiuse x y kordinaadid
				$stampWidth = imagesx($stamp);
				$stampHeight = imagesy($stamp);
				$stampX = imagesx($myImage) - $stampWidth - $marginHor;
				$stampY = imagesy($myImage) - $stampHeight - $marginVer;
				//lisan vesimärgi pildi teise pildi peale pixlite kaupa
				imagecopy($myImage, $stamp, $stampX, $stampY, 0, 0, $stampWidth, $stampHeight);
				
				//lisame teksti vesimärgile
				
				$textToImage = "Heade mõtete veeb";
				
				//määrata värv
				$textColor = imagecolorallocatealpha($myImage, 255, 255, 255, 60);//alpha on 0-127 
				//kirjutab pildile teksti(mis pildile, suurus, nurk vastupäeva, x ja y, värv, font, tekst)
				imagettftext($myImage, 20, -45, 10, 25, $textColor, "../../graphics/ARIAL.TTF", $textToImage);
			
				//salvestame pildi
				
				if($imageFileType == "jpg" or $imageFileType == "jpeg"){
					if(imagejpeg($myImage, $target_file, 90)){
						$notice = "Fail: " .basename($_FILES["fileToUpload"]["name"]) ." laeti üles!";
					} else {
						$notice = "Vabandust! Faili üleslaadimisel tekkis viga!";
					}
				}
				
				if($imageFileType == "png"){
					if(imagejpeg($myImage, $target_file, 5)){
						$notice = "Fail: " .basename($_FILES["fileToUpload"]["name"]) ." laeti üles!";
					} else {
						$notice = "Vabandust! Faili üleslaadimisel tekkis viga!";
					}
				}
				
				if($imageFileType == "gif"){
					if(imagejpeg($myImage, $target_file)){
						$notice = "Fail: " .basename($_FILES["fileToUpload"]["name"]) ." laeti üles!";
					} else {
						$notice = "Vabandust! Faili üleslaadimisel tekkis viga!";
					}
				}
			}//üles laadimine lõppeb
			
		} else {
			$notice = "Palun valige kõigepealt pildifail";
		}
	}
	
	//see funktsioon votab koik need eelnevalt pildi muutmiseks määratud suuruse ja hakkab seda pilti muutma
	function resizeImage($image, $origW, $origH, $w, $h){
		$newImage = imagecreatetruecolor($w, $h);
		//imagecopyresampled teeb vanadele pildi andmetele lähvtuvalt uue pildi pixlite kaudu *SULU SEES*(kuhu, kust, kuhu kordinaatidele x ja y , kust kordinaatidelt x ja y, kui laialt uude kohta, kui kõrgelt uude kohta, kui laialt võtta, kui kõrgelt võtta)
		imagecopyresampled($newImage, $image, 0, 0, 0, 0, $w, $h, $origW, $origH);
		return $newImage;
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>
		Sten Piirsalu veebiprogemise asjad
	</title>
</head>
<body>
	<h1>Sten Piirsalu</h1>
	<p>See veebileht on loodud veebiprogrammeerimise kursusel ning ei sisalda mingisugust tõsiseltvõetavat sisu.</p>
	<p><a href="?logout=1">Logi välja</a>!</p>
	<p><a href="main.php">Pealeht</a></p>
	<hr>
	<h2>Foto üleslaadimine</h2>
	<form action="photoupload.php" method="post" enctype="multipart/form-data">
		<label>Valige pildifail:</label>
		<input type="file" name="fileToUpload" id="fileToUpload">
		<input type="submit" value="Lae üles" name="submit">
	</form>
	
	<span><?php echo $notice; ?></span>
	<hr>
</body>
</html>