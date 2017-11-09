<?php	
	$database = "if17_sten";
	require("../../../config.php");
	//alustame sessiooni
	session_start();
	
	
	
	//sisselogimise funktsioon
	function signin($email, $password){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, firstname, lastname, email, password FROM vpusers WHERE email = ?");
		$stmt->bind_param("s",$email);
		$stmt->bind_result($id, $firstnameFromDb, $lastnameFromDb, $emailFromDb, $passwordFromDb);
		$stmt->execute();
		
		//kontrollime kasutajat
		if($stmt->fetch()){
			$hash = hash("sha512", $password);
			if($hash == $passwordFromDb){
				$notice = "Kõik korras! Logisimegi sisse!";
				
				//salvestame sessioonimuutujad
				$_SESSION["userId"] = $id;
				$_SESSION["firstname"] = $firstnameFromDb;
				$_SESSION["lastname"] = $lastnameFromDb;
				$_SESSION["userEmail"] = $emailFromDb;
				
				//liigume pealehele
				header("Location: main.php");
				exit();	
			} else {
				$notice = "Sisestasite vale salasõna!";
			}
		} else {
			$notice = "sellist kasutajat (" .$email .") ei ole";
		}
		return $notice;
	}
	
	function listUsers (){
	$mees = "mees";
	$naine = "naine";
	$gender1 = "1";
	$gender2 = "2";
	
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, firstname, lastname, email, birthday, gender FROM vpusers");
	echo $mysqli->error;
	$stmt->bind_result($id, $firstname, $lastname, $email, $birthday, $gender);
	$stmt->execute();
	
	while($stmt->fetch()){
		if ($gender == $gender1){	
			$notice .='<tr><td>' .$id . '</td><td>' .$firstname . '</td><td>' .$lastname . '</td><td>' .$email . '</td><td>' .$birthday . '</td><td>' .$mees .'</td></tr>';
		} else { 
			$notice .='<tr><td>' .$id . '</td><td>' .$firstname . '</td><td>' .$lastname . '</td><td>' .$email . '</td><td>' .$birthday . '</td><td>' .$naine .'</td></tr>';
		}		
	}
	
	
	$stmt->close();
	$mysqli->close();
	return $notice;
	
	
	}
	
	
	function listIdeas(){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, idea, ideacolor FROM vp1userideas WHERE userid = ? AND deleted IS NULL ORDER BY id DESC");
		echo $mysqli->error;
		$stmt->bind_param("i", $_SESSION["userId"]);
		$stmt->bind_result($id, $idea, $color);
		$stmt->execute();
		
		while($stmt->fetch()){
			//<p> style="background-color: #ff5577;">Hea mõte</p>
			$notice .= '<p style="background-color: ' .$color .'">' .$idea .' | <a href="editusersideas.php?id=' .$id .'">toimeta</a>'."</p> \n";
			
			// <p> style="background-color: #ff5577;">Hea mõte!  | <a href="edituseridea.php?id=34">toimeta</a>   </p> 
		
		
		}
		
		$stmt->close();
		$mysqli->close();
		return $notice;
		
		
	}
	
	function latestIdea(){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT idea FROM vp1userideas WHERE id = (SELECT MAX(id) FROM vp1userideas WHERE deleted IS NULL)");
		echo $mysqli->error;
		$stmt->bind_result($idea);
		$stmt->execute();
		$stmt->fetch();
		
		$stmt->close();
		$mysqli->close();
		return $idea;
	
	
	
	}

	
	//uue kasutaja andmebaasi lisamine
	//ühendus serveriga
	function signUp($signupFirstName, $signupFamilyName, $signupBirthDate, $gender, $signupEmail, $signupPassword){
		//andmebaasiühendus
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//käsk serverile
		$stmt = $mysqli->prepare("INSERT INTO vpusers(firstname, lastname, birthday, gender, email, password) VALUES(?, ?, ?, ?, ?, ?)");
		echo $mysqli->error;
		//s - string ehk tekst
		//i - integer ehk täisarv
		//d - decimal, ujukomaarv
		$stmt->bind_param("sssiss", $signupFirstName, $signupFamilyName, $signupBirthDate, $gender, $signupEmail, $signupPassword);
		//stmt->execute();  - tuleb  enne vigu kontrollida
		if($stmt->execute()){
			echo "Läks väga hästi!";
		} else {
			echo "Tekkis viga: " .$stmt->error;
		}	
	}
	
	function saveIdea($idea, $color){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO vp1userideas (userid, idea, ideacolor) VALUES (?, ?, ?)");
		echo $mysqli->error;
		$stmt->bind_param("iss",$_SESSION["userId"], $idea, $color);
		if($stmt->execute()){
			$notice = "Mõte on salvestatud!";
		} else {
			$notice = "salvestamisel tekkis viga: " .$stmt->error;
		}
		
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	//sisestuse kontrollimine
	function test_input($data){
		$data = trim($data); //eemaldab lõpust tühiku, tab, vms
		$data = stripslashes($data); //eemaldab "\"
		$data = htmlspecialchars($data); //eemaldab keelatud märgid
		return $data;
	}	

	/*$x = 8;
	$y = 5;
	echo "esimene summa on: " .($x + $y);
	addValues();
	
	function addValues(){
		echo "Teine summa on: " .($GLOBALS["x"] + $GLOBALS["y"]);
		$a = 4;
		$b = 1;
		echo "Kolmas summa on: " .($a + $b);
	
	}
	
	echo "neljas summa on: " .($a + $b);*/


?>	