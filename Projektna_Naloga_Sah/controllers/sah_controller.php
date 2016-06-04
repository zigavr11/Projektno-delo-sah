<?php
require_once('views/sah/AlfaBeta.php'); 
	class SahController {
		
		public function index() {
			require_once('views/sah/index.php');
		}
		
		public function ai(){
			$id = Sah::newGameVsAi();
			require_once('views/sah/ai.php');
		}
		
		public function move(){
			
			if(isset($_POST["ai"])){
				$db = Db::getInstance();
				$move = Sah::updatePolje($_POST["polje"], $_POST["row1"], $_POST["col1"], $_POST["row2"], $_POST["col2"], $_POST["figure"], $_POST["game_id"], $_POST["poteza"]);
				if($move){
					$sql = "SELECT i.id as game_id, i.stanje as stanje_igre, s.* FROM igra i, stanja s WHERE tk_igra = ".$_POST["game_id"]." AND i.id = tk_igra ORDER BY s.id DESC LIMIT 1";
					$result = mysqli_query($db , $sql);
					$row = mysqli_fetch_assoc($result);
					$polje = Sah::toTable($row["stanje"]);
					
					$moveB = moveA(($_POST["row1"].$_POST["col1"].$_POST["row2"].$_POST["col2"])." ", $_POST["polje"]);
					$move1 = Sah::updatePolje($polje, $moveB[0], $moveB[1], $moveB[2], $moveB[3], $polje[$moveB[0]][$moveB[1]], $_POST["game_id"], "b");
					
					echo json_encode($move);
				}
				else{
					echo json_encode($move);
				} //
				
			}
			else{
				$move = Sah::updatePolje($_POST["polje"], $_POST["row1"], $_POST["col1"], $_POST["row2"], $_POST["col2"], $_POST["figure"], $_POST["game_id"], $_POST["poteza"]);
				echo json_encode($move);
			}
		}
		
		public function friend(){
			require_once('views/sah/friend.php');
		}
		
		public function opponent(){
			require_once('views/sah/opponent.php');
		}
		
		public function vrniStanjeIgre(){
			$Sah = Sah::returnGameState($_POST["game_id"]);
			echo json_encode($Sah);
		}
		
		public function deleteGame(){
			Sah::deleteGame($_POST["id"]);
		}
		public function vrniAktivneIgre(){
			$igre = Sah::returnActiveGames();
			echo json_encode($igre);
		}
		
		public function izzoviPrijatelja(){
			Uporabnik::izzoviPrijatelja($_POST["friend_id"]);
		}
		
		public function vrniIzzive(){
			$izzivi = Uporabnik::vrniIzzive();
			echo json_encode($izzivi);
		}
		
		public static function checkIzzive(){
			$stanje_izzivov = Uporabnik::checkIzzive();
			echo json_encode($stanje_izzivov);
		}
		
		public static function endGame(){
			Sah::endGame($_POST["game_id"], $_POST["forfeit"]); //post ni delal zakaj?
			SahController::endScreen();
		}
		
		public static function endScreen(){
			require_once('views/sah/endScreen.php');
		}
		
		public static function updateIzzive(){
			Uporabnik::updateIzzive($_POST["novo_stanje"], $_POST["friend_id"]);
			if($_POST["novo_stanje"] == "a"){
				$id = Sah::newGameVsFriend($_POST["friend_id"]);
				echo $id;
			}
		}
	}
?>