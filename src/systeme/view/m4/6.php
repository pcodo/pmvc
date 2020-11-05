<?php
/*
 Auteur: CODO Paterne
 Date de création : 11/03/2013
*/
/* Liste des menu */
    $liste_cat =$db->queryAllRecords('select * from categorie_menu');
	
?>
<div align="center">
    <h3> Liste des Menus </h3>
	<form action="" method="post">
    <table border="0" id="dataTable" class="display datatable">
        <thead>
            <tr height="30" align="center">
				<th>id</th>
                <th>Menu</th>
                <th>Catégorie</th>
				<th>Changer la catégorie</th>
				<th>Etat</th>
				<th>Détails</th>
            </tr>
        </thead>
        <?php
        $menus = $db->query('select menu.*,cat.id id_categorie,cat.designation categorie from menu left join categorie_menu cat on menu.id_categorie=cat.id');
        while ($menu = $menus->fetch()) {
			echo '<tr '.($menu['active_state']==0?'style="background-color:pink;" title="Menu désactivé!"':'').'>';
			echo '<td>';
			echo $menu['id'];
			echo '</td>';
			echo '<td title="'.$menu['description'].'">';
			echo $menu['designation'];
			echo '</td>';
			echo '<td>';
			echo $menu['categorie'];
			echo '</td>';
			echo '<td>';
				echo '<select name="cat_menu_'.$menu['id'].'">';
					foreach($liste_cat as $cat)
					{   
						if($cat['id']==$menu['id_categorie'])
							echo '<option value="'.$cat['id'].'" selected="selected">'.$cat['designation'].'</option>';
						else
							echo '<option value="'.$cat['id'].'">'.$cat['designation'].'</option>';
					}
					
				echo '</select>';
			echo '</td>';
			echo '<td>';
				echo '<select name="active_state_'.$menu['id'].'">';
					echo '<option value="0" '.(($menu['active_state']==0)?'selected="selected"':'').'>Désactivé</option>';
					echo '<option value="1" '.(($menu['active_state']==1)?'selected="selected"':'').'>Activé</option>';
				echo '</select>';
			echo '</td>';
			echo '<td>';
			echo '<a href="index.php?m='.$_GET['m'].'&sm=5_update&id_menu='.$menu['id'].'">Attributs</a>';
			echo '</td>';
			echo '</tr>';
        }
      	
		?>
    </table>
	<table>
		<tr>
			<?php
				$idMenu_max = $db->lastTabId('menu');
				echo '<input type="hidden" name="menu_id_max" value="'.$idMenu_max.'"/>';
			?>
			<td><input type="submit" name="valider" value="Valider"/></td>
			<td><input type="reset" name="annuler" value="Annuler"/></td>
		</tr>
	</table>
	</form>
</div>

<?php
/* Traitement de la modification des categories*/
if(isset($_POST['valider'])&&isset($_POST['menu_id_max']))
{
	
	$menu_id_max = $db->escape($_POST['menu_id_max']);
	if($menu_id_max!='')
	{
		for($i=1;$i<=$menu_id_max;$i++)
		{	
			if(isset($_POST['cat_menu_'.$i]))
			{
				$ok = $db->update('menu', array('id_categorie'=>$_POST['cat_menu_'.$i],'active_state'=>$_POST['active_state_'.$i]),array('id'=>$i));
				if($ok)
				echo '<script language="javascript">document.location.href="index.php?m='.$_GET['m'].'&sm=6"</script>';  
			}
		}
	}
}

?>