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
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script src="http://www.google.com/uds/solutions/dynamicfeed/gfdynamicfeedcontrol.js"
		type="text/javascript"></script>

		<style type="text/css">
			
		</style>
		<script type="text/javascript">
			function load() {
			var feed ="https://www.chess.com/rss/news";
			new GFdynamicFeedControl(feed, "feedControl1");

			}
			google.load("feeds", "1");
			google.setOnLoadCallback(load);
		</script>
		<script type="text/javascript">
			function load() {
			var feed ="http://theweekinchess.com/twic-rss-feed";
			new GFdynamicFeedControl(feed, "feedControl2");

			}
			google.load("feeds", "1");
			google.setOnLoadCallback(load);
		</script>
	</head>
	<style>
		@import url("http://www.google.com/uds/solutions/dynamicfeed/gfdynamicfeedcontrol.css");
		@import url("http://fonts.googleapis.com/css?family=Roboto");
		
		#feedControl {
			margin-top : 10px;
			margin-left: auto;
			margin-right: auto;
			font-size: 12px;
			color: #9CADD0;
		}
			
		
		body{
			font-family: 'Roboto', sans-serif;
			font-size:14px;
		}
		.navbar {
			margin-bottom: 0;
			border-radius: 0;
		}
		
		mainnav{
			height:100%;
		}
		
		table{
			border-style:solid;
			border-width:1px;
			border-color:black;
		}
		
		td{
			text-align:center;
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
			min-height: 100%;
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
		
		.inactiveLink {
			pointer-events: none;
			cursor: default;
		}
		
		.btn-outlined {
			border-radius: 0;
			-webkit-transition: all 0.3s;
			   -moz-transition: all 0.3s;
					transition: all 0.3s;
		}
		
		.btn-outlined.btn-info {
			background: none;
			border: 3px solid #222222;
			color: black;
			height:150px;
			width:150px;
		}
		.btn-outlined.btn-info:hover,
		.btn-outlined.btn-info:active {
			color: #FFF;
			background: #222222;
			height:150px;
			width:150px;
		}
		
		.game{
			float:left;
			margin-left:5px;
			margin-top:5px;
		}
	</style>
	<body>
  
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle " data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>                        
				</button>
				<a class="navbar-brand" >Projektno delo - Šah</a>
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav">
					<li <?php if($controller=='index') echo "class=\"active\""?>><a href='?controller=strani&action=domov'><span class="glyphicon glyphicon-home"></span> Domov</a></li>
					<?php
						if(isset($_SESSION["id"])){
							echo "<li"; if($action=='profile') echo " class=\"active\"";
							echo "> <a href='#' class=\"dropdown-toggle \" data-toggle=\"dropdown\"><span class=\"glyphicon glyphicon-user\"></span> Profil<span class=\"caret\"></span></a>
								<ul class=\"dropdown-menu\">
								  <li><a href=\"?controller=uporabnik&action=profile\"><span class=\"glyphicon glyphicon-eye-open\"></span> Ogled profila</a></li>
								  <li><a href=\"#\"><span class=\"glyphicon glyphicon-pencil\"></span> Spremeni profil</a></li> 
								  <li><a href=\"?controller=uporabnik&action=zgodovina\"><span class=\"glyphicon glyphicon-film\"></span> Zgodovina iger</a></li>
								</ul>
							</li>";
						}
						else{
							echo "<li "; if($action=='profile') echo " class=\"active\"";
							echo "> <a href='#' class=\"dropdown-toggle inactiveLink\" data-toggle=\"dropdown\"><span class=\"glyphicon glyphicon-user\"></span> Profil<span class=\"caret\"></span></a>
								<ul class=\"dropdown-menu\">
								  <li><a href=\"?controller=uporabnik&action=profile\  ><span class=\"glyphicon glyphicon-eye-open\"></span> Ogled profila</a></li>
								  <li><a href=\"#\"><span class=\"glyphicon glyphicon-pencil\"></span> Spremeni profil</a></li> 
								  <li><a href=\"?controller=uporabnik&action=zgodovina\"><span class=\"glyphicon glyphicon-film\"></span> Zgodovina iger</a></li>
								</ul>
							</li>";
						}
					?>
					
					<li <?php if($action=='pravila') echo "class=\"active\""?>><a href='?controller=uporabnik&action=pravila'><span class="glyphicon glyphicon-education"></span> Pravila</a></li>
					<li <?php if($action=='index') echo "class=\"active\""?>><a href='?controller=sah&action=index'><span class="glyphicon glyphicon-play"></span> Igraj</a></li>
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
			<div class="col-sm-8 text-left mainnav"> 
				<?php require_once('routes.php'); ?> 
			</div>
			
			<div class="col-sm-2 sidenav">
				<div class="panel panel-default ">
					<div class="panel-heading ">Prijatelji</div>
					<?php
						if(isset($_SESSION["username"])){
							$db = Db::getInstance();
							$result = mysqli_query($db,"SELECT * FROM uporabnik, prijatelji WHERE uporabnik.id = id_prijatelja AND id_uporabnika = ".$_SESSION["id"]."");
			
							while($row = mysqli_fetch_assoc($result)){
								
								$sql = "SELECT * FROM izziv WHERE (id_uporabnika = ".$_SESSION["id"]." || id_uporabnika = ".$row["id"].") AND (id_prijatelja = ".$row["id"]." || id_prijatelja = ".$_SESSION["id"].")";
								$res = mysqli_query($db, $sql);
								if(mysqli_num_rows($res) == 0){
									echo "<div class=\"panel-body well well-sm\">".$row["uporabnisko_ime"]."
									<button type=\"button\" id=\"".$row["id"]."\" class=\"btn btn-primary btn-xs glyphicon glyphicon-play friend_play\"></button>
									</div>";
								}
								else{
									echo "<div class=\"panel-body\">".$row["uporabnisko_ime"]."</div>";
								}
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
				<div class="panel panel-default">
				<div class="panel-heading">Najdi podobnega igralca</div>
					<?php 
						if(isset($_GET["action2"])){
							$db = Db::getInstance();
							$sql = "SELECT rating FROM uporabnik WHERE uporabnisko_ime='" . $_SESSION["username"] . "'";
							$result = mysqli_query($db, $sql);
							$row = mysqli_fetch_assoc($result);
							//var_dump($row);

							$sql2 = "SELECT ABS(rating - " . $row['rating'] . ") AS ocena, uporabnisko_ime AS username FROM uporabnik ORDER BY ocena";
							$result2 = mysqli_query($db, $sql2);
							$row2 = mysqli_fetch_assoc($result2);
							//var_dump($row2);
							//echo $row2['ocena'];
							//echo $row2['username'];
							echo 'Predlagani igralec na podlagi vasega ratinga je ' . $row2['username'] . '. Dodajte ga med prijatelje in z njim igrajte igro!';
							
						} else if(! isset($_SESSION["username"])) {
							echo "Za iskanje nasprotnika se morate registrirate/vpisati";
						}
					 	else {
							echo '<a href="?action2=something">Isci!</button>'; 
						}
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