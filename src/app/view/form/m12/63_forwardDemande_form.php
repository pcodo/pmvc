<?php
require_once("../../requirements.php");
/*
 Auteur: CODO Paterne
 Date de création : 11/03/2013
*/
$demandes = array();
$id_post = 0;
$selected_items = Systeme::getJsonItemsOnDialog();
if(null!==$selected_items && count($selected_items)>0)
{
	$demandes = DemandeObjectBuilder::build($selected_items);	
}


?>

<script>
	$(function(){
		$('input[name=quitter]').bind('click',function(){
			parent.$.fancybox.close();
		});		
	});
</script>
<div align="center">
	<div id="form_header"> <h3>Affectation de demandes</h3></div>
    <form action="" method="post" enctype="multipart/form-data">
		<fieldset>
			<legend> choix du destinataire </legend>
			<table class="form_table" style="text-align:left;">
				<tr>
					<td>
						<select name="id_intervenant_assigned" class="select2">
						<?php
						  $users = Systeme::userList(0,0,true);
						  echo '<option value="0">--select--</option>';
						  foreach($users as $u)
						  {
						    if(isset($_POST['id_intervenant_assigned'])&&$_POST['id_intervenant_assigned']==$u['id'])
								echo '<option value="'.$u['id'].'" selected="selected">'.$u['nom'].' '.$u['prenom'].'</option>';
							else
								echo '<option value="'.$u['id'].'">'.$u['nom'].' '.$u['prenom'].'</option>';
						  }
						?>
						</select>						
					</td>					
				</tr>
				
			</table>
		</fieldset>	
		<fieldset>
			<legend> Liste des demandes sélectionnées </legend>
			<table class="form_table" border="1" style="text-align:left;border-collapse:collapse;">
				<thead>
					<tr style="background-color:gray;">
						<th></th>
						<th>Structure</th>
						<th>Objet</th>											
						<th>Enregistrement</th>											
					</tr>
				</thead>
			<?php
				// On affiche la liste des dossiers selectionnés afin de permettre une désélection
				$i=0;
				foreach($demandes as $d)
				{
					echo '<tr>';
					echo '<td><input type="checkbox" name="item_'.$d->id().'" value="'.$d->id().'" checked="checked"/></td>';			
					echo '<td>'.$d->structure()->nom().'</td>';
					echo '<td>'.$d->objet().'</td>';
					echo '<td>'.Systeme::dateTimeToFrench($d->insertDate()).' par '.$d->insertUser()->fullname().'</td>';				
					echo '</tr>';
					$i++;
				}
			?>
			</table>
		</fieldset>

		<fieldset>
			<legend> Validation </legend>
			<table cellspacing="10" width="100%" align="left" style="text-align:right;">
				<tr>
					<td>
						<input type="submit" name="valider" value="Envoyer"/>
						<input type="reset" name="annuler" value="Annuler"/>
						<input type="button" name="quitter" value="Quitter"/>
					</td>
				
				</tr>				
			</table>
		</fieldset>
	</form>
</div>

<?php
    if(isset($_POST['valider']))
    {
    	$result = DemandeFormManager::forwardDemandeSubmit($demandes,$intervenant->id());
		
		AppFormErrorController::checkSubmitResult($result);
    }
	
?>