<?php
/*
  Formulaire de création de ménus avec des sous_menus
  Auteur: CODO Paterne

*/
?>
<h3>Changement de mot de passe </h3>
<div align = "center" class="radius">
	<form action="" method="post">
		<fieldset >
			<legend> Ancien mot de passe </legend>
			<table class="form_table">
				<tr>
					<td><label for="currentpswd">MOT DE PASSE ACTUEL</label></td>
					<td><input type="password" name="currentpswd" id="currentpswd" size="30"/></td>
				</tr>
			</table>
		</fieldset>
		<fieldset >
			<legend> Nouveau mot de passe </legend>
			<table class="form_table">
				<tr>
					<td><label for="newpswd">NOUVEAU MOT DE PASSE</label></td>
					<td><input type="password" name="newpswd" id="newpswd" size="30"/></td>
				</tr>
				<tr>
					<td><label for="newpswd_conf">CONFIRMATION</label></td>
					<td><input title="confirmer le nouveau mot de passe" type="password" name="newpswd_conf" id="newpswd_conf" size="30"/></td>
				</tr>
			</table>
		</fieldset>
		<fieldset>
			<legend> Validation </legend>
			<table cellspacing="10" width="100%" style="text-align:right;">
				<tr>
					<td>
						<input type="submit" name="valider" value="Valider"/>
						<input type="reset" name="annuler" value="Annuler"/>
					</td>
				
				</tr>				
			</table>
		</fieldset>
	</form>
</div>

<?php
   if(isset($_POST['valider'])&&isset($_POST['currentpswd'])&&$_POST['currentpswd']!=''&&isset($_POST['newpswd'])&&$_POST['newpswd']!='')
   {
        $currentpswd = $db->escape($_POST['currentpswd']);
        $newpswd = $db->escape($_POST['newpswd']);
        $newpswd_conf = $db->escape($_POST['newpswd_conf']);
		$id_intervenant = $_SESSION['id_intervenant'];
		$rep = $db->countMatchedRows('intervenant', array('id'=>$id_intervenant,'mdp'=>md5($currentpswd)));
		if($rep==1&&$newpswd==$newpswd_conf)// on a une occurrence donc c'est bon:: on verifie aussi si les nouveaux mots de passes sont conformes
		{
			$db->update('intervenant',array('mdp'=>md5($newpswd)), array('id'=>$id_intervenant));
			// redirection :: ici, on va detruire la session, et le renvoyer sur index
			session_destroy();
		    echo '<script language="javascript">document.location.href="index.php"</script>';
		}
	  
   }
?>