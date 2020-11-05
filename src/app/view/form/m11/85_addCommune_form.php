<?php
require_once("../../requirements.php");
/*
 Auteur: CODO Paterne
 Date de création : 11/03/2013
*/
$id_commune = 0;
$commune = null;
$selected_items = Systeme::getJsonItemsOnDialog();
if(null!==$selected_items && count($selected_items)>0)
{
	$id_commune = $selected_items[0];
	$commune = new Commune($id_commune);
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
	<div id="form_header"> <h3>Formulaire d'ajout ou de modification d'une commune</h3></div>
    <form action="" method="post" enctype="multipart/form-data">
		<fieldset >
			<legend> Informations du département</legend>
			<table class="form_table" style="text-align:left;">
				<tr>
					<td><span class="required">*</span>Département</td>
					<td>
						<select name="id_departement" class="select2">
						<?php
						  $departements = Departement::all();
						  echo '<option value="0">--select--</option>';
						  foreach($departements as $departement)
						  {
						    if( (isset($_POST['id_departement'])&&$_POST['id_departement']==$departement->id()) || ($id_commune>0&&$commune->departement()->id()==$departement->id()) )
								echo '<option title="'.$departement->description().'" value="'.$departement->id().'" selected="selected">'.$departement->nom().'</option>';
							else
								echo '<option title="'.$departement->description().'" value="'.$departement->id().'">'.$departement->nom().'</option>';
						  }
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td><label for="nom"><span class="required">*</span>Commune de:</label></td>
					<td>
						<input type="text" name="nom" id="nom" value="<?php if(isset($_POST['nom'])) echo $_POST['nom'];else if($id_commune>0) echo $commune->nom();?>"/>
					</td>
				</tr>
				<tr>
					<td><label for="description">Description</label></td>
					<td>
						<input type="text" name="description" id="description" value="<?php if(isset($_POST['description'])) echo $_POST['description'];else if($id_commune>0) echo $commune->description();?>" size="80"/>
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
    	if($id_commune>0)
    		$result = ConfigFormManager::processUpdateCommuneSubmit($intervenant->id(),$id_commune);
    	else
    		$result = ConfigFormManager::processAddCommuneSubmit($intervenant->id());

		AppFormErrorController::checkSubmitResult($result);
    }
	
?>