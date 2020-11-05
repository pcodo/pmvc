<?php
require_once("../../requirements.php");
/*
 Auteur: CODO Paterne
 Date de création : 11/03/2013
*/
$id_structure = 0;
$structure = null;
$selected_items = Systeme::getJsonItemsOnDialog();
if(null!==$selected_items && count($selected_items)>0)
{
	$id_structure = $selected_items[0];
	$structure = new Structure($id_structure);
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
<div align="center">
	<div id="form_header"> <h3>Formulaire d'ajout ou de modification d'une structure</h3></div>
    <form action="" method="post" enctype="multipart/form-data">
		<fieldset >
			<legend> Informations de la structure </legend>
			<table class="form_table" style="text-align:left;">
				<tr >
	                <td><label for="structure_type"><span class="required">*</span>Type de structure</label></td>
	                <td colspan="3">
	                    <select name="structure_type" id="structure_type" class="hider_select select2">
	                        <option value="-1">-- select --</option>
	                        <option value="0" <?php if(isset($_POST['structure_type'])&&$_POST['structure_type']==0) echo 'selected="selected"';?>>Nouveau</option>
	                        <?php
	                            $structuretypes = Structuretype::all();
	                            foreach($structuretypes as $structuretype)
	                            {
	                                if( (isset($_POST['structure_type'])&&$_POST['structure_type']==$structuretype->id()) || ($id_structure>0&&$structure->structureType()->id()==$structuretype->id()) )
	                                    echo '<option value="'.$structuretype->id().'" selected="selected">'.$structuretype->nom().'</option>';
	                                else
	                                    echo '<option value="'.$structuretype->id().'">'.$structuretype->nom().'</option>';
	                            }
	                        ?>
	                    </select>
	                   <input type="text" name="new_structure_type_nom" placeholder="Nom du nouveau type" value="<?php if(isset($_POST['new_structure_type_nom'])) echo $_POST['new_structure_type_nom'];?>" class="structure_type" style="<?php if(isset($_POST['structure_type'])&&$_POST['structure_type']==0) echo 'display:block;'; else echo 'display:none;'; ?>" />
	                   
	                </td>                
	            </tr>
				<tr>
					<td><label for="nom"><span class="required">*</span>Nom</label></td>
					<td>
						<input type="text" name="nom" id="nom" value="<?php if(isset($_POST['nom'])) echo $_POST['nom'];else if($id_structure>0) echo $structure->nom();?>"/>
					</td>
				</tr>
				<tr>
					<td><label for="description">Description</label></td>
					<td>
						<input type="text" name="description" id="description" value="<?php if(isset($_POST['description'])) echo $_POST['description'];else if($id_structure>0) echo $structure->description();?>" size="80"/>
					</td>
				</tr>
				<tr>
					<td><label for="telephone">Téléphone</label></td>
					<td>
						<input type="text" name="telephone" id="telephone" value="<?php if(isset($_POST['telephone'])) echo $_POST['telephone'];else if($id_structure>0) echo $structure->telephone();?>" size="30"/>
					</td>
				</tr>
				<tr>
					<td><label for="telephone_alt">Téléphone (alternatif)</label></td>
					<td>
						<input type="text" name="telephone_alt" id="telephone_alt" value="<?php if(isset($_POST['telephone_alt'])) echo $_POST['telephone_alt'];else if($id_structure>0) echo $structure->telephoneAlt();?>" size="30"/>
					</td>
				</tr>
				<tr>
					<td><label for="mail">E-Mail</label></td>
					<td>
						<input type="text" name="mail" id="mail" value="<?php if(isset($_POST['mail'])) echo $_POST['mail'];else if($id_structure>0) echo $structure->Mail();?>" size="30"/>
					</td>
				</tr>
				<tr>
					<td><label for="mail_alt">E-Mail (alternatif)</label></td>
					<td>
						<input type="text" name="mail_alt" id="mail_alt" value="<?php if(isset($_POST['mail_alt'])) echo $_POST['mail_alt'];else if($id_structure>0) echo $structure->MailAlt();?>" size="30"/>
					</td>
				</tr>
				<tr>
					<td><span class="required">*</span>Département</td>
					<td>
						<select name="id_departement">
						<?php
						  $departements = Departement::all();
						  echo '<option value="0">--select--</option>';
						  foreach($departements as $departement)
						  {
						    if( (isset($_POST['id_departement'])&&$_POST['id_departement']==$departement->id()) || ($id_structure>0&&$structure->departement()->id()==$departement->id()) )
								echo '<option title="'.$departement->description().'" value="'.$departement->id().'" selected="selected">'.$departement->nom().'</option>';
							else
								echo '<option title="'.$departement->description().'" value="'.$departement->id().'">'.$departement->nom().'</option>';
						  }
						?>
						</select>
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
    	if($id_structure>0)
    		$result = ConfigFormManager::processUpdateStructureSubmit($intervenant->id(),$id_structure);
    	else
    		$result = ConfigFormManager::processAddStructureSubmit($intervenant->id());

		AppFormErrorController::checkSubmitResult($result);
    }
	
?>