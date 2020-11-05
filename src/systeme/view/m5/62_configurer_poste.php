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
		<?php include('config_poste_sous_menu_admin.php');?>
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
		Systeme::redirect('index.php?m='.$_GET['m'].'&sm=10');		
	}
?>
