<!DOCTYPE html>  
     <html>   
     <head>   
  <title></title>  
  </head>  
  <body>   
  <table border="1px" >  
  
<?php  
	$sah = array
		(
			array("r","n","b","q","k","b","n","r"),
			array("p","p","p","p","p","p","p","p"),
			array("0","0","0","0","0","0","0","0"),
			array("0","0","0","0","0","0","0","0"),
			array("0","0","0","0","0","0","0","0"),
			array("0","0","0","0","0","0","0","0"),
			array("P","P","P","P","P","P","P","P"),
			array("R","N","B","Q","K","B","N","R")
		);
    for($row=0;$row<8;$row++)  
    {   
        echo "<tr>";  
        for($col=0;$col<8;$col++)  
        {  
			$total=$row+$col;  
			if($total%2==0)  
			{  
				echo "<td height=50px width=50px bgcolor=#FFFFFF>".$sah[$row][$col]."</td>";  
			}  
			else  
			{  
				echo "<td height=50px width=50px bgcolor=#D3D3D3>".$sah[$row][$col]."</td>";  
			}  
        }  
        echo "</tr>";  
    } 
	
?>  
  </table> 
   
  <?php
	function toFEN($sah){	
		$fen = "";
		
		//Za enodimazionalno 
		/*for($x = 0; $x < 8; $x++)
		{
			$arr = "";
			for($y = 0; $y < 8; $y++)
			{
				$znak = $sah[8 * $x + $y];
				$arr = $arr.$znak;
				if($y % 7 == 0 && $y != 0 && $x < 7)
				{
					$arr .= "/";
				}
			}
			$fen .= $arr;
		}	*/
		//Za dvominezionalno
		for($i=0; $i<8; $i++)
		{		
			for($j=0; $j<8; $j++)
			{
				$arr = implode("", $sah[$i]);
			}
			$fen .= $arr;
			if($i<7)
			{
				$fen .= "/";
			}
		}
		
		$counter = 0;
		
		$arr = array();
		 for($c = 0; $c < strlen($fen); $c++)
		{
			if($fen[$c] != "0")
			{
				$counter = 0;
			}
			else{
				while($fen[$c]=="0" )
				{
					$counter++;
					$fen[$c] = $counter;
					 if($counter >= 2)
					{
						$fen[$c-1] = ".";
					}
				}
			}
		}
		$q=0;
		for($i = 0; $i < strlen($fen); $i++)
		{
			if($fen[$i] != ".")
			{
				$arr[$q] = $fen[$i];
				$q++;
			}
		}
		return $arr;
	}

	function toTable($fen){
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
	
	echo "<br><br>";
	echo "funkcija toFen()";
	echo "<br>";
	$proba = toFEN($sah);
	for($m = 0; $m < sizeof($proba); $m++)
	{
		echo $proba[$m];
	} 
	echo "<br><br>";
	$proba2 = array();
	$proba2 = toTable($proba);
		
	echo '<table border="1px" >' ;
	for($row=0;$row<sizeof($proba2);$row++)  
    {   
        echo "<tr>";  
        for($col=0;$col<sizeof($proba2);$col++)  
        {  
			$total=$row+$col;  
			if($total%2==0)  
			{  
				echo "<td height=50px width=50px bgcolor=#FFFFFF>".(string)$proba2[$row][$col]."</td>";  
			}  
			 else  
			{  
				echo "<td height=50px width=50px bgcolor=#D3D3D3>".(string)$proba2[$row][$col]."</td>";  
			}   
        }  
        echo "</tr>";  
    }
	echo '</table>';  
	
  ?>
  <p>
	
  </p>
  </body>  
  </html>  
</html> 