<script>
var player1 = Math.floor((Math.random()* 2)); // 0 -> beli, 1 -> crni
var sah;
if(player1 == 0){
	player2 = 1;
	sah = new Array(
	"r","n","b","q","k","b","n","r",
	"p","p","p","p","p","p","p","p",
	"0","0","0","0","0","0","0","0",
	"0","0","0","0","0","0","0","0",
	"0","0","0","0","0","0","0","0",
	"0","0","0","0","0","0","0","0",
	"P","P","P","P","P","P","P","P",
	"R","N","B","Q","K","B","N","R"
	);
}
else{
	player2 = 0;
	sah = new Array(
	"R","N","B","Q","K","B","N","R",
	"P","P","P","P","P","P","P","P",
	"0","0","0","0","0","0","0","0",
	"0","0","0","0","0","0","0","0",
	"0","0","0","0","0","0","0","0",
	"0","0","0","0","0","0","0","0",
	"p","p","p","p","p","p","p","p",
	"r","n","b","q","k","b","n","r"
	);
}
var Click = 0;
var newData = false;
var whiteMove = true;
var blackMove = false;

$(document).ready(function(){
	$("#sahovnica").on("click", "td", function() {
		if(Click == 0){
			console.log(Click);
			var id = $(this).attr('id');
			figure = id.charAt(2);
			if(figure != '0'){
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
						url: "http://localhost/Projektna_Naloga_Sah_01/index.php?controller=api&action=move",
						data: {"polje":sah,"row1":row1,"row2":row2,"col1":col1,"col2":col2,"figure":figure},
						success:function(data){
							console.log(data);
							sah = JSON.parse(data);
							console.log(data);
							generatePolje();
						},
						error:function(error){
							console.log(error);
						}
					})
				}
			}	
		}
	});
	generatePolje();
	/*setInterval(function(){
		if(newData){
			generatePolje();
			newData = false;
		}
	}, 2000);*/
});

function generatePolje(){	
	
	var sahovnica = "";
	var figureName = "";
	var figureChar = "";
	for(x = 0; x < 8; x++){ //row
	sahovnica += "<tr>";
		for(y = 0; y < 8; y++){ //col
			var total=x+y;
			figureChar = sah[8*x+y];
			figureName = sah[8*x+y];
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
					sahovnica += "<td  id=\""+x+y+figureChar+"\" bgcolor=#FFFFFF> <img height=50px width=50px src=\"images/Chess_Figures/"+figureName+".png\" id=\"chess_board_images\"></td>"; 
				}
				else {
					sahovnica += "<td  id=\""+x+y+figureChar+"\" height=50px width=50px bgcolor=#FFFFFF> </td>";   
				}
			}  
			else  
			{
				if(figureName != ""){
					sahovnica += "<td  id=\""+x+y+figureChar+"\" height=50px width=50px bgcolor=#D3D3D3> <img height=50px width=50px src=\"images/Chess_Figures/"+figureName+".png\" id=\"chess_board_images\"></td>"; 
				}
				else{
					sahovnica += "<td  id=\""+x+y+figureChar+"\" height=50px width=50px bgcolor=#D3D3D3></td>"; 
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

<!-- Ko igras proti uporabniku se sahovnica obrne. Torej vsi premiki se gledajo enako. Kar pomeni, da črni igralec kmete premika gor, ne dol, ampak beli igralec vidi da se kmeti premaknejo dol. -->