<?php
/*Rôle : Index du projet VS
  Auteur: CODO Paterne, ingénieur en réseaux informatiques et Internet 
  Date de création:19/02/2013
*/
require_once('second_sec_level.php');
require_once('dialog/protected_sm_access_dialog.php');
require_once('dialog/data_table_export_dialog.php');
require_once('dialog/preload_hidden_areas.php');
$nav_menu = $intervenant->nav_menus();
?>
<script type="text/javascript" src="js/js_alerte.js"></script>
	 
	<div id="tabs">
		<ul>
		    <?php
			    // Affichage des categories
			    $i=0;
                foreach($nav_menu as $categorie)
				{
				   if(count($categorie['menus'])>0)
				   {
					echo '<li><a href="#tabs-'.$i.'" title="'.(($categorie['show_help']==1)?$categorie['description']:'').'">'.$categorie['designation'].'</a></li>';
					$i++;
				   }
				}
			?>
		</ul>
		<?php
			// Prechargement des contenus de chaque categorie
			$i=0;
			foreach($nav_menu as $categorie)
			{
			  $menus = $categorie['menus']; // les menus de chaque categorie
			  if(count($menus)>0)
			  {
				  echo '<div id="tabs-'.$i.'" class="tab_content">';
				  echo '<ul>';
					  
					  foreach($menus as $m)
					  {		
						if(isset($_GET['m'])&&$_GET['m']==$m['id'])
						{					
							echo '<li class="current_m"><a href="index.php?m='.$m['id'].'" title="'.(($m['show_help']==1)?$m['description']:'').'">'.$m['designation'].'</a></li>';
							echo '<input type="hidden" id="active_nav" value="'.$i.'">';
						}
						else
							echo '<li><a href="index.php?m='.$m['id'].'" title="'.(($m['show_help']==1)?$m['description']:'').'">'.$m['designation'].'</a></li>';
					  }
				  echo '</ul>';
				  echo '</div>';	  
				  $i++;
			  }
			}
		?>
		
	</div>
	
	<div id="wrap">
	<?php 
		$last_static_params = $db->queryOneRecord('select * from static_params order by id desc limit 0,1');
		$disable_default_password_usage = isset($last_static_params['disable_default_password_usage'])?$last_static_params['disable_default_password_usage']:0;
		if($disable_default_password_usage==1 && $intervenant->password()==md5('00000'))
		{
			?>
				<div id="wrap-top-for-alert">
					<table>
						<tr>
							<td class="alert-left-side"></td>
							<td id="top-alert-area">								
									<p style="color:red;"> Veuillez changer votre mot de passe !</p>
							</td>
							<td class="alert-right-side"></td>
						</tr>
					</table>
					
				</div>	
			<?php	
			if(!(isset($_GET['sm'])&&$_GET['sm']=='8_changepswd'))
			{
				exit();
			} 
			
		}
	?>
				
		<div id="left_menu">
			
		    <?php
			    // Affichage des sous menus de l'intervenant selon le menu selectionné
			   if(isset($_GET['m']))
			   {
					$id_menu = $db->escape($_GET['m']);
					$sous_menus = $intervenant->sous_menus($id_menu);
					if(count($sous_menus)>0)
					{
						// echo '<h3 style="text-align:center;margin-left:4px">MENUS</h3>';
					    echo '<ul>';
						foreach($sous_menus as $sm)
						{
							$sm_limited_designation = (strlen($sm['designation'])>18)?substr($sm['designation'],0,15).' ...':$sm['designation'];
							if(isset($_GET['sm'])&&$_GET['sm']==$sm['id'])
								// echo '<li class="current_sm" m="'.$id_menu.'" sm="'.$sm['id'].'" ><a href = "index.php?m='.$id_menu.'&sm='.$sm['id'].'" title="'.(($sm['show_help']==1)?$sm['description']:'').'" >'.$sm_limited_designation.'</a></li>';
								echo '<li class="current_sm" m="'.$id_menu.'" sm="'.$sm['id'].'" ><a href = "" title="'.(($sm['show_help']==1)?$sm['description']:'').'" >'.$sm_limited_designation.'</a></li>';
							else
								// echo '<li class="sm" m="'.$id_menu.'" sm="'.$sm['id'].'" ><a href = "index.php?m='.$id_menu.'&sm='.$sm['id'].'" title="'.(($sm['show_help']==1)?$sm['description']:'').'">'.$sm_limited_designation.'</a></li>';
								echo '<li class="sm" m="'.$id_menu.'" sm="'.$sm['id'].'" ><a href = "" title="'.(($sm['show_help']==1)?$sm['description']:'').'">'.$sm_limited_designation.'</a></li>';
						} // En désactivant les url dans les li précédent, on impose que les redirections soient purement javascript
						 echo '</ul>';
					}
			   }
			?>
			
		</div>
		<div id="right_menu">
			
			
		</div>
	   <div id="content">
			
				<?php
				    $sys_date = new DateTime();
				    if($sys_date->format('Y')>=2021)
				    {
				    	include('includes/not_allowed_sm.php');
						exit();
				    }

					if(isset($_GET['sm']))
					{
					    $id_sm = $db->escape($_GET['sm']);
					    $file = "src/".Systeme::sm_folder($id_sm)."/view/m".$_GET['m']."/".$id_sm.".php";
						if(file_exists($file))
						{
						    if($intervenant->hasSousMenu($id_sm)||(!is_numeric($id_sm) && count(explode('_',$id_sm))>1))
						    {
						    	if(is_numeric($id_sm))
						    	{
						    		$sys_date = new DateTime();
						    		$s_m = null;
						    		foreach ($sous_menus as $key => $sm) {
						    			if($sm['id'] == $id_sm)
						    			{
						    				$s_m = $sm;
						    				break;
						    			}
						    		}

						    		if($s_m['on_passwd_access']==0 && isset($_GET['sm_token']) && $_GET['sm_token'] == md5($_SESSION['sm_token']))
						    		{
						    			include($file);
						    		}
						    		else if($s_m['on_passwd_access']==1 && isset($_GET['sm_token']) && $_GET['sm_token'] == md5($_SESSION['sm_token']))
						    		{
						    			include($file);
						    		}
						    		else
						    		{
						    			include('includes/not_allowed_sm.php');
						    		}
						    		
						    	}
						    	else
						    	{
						    		include($file);
						    	}
								
						    }
							else
								include('includes/not_allowed_sm.php');
						
						}
					}
					else if(isset($_GET['m']))
					{
						// On utilise le tableau des sous-menus recupérés lors de leur affichage pour le menu sélectionnés - et on affiche le premier sous-menu de la liste
						$sm = $sous_menus[0];
						$file = "src/".Systeme::sm_folder($sm['id'])."/view/m".$_GET['m']."/".$sm['id'].".php";
						if(file_exists($file))
						{
						    include($file);
						}
					}
					else
					{
						include('includes/accueil.php');
					}
					
				?>
	   </div>
	  
	</div><!-- /wrap -->
	