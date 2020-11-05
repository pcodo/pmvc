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
	
	.accueil_sms
	{
		
	}
	.accueil_sms td
	{
		text-align:center;
		padding:5px;
		width:200px;
		background-color:rgb(30,100,100);
		border:1px solid rgb(128,128,128);
		font-color:white;
	}
	.accueil_sms td:hover
	{
		background-color:rgb(30,100,100);
		border:1px solid pink;
	}
	
</style>
<div align="center" class="radius" style="height:800px;">
	<!-- div align="center" id="image_type">
		 <marquee direction="left" Behavior="alternate" onMouseOver="this.stop();" onMouseOut="this.start();">
			<img src="img/p1.jpg"/><img src="img/p2.jpg"/><img src="img/p3.jpg"/><img src="img/p4.jpg"/><img src="img/p5.jpg"/>
			<img src="img/p6.jpg"/>
		</marquee>
	</div -->
	<div class="zone_info">
		<!--h2> Bienvenue dans <span style="color:rgb(200,0,0);">PMVC</span></h2-->
		<h2></h2>
		<?php
		   /* if(isset($intervenant))
			{
				$sous_menus = $intervenant->sous_menus();
				echo '<table class="accueil_sms"><tr>';
				$col = 0;
				foreach($sous_menus as $sm)
				{	
					if($sm['show_at_startup']==1)
					{
						if($col>=3)
						{
							$col = 0;
							echo '</tr><tr>';
						}
						else $col++;
						echo '<td title="'.$sm['description'].'">';
							echo '<a href="index.php?m='.$sm['id_menu'].'&sm='.$sm['id'].'" ><img  src="'.$sm['icone_url'].'"/>';
							$desc_items = explode('(',$sm['description']);
							echo '<p >'.$desc_items[0].'</p>';
						echo '</a></td>';
					}
				}
				echo '</tr></table>';
			}*/
		?>
		<!--img src="img/epension.png" /-->	
		<?php
			$default = 'img/pmvc.jpg';
	       	$posts = Post::allCreated(1);	       	
	       	if(count($posts)==0)
	       	{
	       		$posts = Post::allCreated();
	       		if(count($posts)>0)
	       		{
	       			$post = $posts[0];
	       			if($post->file()->id()>0)
	       			{
	       				echo '<img class="static_accueil_img" src="'.$post->file()->url().'" />';
	       			}
	       			else
	       			{
	       				echo '<img class="static_accueil_img" src="'.$default.'" />';
	       			}
	       		}
	       		else
	       		{
	       			echo '<img class="static_accueil_img" src="'.$default.'"/>';
	       		}
	       		
	       	}
	       	else
	       	{
	       		echo '<div id="slides" class="main-slide-zone" align="center">';
			    	echo '<ul class="bxslider" style="margin-left:0px;">';
			    	foreach ($posts as $key => $post) {
			    		echo '<li>';
			    			if($post->subject()!='')
			    			{
			    				echo '<h1>'.$post->subject().'</h1>';
			    			}
			    			else if($post->message()!='')
			    			{
			    				echo '<p>'.$post->message().'</p>';
			    			}

			    			if($post->message()!='')
			    			{
			    				echo '<div style="float:left;margin-top:-50px;padding-left:10px;padding-right:10px;">';
			    				echo '<img src="'.$post->file()->url().'"/>';
			    				echo '</div>';
			    				echo '<p style="text-align:justify;padding:20px;">'.$post->message().'</p>';
			    				echo '<div style="clear:both;"></div>';
			    			}
			    			else if($post->file()->id()>0)
			    			{
			    				echo '<img src="'.$post->file()->url().'"/>';
			    			}
			    			
			    		echo '</li>';
			    	}			        
			      echo '</ul>';
			    echo '</div>';	
	       	}
	    ?>
				
	</div>
</div>
	