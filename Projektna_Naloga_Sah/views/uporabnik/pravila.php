<script>
/*Starting position
Knight: "8/8/8/8/3N4/8/8/8";
//All possible positions
8/8/8/5N2/8/8/8/8
8/8/8/8/8/5N2/8/8
8/8/8/8/8/8/4N3/8
8/8/4N3/8/8/8/8/8
8/8/8/8/8/8/2N5/8
8/8/2N5/8/8/8/8/8
8/8/8/8/8/1N6/8/8
8/8/8/1N6/8/8/8/8
//Start:
King: "8/8/8/8/3K4/8/8/8";
//All
8/8/8/3K4/8/8/8/8
8/8/8/4K3/8/8/8/8
8/8/8/8/4K3/8/8/8
8/8/8/8/8/4K3/8/8
8/8/8/8/8/3K4/8/8
8/8/8/8/8/2K5/8/8
8/8/8/8/2K5/8/8/8
8/8/8/2K5/8/8/8/8
8/8/8/3K4/8/8/8/8


*/
	<?php
		
	?>
	$(document).ready(function(){
		$.ajax(){
		
		}
		var stevec = 1;
		$(".next").click(function(){
			if(stevec == 6){
				//stevec = 1;
			}
			else{
				$("#"+stevec).slideToggle("slow");
				stevec++;
				$("#"+stevec).slideToggle("slow");
			}
			
		});
		
		$(".previous").click(function(){
			if(stevec == 1){
				//stevec = 6;
			}
			else{
				$("#"+stevec).slideToggle("slow");
				stevec--;
				$("#"+stevec).slideToggle("slow");
			}
			
		});
	});
	
	function generatePolje(){	
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
					sahovnica += "<td class=\""+player_turn+"\" id=\""+x+y+figureChar+"\" bgcolor=#FFFFFF> <img height=50px width=50px src=\"images/Chess_Figures/"+figureName+".png\" id=\"chess_board_images\"></td>"; 
				}
				else {
					sahovnica += "<td class=\""+player_turn+"\" id=\""+x+y+figureChar+"\" height=50px width=50px bgcolor=#FFFFFF> </td>";   
				}
			}  
			else  
			{
				if(figureName != ""){
					sahovnica += "<td class=\""+player_turn+"\" id=\""+x+y+figureChar+"\" height=50px width=50px bgcolor=#D3D3D3> <img height=50px width=50px src=\"images/Chess_Figures/"+figureName+".png\" id=\"chess_board_images\"></td>"; 
				}
				else{
					sahovnica += "<td class=\""+player_turn+"\" id=\""+x+y+figureChar+"\" height=50px width=50px bgcolor=#D3D3D3></td>"; 
				}
			} 
		}	
	sahovnica += "</tr>";
	}
	document.getElementById("sahovnica").innerHTML = sahovnica;
}
	
</script>
<style>
.poteze{
}
.align{
	padding-left:10px;
	text-align:center;
}
img{
}
p_rook{

}
</style>
<div class="poteze">
	<div id="1" class="rook align">
		<div class="rook_description"> Poteze ki jih lahko naredi trdnjava. </div>
		<div id="rook_Anim"><img height="300px" width="300px" src="images/Chess_Moves/possibleRookMoves.jpg" />
		<img height="300px" width="300px" src="images/Chess_Moves/possibleRookMoves.jpg" /></div>
		<button type="button" id="p_rook" class="p_rook"> Play </button>
	</div> 
	<div id="2" class="bishop align" style="display:none">
	<div class="bishop_description"> Poteze ki jih lahko naredi tekač. </div>
		<div id="bishop_Anim"><img height="300px" width="300px" src="images/Chess_Moves/possibleBishopMoves.jpg" />
		<img height="300px" width="300px" src="images/Chess_Moves/possibleBishopMoves.jpg" /></div>
		<button type="button" id="p_bishop"> Play </button>
	</div> 
	<div id="3" class="knight align" style="display:none">
	<div class="knight_description"> Poteze ki jih lahko naredi konj. </div>
		<div id="knight_Anim"><img height="300px" width="300px" src="images/Chess_Moves/possibleKnightMoves.jpg" />
		<img height="300px" width="300px" src="images/Chess_Moves/possibleKnightMoves.jpg" /></div>
		<button type="button" id="p_knight"> Play </button>
	</div> 
	<div id="4" class="queen align" style="display:none">
		<div class="queen_description"> Poteze ki jih lahko naredi kraljica. </div>
		<div id="queen_Anim"><img height="300px" width="300px" src="images/Chess_Moves/possibleQueenMoves.jpg" />
		<img height="300px" width="300px" src="images/Chess_Moves/possibleQueenMoves.jpg" /></div>
		<button type="button" id="p_queen"> Play </button>
	</div> 
	<div id="5" class="king align" style="display:none">
		<div class="king_description"> Poteze ki jih lahko naredi kralj. </div>
		<div id="king_Anim"><img height="300px" width="300px" src="images/Chess_Moves/possibleKingMoves.jpg" />
		<img height="300px" width="300px" src="images/Chess_Moves/possibleKingMoves.jpg" /></div>
		<button type="button" id="p_king"> Play </button>
	</div> 
	<div id="6" class="pawn align" style="display:none">
		<div class="pawn_description"> Poteze ki jih lahko naredi kmet. </div>
		<div id="pawn_Anim"><img height="300px" width="300px" src="images/Chess_Moves/possiblePawnMoves.jpg" />
		<img height="300px" width="300px" src="images/Chess_Moves/possiblePawnMoves.jpg" /></div>
		<button type="button" id="p_pawn"> Play </button>
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















