<DOCTYPE html>
<?php
	session_start();
?>
<html>
	<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script src="main.js"></script>
	</head>
	<style>
		.navbar {
			margin-bottom: 0;
			border-radius: 0;
		}

		
		table td.active1 {
			background: #ff8000;
		}
		
		table td.active2 {
			background: #ff6666;
		}
		
		.w{
			transform: matrix(-1,0,0,-1,0,0);
		}
		
		.row.content {
			height: {height:auto;} 
		}
		
		.sidenav {
			padding-top: 20px;
			background-color: #f1f1f1;
			height: 100%;
		}

		footer {
			background-color: #101010;
			color: #9d9d9d;
			padding: 9px;
		}

		@media screen and (max-width: 767px) {
			.sidenav {
				height: auto;
				padding: 15px;
			}
			.row.content {
				height:auto;
			} 
		}
	</style>
	<body>
  
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>                        
				</button>
				<a class="navbar-brand" href="?controller=strani&action=">Projektno delo - Sah</a>
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav">
					<li <?php if($controller=='index') echo "class=\"active\""?>><a href='?controller=strani&action=domov'>Domov</a></li>
					<li <?php if($action=='profile') echo "class=\"active\""?>><a href='?controller=uporabnik&action=profile'>Profil</a></li>
					<li><a href='?controller=sah&action=index'>Igra</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
				<?php
					if(isset($_SESSION["username"])){
						echo "<li><a href=\"?controller=registracija&action=odjava\"><span class=\"glyphicon glyphicon-log-in\"></span> Odjava</a></li>";
					}
					else{
						echo "<li><a href=\"?controller=registracija&action=index\"><span class=\"glyphicon glyphicon-user\"></span> Registracija</a></li>"; 
						echo "<li><a href=\"?controller=registracija&action=prijavaHTML\"><span class=\"glyphicon glyphicon-log-in\"></span> Prijava</a></li>";
					}
				?>
				</ul>
			</div>
		</div>
	</nav>

	<div class="container-fluid text-center">    
		<div class="row content">
			<div class="col-sm-2 sidenav">
				<p><a href="?controller=&action=">Novice</a></p>
			</div>
			<div class="col-sm-8 text-left"> 
				<?php require_once('routes.php'); ?> 
			</div>
			
			<div class="col-sm-2 sidenav">
				<div class="panel panel-default">
					<div class="panel-heading">Prijatelji</div>
					<?php
						if(isset($_SESSION["username"])){
							$db = Db::getInstance();
							$result = mysqli_query($db,"SELECT * FROM uporabnik, prijatelji WHERE uporabnik.id = id_prijatelja AND id_uporabnika = ".$_SESSION["id"]."");
			
							while($row = mysqli_fetch_assoc($result)){
								echo "<div class=\"panel-body\">".$row["uporabnisko_ime"]."
								<button type=\"button\" id=\"".$row["id"]."\" class=\"btn btn-primary btn-xs glyphicon glyphicon-play friend_play\"></button>
								</div>";

							}
							
							echo "<form class=\"form-inline\" method=\"post\" action=\"?controller=uporabnik&action=dodaj_Prijatelja\" >";
							echo "<div class=\"form-group\">";
							echo "<hr>";
							echo "Vpisi username prijatelja:";
							echo "<input value=\"\" type=\"text\" name=\"username\" class=\"form-control\" placeholder=\"Username\"> </input>";
							echo "<button type=\"submit\" class=\"btn btn-primary btn-xs\">Dodaj prijatelja</button>";
							echo "</div>";
							echo "</form>";
							//<span class=\"glyphicon glyphicon-plus\" aria-hidden=\"true\"></span>
						}
						else{
							echo "Ce zelite dodati in igrati proti prijateljem se morate registrirate/vpisati";
						}
					?>					
				</div>
				<div class="panel panel-default">
				<div class="panel-heading">Izzivi od prijateljev</div>
				<?php
					if(!isset($_GET["game_id"]))
						echo "<div id=\"panel_izzivi\" class=\"panel panel-default\"> </div>";
					else
						echo "<div id=\"panel_izzivi\" style=\"display: none;\" class=\"panel panel-default\"> </div>";
					
				?>
				</div>
				<hr>
				<div class="panel panel-default">
				<div class="panel-heading">Aktivne igre</div>
				<?php
					if(!isset($_GET["game_id"]))
						echo "<div id=\"panel_aktivne_igre\" class=\"panel panel-default\"> </div>";
					else
						echo "<div id=\"panel_aktivne_igre\" style=\"display: none;\" class=\"panel panel-default\"> </div>";
				?>
				
				
				</div>
				<hr>
			</div>
		</div>
	</div>
	
	
	
	<footer class="container-fluid text-center">
	<p> Ziga Vodusek Resnik, Emilija Taseva, Zan Rojko</p>
	</footer>
	<body>
</html>

<!-- Naredi da so username razlicni. V podatkovni bazi ne sme bit enakih usernamov. -->