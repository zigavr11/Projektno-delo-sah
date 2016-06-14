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
			Uporabnik::prijaviUporabnika($_POST["username"], $_POST["password"]);
		}
		
		public function odjava() {
			Uporabnik::odjaviUporabnika();
		}
	}
?>