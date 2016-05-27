<?php
  class StraniController {
    public function domov() {
		require_once('views/strani/domov.php');
    }

    public function napaka() {
		require_once('views/strani/napaka.php');
    }
	
	public function PrijateljNeobstaja(){
		require_once('views/strani/Neobstaja.php');	
	}
	
	public function PrijateljObstaja(){
		require_once('views/strani/Obstaja.php');	
	}
	
  }
?>