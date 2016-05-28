$(document).ready(function(){
	$(".friend_play").on("click",function(){
		var friend_id = this.id;
		$.ajax({
			type: "POST",
			url: "http://localhost/Projektno-delo-sah/Projektna_Naloga_Sah/index.php?controller=api&action=izzoviPrijatelja",
			data: {"friend_id":friend_id},
			success:function(data){
				
			},
			error:function(error){
				console.log(error);
			}
		})
	});
	
	
	setInterval(function(){
		document.getElementById("panel_izzivi").innerHTML = "";
		document.getElementById("panel_aktivne_igre").innerHTML = "";
		$.ajax({
			type: "GET",
			url: "http://localhost/Projektno-delo-sah/Projektna_Naloga_Sah/index.php?controller=api&action=vrniIzzive",
			success:function(data){
				//v data so izzivi
				var izzivi = JSON.parse(data);
				console.log(data);
				for(var i = 0; i < izzivi.length; i++){
					var iDiv = document.createElement('div');
					iDiv.className = "panel-body";
					iDiv.innerHTML =  izzivi[i].username;
					
					var acceptGumb = document.createElement("button");
						acceptGumb.type ="button";
						acceptGumb.id= izzivi[i].id;
						acceptGumb.className ="btn btn-primary btn-xs accept";
						acceptGumb.innerHTML ="Accept";
						iDiv.appendChild(acceptGumb);
						
					var rejectGumb = document.createElement("button");
						rejectGumb.type ="button";
						rejectGumb.id= izzivi[i].id;
						rejectGumb.className ="btn btn-primary btn-xs reject";
						rejectGumb.innerHTML ="Reject";
						iDiv.appendChild(rejectGumb);
						
					document.getElementById("panel_izzivi").appendChild(iDiv);
				}
				$(".accept").on("click",function(){
					$.ajax({
						type: "POST",
						url: "http://localhost/Projektno-delo-sah/Projektna_Naloga_Sah/index.php?controller=api&action=updateIzzive",
						data: {"novo_stanje":"a", "friend_id": this.id},
						success:function(data){
							console.log(data);
							window.location.href = "http://localhost/Projektno-delo-sah/Projektna_Naloga_Sah/index.php?controller=sah&action=friend&game_id=" + data;
						},
						error:function(error){
							console.log(error);
						}
					})
				});
				$(".reject").on("click",function(){
					$.ajax({
						type: "POST",
						url: "http://localhost/Projektno-delo-sah/Projektna_Naloga_Sah/index.php?controller=api&action=updateIzzive",
						data: {"novo_stanje":"r", "friend_id": this.id},
						success:function(data){
							console.log(data);
						},
						error:function(error){
							console.log(error);
						}
					})
				});
			},
			error:function(error){
				console.log(error);
			}
		})
		$.ajax({
			type: "GET",
			url: "http://localhost/Projektno-delo-sah/Projektna_Naloga_Sah/index.php?controller=api&action=vrniAktivneIgre",
			success:function(data){
				//v data so izzivi
				var igre = JSON.parse(data);
				console.log(data);
				for(var i = 0; i < igre.length; i++){
					if(typeof(game_id) !== 'undefined' && game_id == igre[i].id) continue;
					
					var iDiv = document.createElement('div');
					iDiv.className = "panel-body";
					iDiv.innerHTML =  igre[i].username;
					
					var acceptGumb = document.createElement("button");
						acceptGumb.type ="button";
						acceptGumb.id= igre[i].id;
						acceptGumb.className ="btn btn-primary btn-xs join";
						acceptGumb.innerHTML ="Join";
						iDiv.appendChild(acceptGumb);
						
					document.getElementById("panel_aktivne_igre").appendChild(iDiv);
				}
				$(".join").on("click",function(){
					var gameId = this.id;
					window.location.href = "http://localhost/Projektno-delo-sah/Projektna_Naloga_Sah/index.php?controller=sah&action=friend&game_id=" + gameId;
				});
			},
			error:function(error){
				console.log(error);
			}
		})
	}
	,5000)
});

//text += "<div class=\"panel-body\">"+izzivi[i].username+" "+" <button type=\"button\" id=\""+izzivi[i].id+"\" class=\"btn btn-primary btn-xs glyphicon accept\">Accept</button> <button type=\"button\" id=\""+izzivi[i].id+"\" class=\"btn btn-primary btn-xs glyphicon reject\">Reject</button></div>";