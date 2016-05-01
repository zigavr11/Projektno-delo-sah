<?php
	class RegistracijaController {
		public function index(){
		  require_once('views/registracija/index.php');
		}
		
		public function shrani() {
			$oglas = Uporabnik::dodajUporabnika($_POST["username"] ,$_POST["password"], $_POST["ime"], $_POST["priimek"], $_POST["email"], $_POST["telefonska_st"], $_POST["ulica"] , $_POST["posta"],$_POST["spol"],$_POST["starost"], $_POST["hisna_st"], 1);
			require_once('views/registracija/shrani.php');
		}
		
		public function prijavaHTML() {
			require_once('views/registracija/prijavaHTML.php');
		}
		
		public function prijava() {
			Uporabnik::prijaviUporabnika($_POST["username"], $_POST["password"]);
			//require_once('views/registracija/index.php');
			header("Location: /MVC/mvc/index.php?controller=uporabnik&action=index");
		}
		
		public function odjava() {
			session_destroy();
			header("Location: /MVC/mvc/index.php");
		}
	}
?>