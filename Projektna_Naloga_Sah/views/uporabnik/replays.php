<script>
var stevec = 0;
var id;

function generatePolje(sah){	
	var sahovnica = "";
	var figureName = "";
	var figureChar = "";
	for(x = 0; x < 8; x++){ //row
	sahovnica += "<tr>";
		for(y = 0; y < 8; y++){ //col
			var total=x+y;
			figureChar = sah[x][y];
			figureName = sah[x][y];
			switch(figureName){
				case "r": figureName="W_R"; break;
				case "n": figureName="W_N"; break;
				case "b": figureName="W_B"; break;
				case "q": figureName="W_Q"; break;
				case "k": figureName="W_K"; break;
				case "p": figureName="W_P"; break;
				case "R": figureName="B_R"; break;
				case "N": figureName="B_N"; break;
				case "B": figureName="B_B"; break;
				case "Q": figureName="B_Q"; break;
				case "K": figureName="B_K"; break;
				case "P": figureName="B_P"; break;
				case "0": figureName=""; break;
			}
			if(total%2==0)  
				{  
					if(figureName != ""){
						sahovnica += "<td id=\""+x+y+figureChar+"\" bgcolor=#ffce9e> <img height=50px width=50px src=\"images/Chess_Figures/"+figureName+".png\" id=\"chess_board_images\"></td>"; 
					}
					else {
						sahovnica += "<td id=\""+x+y+figureChar+"\" height=50px width=50px bgcolor=#ffce9e> </td>";   
					}
				}  
				else  
				{
					if(figureName != ""){
						sahovnica += "<td id=\""+x+y+figureChar+"\" height=50px width=50px bgcolor=#d18b47> <img height=50px width=50px src=\"images/Chess_Figures/"+figureName+".png\" id=\"chess_board_images\"></td>"; 
					}
					else{
						sahovnica += "<td id=\""+x+y+figureChar+"\" height=50px width=50px bgcolor=#d18b47></td>"; 
					}
				} 
			 
		}	
	sahovnica += "</tr>";
	}
	document.getElementById("sahovnica").innerHTML = sahovnica;
	
}
function startReplay(list){
	if(stevec == list.length){
		stevec = 0;
	}
	generatePolje(list[stevec++]);
	id = setInterval(function(){
		if(stevec == list.length){
			clearInterval(id);
		}
		else{
			generatePolje(list[stevec]);
			stevec++;
		}
	}, 250);
}

function stopReplay(){
	clearInterval(id);
}

function nextMove(){
	stopReplay();
	if(stevec < list.length){
		generatePolje(list[stevec]);
		stevec++;
	}
}

function previousMove(){
	stopReplay();
	if(stevec > 0){
		stevec--;
		generatePolje(list[stevec]);
	}
}

</script>

<?php
	if(isset($_SESSION["id"])){
		$db = Db::getInstance();
		if(isset($_GET["game_id"])){
			$sql = "SELECT * FROM stanja WHERE stanja.tk_igra = ".$_GET["game_id"]."";
			$result = mysqli_query($db,$sql);
			echo "Replay ".$_GET["game_id"].": <br>";
			while($row = mysqli_fetch_assoc($result)){
				$list_potez[] = toTable($row["stanje"]);
				
			}
			echo "<script> var list = ".json_encode($list_potez).";</script>";
			echo "<button onclick=\"startReplay(list)\" type=\"button\"> Play replay </button>";
			echo "<button onclick=\"stopReplay()\" type=\"button\"> Stop replay </button>";
			echo "<button onclick=\"nextMove()\" type=\"button\"> Next move </button>";
			echo "<button onclick=\"previousMove()\" type=\"button\"> Previous move </button>";
		}
		else{
			$sql = "SELECT * FROM igra WHERE igra.tk_uporabnik1 = ".$_SESSION["id"]." || igra.tk_uporabnik2 = ".$_SESSION["id"]."";
			$result = mysqli_query($db,$sql);
			$stevec = 1;
			echo "Replays: <br>";
			while($row = mysqli_fetch_assoc($result)){
				echo "<a href=\"?controller=uporabnik&action=zgodovina&game_id=".$row["id"]."\">Igra".$row["id"]."</a><br>";
				$list[] = $row["id"];
				$stevec++;
			}
		}
	}
	else{
		echo "<div class=\"well well-sm\">Za ogled iger se morate vpisati.</div>";
	}
	
	
	function toTable($arg){
		$fen = array();
		$fen = str_split ( $arg );
		$str = implode("",$fen);
		$table = ["", "", "", "", "", "", "", ""];
		$idx = 0;

		for($i = 0; $i < strlen($str); $i++)
		{
			if($str[$i] == "/")
			{
			$idx++;
			}
			else if (ord($str[$i]) >= 48 && ord($str[$i]) <= 57)
			{
				$int = intval($str[$i]);
				for ($j = 0; $j < $int; $j++) $table[$idx] .= "0";
			}
			else
				$table[$idx] .= $str[$i];
			}
		return $table;
	}
?>

<table class="sahovnica" id="sahovnica"> 

</table>

<?php
	if(isset($_GET["game_id"])){
		echo "<a href = \"http://localhost/Projektno-delo-sah/Projektna_Naloga_Sah/index.php?controller=uporabnik&action=zgodovina\" > Back </a>";
	}
	
?>













































