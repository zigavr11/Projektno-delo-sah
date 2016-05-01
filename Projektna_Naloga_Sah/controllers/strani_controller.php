<?php
  class StraniController {
    public function sah() {
      require_once('views/strani/sah.php');
    }
	
	public function domov() {
      require_once('views/strani/domov.php');
    }
	
    public function napaka() {
      require_once('views/strani/napaka.php');
    }
  }
?>