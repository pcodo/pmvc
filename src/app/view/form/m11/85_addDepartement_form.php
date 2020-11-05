<?php
require_once("../../requirements.php");
/*
 Auteur: CODO Paterne
 Date de création : 11/03/2013
*/
$id_departement = 0;
$departement = null;
$selected_items = Systeme::getJsonItemsOnDialog();
if(null!==$selected_items && count($selected_items)>0)
{
	$id_departement = $selected_items[0];
	$departement = new Departement($id_departement);
}

?>

<script>
	$(function(){
		$('input[name=quitter]').bind('click',function(){
			parent.$.fancybox.close();
		});
		
		$('select[name=type_dossier]').bind('change',function(){
			$('#dossierTypeFormBlock').html('');
			$('#dossierTypeFormBlock').load('60_addDossier_'+$(this).val()+'.php',function(){
				$('#dossierTypeFormBlock').fadeIn('slow');
			});
		});
		
	});
</script>
<div align="center" id="form_div">
	<div id="form_header"> <h3>Formulaire d'ajout ou de modification d'un departement</h3></div>
    <form action="" method="post" enctype="multipart/form-data">
		<fieldset >
			<legend> Informations du département</legend>
			<table class="form_table" style="text-align:left;">
				<tr>
					<td><label for="nom"><span class="required">*</span>Nom</label></td>
					<td>
						<input type="text" name="nom" id="nom" value="<?php if(isset($_POST['nom'])) echo $_POST['nom'];else if($id_departement>0) echo $departement->nom();?>"/>
					</td>
				</tr>
				<tr>
					<td><label for="description">Description</label></td>
					<td>
						<input type="text" name="description" id="description" value="<?php if(isset($_POST['description'])) echo $_POST['description'];else if($id_departement>0) echo $departement->description();?>" size="80"/>
					</td>
				</tr>									
			</table>
		</fieldset>
		
		<fieldset>
			<legend> Validation </legend>
			<table cellspacing="10" width="100%" align="left" style="text-align:right;">
				<tr>
					<td>
						<input type="submit" name="valider" value="Enregistrer"/>
						<input type="reset" name="annuler" value="Annuler"/>
						<input type="button" name="quitter" value="Quitter"/>
					</td>
				
				</tr>				
			</table>
		</fieldset>
	</form>
</div>

<?php
	//Systeme::array_key_values()
    if(isset($_POST['valider']))
    {
    	if($id_departement>0)
    		$result = ConfigFormManager::processUpdateDepartementSubmit($intervenant->id(),$id_departement);
    	else
    		$result = ConfigFormManager::processAddDepartementSubmit($intervenant->id());

		AppFormErrorController::checkSubmitResult($result);
    }
	
?>