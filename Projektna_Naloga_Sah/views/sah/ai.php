<script>
<?php 
	echo "var game_id = ".$id.";";
	$move = "w";
	echo "var player_turn = \"".$move."\";"; 
?>
var Click = 0;
var newData = false;
var poteza;
var check;
var sah;
$(document).ready(function(){
	
	$("#predaj").on("click", function(){
		$.ajax({
			type: "POST",
			url: "http://localhost/Projektno-delo-sah/Projektna_Naloga_Sah/index.php?controller=sah&action=endGame",
			data: {"game_id":game_id, "forfeit":1},
			success:function(data){
				//window.reload();
			},
			error:function(error){
				
			}
		})
	});
	
	getBoard();
	$("#sahovnica").on("click", "td", function() {
		if(poteza == player_turn){
			if(Click == 0){
				console.log(Click);
				var id = $(this).attr('id');
				figure = id.charAt(2);
				if(( player_turn == "w" && figure.toLowerCase() == figure ) || (player_turn == "b" && figure.toUpperCase() == figure) && figure != '0'){ //S tem if stavkom povem da lahko bel igralec premika samo bele figure(lowercase) crni igralec pa crne figure (uppercase)
					row1 = id.charAt(0);
					col1 = id.charAt(1);
					$(this).toggleClass("active1");
					Click++;
				}
				else{
					//Error niste izbrali figure
				}
			}
			else{
				if(Click == 1){
					console.log(Click);
					var id = $(this).attr('id');
					var figure1 = id.charAt(2);
					row2 = id.charAt(0);
					col2 = id.charAt(1);
					figure2 = id.charAt(2);
					Click = 0;
					if(row1 == row2 && col1 == col2){ //Double click na isto figuro jo odznaci
						generatePolje();
					}
					else{
						$(this).toggleClass("active2");
						console.log(" Row 1: " + row1 + " Col 1: " + col1 + " Row 2: " + row2 + " Col 2: " + col2 + " Figure: " + figure );
						$.ajax({
							type: "POST",
							url: "http://localhost/Projektno-delo-sah/Projektna_Naloga_Sah/index.php?controller=api&action=move",
							data: {"polje":sah,"row1":row1,"row2":row2,"col1":col1,"col2":col2,"figure":figure,"game_id":game_id,"poteza":poteza},
							success:function(data){
								if(!JSON.parse(data)){
									generatePolje();
								}
								else{
									getBoard();
								}
							},
							error:function(error){
								console.log(error);
							}
						})
					}
				}	
			}
		} //else {function AI()}
	});
	setInterval(function(){
		getBoard();
	}, 2000);
});

function getBoard(){
	$.ajax({
		type: "POST",
		url: "http://localhost/Projektno-delo-sah/Projektna_Naloga_Sah/index.php?controller=api&action=vrniStanjeIgre",
		data: {"game_id":game_id},
		success:function(data){
			console.log(data);
			var changed = JSON.parse(data).polje;
			if(JSON.parse(data).stanje_igre == "e"){
				var newLocation = "http://localhost/Projektno-delo-sah/Projektna_Naloga_Sah/index.php?controller=sah&action=endScreen&game_id="+game_id;
				window.location = newLocation;
			}
			
			poteza = JSON.parse(data).poteza;
			
			if(JSON.parse(data).check == 1) 
				document.getElementById("check").innerHTML = "Check!";
			else
				document.getElementById("check").innerHTML = "No check.";
			if(poteza == "w")
				document.getElementById("poteza").innerHTML = "Na potezi je beli igralec.";
			else
				document.getElementById("poteza").innerHTML = "Na potezi je crni igralec.";
						
			for(var x = 0; x < 8; x++){
				var isBreak = false;
				for(var y = 0; y < 8; y++){
					if(!sah || sah[x][y] != changed[x][y]){ //pregleda ce sah polje obstaja, in ali je kaksna sprememba
						sah = changed;
						generatePolje();
						isBreak = true;
						break;
					}
				}
				if(isBreak) break;
			}
		},
		error:function(error){
			console.log(error);
		}
	})
}
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
						sahovnica += "<td class=\""+player_turn+"\" id=\""+x+y+figureChar+"\" height=50px width=50px bgcolor=#ffce9e> </td>";   
					}
				}  
				else  
				{
					if(figureName != ""){
						sahovnica += "<td class=\""+player_turn+"\" id=\""+x+y+figureChar+"\" height=50px width=50px bgcolor=#d18b47> <img height=50px width=50px src=\"images/Chess_Figures/"+figureName+".png\" id=\"chess_board_images\"></td>"; 
					}
					else{
						sahovnica += "<td class=\""+player_turn+"\" id=\""+x+y+figureChar+"\" height=50px width=50px bgcolor=#d18b47></td>"; 
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
						sahovnica += "<td  id=\""+x+y+figureChar+"\" height=50px width=50px bgcolor=#ffce9e> </td>";   
					}
				}  
				else  
				{
					if(figureName != ""){
						sahovnica += "<td  id=\""+x+y+figureChar+"\" height=50px width=50px bgcolor=#d18b47> <img height=50px width=50px src=\"images/Chess_Figures/"+figureName+".png\" id=\"chess_board_images\"></td>"; 
					}
					else{
						sahovnica += "<td  id=\""+x+y+figureChar+"\" height=50px width=50px bgcolor=#d18b47></td>"; 
					}
				} 
			}
			 
		}	
	sahovnica += "</tr>";
	}
	document.getElementById("sahovnica").innerHTML = sahovnica;
	
}

</script>

<table class="sahovnica" id="sahovnica"> 

</table>
<div id="check" class="check">
	
</div>
<div id="poteza" class="poteza">
	
</div>

<button id="predaj" type="button" class="btn btn-default">Predaj se</button>



