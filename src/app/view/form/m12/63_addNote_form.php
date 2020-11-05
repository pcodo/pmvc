<?php
require_once("../../requirements.php");
/*
 Auteur: CODO Paterne
 Date de création : 11/03/2013
*/
$id_note = 0;
$note = null;
$id_demande = 0;
$demande = null;
$selected_items = Systeme::getJsonItemsOnDialog();
if(null!==$selected_items && count($selected_items)>0)
{
	$id_demande = $selected_items[0];
	$demande = new Demande($id_demande);
}
if($id_note>0) $id_demande = $note->demande()->id();

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
	<div id="form_header"> <h3>Formulaire de note de demande</h3></div>
    <form action="" method="post" enctype="multipart/form-data">
		<fieldset >
			<legend> Informations</legend>
			<table class="form_table" style="text-align:left;">
				<tr>
					<td><label for="objet"><span class="required">*</span>Objet</label></td>
					<td>
						<input type="text" name="objet" id="objet" size="67" value="<?php if(isset($_POST['objet'])) echo $_POST['objet']; else if($id_note>0) echo $note->objet();?>"/>
					</td>
				</tr>
				<tr>
					<td><label for="description"><span class="required">*</span>Description</label></td>
					<td>
						<textarea name="description" id="description" rows="5" cols="50"><?php if(isset($_POST['description'])) echo $_POST['description'];else if($id_note>0) echo $note->description(); ?></textarea>
					</td>					
				</tr>	
				<tr>
					<td><label for="objet">Fichier</label></td>
					<td>
						<input type="file" name="fichier"/>
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
    	if($id_note>0)
    		$result = DemandeFormManager::updateNoteSubmit($id_note,$intervenant->id());
    	else
    		$result = DemandeFormManager::addNoteSubmit($id_demande,$intervenant->id());

		AppFormErrorController::checkSubmitResult($result);
    }
	
?>