<?php
class Sah {
	public $polje;
	public $poteza;
	public $check;
	public $friendly_check;
	public $stanje_igre;
	
	public function __construct($polje) {
		$this->polje = $polje;
	}
	
	public static function generateNewBoard(){
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
			
		return $sah;
	}
	
	//Sem posljemo dvodimenzionalno polje -> Vrne fen string
	function toFEN($sah){
		$fen = "";
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
		for($c = 0; $c < strlen($fen); $c++){
			if($fen[$c] != "0"){
				$counter = 0;
			}
			else{
				while($fen[$c]=="0" ){
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
		for($i = 0; $i < strlen($fen); $i++){
			if($fen[$i] != ".")
			{
				$arr[$q] = $fen[$i];
				$q++;
			}
		}
		$rezultat = "";
		for($m = 0; $m < sizeof($arr); $m++)
		{
			$rezultat .= $arr[$m];
		} 
		return $rezultat;
	}
	
	//Posljemo fen string -> Vrne dvodimenzionalno polje
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
	
	public static function newGameVsAi(){
		$db = Db::getInstance();
		$uporabnik2 = -1;
		$sql = "INSERT INTO igra(tk_uporabnik1, tk_uporabnik2, zmagovalec, tip, stanje) VALUES(".$_SESSION["id"].", $uporabnik2, 0, \"a\", \"l\")"; //stanje v tem primeru pomeni ali je igra koncana ali ne
		
		mysqli_query($db , $sql);
		$id = mysqli_insert_id($db);
		$fen_string = Sah::toFEN(Sah::generateNewBoard());
		
		$sql = "INSERT INTO stanja(stanje, poteza, tk_igra) VALUES(\"$fen_string\", \"w\", $id)";
		mysqli_query($db,$sql);
		
		$polje = Sah::toTable($fen_string);
		return $id;
	}
	
	public static function newGameVsFriend($friend_id){
		$_SESSION["barva"] = "w";
		$_SESSION["id"];
		$db = Db::getInstance();
		$sql = "INSERT INTO igra(tk_uporabnik1, tk_uporabnik2, zmagovalec, tip, stanje) VALUES(".$_SESSION["id"].", $friend_id, 0, \"f\", \"l\")"; //stanje v tem primeru pomeni ali je igra koncana ali se igra
		
		mysqli_query($db , $sql);
		$id = mysqli_insert_id($db);
		$fen_string = Sah::toFEN(Sah::generateNewBoard());
		
		$sql = "INSERT INTO stanja(stanje, poteza, tk_igra) VALUES(\"$fen_string\", \"w\", $id)";
		mysqli_query($db,$sql);
		
		$polje = Sah::toTable($fen_string);
		return $id;
	}
	
	public static function newGameVsOpponent(){
	
	}
	
	public static function endGame($game_id, $forfeit){
		$db = Db::getInstance();
		if($forfeit == 1){
			$sql = "SELECT * FROM igra WHERE id=$game_id";
			$result = mysqli_query($db, $sql);
			$row = mysqli_fetch_assoc($result);
			$zmagovalec = $row["tk_uporabnik1"];
			if($row["tk_uporabnik1"] == $_SESSION["id"]){
				$zmagovalec = $row["tk_uporabnik2"];
			}
			$sql = "UPDATE igra SET zmagovalec = $zmagovalec , stanje = \"e\" WHERE id=$game_id";
			mysqli_query($db, $sql);
		}
		else{
			$sql = "UPDATE igra SET zmagovalec = ".$_SESSION["id"]." , stanje = \"e\" WHERE id=$game_id";
			mysqli_query($db, $sql);
		}
	}
	
	public static function returnGameState($game_id){
		$db = Db::getInstance();
		$sql = "SELECT i.id as game_id, i.stanje as stanje_igre, s.* FROM igra i, stanja s WHERE tk_igra = $game_id AND i.id = tk_igra ORDER BY s.id DESC LIMIT 1";
		$result = mysqli_query($db , $sql);
		$row = mysqli_fetch_assoc($result);
		
		$polje = Sah::toTable($row["stanje"]);
		$sahObj = new Sah($polje);
		$sahObj->poteza = $row["poteza"];
		$sahObj->check = $row["sah"];
		$sahObj->stanje_igre = $row["stanje_igre"];
		return $sahObj;
	}
	
	public static function returnActiveGames(){
		$list = [];
		$db = Db::getInstance();
		$sql = "SELECT i.id, u.uporabnisko_ime FROM igra i, uporabnik u WHERE ((tk_uporabnik2 = ".$_SESSION["id"]." AND u.id = tk_uporabnik1) OR (tk_uporabnik1 = ".$_SESSION["id"]." AND u.id = tk_uporabnik2)) AND stanje = \"l\" AND tip = \"f\"";
		$result = mysqli_query($db , $sql);
		
		while($row = mysqli_fetch_assoc($result)){
			$list[] = new Uporabnik($row['id'], $row['uporabnisko_ime'],"","");
		}
		return $list;
	}
	
	public static function Rook($polje, $row1, $col1, $row2, $col2, $side, $figure){
		if(Sah::preglejVrsto($polje, $row1, $col1, $row2, $col2, $side, $figure)){ return true; }
		if(Sah::preglejStolpec($polje, $row1, $col1, $row2, $col2, $side, $figure)){ return true; }
	}
	public static function Knight($polje, $row1, $col1, $row2, $col2, $side, $figure){
		if($polje[$row2][$col2] != '0'){
			if($side == 1 && ($polje[$row2][$col2] == 'p' || $polje[$row2][$col2] == 'r' || $polje[$row2][$col2] == 'n' || $polje[$row2][$col2] == 'q' || $polje[$row2][$col2] == 'b' || $polje[$row2][$col2] == 'k')){ //Bela stran 
				return false;
			}
			else if($side != 1 && ($polje[$row2][$col2] == 'P' || $polje[$row2][$col2] == 'R' || $polje[$row2][$col2] == 'N' || $polje[$row2][$col2] == 'Q' || $polje[$row2][$col2] == 'B'|| $polje[$row2][$col2] == 'K')){ //Črna stran
				return false;
			}
		}
		
		if($row1-2 == $row2 && $col1-1 == $col2){
			return true;
		}
		else if($row1-2 == $row2 && $col1+1 == $col2){
			return true;
		}
		else if($row1-1 == $row2 && $col1-2 == $col2){
			return true;
		}
		else if($row1-1 == $row2 && $col1+2 == $col2){
			return true;
		}
		else if($row1+1 == $row2 && $col1-2 == $col2){
			return true;
		}
		else if($row1+1 == $row2 && $col1+2 == $col2){
			return true;
		}
		else if($row1+2 == $row2 && $col1-1 == $col2){
			return true;
		}
		else if($row1+2 == $row2 && $col1+1 == $col2){
			return true;
		}
		return false;
	}
	public static function Bishop($polje, $row1, $col1, $row2, $col2, $side, $figure){
		if(Sah::preglejDiagonalo1($polje, $row1, $col1, $row2, $col2, $side, $figure)){ return true;}
		if(Sah::preglejDiagonalo2($polje, $row1, $col1, $row2, $col2, $side, $figure)){ return true;}
	}
	public static function Queen($polje, $row1, $col1, $row2, $col2, $side, $figure){
		if(Sah::preglejDiagonalo1($polje, $row1, $col1, $row2, $col2, $side, $figure)){ return true;}
		if(Sah::preglejDiagonalo2($polje, $row1, $col1, $row2, $col2, $side, $figure)){ return true;}
		if(Sah::preglejVrsto($polje, $row1, $col1, $row2, $col2, $side, $figure)){ return true; }
		if(Sah::preglejStolpec($polje, $row1, $col1, $row2, $col2, $side, $figure)){ return true; }
	}
	public static function King($polje, $row1, $col1, $row2, $col2, $side, $figure){
		if($polje[$row2][$col2] != '0'){
			if($side == 1 && ($polje[$row2][$col2] == 'p' || $polje[$row2][$col2] == 'r' || $polje[$row2][$col2] == 'n' || $polje[$row2][$col2] == 'q' || $polje[$row2][$col2] == 'b' || $polje[$row2][$col2] == 'k')){ //Bela stran 
				return false;
			}
			else if($side != 1 && ($polje[$row2][$col2] == 'P' || $polje[$row2][$col2] == 'R' || $polje[$row2][$col2] == 'N' || $polje[$row2][$col2] == 'Q' || $polje[$row2][$col2] == 'B'|| $polje[$row2][$col2] == 'K')){ //Črna stran
				return false;
			}
		}
		
		if($row1-1 == $row2 && $col1-1 == $col2){	//left up
			return true;
		}
		else if($row1-1 == $row2 && $col1 == $col2){ //middle up
			return true;
		}
		else if($row1-1 == $row2 && $col1+1 == $col2){	//right up
			return true;
		}
		else if($row1 == $row2 && $col1-1 == $col2){ //left
			return true;
		}
		else if($row1 == $row2 && $col1+1 == $col2){ //right
			return true;
		}
		else if($row1+1 == $row2 && $col1-1 == $col2){ //left bottom
			return true;
		}
		else if($row1+1 == $row2 && $col1 == $col2){ //middle bottom
			return true;
		}
		else if($row1+1 == $row2 && $col1+1 == $col2){ //right bottom
			return true;
		}
		return false;
	}
	public static function Pawn($polje, $row1, $col1, $row2, $col2, $side, $figure){
		if($polje[$row2][$col2] != '0'){
			if($side == 1 && ($polje[$row2][$col2] == 'p' || $polje[$row2][$col2] == 'r' || $polje[$row2][$col2] == 'n' || $polje[$row2][$col2] == 'q' || $polje[$row2][$col2] == 'b' || $polje[$row2][$col2] == 'k')){ //Bela stran 
				return false;
			}
			else if($side != 1 && ($polje[$row2][$col2] == 'P' || $polje[$row2][$col2] == 'R' || $polje[$row2][$col2] == 'N' || $polje[$row2][$col2] == 'Q' || $polje[$row2][$col2] == 'B'|| $polje[$row2][$col2] == 'K')){ //Črna stran
				return false;
			}
		}
		if($side == -1){ //Crni
			if($row1-1 == $row2 && $col1 == $col2 && $polje[$row2][$col2] == '0'){
				return true;
			}
			else if($row1-1 == $row2 && $col1-1 == $col2 && ($polje[$row2][$col2] == 'p' || $polje[$row2][$col2] == 'r' || $polje[$row2][$col2] == 'n' || $polje[$row2][$col2] == 'q' || $polje[$row2][$col2] == 'b' || $polje[$row2][$col2] == 'k')){
				return true;
			}
			else if($row1-1 == $row2 && $col1+1 == $col2 && ($polje[$row2][$col2] == 'p' || $polje[$row2][$col2] == 'r' || $polje[$row2][$col2] == 'n' || $polje[$row2][$col2] == 'q' || $polje[$row2][$col2] == 'b' || $polje[$row2][$col2] == 'k')){
				return true;
			}
			else if($row1-2 == $row2 && $col1 == $col2 && $row1 == 6 && $polje[$row2][$col2] == '0'){
				return true;
			}
			return false;
		}
		else{ //Beli
			if($row1+1 == $row2 && $col1 == $col2 && $polje[$row2][$col2] == '0'){
				return true;
			}
			else if($row1+1 == $row2 && $col1-1 == $col2 && ($polje[$row2][$col2] == 'P' || $polje[$row2][$col2] == 'R' || $polje[$row2][$col2] == 'N' || $polje[$row2][$col2] == 'Q' || $polje[$row2][$col2] == 'B'|| $polje[$row2][$col2] == 'K')){
				return true;
			}
			else if($row1+1 == $row2 && $col1+1 == $col2 && ($polje[$row2][$col2] == 'P' || $polje[$row2][$col2] == 'R' || $polje[$row2][$col2] == 'N' || $polje[$row2][$col2] == 'Q' || $polje[$row2][$col2] == 'B'|| $polje[$row2][$col2] == 'K')){
				return true;
			}
			else if($row1+2 == $row2 && $col1 == $col2 && $row1 == 1 && $polje[$row2][$col2] == '0'){
				return true;
			}
			return false;
		}
		
	}
	
	public static function updatePolje($polje, $row1, $col1, $row2, $col2, $figure, $game_id, $poteza){
		if(ctype_lower($figure)){
			$side = 1;
		}
		else{
			$side = -1;
		}
		$pos_OpposingKing = Sah::getKingPosition($side,$polje);
		//Pozicije kralja
		$k_Row = $pos_OpposingKing[0];
		$k_Col = $pos_OpposingKing[1];
		
		$db = Db::getInstance();
		$updateDB = false;
		switch($figure){
			//White player
			case "r":
			if(Sah::Rook($polje, $row1, $col1, $row2, $col2, $side, $figure)){
				$updateDB = true;
				$temp = $polje[$row2][$col2];
				$polje[$row1][$col1] = "0";
				$polje[$row2][$col2] = $figure;
				if(Sah::checkForCheck($polje, $side, $figure)){
					$polje[$row1][$col1] = $figure;
					$polje[$row2][$col2] = $temp;
					$friendly_check = true;
					$updateDB = false;
				}
				if(Sah::sahVrsta($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){ $check = true; }
				else if(Sah::sahStolpec($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){ $check = true; }
			} break;
			case "n":
			if(Sah::Knight($polje, $row1, $col1, $row2, $col2, $side, $figure)){
				$updateDB = true;
				$temp = $polje[$row2][$col2];
				$polje[$row1][$col1] = "0";
				$polje[$row2][$col2] = $figure;
				if(Sah::checkForCheck($polje, $side, $figure)){
					$polje[$row1][$col1] = $figure;
					$polje[$row2][$col2] = $temp;
					$friendly_check = true;
					$updateDB = false;
				}
				if(Sah::sahKnight($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){ $check = true; }
			} break;
			case "b":
			if(Sah::Bishop($polje, $row1, $col1, $row2, $col2, $side, $figure)){
				$updateDB = true;
				$temp = $polje[$row2][$col2];
				$polje[$row1][$col1] = "0";
				$polje[$row2][$col2] = $figure;
				if(Sah::checkForCheck($polje, $side, $figure)){
					$polje[$row1][$col1] = $figure;
					$polje[$row2][$col2] = $temp;
					$friendly_check = true;
					$updateDB = false;
				}
				if(Sah::sahDiag1($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){ $check = true; }
				else if(Sah::sahDiag2($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){ $check = true; }
			} break;
			case "q":
			if(Sah::Queen($polje, $row1, $col1, $row2, $col2, $side, $figure)){
				$updateDB = true;
				$temp = $polje[$row2][$col2];
				$polje[$row1][$col1] = "0";
				$polje[$row2][$col2] = $figure;
				if(Sah::checkForCheck($polje, $side, $figure)){
					$polje[$row1][$col1] = $figure;
					$polje[$row2][$col2] = $temp;
					$friendly_check = true;
					$updateDB = false;
				}
				if(Sah::sahVrsta($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){ $check = true; }
				else if(Sah::sahStolpec($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){ $check = true; }
				else if(Sah::sahDiag1($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){ $check = true; }
				else if(Sah::sahDiag2($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){ $check = true; }
			} break;
			case "k":
			if(Sah::King($polje, $row1, $col1, $row2, $col2, $side, $figure)){
				$updateDB = true;
				$temp = $polje[$row2][$col2];
				$polje[$row1][$col1] = "0";
				$polje[$row2][$col2] = $figure;
				if(Sah::checkForCheck($polje, $side, $figure)){
					$polje[$row1][$col1] = $figure;
					$polje[$row2][$col2] = $temp;
					$friendly_check = true;
					$updateDB = false;
				}
				if(Sah::sahKing($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){ $check = true; }
			} break;
			case "p":
			if(Sah::Pawn($polje, $row1, $col1, $row2, $col2, $side, $figure)){
				$updateDB = true;
				$temp = $polje[$row2][$col2];
				$polje[$row1][$col1] = "0";
				$polje[$row2][$col2] = $figure;
				if(Sah::checkForCheck($polje, $side, $figure)){
					$polje[$row1][$col1] = $figure;
					$polje[$row2][$col2] = $temp;
					$friendly_check = true;
					$updateDB = false;
				}
				if(Sah::sahPawn($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){ $check = true; }
			} break;
			//Black player
			case "R":
			if(Sah::Rook($polje, $row1, $col1, $row2, $col2, $side, $figure)){
				$updateDB = true;
				$temp = $polje[$row2][$col2];
				$polje[$row1][$col1] = "0";
				$polje[$row2][$col2] = $figure;
				if(Sah::checkForCheck($polje, $side, $figure)){
					$polje[$row1][$col1] = $figure;
					$polje[$row2][$col2] = $temp;
					$friendly_check = true;
					$updateDB = false;
				}
				if(Sah::sahVrsta($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){ $check = true; }
				else if(Sah::sahStolpec($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){ $check = true; }
			} break;
			case "N":
			if(Sah::Knight($polje, $row1, $col1, $row2, $col2, $side, $figure)){
				$updateDB = true;
				$temp = $polje[$row2][$col2];
				$polje[$row1][$col1] = "0";
				$polje[$row2][$col2] = $figure;
				if(Sah::checkForCheck($polje, $side, $figure)){
					$polje[$row1][$col1] = $figure;
					$polje[$row2][$col2] = $temp;
					$friendly_check = true;
					$updateDB = false;
				}
				if(Sah::sahKnight($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){ $check = true; }
			} break;
			case "B":
			if(Sah::Bishop($polje, $row1, $col1, $row2, $col2, $side, $figure)){
				$updateDB = true;
				$temp = $polje[$row2][$col2];
				$polje[$row1][$col1] = "0";
				$polje[$row2][$col2] = $figure;
				if(Sah::checkForCheck($polje, $side, $figure)){
					$polje[$row1][$col1] = $figure;
					$polje[$row2][$col2] = $temp;
					$friendly_check = true;
					$updateDB = false;
				}
				if(Sah::sahDiag1($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){ $check = true; }
				else if(Sah::sahDiag2($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){ $check = true; }
			} break;
			case "Q":
			if(Sah::Queen($polje, $row1, $col1, $row2, $col2, $side, $figure)){
				$updateDB = true;
				$temp = $polje[$row2][$col2];
				$polje[$row1][$col1] = "0";
				$polje[$row2][$col2] = $figure;
				if(Sah::checkForCheck($polje, $side, $figure)){
					$polje[$row1][$col1] = $figure;
					$polje[$row2][$col2] = $temp;
					$friendly_check = true;
					$updateDB = false;
				}
				if(Sah::sahVrsta($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){ $check = true; }
				else if(Sah::sahStolpec($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){ $check = true; }
				else if(Sah::sahDiag1($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){ $check = true; }
				else if(Sah::sahDiag2($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){ $check = true; }
			} break;
			case "K":
			if(Sah::King($polje, $row1, $col1, $row2, $col2, $side, $figure)){
				$updateDB = true;
				$temp = $polje[$row2][$col2];
				$polje[$row1][$col1] = "0";
				$polje[$row2][$col2] = $figure;
				if(Sah::checkForCheck($polje, $side, $figure)){
					$polje[$row1][$col1] = $figure;
					$polje[$row2][$col2] = $temp;
					$friendly_check = true;
					$updateDB = false;
				}
				if(Sah::sahKing($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){$check = true; }
			} break;
			case "P":
			if(Sah::Pawn($polje, $row1, $col1, $row2, $col2, $side, $figure)){
				$updateDB = true;
				$temp = $polje[$row2][$col2];
				$polje[$row1][$col1] = "0";
				$polje[$row2][$col2] = $figure;
				if(Sah::checkForCheck($polje, $side, $figure)){
					$polje[$row1][$col1] = $figure;
					$polje[$row2][$col2] = $temp;
					$friendly_check = true;
					$updateDB = false;
				}
				if(Sah::sahPawn($polje, $row2, $col2, $k_Row, $k_Col, $side, $figure)){$check = true; }
			} break;
		}
		
		if($updateDB){
			$move = false;
			$c = 0;
			$fen_string = Sah::generate2DBoard($polje);
			if($poteza == "w")
				$poteza = "b";
			else
				$poteza = "w";
			if($check){
				$c = 1;
			}
			$sql = "INSERT INTO stanja(stanje, poteza, sah, tk_igra) VALUES(\"$fen_string\", \"$poteza\", $c ,$game_id)";
			mysqli_query($db,$sql);
			
			return true;
		}
		else{
			return false;
		}
	}
	
	public static function preglejDiagonalo1($polje, $row1, $col1, $row2, $col2, $side, $figure){
		//echo " [".$row1.",".$col1."] - [".$row2.",".$col2."]";
		$move = false;
		$min = min($row1, $col1);
		$tempRow = $row1;
		$tempCol = $col1;
		$row1 = $row1 - $min;
		$col1 = $col1 - $min;
		//echo " [".$row1.",".$col1."] - [".$row2.",".$col2."]\n";
		for($n = 0; $n < 8; $n++){
			if($polje[$row2][$col2] != '0'){
				if($side == 1 && ($polje[$row2][$col2] == 'p' || $polje[$row2][$col2] == 'r' || $polje[$row2][$col2] == 'n' || $polje[$row2][$col2] == 'q' || $polje[$row2][$col2] == 'b' || $polje[$row2][$col2] == 'k')){ //Bela stran 
					return false;
				}
				else if($side != 1 && ($polje[$row2][$col2] == 'P' || $polje[$row2][$col2] == 'R' || $polje[$row2][$col2] == 'N' || $polje[$row2][$col2] == 'Q' || $polje[$row2][$col2] == 'B'|| $polje[$row2][$col2] == 'K')){ //Črna stran
					return false;
				}
			}
			if($row1 > min($tempRow, $row2) && $row1 < max($tempRow, $row2)){
				if($polje[$row1][$col1] != $figure && $polje[$row1][$col1] != "0"){
					return false;
				}
			}
			if($row1 == $row2 && $col1 == $col2){
				//echo "true";
				$move = true;
			}
			else if($row1 == 7 && $col1 == 7){
				//echo "false";
				return $move;
			}
			if($row1 == $tempRow && $col1 == $tempCol){
				$row1++;
				$col1++;
				//echo "continue";
				continue;
			}
			$row1++;
			$col1++;
		}
		return $move;
	}
	public static function preglejDiagonalo2($polje, $row1, $col1, $row2, $col2, $side, $figure){
		//echo " [".$row1.",".$col1."] - [".$row2.",".$col2."]";
		$move = false;
		$skupno = $row1 + $col1;
		$tempRow = $row1;
		$tempCol = $col1;
		if($skupno > 7){
			$row1 = $skupno - 7;
			$col1 = 7;
		}
		else{
			$row1 = 0;
			$col1 = $skupno;
		}
		//echo " [".$row1.",".$col1."] - [".$row2.",".$col2."]\n";
		for($n = 0; $n < 8; $n++){
			if($polje[$row2][$col2] != '0'){
				if($side == 1 && ($polje[$row2][$col2] == 'p' || $polje[$row2][$col2] == 'r' || $polje[$row2][$col2] == 'n' || $polje[$row2][$col2] == 'q' || $polje[$row2][$col2] == 'b' || $polje[$row2][$col2] == 'k')){ //Bela stran 
					return false;
				}
				else if($side != 1 && ($polje[$row2][$col2] == 'P' || $polje[$row2][$col2] == 'R' || $polje[$row2][$col2] == 'N' || $polje[$row2][$col2] == 'Q' || $polje[$row2][$col2] == 'B'|| $polje[$row2][$col2] == 'K')){ //Črna stran
					return false;
				}
			}
			if($row1 > min($tempRow, $row2) && $row1 < max($tempRow, $row2)){
				//echo "polje:".$polje[$row1][$col1];
				if($polje[$row1][$col1] != $figure && $polje[$row1][$col1] != "0"){
					//echo "false";
					return false;
				}
			}
			if($row1 == $row2 && $col1 == $col2){
				//echo "true";
				$move = true;
			}
			else if($row1 == 7 && $col1 == 7){
				//echo "move";
				return $move;
			}
			if($row1 == $tempRow && $col1 == $tempCol){
				$row1++;
				$col1--;
				//echo "continue";
				continue;
			}
			$row1++;
			$col1--;
		}
		return $move;
	}
	public static function preglejVrsto($polje, $row1, $col1, $row2, $col2, $side, $figure){
		//echo "[".$row1.",".$col1."] - [".$row2.",".$col2."]";
		$move = false;
		$tempRow = $row1;
		$tempCol = $col1;
		
		$col1 = 0;
		for($n = 0; $n < 8; $n++){
			//echo "col1:".$col1."min:".min($tempCol, $col2)."max:".max($tempCol, $col2)."\n";
			if($polje[$row2][$col2] != '0'){
				if($side == 1 && ($polje[$row2][$col2] == 'p' || $polje[$row2][$col2] == 'r' || $polje[$row2][$col2] == 'n' || $polje[$row2][$col2] == 'q' || $polje[$row2][$col2] == 'b' || $polje[$row2][$col2] == 'k')){ //Bela stran 
					return false;
				}
				else if($side != 1 && ($polje[$row2][$col2] == 'P' || $polje[$row2][$col2] == 'R' || $polje[$row2][$col2] == 'N' || $polje[$row2][$col2] == 'Q' || $polje[$row2][$col2] == 'B'|| $polje[$row2][$col2] == 'K')){ //Črna stran
					return false;
				}
			}
			if($col1 > min($tempCol, $col2) && $col1 < max($tempCol, $col2)){
				//echo "polje:".$polje[$row1][$col1];
				if($polje[$row1][$col1] != $figure && $polje[$row1][$col1] != "0"){
					//echo "false1";
					return false;
				}
			}
			if($row1 == $row2 && $col1 == $col2){
				//echo "true";
				$move = true;
			}
			else if($row1 == 7 && $col1 == 7){
				//echo "move";
				return $move;
			}
			if($row1 == $tempRow && $col1 == $tempCol){
				//echo "continue";
				$col1++;
				continue;
			}
			$col1++;
		}
		return $move;
	}
	public static function preglejStolpec($polje, $row1, $col1, $row2, $col2, $side, $figure){
		$move = false;
		$tempRow = $row1;
		$tempCol = $col1;
		$row1 = 0;
		for($n = 0; $n < 8; $n++){
			if($polje[$row2][$col2] != '0'){
				if($side == 1 && ($polje[$row2][$col2] == 'p' || $polje[$row2][$col2] == 'r' || $polje[$row2][$col2] == 'n' || $polje[$row2][$col2] == 'q' || $polje[$row2][$col2] == 'b' || $polje[$row2][$col2] == 'k')){ //Bela stran 
					return false;
				}
				else if($side != 1 && ($polje[$row2][$col2] == 'P' || $polje[$row2][$col2] == 'R' || $polje[$row2][$col2] == 'N' || $polje[$row2][$col2] == 'Q' || $polje[$row2][$col2] == 'B'|| $polje[$row2][$col2] == 'K')){ //Črna stran
					return false;
				}
			}
			if($side == 1){ //Bela stran
				if($row1 > min($tempRow, $row2) && $row1 < max($tempRow, $row2)){
					if($polje[$row1][$col1] != $figure && $polje[$row1][$col1] != "0"){
						return false;
					}
				}
			}
			else{
				if($row1 > min($tempRow, $row2) && $row1 < max($tempRow, $row2)){
					if($polje[$row1][$col1] != $figure && $polje[$row1][$col1] != "0"){
						return false;
					}
				}
			}
			if($row1 == $row2 && $col1 == $col2){
				$move = true;
			}
			else if($row1 == 7 && $col1 == 7){
				return $move;
			}
			if($row1 == $tempRow && $col1 == $tempCol){
				$row1++;
				continue;
			}
			$row1++;
		}
		return $move;
	}

	public static function generate2DBoard($polje){
		$sah = array
		(
			array($polje[0][0],$polje[0][1],$polje[0][2],$polje[0][3],$polje[0][4],$polje[0][5],$polje[0][6],$polje[0][7]),
			array($polje[1][0],$polje[1][1],$polje[1][2],$polje[1][3],$polje[1][4],$polje[1][5],$polje[1][6],$polje[1][7]),
			array($polje[2][0],$polje[2][1],$polje[2][2],$polje[2][3],$polje[2][4],$polje[2][5],$polje[2][6],$polje[2][7]),
			array($polje[3][0],$polje[3][1],$polje[3][2],$polje[3][3],$polje[3][4],$polje[3][5],$polje[3][6],$polje[3][7]),
			array($polje[4][0],$polje[4][1],$polje[4][2],$polje[4][3],$polje[4][4],$polje[4][5],$polje[4][6],$polje[4][7]),
			array($polje[5][0],$polje[5][1],$polje[5][2],$polje[5][3],$polje[5][4],$polje[5][5],$polje[5][6],$polje[5][7]),
			array($polje[6][0],$polje[6][1],$polje[6][2],$polje[6][3],$polje[6][4],$polje[6][5],$polje[6][6],$polje[6][7]),
			array($polje[7][0],$polje[7][1],$polje[7][2],$polje[7][3],$polje[7][4],$polje[7][5],$polje[7][6],$polje[7][7])
		);
		
		return Sah::toFEN($sah);
	}
	
	//Za pregled saha
	public static function sahDiag1($polje, $row1, $col1, $row2, $col2, $side, $figure){
		$move = false;
		$min = min($row1, $col1);
		$tempRow = $row1;
		$tempCol = $col1;
		$row1 = $row1 - $min;
		$col1 = $col1 - $min;
		
		for($n = 0; $n < 8; $n++){
			if($row1 > min($tempRow, $row2) && $row1 < max($tempRow, $row2)){
				if($polje[$row1][$col1] != $figure && $polje[$row1][$col1] != "0"){
					//echo "false";
					return false;
				}
			}
			if($row1 == $row2 && $col1 == $col2){ //Ce je poteza veljavna
				//echo "true";
				$move = true;
			}
			else if($row1 == 7 && $col1 == 7){ //Ce smo na koncu sahovnice
				//echo "false";
				return $move;
			}
			if($row1 == $tempRow && $col1 == $tempCol){ //ko pregledamo svojo pozicijo
				$row1++;
				$col1++;
				//echo "continue";
				continue;
			}
			$row1++;
			$col1++;
		}
		return $move;
	}
	public static function sahDiag2($polje, $row1, $col1, $row2, $col2, $side, $figure){
		//echo " [".$row1.",".$col1."] - [".$row2.",".$col2."]";
		$move = false;
		$skupno = $row1 + $col1;
		$tempRow = $row1;
		$tempCol = $col1;
		if($skupno > 7){
			$row1 = $skupno - 7;
			$col1 = 7;
		}
		else{
			$row1 = 0;
			$col1 = $skupno;
		}
		//echo " [".$row1.",".$col1."] - [".$row2.",".$col2."]\n";
		for($n = 0; $n < 8; $n++){
			if($row1 > min($tempRow, $row2) && $row1 < max($tempRow, $row2)){
				//echo "polje:".$polje[$row1][$col1];
				if($polje[$row1][$col1] != $figure && $polje[$row1][$col1] != "0"){
					//echo "false";
					return false;
				}
			}
			if($row1 == $row2 && $col1 == $col2){
				//echo "true";
				$move = true;
			}
			else if($row1 == 7 && $col1 == 7){
				//echo "move";
				return $move;
			}
			if($row1 == $tempRow && $col1 == $tempCol){
				$row1++;
				$col1--;
				//echo "continue";
				continue;
			}
			$row1++;
			$col1--;
		}
		return $move;
	}
	public static function sahVrsta($polje, $row1, $col1, $row2, $col2, $side, $figure){
		$move = false;
		$tempRow = $row1;
		$tempCol = $col1;
		$col1 = 0;
		for($n = 0; $n < 8; $n++){
			if($col1 > min($tempCol, $col2) && $col1 < max($tempCol, $col2)){
				//echo "polje:".$polje[$row1][$col1];
				if($polje[$row1][$col1] != $figure && $polje[$row1][$col1] != "0"){
					//echo "false1";
					return false;
				}
			}
			if($row1 == $row2 && $col1 == $col2){
				//echo "true";
				$move = true;
			}
			else if($row1 == 7 && $col1 == 7){
				//echo "move";
				return $move;
			}
			if($row1 == $tempRow && $col1 == $tempCol){
				//echo "continue";
				$col1++;
				continue;
			}
			$col1++;
		}
		return $move;
	}
	public static function sahStolpec($polje, $row1, $col1, $row2, $col2, $side, $figure){
		$move = false;
		$tempRow = $row1;
		$tempCol = $col1;
		$row1 = 0;
		for($n = 0; $n < 8; $n++){
			if($side == 1){ //Bela stran
				if($row1 > min($tempRow, $row2) && $row1 < max($tempRow, $row2)){
					if($polje[$row1][$col1] != $figure && $polje[$row1][$col1] != "0"){
						//echo "false 1";
						return false;
					}
				}
			}
			else{
				if($row1 > min($tempRow, $row2) && $row1 < max($tempRow, $row2)){
					//echo $polje[$row1][$col1];
					if($polje[$row1][$col1] != $figure && $polje[$row1][$col1] != "0"){
						//echo "false 2";
						return false;
					}
				}
			}
			if($row1 == $row2 && $col1 == $col2){
				//echo "true";
				$move = true;
			}
			else if($row1 == 7 && $col1 == 7){
				//echo "move";
				return $move;
			}
			if($row1 == $tempRow && $col1 == $tempCol){
				//echo "continue";
				$row1++;
				continue;
			}
			$row1++;
		}
		return $move;
	}
	public static function sahKnight($polje, $row1, $col1, $row2, $col2, $side, $figure){
		if($row1-2 == $row2 && $col1-1 == $col2){
			return true;
		}
		else if($row1-2 == $row2 && $col1+1 == $col2){
			return true;
		}
		else if($row1-1 == $row2 && $col1-2 == $col2){
			return true;
		}
		else if($row1-1 == $row2 && $col1+2 == $col2){
			return true;
		}
		else if($row1+1 == $row2 && $col1-2 == $col2){
			return true;
		}
		else if($row1+1 == $row2 && $col1+2 == $col2){
			return true;
		}
		else if($row1+2 == $row2 && $col1-1 == $col2){
			return true;
		}
		else if($row1+2 == $row2 && $col1+1 == $col2){
			return true;
		}
		return false;
	}
	public static function sahPawn($polje, $row1, $col1, $row2, $col2, $side, $figure){
		if($side == 1)
			$side = -1;
		else
			$side = 1;

		if($side == -1){ //Crni
			if($row1-1 == $row2 && $col1 == $col2 && $polje[$row2][$col2] == '0'){
				return true;
			}
			else if($row1-1 == $row2 && $col1-1 == $col2 && ($polje[$row2][$col2] == 'p' || $polje[$row2][$col2] == 'r' || $polje[$row2][$col2] == 'n' || $polje[$row2][$col2] == 'q' || $polje[$row2][$col2] == 'b' || $polje[$row2][$col2] == 'k')){
				return true;
			}
			else if($row1-1 == $row2 && $col1+1 == $col2 && ($polje[$row2][$col2] == 'p' || $polje[$row2][$col2] == 'r' || $polje[$row2][$col2] == 'n' || $polje[$row2][$col2] == 'q' || $polje[$row2][$col2] == 'b' || $polje[$row2][$col2] == 'k')){
				return true;
			}
			else if($row1-2 == $row2 && $col1 == $col2 && $row1 == 6 && $polje[$row2][$col2] == '0'){
				return true;
			}
			return false;
		}
		else{ //Beli
			if($row1+1 == $row2 && $col1 == $col2 && $polje[$row2][$col2] == '0'){
				return true;
			}
			else if($row1+1 == $row2 && $col1-1 == $col2 && ($polje[$row2][$col2] == 'P' || $polje[$row2][$col2] == 'R' || $polje[$row2][$col2] == 'N' || $polje[$row2][$col2] == 'Q' || $polje[$row2][$col2] == 'B'|| $polje[$row2][$col2] == 'K')){
				return true;
			}
			else if($row1+1 == $row2 && $col1+1 == $col2 && ($polje[$row2][$col2] == 'P' || $polje[$row2][$col2] == 'R' || $polje[$row2][$col2] == 'N' || $polje[$row2][$col2] == 'Q' || $polje[$row2][$col2] == 'B'|| $polje[$row2][$col2] == 'K')){
				return true;
			}
			else if($row1+2 == $row2 && $col1 == $col2 && $row1 == 1 && $polje[$row2][$col2] == '0'){
				return true;
			}
			return false;
		}
	}
	public static function sahKing($polje, $row1, $col1, $row2, $col2, $side, $figure){
		if($row1-1 == $row2 && $col1-1 == $col2){	//left up
			return true;
		}
		else if($row1-1 == $row2 && $col1 == $col2){ //middle up
			return true;
		}
		else if($row1-1 == $row2 && $col1+1 == $col2){	//right up
			return true;
		}
		else if($row1 == $row2 && $col1-1 == $col2){ //left
			return true;
		}
		else if($row1 == $row2 && $col1+1 == $col2){ //right
			return true;
		}
		else if($row1+1 == $row2 && $col1-1 == $col2){ //left bottom
			return true;
		}
		else if($row1+1 == $row2 && $col1 == $col2){ //middle bottom
			return true;
		}
		else if($row1+1 == $row2 && $col1+1 == $col2){ //right bottom
			return true;
		}
	}
	public static function checkForCheck($polje, $side, $figure){
		$check = false;
		$pos = Sah::getFriendlyKingPosition($side, $polje);
		//Pozicije kralja
		$k_Row = $pos[0];
		$k_Col = $pos[1];
		//echo "king:".$k_Row.$k_Col;
		if($side == -1){ // Ko naredimo potezo kor crni igralec moramo pregledati vse ostale figure belega igralca ce katera naredi sah
			for($x = 0; $x < 8; $x++){
				for($y = 0; $y < 8; $y++){	
					$figure = $polje[$x][$y];
					switch($polje[$x][$y]){
						//White player
						case "r":
							if(Sah::sahVrsta($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){ /*echo "r true";*/ $check = true; }
							else if(Sah::sahStolpec($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){ $check = true; }
						break;
						case "b":
							if(Sah::sahDiag1($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){ $check = true; }
							else if(Sah::sahDiag2($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){ $check = true; }
						break;
						case "q":
							if(Sah::sahVrsta($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){ $check = true; }
							else if(Sah::sahStolpec($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){ $check = true; }
							else if(Sah::sahDiag1($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){ $check = true; }
							else if(Sah::sahDiag2($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){ $check = true; }
						break;
						case "n":
							if(Sah::sahKnight($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){ $check = true; }
						break;
						case "p":
							if(Sah::sahPawn($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){ $check = true; }
						break;
						case "k":
							if(Sah::sahKing($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){ $check = true; }
						break;
					}
				}
				if($check)
					break;
			}
		}
		else{
			for($x = 0; $x < 8; $x++){
				for($y = 0; $y < 8; $y++){
					$figure = $polje[$x][$y];
					switch($polje[$x][$y]){
						//Black player
						case "R":
							if(Sah::sahVrsta($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){ $check = true; }
							else if(Sah::sahStolpec($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){ $check = true; }
						break;
						case "B":
							if(Sah::sahDiag1($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){ $check = true; }
							else if(Sah::sahDiag2($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){$check = true; }
						break;
						case "Q":
							if(Sah::sahVrsta($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){ $check = true; }
							else if(Sah::sahStolpec($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){ $check = true; }
							else if(Sah::sahDiag1($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){ $check = true; }
							else if(Sah::sahDiag2($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){ $check = true; }
						break;
						case "N":
							if(Sah::sahKnight($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){ $check = true; }
						break;
						case "P":
							if(Sah::sahPawn($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){ $check = true; }
						break;
						case "K":
							if(Sah::sahKing($polje, $x, $y, $k_Row, $k_Col, $side, $figure)){ $check = true; }
						break;
					}
				}
				if($check)
					break;
			}
		}
		return $check;
	}
	public static function getKingPosition($side, $polje){
		$pos = "";
		for($x = 0; $x < 8; $x++){
			for($y = 0; $y < 8; $y++){
				if($side == -1){
					if($polje[$x][$y] == "k"){
						$pos = $x.$y;
					}
				}
				else{
					if($polje[$x][$y] == "K"){
						$pos = $x.$y;
					}
				}
			}
		}
		return $pos;
	}
	
	public static function getFriendlyKingPosition($side, $polje){
		
		$pos = "";
		for($x = 0; $x < 8; $x++){
			for($y = 0; $y < 8; $y++){
				if($side == 1){
					if($polje[$x][$y] == "k"){
						$pos = $x.$y;
					}
				}
				else{
					if($polje[$x][$y] == "K"){
						$pos = $x.$y;
					}
				}
			}
		}
		return $pos;
	}
	
  }
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
?>

