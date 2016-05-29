<?php
	class SahController {
	
		public function index() {
			require_once('views/sah/index.php');
		}
		
		public function ai(){
			$Sah = Sah::newGameVsAi();
			require_once('views/sah/ai.php');
		}
		
		public function move(){
			$move = Sah::updatePolje($_POST["polje"], $_POST["row1"], $_POST["col1"], $_POST["row2"], $_POST["col2"], $_POST["figure"], $_POST["game_id"], $_POST["poteza"]);
			echo json_encode($move);
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
			Sah::endGame($_GET["game_id"], $_GET["forfeit"]); //post ni delal zakaj?
			SahController::endScreen();
		}
		
		public static function endScreen(){
			require_once('views/sah/endScreen.php');
		}
		
		public static function updateIzzive(){
			Uporabnik::updateIzzive($_POST["novo_stanje"], $_POST["friend_id"]);
			$id = Sah::newGameVsFriend($_POST["friend_id"]);
			echo $id;
		}
	}
?>