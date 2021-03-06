﻿
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
			echo "<button onclick=\"previousMove()\" type=\"button\"> Previous move </button>";
			echo "<button onclick=\"nextMove()\" type=\"button\"> Next move </button>";
			
		}
		else{
			$sql = "SELECT * FROM igra WHERE igra.tk_uporabnik1 = ".$_SESSION["id"]." || igra.tk_uporabnik2 = ".$_SESSION["id"]."";
			$result = mysqli_query($db,$sql);
			$stevec = 1;
			
			echo "<div align=\"center\" class=\"rook_description well well-sm\">Replays: </div><br>";
			echo "<div class=\"games\">";
			while($row = mysqli_fetch_assoc($result)){
				$sql = "SELECT count(stanja.tk_igra) FROM stanja WHERE stanja.tk_igra = ".$row["id"]."";
				$res = mysqli_query($db, $sql);
				$row1 =mysqli_fetch_assoc($res);
				
				//echo "<div class=\"game well well-sm\"><a href=\"?controller=uporabnik&action=zgodovina&game_id=".$row["id"]."\" class=\"btn btn-outlined btn-info\">Igra".$row["id"]."</a></div>";
				if($row["id"] == 21){
					echo "<div class=\"gametest well well-sm\">
					<a href=\"?controller=uporabnik&action=zgodovina&game_id=".$row["id"]."\" class=\"btn btn-outlined btn-info\">Igra".$row["id"]."<br> St potez: ".$row1["count(stanja.tk_igra)"]."</a>
					</div>";
				}
				else{
					echo "<div id=\""."game_".$row["id"]."\" class=\"gametest well well-sm\">
					<a href=\"?controller=uporabnik&action=zgodovina&game_id=".$row["id"]."\" class=\"btn btn-outlined btn-info\">Igra".$row["id"]."<br> St potez: ".$row1["count(stanja.tk_igra)"]."</a>
					<button onclick=\"deleteGame(".$row["id"].")\" style=\"margin-top:2px\" class=\"btn btn-danger\"><span class=\"glyphicon glyphicon-remove\"></span></button>
					</div>";
				}
				
				$list[] = $row["id"];
				$stevec++;
			}
			echo "</div>";
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
<style>
	.gametest{
		width:150px;
		height:150px;
		text-align:right;
		float:left;
		margin-left:5px;
		margin-top:5px; 
	}
	.btn-outlined {
		border-radius: 0;
		-webkit-transition: all 0.3s;
		   -moz-transition: all 0.3s;
				transition: all 0.3s;
	}
	
	.btn-outlined.btn-info {
		background: none;
		border: 3px solid #222222;
		color: black;
		height:100px;
		width:130px;
	}
	.btn-outlined.btn-info:hover,
	.btn-outlined.btn-info:active {
		color: #FFF;
		background: #222222;
		height:100px;
		width:130px;
	}
	
	.game{
		
	}
	
	.btn-danger{
		padding:6px 6px;
	}
</style>
<!--<div class="gametest well well-sm">
	<a href=\"#" class="btn btn-outlined btn-info">Igra</a>
	<button id="" style="margin-top:2px" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></button>
	 <i class="icon-user icon-white"></i>
</div>-->

<?php
	if(isset($_GET["game_id"])){
		echo "<a style=\"margin-top:2px\" class=\"btn btn-default\" href = \"http://164.8.230.124/sah/index.php?controller=uporabnik&action=zgodovina\" > Back </a>";
		echo "<script> generatePolje(list[stevec]); </script>";
	}
	
?>











































