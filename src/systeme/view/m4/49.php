<?php
/*
 Auteur: CODO Paterne
 Date de création : 11/03/2013
*/
/* Liste des onglets */
?>
<div align="center">
    <h3> Liste des grands menus ou onglets </h3>
	<form action="" method="post">
    <table border="0" id="dataTable" class="display datatable">
        <thead>
            <tr height="30" align="center">
				<th>Catgorie</th>
				<th>Changer la catégorie</th>
				<th>Changer la description</th>
				<th>Position</th>
				<th>Activer/Désactiver</th>
			</tr>
        </thead>
        <?php
        $categories = $db->queryAllRecords('select * from categorie_menu');
		foreach ($categories as $cat){
			echo '<tr>';
				echo '<td title="'.$cat['description'].'">';
					echo $cat['designation'];
				echo '</td>';
				echo '<td title="Nouvelle désignation pour '.$cat['designation'].'">';
					echo '<input  type="text" name="new_catName_'.$cat['id'].'"/>';
				echo '</td>';
				echo '<td title="Nouvelle description pour '.$cat['designation'].' ">';
					echo '<input type="text" name="new_catDesc_'.$cat['id'].'"/>';
				echo '</td>';
				echo '<td title="Nouvelle position pour '.$cat['designation'].' ">';
					echo '<input type="text" name="position_'.$cat['id'].'" value="'.$cat['position'].'"/>';
				echo '</td>';
				echo '<td>';
						echo '<select name="active_state_'.$cat['id'].'">';
							echo '<option value="1" '.(( (isset($_POST['active_state'])&&$_POST['active_state']==1) || ( isset($cat['active_state']) && $cat['active_state'] == 1) )?'selected = "selected"':'').'> Activé </option>';
							echo '<option value="0" '.(( (isset($_POST['active_state'])&&$_POST['active_state']==0) || ( isset($cat['active_state']) && $cat['active_state'] == 0) )?'selected = "selected"':'').'> Désactivé </option>';
						echo '</select>';
				echo '</td>';
			echo '</tr>';
        }
      	
		?>
    </table>
	<table>
		<tr>
			<td><input type="submit" name="valider" value="Valider"/></td>
			<td><input type="reset" name="annuler" value="Annuler"/></td>
		</tr>
	</table>
	</form>
</div>

<?php
/* Traitement de la modification des categories*/
if(isset($_POST['valider']))
{
	$ok=false;
	foreach ($categories as $cat)
	{
	    $attributes = array();
		$cat_active_state = -1;
	    if(isset($_POST['new_catName_'.$cat['id']])&&$_POST['new_catName_'.$cat['id']]!='')
		{
			$attributes['designation']=$db->escape($_POST['new_catName_'.$cat['id']]);
		}
		if(isset($_POST['new_catDesc_'.$cat['id']])&&$_POST['new_catDesc_'.$cat['id']]!='')
		{
			$attributes['description']=$db->escape($_POST['new_catDesc_'.$cat['id']]);
		}
		if(isset($_POST['active_state_'.$cat['id']]))
		{
		    $cat_active_state = $db->escape($_POST['active_state_'.$cat['id']]);
			$attributes['active_state']=$cat_active_state;
		}
		if(isset($_POST['position_'.$cat['id']]))
		{
			$attributes['position']=$db->escape($_POST['position_'.$cat['id']]);
		}
		
		if(count($attributes)!=0)
		{
			$db->update('categorie_menu',$attributes,array('id'=>$cat['id']));
			if($cat_active_state!=-1)
			{
				$db->update('menu',array('active_state'=>$cat_active_state),array('id_categorie'=>$cat['id']));
			}
			$ok = true;
		}
	}
	if($ok)
	{
		Systeme::redirect('index.php?m='.$_GET['m'].'&sm=49');
	}		
}

?>