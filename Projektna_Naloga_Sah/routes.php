<?php
//funkcija, ki klice kontrolerje in hkrati vkljucuje njihovo kodo
  function call($controller, $action) {
    require_once('controllers/' . $controller . '_controller.php');

    switch($controller) {
		case 'strani':
			$controller = new StraniController();
			break;
		case 'registracija':
			require_once('models/uporabnik.php');
			$controller = new RegistracijaController();
			break;
		case 'uporabnik':
			require_once('models/uporabnik.php');
			$controller = new UporabnikController();
			break;
		case 'sah':
			require_once('models/sah.php');
			$controller = new SahController();
			break;
    }
    $controller->{ $action }();
  }

   $controllers = array('strani' => ['domov', 'PrijateljNeobstaja', 'PrijateljObstaja' ,'napaka'],
					   'uporabnik' => ['index', 'prikazi','shrani', 'profile', 'dodaj_Prijatelja'],
					   'registracija' => ['index','shrani', 'prijavaHTML', 'prijava', 'odjava'],
					   'sah' => ['index', 'ai', 'friend', 'opponent', 'move']);
  if (array_key_exists($controller, $controllers)) {
    if (in_array($action, $controllers[$controller])) {
      call($controller, $action);
    } else {
      call('strani', 'napaka');
    }
  } else {
    call('strani', 'napaka');
  }
?>