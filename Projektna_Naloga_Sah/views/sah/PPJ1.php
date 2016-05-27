<script>$(document).ready(function(){
	$("td").click(function(){
			var id = $(this).attr('id');
			var row = id.charAt(0);
			var col = id.charAt(1);
			
			$(this).toggleClass("active");
		});
	});
</script>
<?php
	echo "<br>";
		$sah = array
		(
			"r","n","b","q","k","b","n","r","p","p","p","p","p","p","p","p","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","P","P","P","P","P","P","P","P","R","N","B","Q","K","B","N","R"
		);
		echo '<table id="rowClick"" border="1px">';
		$figureName =  "";
		for($row=0;$row<8;$row++) 
		{    
			echo "<tr>";
			for($col=0;$col<8;$col++) 
			{  
				$figureChar = (string)$sah[8*$row+$col];
				switch($figureChar){
					case "r": $figureName="W_R"; break;
					case "n": $figureName="W_N"; break;
					case "b": $figureName="W_B"; break;
					case "q": $figureName="W_Q"; break;
					case "k": $figureName="W_K"; break;
					case "p": $figureName="W_P"; break;
					case "R": $figureName="B_R"; break;
					case "N": $figureName="B_N"; break;
					case "B": $figureName="B_B"; break;
					case "Q": $figureName="B_Q"; break;
					case "K": $figureName="B_K"; break;
					case "P": $figureName="B_P"; break;
					case "0": $figureName=""; break;
				}
				$total=8*$row+$col;
				if($total%2==0)  
				{  
					if($figureName != ""){
						echo "<td id=\"$row$col\" bgcolor=#FFFFFF> <img height=50px width=50px src=\"images/Chess_Figures/$figureName.png\" id=\"chess_board_images\"></td>"; 
					}
					else {
						echo "<td id=\"$row$col\" height=50px width=50px bgcolor=#FFFFFF> </td>";   
					}
				}  
				 else  
				{
					if($figureName != ""){
						echo "<td id=\"$row$col\" height=50px width=50px bgcolor=#D3D3D3> <img height=50px width=50px src=\"images/Chess_Figures/$figureName.png\" id=\"chess_board_images\"></td>"; 
					}
					else{
						echo "<td id=\"$row$col\" height=50px width=50px bgcolor=#D3D3D3></td>"; 
					}
					 
				}   
			}  
			echo "</tr>";  
		}
	echo '</table>';  
?>