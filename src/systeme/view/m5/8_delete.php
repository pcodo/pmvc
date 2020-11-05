<?php
/*
  Formulaire d'enregistrement des membres du personnel
  Auteur: CODO Paterne
*/
if(!isset($_GET['id_membre'])) exit();
$id_membre = $db->escape($_GET['id_membre']);
include('8_fiche.php');
?>
<script>
</script>

<h4 id="error_shower"></h4>
<div align = "center" class="radius">
	<form action="" method="post" enctype="multipart/form-data">
		<fieldset >
			<legend> MOTIFS DE SUPPRESSION</legend>
			<table class="form_table">
				<tr>
					<td><label for="motif"><span style="color:red;">*</span>MOTIF DE SUPPERSSION</label></td>
					<td><textarea name="motif" cols="45" rows="10"><?php if(isset($_POST['motif'])) echo $_POST['motif'];?></textarea></td>
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
   $validate_state = 0;
   if(isset($_POST['valider'])&&isset($_POST['motif']))
   {
      $motif = isset($_POST['motif'])?$db->escape($_POST['motif']):'';
      if($motif!='')
	  {
		//insertion de l'intervenant dans la table intervenant_ex
		if($db->insertion('intervenant_ex','',$membre['nom'],$membre['prenom'],$membre['login'],$membre['mdp'],$membre['genre'],$membre['etat_matrimoniale'],$membre['nombre_enfant'],$membre['telephone'],$membre['email'],$membre['photo_url'],$membre['id_poste'],$membre['salaire'],$membre['mode_payement'],$membre['date_embauche'],$membre['date_service'],$membre['status'],$motif,$membre['id'],$intervenant->id(),Systeme::now()))
		{
		  // suppression de ce membre de la liste des intervenant
		  // $db->execute('delete from intervenant where id='.$id_membre);
		  if(isset($_GET['action_source']) && $_GET['action_source'] =='user_plus' )
		  	Systeme::redirect('index.php?m='.$_GET['m'].'&sm=38');		  
		  else
		  	Systeme::redirect('index.php?m='.$_GET['m'].'&sm=8');
		}
		  
		
	  }
	  else
	  {
		$validate_state = 2;
	  }
   }
?>

