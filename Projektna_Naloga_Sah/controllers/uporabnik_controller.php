<?php
  class UporabnikController {
    public function index() {
		$uporabniki = Uporabnik::vsiUporabniki();
		require_once('views/uporabnik/index.php');
    }

	public function profile(){
		require_once('views/uporabnik/profile.php');
	}
	
    public function prikazi() {
      if (!isset($_GET['id']))
        return call('strani', 'napaka');

      // we use the given id to get the right post
      $uporabnik = Uporabnik::najdiUporabnika($_GET['id']);
      require_once('views/uporabnik/prikazi.php');
    }
	
	public function shrani() {
		$uporabnik=Uporabnik::dodajUporabnika($_POST["username"] ,$_POST["password"], $_POST["ime"], $_POST["priimek"], $_POST["email"], $_POST["telefonska_st"], $_POST["ulica"] , $_POST["posta"],$_POST["spol"],$_POST["starost"], $_POST["hisna_st"], 1);
		require_once('views/uporabnik/uspesnoDodal.php');
	}
	
	public function dodaj_Prijatelja() {
		Uporabnik::dodajPrijatelja($_POST["username"]);
	}
  }
?>