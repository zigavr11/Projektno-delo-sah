
<script>
<?php
	$db = Db::getInstance();
	$sql = "SELECT stanje FROM pravila";
	$result = mysqli_query($db,$sql);
	while($row = mysqli_fetch_assoc($result)){
		$list_potez[] = toTable($row["stanje"]);
	}
	echo "var list = ".json_encode($list_potez).";";
	
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

	var slika = 0;
	var stevec;
	$(document).ready(function(){		
		var id;
		stevec = slika * 2;
		$(".next").click(function(){
			if(slika == 5){
			}
			else{
				$("#"+slika).slideToggle("slow");
				slika++;
				$("#"+slika).slideToggle("slow");
				stevec = slika * 2;
			}
			generatePolje(list[stevec]);
		});
		
		$(".previous").click(function(){
			if(slika == 0){
			}
			else{
				$("#"+slika).slideToggle("slow");
				slika--;
				$("#"+slika).slideToggle("slow");
				stevec = slika * 2;
			}
			generatePolje(list[stevec]);
		});
		
		generatePolje(list[stevec]);
	});
	
	

	function generatePolje(sah){
		var sahovnica = "<table>";
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
						sahovnica += "<td id=\""+x+y+figureChar+"\" bgcolor=#ffce9e> <img height=37px width=37px src=\"images/Chess_Figures/"+figureName+".png\" id=\"chess_board_images\"></td>"; 
					}
					else {
						sahovnica += "<td id=\""+x+y+figureChar+"\" height=37px width=37px bgcolor=#ffce9e> </td>";   
					}
				}  
				else  
				{
					if(figureName != ""){
						sahovnica += "<td id=\""+x+y+figureChar+"\" height=37px width=37px bgcolor=#d18b47> <img height=37px width=37px src=\"images/Chess_Figures/"+figureName+".png\" id=\"chess_board_images\"></td>"; 
					}
					else{
						sahovnica += "<td id=\""+x+y+figureChar+"\" height=37px width=37px bgcolor=#d18b47></td>"; 
					}
				} 
				 
			}	
		sahovnica += "</tr>";
		}
		sahovnica += "</table>";
		document.getElementById($("."+slika).attr("id")+"_Anim").innerHTML = sahovnica;
		
	}
	
	function startReplay(){
		id = setInterval(function(){
			if(stevec > slika * 2 + 1){
				stevec = slika * 2;
			}
			else{
				generatePolje(list[stevec]);
				stevec++;
			}
		}, 1000);
	}
	
	function stopReplay(){
		clearInterval(id);
	}
	
</script>
<style>
.poteze{
}
.align{
	padding-left:10px;
	text-align:center;
}
.layout{
	display: -webkit-inline-box;
}
.animation{
	margin-left:2px;
}
img{
	margin-top:5px;
}
</style>
<div class="poteze">

	<div id="0" class="rook align">
		<div class="rook_description"> Poteze ki jih lahko naredi trdnjava. </div>
		<div id="rook" class="0 layout">
			<img height="300px" width="300px" src="images/Chess_Moves/possibleRookMoves.jpg" />
			<div id="rook_Anim" class ="animation"> </div>
		</div>
		<button type="button" id="p_rook" onclick="startReplay()" class="p_rook"> Play </button>
		<button type="button" onclick="stopReplay()" id="p_pawn"> Stop </button>
	</div> 
	
	<div id="1" class="bishop align" style="display:none">
		<div class="bishop_description"> Poteze ki jih lahko naredi tekač. </div>
		<div id="bishop" class="1 layout">
			<img height="300px" width="300px" src="images/Chess_Moves/possibleBishopMoves.jpg" />
			<div id="bishop_Anim" class ="animation"> </div>
		</div>
		<button type="button" onclick="startReplay()" id="p_bishop"> Play </button>
		<button type="button" onclick="stopReplay()" id="p_pawn"> Stop </button>
	</div> 
	
	<div id="2" class="knight align" style="display:none">
		<div class="knight_description"> Poteze ki jih lahko naredi konj. </div>
		<div id="knight" class="2 layout">
			<img height="300px" width="300px" src="images/Chess_Moves/possibleKnightMoves.jpg" />
			<div id="knight_Anim" class ="animation"> </div>
		</div>
		<button type="button" onclick="startReplay()" id="p_knight"> Play </button>
		<button type="button" onclick="stopReplay()" id="p_pawn"> Stop </button>
	</div> 
	
	<div id="3" class="queen align" style="display:none">
		<div class="queen_description"> Poteze ki jih lahko naredi kraljica. </div>
		<div id="queen" class="3 layout">
			<img height="300px" width="300px" src="images/Chess_Moves/possibleQueenMoves.jpg" />
			<div id="queen_Anim" class ="animation"> </div>
		</div>
		<button type="button" onclick="startReplay()" id="p_queen"> Play </button>
		<button type="button" onclick="stopReplay()" id="p_pawn"> Stop </button>
	</div> 
	
	<div id="4" class="king align" style="display:none">
		<div class="king_description"> Poteze ki jih lahko naredi kralj. </div>
		<div id="king" class="4 layout">
			<img height="300px" width="300px" src="images/Chess_Moves/possibleKingMoves.jpg" />
			<div id="king_Anim" class ="animation"> </div>
		</div>
		<button type="button" onclick="startReplay()" id="p_king"> Play </button>
		<button type="button" onclick="stopReplay()" id="p_pawn"> Stop </button>
	</div> 
	
	<div id="5" class="pawn align" style="display:none">
		<div class="pawn_description"> Poteze ki jih lahko naredi kmet. </div>
		<div id="pawn" class="5 layout">
			<img height="300px" width="300px" src="images/Chess_Moves/possiblePawnMoves.jpg" />
			<div id="pawn_Anim" class ="animation"> </div>
		</div>
		<button type="button" onclick="startReplay()" id="p_pawn"> Play </button>
		<button type="button" onclick="stopReplay()" id="p_pawn"> Stop </button>
	</div> 
	
	<div class="row">
		<div class="col-md-6 text-center">
			<button class="previous"> Previous</button>
		</div>
		<div class="col-md-6 text-center">
			<button class="next" > Next</button>
		</div>
	</div>
	
</div>















