<script src="views/sah/generateBoard.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script>
<?php 
	echo "var game_id = ".$id.";";
	$move = "w";
	echo "var player_turn = \"".$move."\";"; 
?>
var Click = 0;
var poteza;
var check;
var kingPosition;
var sah;

var stevec = 0;
var id;
$(document).ready(function(){
	
	$("#predaj").on("click", function(){
		$.ajax({
			type: "POST",
			url: "http://164.8.230.124/sah/index.php?controller=sah&action=endGame",
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
				if(( player_turn == "w" && figure.toLowerCase() == figure ) || (player_turn == "b" && figure.toUpperCase() == figure) && figure != '0'){
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
							url: "http://164.8.230.124/sah/index.php?controller=api&action=move",
							data: {"polje":sah,"row1":row1,"row2":row2,"col1":col1,"col2":col2,"figure":figure,"game_id":game_id,"poteza":poteza,"ai":1},
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
		}
	});
	id = setInterval(function(){
		getBoard();
	}, 500);
});

function getBoard(){
	$.ajax({
		type: "POST",
		url: "http://164.8.230.124/sah/index.php?controller=api&action=vrniStanjeIgre",
		data: {"game_id":game_id},
		success:function(data){
			console.log(data);
			var changed = JSON.parse(data).polje;
			if(JSON.parse(data).stanje_igre == "e"){
				var newLocation = "http://164.8.230.124/sah/index.php?controller=sah&action=endScreen&game_id="+game_id;
				clearInterval(id);
				alert("Konec igre.");
			}
			
			poteza = JSON.parse(data).poteza;
			mate = JSON.parse(data).check_mate;
			if(JSON.parse(data).check == 1) 
				document.getElementById("check").innerHTML = "Check!";
			else
				document.getElementById("check").innerHTML = "No check.";
			if(poteza == "w")
				document.getElementById("poteza").innerHTML = "Na potezi je beli igralec.";
			else
				document.getElementById("poteza").innerHTML = "Na potezi je crni igralec.";
			if(mate){
				document.getElementById("mate").innerHTML = "Check mate!";
			}
			else{
				document.getElementById("mate").innerHTML = "No check mate.";
			}			
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

function Undo(){
	$.ajax({
		type: "POST",
		url: "http://164.8.230.124/sah/index.php?controller=sah&action=undo",
		data: {"game_id":game_id},
		success:function(data){
			getBoard();
		},
		error:function(error){
			
		}
	})
}
</script>

<table class="sahovnica" id="sahovnica"> 

</table>
<button onclick="Undo()" type="button"> Undo </button>
<div id="check" class="check">
	
</div>
<div id="poteza" class="poteza">
	
</div>
<div id="mate" class="mate">
	
</div>

<button id="predaj" type="button" class="btn btn-default">Predaj se</button>



