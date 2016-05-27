<?php
  require_once('connection.php');

  if (isset($_GET['controller']) && isset($_GET['action'])) {
    $controller = $_GET['controller'];
    $action     = $_GET['action'];
  } 
  else{
    $controller = 'strani';
    $action     = 'domov';
  }
	if($controller == 'api'){
		require_once('views/api.php');
	}else{
		require_once('views/layout.php');
	}
?>