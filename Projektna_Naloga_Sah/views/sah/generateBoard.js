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
			if(player_turn == "w"){
				//To je za rotacijo
				$("#sahovnica").removeClass("b");
				$("#sahovnica").addClass("w");
				if(total%2==0)  
				{  
					if(figureName != ""){
						sahovnica += "<td class=\""+player_turn+"\" id=\""+x+y+figureChar+"\" bgcolor=#ffce9e> <img height=50px width=50px src=\"images/Chess_Figures/"+figureName+".png\" id=\"chess_board_images\"></td>"; 
					}
					else {
						sahovnica += "<td class=\""+player_turn+"\" id=\""+x+y+figureChar+"\" height=60px width=60px bgcolor=#ffce9e> </td>";   
					}
				}  
				else  
				{
					if(figureName != ""){
						sahovnica += "<td class=\""+player_turn+"\" id=\""+x+y+figureChar+"\" height=60px width=60px bgcolor=#d18b47> <img height=50px width=50px src=\"images/Chess_Figures/"+figureName+".png\" id=\"chess_board_images\"></td>"; 
					}
					else{
						sahovnica += "<td class=\""+player_turn+"\" id=\""+x+y+figureChar+"\" height=60px width=60px bgcolor=#d18b47></td>"; 
					}
				} 
			}
			else{
				$("#sahovnica").removeClass("w");
				$("#sahovnica").addClass("b");
				if(total%2==0)  
				{  
					if(figureName != ""){
						sahovnica += "<td  id=\""+x+y+figureChar+"\" bgcolor=#ffce9e> <img height=50px width=50px src=\"images/Chess_Figures/"+figureName+".png\" id=\"chess_board_images\"></td>"; 
					}
					else {
						sahovnica += "<td  id=\""+x+y+figureChar+"\" height=60px width=60px bgcolor=#ffce9e> </td>";   
					}
				}  
				else  
				{
					if(figureName != ""){
						sahovnica += "<td  id=\""+x+y+figureChar+"\" height=60px width=60px bgcolor=#d18b47> <img height=50px width=50px src=\"images/Chess_Figures/"+figureName+".png\" id=\"chess_board_images\"></td>"; 
					}
					else{
						sahovnica += "<td  id=\""+x+y+figureChar+"\" height=60px width=60px bgcolor=#d18b47></td>"; 
					}
				} 
			}
			 
		}	
	sahovnica += "</tr>";
	}
	document.getElementById("sahovnica").innerHTML = sahovnica;
	
}