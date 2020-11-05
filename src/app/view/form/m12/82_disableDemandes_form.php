<?php
require_once("../../requirements.php");
/*
 Auteur: CODO Paterne
 Date de création : 25/04/2018
*/
$demandes = array();
$selected_items = Systeme::getJsonItemsOnDialog();
if(null!==$selected_items && count($selected_items)>0)
{
	$demandes = DemandeObjectBuilder::build($selected_items,array('key'=>'id','order'=>'DESC'));	
}
if($demandes==null || count($demandes)==0)
{
	Systeme::debug('Veuillez sélectionner les demandes concernées!');
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
	<div id="form_header"> <h3>Désactivation de demandes</h3></div>
    <form action="" method="post" enctype="multipart/form-data">
		
		<fieldset>
			<legend> Liste des demandes sélectionnés </legend>
			<table class="form_table" border="1" cellpadding="10" style="text-align:left;border-collapse:collapse;">
				<thead>
					<tr style="background-color:gray;">
						<th></th>
						<th>Ordre</th>
						<th>N°</th>
						<th>Structure</th>
						<th>Objet</th>
						<th>Rejet</th>  																
					</tr>
				</thead>
			<?php
				// On affiche la liste des dossiers selectionnés afin de permettre une désélection
				$i=0;
				foreach($demandes as $item)
				{
					echo '<tr>';
						echo '<td><input type="checkbox" name="item_'.$item->id().'" value="'.$item->id().'" checked="checked"/></td>';	
						echo '<td>'.(++$i).'</td>';
						echo '<td>'.$item->numero().'</td>';									
						echo '<td>'.$item->structure()->nom().'</td>';										
						echo '<td>'.$item->objet().'</td>';	
						$tokenProgress = $item->tokenProgressByState(REJECTED);								
						echo '<td>'.Systeme::dateTimeToFrench($tokenProgress->insertDate()).' par '.$tokenProgress->insertUser()->fullname().'</td>';				
					echo '</tr>';
					$i++;					
				}
				echo '</tr>';
			?>
			</table>
		</fieldset>

		<fieldset>
			<legend> Validation </legend>
			<table cellspacing="10" width="100%" align="left" style="text-align:right;">
				<tr>
					<td>
						<input type="submit" name="valider" value="Valider"/>
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
    	$result = DemandeFormManager::disableDemandesSubmit($demandes, $intervenant->id());
		
		AppFormErrorController::checkSubmitResult($result);
    }
	
?>