<?php
/*
 Rôle: liste des postes
 Auteur: CODO Paterne
 Date de création : 11/03/2013
*/  
if(!(isset($_GET['id_poste'])&&is_numeric($_GET['id_poste']))) exit();
$id_poste =  $db->escape($_GET['id_poste']);
?>
<div align="center" class="main_div">
   <form action="" method = "post">
        <table align="center">
		    <tr><td><input type="hidden" name="type_dossier" value="<?php isset($_POST['type_dossier'])?$_POST['type_dossier']:''?>"/></td></tr>
			<tr><td id="stat_menu_list">
				<ul class="action_button_list">
				    <?php
					  $token_types = $db->queryAllRecords('select * from token_type');
					  
					  foreach($token_types as $tpd)
					  {
						$posteTypes = $db->queryAllRecords('select ptd.* from poste_token_type ptd where ptd.id_poste = '.$id_poste.' AND ptd.token_type_id='.$tpd['id']);
					  	if( (isset($_POST['type_dossier'])&&$_POST['type_dossier']==$tpd['id']) || count($posteTypes)>0)
							echo '<li class="dossier_type_'.$tpd['id'].' inner-center current_state_menu_poste_config"><input type="checkbox" name="type_'.$tpd['id'].'" checked="checked"/><a href="" value="'.$tpd['id'].'"><span class="radius">'.$tpd['nom'].'</span> </a></li>';
						else
							echo '<li class="dossier_type_'.$tpd['id'].' inner-center"><input type="checkbox" name="type_'.$tpd['id'].'"/><a href="" value="'.$tpd['id'].'"><span class="radius">'.$tpd['nom'].'</span> </a></li>';
					  }
					?>
					
				</ul>
			</td></tr>
		</table>

		<table>
			<?php
				/*$etat_dossiers = $db->queryAllRecords('select * from ep_etat_dossier');
				foreach ($etat_dossiers as $key => $etat) {
					echo '<tr>';
					$posteEtats = $db->queryAllRecords('select pte.* from ep_poste_etat_dossier pte where pte.id_poste = '.$id_poste.' AND pte.id_etat_dossier='.$etat['id']);
					 if( count($posteEtats)>0)
						echo '<td><input type="checkbox" name="etat_'.$etat['id'].'" checked="checked"/>'.$etat['description'].' ['.strtoupper($etat['nom']).']</td>';
					else
						echo '<td><input type="checkbox" name="etat_'.$etat['id'].'"/>'.$etat['description'].' ['.strtoupper($etat['nom']).']</td>';
					echo '</tr>';
				}*/
			?>
		</table>

		
		<?php include('config_poste_sous_menu.php');?>
		<div style="margin-top:10px; text-align:right;margin-right:10%;">
			<input style="margin-right:20px;" type="reset" name="annuler" value="Annuler"/>
			<input style="margin-right:20px;" type="submit" name="valider" value="Valider"/>
			<input type="button" name="" value="Quitter" onclick="leave();"/>
		</div>
   </form>
</div>

<?php
	if(isset($_POST['valider']))
	{
		// gestion des types de dossiers
		$db->execute('delete from `poste_token_type` where id_poste='.$id_poste);
		foreach($token_types as $key => $tpd)
		{
			if(isset($_POST['type_'.$tpd['id']]))
			{
				$db->insertion('poste_token_type','',$id_poste,$tpd['id'],$intervenant->id(),Systeme::now());
			}
		}
		// fin type de dossier

		// gestion des etats de dossiers
		/*$db->execute('delete from `ep_poste_etat_dossier` where id_poste='.$id_poste);
		foreach($etat_dossiers as $key => $etat)
		{
			if(isset($_POST['etat_'.$etat['id']]))
			{
				$db->insertion('ep_poste_etat_dossier','',$id_poste,$etat['id'],$intervenant->id(),Systeme::now());
			}
		}*/
		// fin etat de dossier

		$max_sous_menu_id = $db->lastTabId('sous_menu');
		$selected_sous_menus_ids = array();
		for($i=0;$i<=$max_sous_menu_id;$i++)
		{
			if(isset($_POST['sm_'.$i])&&$_POST['sm_'.$i]=='on')
			{
			   // l'indice $i represente en même temps le sous_menu à ajouter
			   $selected_sous_menus_ids[]=$i;
			}
		}
		// Actualisation des sous-menus du poste 
		Systeme::updatePosteSousMenu($id_poste,$selected_sous_menus_ids);
		//echo '<script language="javascript">document.location.href="index.php?m='.$_GET['m'].'&sm=10"</script>';
		//echo '<script language="javascript"> $(function(){ $.redirect("index.php?m='.$_GET['m'].'&sm=10"); }); </script>';
		Systeme::redirect('index.php?m='.$_GET['m'].'&sm=10');
	}
?>
