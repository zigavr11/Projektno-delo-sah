<?php
	class Uporabnik {
    // we define 3 attributes
    // they are public so that we can access them using $post->author directly
		public $id;
		public $username;
		public $ime;
		public $priimek;
		public $email;
		public $telefonska_st;
		public $ulica;
		public $posta;
		public $starost;
		public $hisna_st;
		public $st_oglasov;

		public function __construct($id, $username, $ime, $priimek, $email, $telefonska_st, $ulica, $posta, $starost, $hisna_st, $spol, $st_oglasov) {
			$this->id = $id;
			$this->username = $username;
			$this->ime = $ime;
			$this->priimek = $priimek;
			$this->email = $email;
			$this->telefonska_st = $telefonska_st;
			$this->ulica = $ulica;
			$this->posta = $posta;
			$this->starost = $starost;
			$this->hisna_st = $hisna_st;
			$this->spol = $spol;
			$this->st_oglasov = $st_oglasov;
		}
		
		public static function vsiUporabniki() {
			$list = [];
			$db = Db::getInstance();
			$result = mysqli_query($db,'SELECT * FROM uporabnik');
			
			$st_oglasov = 0;
			while($row = mysqli_fetch_assoc($result)){
				 $list[] = new Uporabnik($row['id'], $row['uporabnisko_ime'], $row['ime'], $row['priimek'], $row['email'], $row['telefonska_stevilka'], $row['ulica'], $row['posta'], $row['starost'], $row['hisna_st'], $row['spol'], $st_oglasov);
			}
		  // we create a list of Post objects from the database results
			return $list;
		}

		public static function najdiUporabnika($id) {
			$id = intval($id);
			$list = [];
			$db = Db::getInstance();
			$result = mysqli_query($db,"SELECT uporabnik.*, count(oglas.TK_Uporabnik) AS st_oglasov FROM uporabnik, oglas WHERE oglas.TK_Uporabnik = \"$id\" AND uporabnik.id = \"$id\"");
			
			$row = mysqli_fetch_assoc($result);
			return new Uporabnik($row['id'], $row['uporabnisko_ime'], $row['ime'], $row['priimek'], $row['email'], $row['telefonska_stevilka'], $row['ulica'], $row['posta'], $row['starost'], $row['hisna_st'], $row['spol'], $row["st_oglasov"]);
		}
		
		public static function dodajUporabnika($username, $passwordH, $ime, $priimek, $email, $telefonska_st, $ulica, $posta,$spol, $starost, $hisna_st, $admin) {
			$password = hash("sha256", $passwordH);
			$db = Db::getInstance();
			$query="INSERT INTO uporabnik(uporabnisko_ime, geslo, ime, priimek, email, telefonska_stevilka, ulica, posta, spol, starost, hisna_st, admin) VALUES (\"$username\", \"$password\", \"$ime\", \"$priimek\", \"$email\", \"$telefonska_st\", \"$ulica\", \"$posta\", \"$spol\", \"$starost\", \"$hisna_st\", \"$admin\")";

			mysqli_query($db,$query);
			$id=mysqli_insert_id($db); // gets the last id used in a query (Returns the auto generated id used in the last query)

			return Uporabnik::najdiUporabnika($id);
		}
		
		public static function prijaviUporabnika($username, $password){
			$password=hash("sha256", $_POST["password"]);
			$db = Db::getInstance();
			$sql = "SELECT * FROM uporabnik WHERE uporabnisko_ime=\"$username\" AND geslo=\"$password\" AND admin = 1";
			$result = mysqli_query($db, $sql);
			if (mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_assoc($result);
				$_SESSION["username"]=$username;
				$_SESSION["id"] = $row["id"];
				header("views/uporabnik/index.php?controller=uporabnik&action=index");
			}
			else
			{
				header("Location: /MVC/mvc/index.php");
			}
		}
		
		public static function spremeniUporabnika($id, $username, $ime, $priimek, $email, $telefonska_st, $ulica, $posta, $starost, $hisna_st){
			$db = Db::getInstance();
			$query="UPDATE uporabnik SET uporabnisko_ime = '$username', ime = '$ime', priimek = '$priimek', email='$email', telefonska_stevilka = '$telefonska_st', ulica = '$ulica', posta = '$posta', starost = '$starost', hisna_st = '$hisna_st' WHERE id = \"$id\"";
			if (mysqli_query($db, $query)){
			} 
			else{
				echo "Error: " . $query . "<br>" . mysqli_error($db);
			}
		}
		
		public static function zbrisiUporabnika($id) {
			$id = intval($id);
			$db = Db::getInstance();
			$result = mysqli_query($db,"DELETE FROM uporabnik WHERE id=$id AND admin = 0");
			mysqli_query($db,$query);
		}
  }
?>