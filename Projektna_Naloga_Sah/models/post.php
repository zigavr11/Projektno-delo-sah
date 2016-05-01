<?php
  class Oglas {
    // we define 3 attributes
    // they are public so that we can access them using $post->author directly
    public $id;
    public $naslov;
    public $vsebina;
	public $datumObjave;

    public function __construct($id, $naslov, $vsebina,$datumObjave) {
      $this->id      = $id;
      $this->naslov  = $naslov;
      $this->vsebina = $vsebina;
	  $this->datumObjave=$datumObjave;
    }

    public static function vsi() {
      $list = [];
      $db = Db::getInstance();
      $result = mysqli_query($db,'SELECT * FROM oglas');

		while($row = mysqli_fetch_assoc($result)){
			 $list[] = new Oglas($row['id'], $row['naslov'], $row['vsebina'],$row['datumObjave']);
		}
      // we create a list of Post objects from the database results
        return $list;
    }

    public static function najdi($id) {
      $id = intval($id);
      $list = [];
      $db = Db::getInstance();
      $result = mysqli_query($db,'SELECT * FROM oglas where id=$id');
	  $row = mysqli_fetch_assoc($result);
      return new Oglas($row['id'], $row['naslov'], $row['vsebina'],$row['datumObjave']);
    }
  }
?>