<?php
/*
 Auteur: CODO Paterne
 Date de création : 11/03/2013
*/ 
if(!isset($id_poste)) exit();// la variable $id_poste doit exister sur le fichier incluant celui-ci.
if($id_poste&&$id_poste>0)
{
	$poste_request = $db->queryOneRecord('select * from poste where id='.$id_poste); 
?>
	<script>
	$(function() {
		$('.menu input[type=button]').click(function(){
			if($(this).attr('value')=='+')
			{
				// plier (cacher) la zone des sous-menus du menu selectionné
				$('#sm_'+$(this).attr('name')).hide();
				$(this).attr('value','-');
			}			
			else
			{
				 // deplier (afficher) la zone des sous-menus du menu selectionné
				$('#sm_'+$(this).attr('name')).show();
				$(this).attr('value','+');
			}
				
		});
		$('.menu input[type=checkbox]').click(function(){
			var id_menu = $(this).attr('name').split('_')[1];
			// verifier qu'un checkbox est coché
			if($(this).is(':checked'))
			{
				$('#sm_'+id_menu+' input[type=checkbox]').attr('checked',false); // on décoche tout
				$('#sm_'+id_menu+' input[type=checkbox]').trigger('click');// puis on recoche tout par click
			}
			else $('#sm_'+id_menu+' input[type=checkbox]').attr('checked',false);// on décoche tout
								
		});
	 });
	</script>
	   <h3> CONFIGURATION DES DROITS DU POSTE <?php echo strtoupper($poste_request['designation']); ?> </h3>
		<table id="liste_menus" cellspacing="15" width="90%">
		<?php
			$menus = Systeme::menus();
			$alternate_row_color = true;$first = "rgb(204,204,204);";$second = "rgb(200,200,2);";
			foreach($menus as $m)
			{
				$check_menu = false;
				$sm = $m['sous_menus']; //Non!!! voir la ligne suivante
				//$sm = $intervenant->sous_menus($m['id']);// L'utilisateur ne peut attribuer que parmi les postes auquels lui-même a accès.
				$poste_sm = Systeme::sous_menus($m['id'],$id_poste); 
				$poste_sm_id = Systeme::array_key_values($poste_sm,'id');
				$nbr1 = count($sm);$nbr2 = count($poste_sm);
				if($nbr1==$nbr2&&$nbr1!=0)$check_menu = true;
				echo '<tr>';
					echo '<td>';
						echo '<div class="menu" id="m_'.$m['id'].'" title="'.$m['description'].'">';
							echo '<input type="checkbox" name="m_'.$m['id'].'" '.($check_menu?'checked="checked"':'').'/><input type="button" name="'.$m['id'].'" value="+" /><label>'.$m['designation'].'</label>';
						echo '</div>';
						echo '<div class="sous_menus" id="sm_'.$m['id'].'">';
							foreach($sm as $s)
							{
								$check_sm = Systeme::hasSousMenu($id_poste,$s['id']);
								echo '<div class="sm" title="'.$s['description'].'"><input type="checkbox" name="sm_'.$s['id'].'" '.($check_sm?'checked="checked"':'').' /><label>'.$s['designation'].'</label></div>';
							}
						echo '</div>';
					echo '</td>';
				echo '</tr>';
			}
			
		?>
		</table>
<?php
}
?>

