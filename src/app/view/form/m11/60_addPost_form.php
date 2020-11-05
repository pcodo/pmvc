<?php
require_once("../../requirements.php");
/*
 Auteur: CODO Paterne
 Date de création : 11/03/2013
*/
$dossiers = array();
$id_post = 0;
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
			<legend> Choix de la catégorie </legend>
			<table class="form_table" style="text-align:left;">
				<tr>
					<td><label for="subject">Sujet (titre)</label></td>
					<td>
						<input title="subject" type="text" name="subject" id="subject" value="<?php if(isset($_POST['subject'])) echo $_POST['subject'];else if($id_post>0) echo $post->subject();?>" size="80"/>
					</td>					
				</tr>
				<tr>
					<td colspan="2"> <textarea name="message" cols="80" placeholder="Votre message ici!"><?php if(isset($_POST['message'])) echo $_POST['message']; else if($id_post>0) echo $post->message(); ?></textarea></td>
				</tr>
				<tr>
					<td colspan="2"><input type="checkbox" name="enabled" <?php if(isset($_POST['enabled'])||($id_post>0 && $post->enabled()==1)) echo 'checked="checked"';?> /><label for="enabled">Défiler ?</label></td>
									
				</tr>
				<tr>
					<td><label for="image_uploaded">Image</label></td>
					<td><input type="file" name="image_uploaded" id="image_uploaded" size="30" /></td>
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
    	if($id_post>0){
			$result = ConfigFormManager::processUpdatePostSubmit($intervenant->id(),$id_post);
		}
		else{
			$result = ConfigFormManager::processAddPostSubmit($intervenant->id());
		}		
		
		AppFormErrorController::checkSubmitResult($result);
    }
	
?>