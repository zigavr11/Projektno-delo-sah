<?php
	class RegistracijaController {
		public function index(){
		  require_once('views/registracija/index.php');
		}
		
		public function shrani() {
			$uporabnik = Uporabnik::dodajUporabnika($_POST["username"] ,$_POST["password"], $_POST["ime"], $_POST["priimek"]);
			require_once('views/registracija/shrani.php');
		}
		
		public function prijavaHTML() {
			require_once('views/registracija/prijavaHTML.php');
		}
		
		public function prijava() {
			echo "prijava";
			Uporabnik::prijaviUporabnika($_POST["username"], $_POST["password"]);
			header("Location: ?controller=uporabnik&action=profile");
		}
		
		public function odjava() {
			$db = Db::getInstance();
			$sql="UPDATE uporabnik SET prijava = -1 WHERE id = '".$_SESSION["id"]."'";
			mysqli_query($db, $sql);
			session_destroy();
			header("Location: ?controller=strani&action=domov");
		}
	}
?>