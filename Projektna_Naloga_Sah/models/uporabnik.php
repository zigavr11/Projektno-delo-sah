<?php
	class Uporabnik {
    // we define 3 attributes
    // they are public so that we can access them using $post->author directly
		public $id;
		public $username;
		public $ime;
		public $priimek;

		public function __construct($id, $username, $ime, $priimek) {
			$this->id = $id;
			$this->username = $username;
			$this->ime = $ime;
			$this->priimek = $priimek;
		}
		
		public static function vsiUporabniki() {
			$list = [];
			$db = Db::getInstance();
			$result = mysqli_query($db,'SELECT * FROM uporabnik');
			
			$st_oglasov = 0;
			while($row = mysqli_fetch_assoc($result)){
				 $list[] = new Uporabnik($row['id'], $row['uporabnisko_ime'], $row['ime'], $row['priimek']);
			}
		  // we create a list of Post objects from the database results
			return $list;
		}

		public static function najdiUporabnika($id) {
			$id = intval($id);
			$db = Db::getInstance();
			$result = mysqli_query($db,"SELECT * FROM uporabnik WHERE uporabnik.id = \"$id\"");
			
			$row = mysqli_fetch_assoc($result);
			return new Uporabnik($row['id'], $row['uporabnisko_ime'], $row['ime'], $row['priimek']);
		}
		
		public static function dodajUporabnika($username, $passwordH, $ime, $priimek) {
			$password = hash("sha256", $passwordH);
			$db = Db::getInstance();
			$sql="INSERT INTO uporabnik(prijava, uporabnisko_ime, geslo, ime, priimek) VALUES (-1, \"$username\", \"$password\", \"$ime\", \"$priimek\")";

			mysqli_query($db,$sql);

			return Uporabnik::najdiUporabnika($id);
		}
		
		public static function prijaviUporabnika($username, $password){
			echo "test1";
			$password=hash("sha256", $_POST["password"]);
			$db = Db::getInstance();
			$sql = "SELECT * FROM uporabnik WHERE uporabnisko_ime=\"$username\" AND geslo=\"$password\"";
			$result = mysqli_query($db, $sql);
			if (mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_assoc($result);
				$_SESSION["username"]=$username;
				$_SESSION["id"] = $row["id"];
				$sql="UPDATE uporabnik SET prijava = 1 WHERE id = '".$row["id"]."'";
				mysqli_query($db, $sql);
			}
			else
			{
				header("Location: ?controller=strani&action=domov");
			}
		}
		
		public static function dodajPrijatelja($uporabnisko_ime){
			$db = Db::getInstance();
			$result = mysqli_query($db,"SELECT * FROM uporabnik WHERE uporabnik.uporabnisko_ime = \"$uporabnisko_ime\"");
			$row = mysqli_fetch_assoc($result);
			if($row > 0)
			{
				$sql="INSERT INTO prijatelji(id_uporabnika, id_prijatelja) VALUES (\"".$_SESSION["id"]."\", \"".$row['id']."\")";
				mysqli_query($db, $sql);
				header("Location: ?controller=strani&action=PrijateljObstaja");
			}
			else{
				header("Location: ?controller=strani&action=PrijateljNeobstaja");
			}
		}
  
		public static function izzoviPrijatelja($id_prijatelja){
			$db = Db::getInstance();
			$sql = "INSERT INTO izziv(id_uporabnika, id_prijatelja, stanje) VALUES (\"".$_SESSION["id"]."\", \"".$id_prijatelja."\", \"w\")";
			mysqli_query($db, $sql);
		}
		
		public static function vrniIzzive(){
			$list = [];
			$db = Db::getInstance();
			$result = mysqli_query($db,"SELECT * FROM izziv, uporabnik WHERE id_uporabnika = uporabnik.id AND id_prijatelja = \"".$_SESSION["id"]."\"");
		
			while($row = mysqli_fetch_assoc($result)){
				 $list[] = new Uporabnik($row['id'], $row['uporabnisko_ime'], $row['ime'], $row['priimek']);
			}
			return $list;
		}
		
		public static function updateIzzive($novo_stanje, $id_prijatelja){
			$db = Db::getInstance();
			if($novo_stanje == "a"){
				$sql="UPDATE izziv SET stanje = '$novo_stanje' WHERE id_uporabnika = $id_prijatelja AND id_prijatelja = ".$_SESSION["id"]."";
				
			}
			else if($novo_stanje == "r"){
				$sql="DELETE FROM izziv WHERE id_uporabnika = $id_prijatelja AND id_prijatelja = ".$_SESSION["id"]."";
			}
			
			$result = mysqli_query($db,$sql);
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
  }
?>