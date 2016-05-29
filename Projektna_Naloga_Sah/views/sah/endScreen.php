<?php
	$db = Db::getInstance();
	$sql = "SELECT * FROM igra WHERE id=".$_GET["game_id"]."";
	$result = mysqli_query($db, $sql);
	$row = mysqli_fetch_assoc($result);
	
	if($_SESSION["id"] == $row["zmagovalec"])
		echo "Cestitam zmagal si! :D";
	else
		echo "Na žalost si izgubil. :(";
	
	
?>