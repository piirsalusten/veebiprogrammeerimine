function listIdeas(){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT idea, ideacolor FROM vp1userideas");
		echo $mysqli->error;
		$stmt->bind_result($idea, $color);
		$stmt->execute();
		
		while($stmt->fetch()){
			//<p> style="background-color: #ff5577;">Hea mõte</p>
			$notice .= '<p style="background-color: ' .$color .'">' .$idea ."</p> \n";
		}
		
		$stmt->close();
		$mysqli->close();
		return $notice;