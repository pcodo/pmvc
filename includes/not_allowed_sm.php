<?php
/*Rôle : Index du projet VS
  Auteur: CODO Paterne, ingénieur en réseaux informatiques et Internet 
  Date de création:19/02/2013
*/
?>
<style>
	#main_accueil
	{
		border: 4px solid red;
		width:100%;
		height:100%;
		margin-right:10%;
	}
	#image_type
	{
		/*background-color:#abcdef;*/
		background-color:rgb(99,127,164);
		margin-top:5px;
	}
	#image_type img{
		width:200px;height:100px;
		margin-left:20px;
		cursor:pointer;
		border:1px solid;
		
	}
	#image_type img:hover{
		border:1px solid red;
	}
	.zone_info
	{
		
	}
	.zone_info h2
	{
		margin-top:0px;border:thin outset;
		background-color:rgb(0,0,0);
		color:rgb(0,128,255);
	}
	.zone_info .logo_info
	{
		margin-left:40px;
		display:inline-block;
		width:200px;height:200px;
		background-color:rgb(30,100,100);
		border:1px solid rgb(128,128,128);
	}
	.zone_info .logo_info:hover
	{
		border:1px solid pink;background-color:rgb(30,128,128);cursor:pointer;
	}
</style>
<div align="center" class="radius" style="height:800px;">
	<!--div align="center" id="image_type">
		 <marquee direction="left" Behavior="alternate" onMouseOver="this.stop();" onMouseOut="this.start();">
			<img src="img/p1.jpg"/><img src="img/p2.jpg"/><img src="img/p3.jpg"/><img src="img/p4.jpg"/><img src="img/p5.jpg"/>
			<img src="img/p6.jpg"/>
		</marquee>
	</div -->
	<div class="zone_info">
		<h2 style="color:rgb(255,0,0);text-decoration:blink;"> ACCES REFUSE !</h2>
		 <p style="font-size:18px;">
			Désolé, Vous n'avez pas accès à cette section. Veuillez contacter l'administrateur du système!
		 </p>
		 <img src="img/epension.png" />	
	</div>
</div>
	