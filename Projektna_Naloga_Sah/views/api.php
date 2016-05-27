<?php
	session_start();
	require_once('models/sah.php');
	require_once('models/uporabnik.php');
	require_once('controllers/sah_controller.php');
	$controller = new SahController();
	$controller->{ $action }(); //Klice se funkcija z imenom ki je pod action
?>