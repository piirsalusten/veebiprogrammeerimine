<?php
	$database = "if17_sten";
	require("../../../config.php");
	
	function getSingleIdea($editId){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT idea, ideacolor FROM vp1userideas WHERE id=?");
		echo $mysqli->error;
		$stmt->bind_param("i", $editId);
		$stmt->bind_result($idea, $ideaColor);
		$stmt->execute();
		$ideaObject = new stdclass();
		if($stmt->fetch()){
			$ideaObject->text = $idea;
			$ideaObject->color = $ideaColor;
		} else {
			//sellist mõtet polegi
			$stmt->close();
			$mysqli->close();
			header("Location: usersideas.php");
			exit();
		}
		$stmt->close();
		$mysqli->close();
		return $ideaObject;
	}

	function updateIdea($id, $idea, $ideaColor){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("UPDATE vp1userideas SET idea=?, ideacolor=? WHERE id=?");
		echo $mysqli->error;
		$stmt->bind_param("ssi", $idea, $ideaColor, $id);
		$stmt->execute();
	
	
		$stmt->close();
		$mysqli->close();
	}

	function deleteIdea($id){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("UPDATE vp1userideas SET deleted = NOW() WHERE id=?");
		echo $mysqli->error;
		$stmt->bind_param("i", $id);
		$stmt->execute();
		echo $stmt->error;
		
		$stmt->close();
		$mysqli->close();
		
	}
		
?>