<?php
require_once("../../requirements.php");
/*
 Auteur: CODO Paterne
 Date de création : 11/03/2013
*/
$dossiers = array();
$selected_items = Systeme::getJsonItemsOnDialog();
if(null!==$selected_items && count($selected_items)>0)
{
	$id_post = $selected_items[0];
	$post = new Post($id_post);	
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
	<div id="form_header"> <h3>Formulaire d'enregistrement des publications à l'accueil</h3></div>
    <form action="" method="post" enctype="multipart/form-data">
		<fieldset >
			<legend> </legend>
			
			<table class="form_table" style="text-align:left;">
				<tr>
					<td colspan="2"><input type="checkbox" name="disabled" <?php if(isset($_POST['disabled'])) echo 'checked="checked"';?> /><label for="disabled">Désactiver tous les défilements?</label></td>
									
				</tr>
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
    	$result = ConfigFormManager::processDisableAllPostPostSubmit($intervenant->id());
				
		AppFormErrorController::checkSubmitResult($result);
    }
	
?>