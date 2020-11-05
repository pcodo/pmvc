<?php
/*
  Formulaire d'enregistrement des membres du personnel
  Auteur: CODO Paterne

*/
?>
<script>
$(function(){
	function load_poste(id_direction)
	{
		$.get(site_url+'ajax/request.php?req=4&id_direction='+id_direction, function(data) {
		    $('select[name=poste]').html('');
			var obj = $.parseJSON(data);
			var previous_poste = parseInt($('#previous_poste').val());
			var html = '';
			$.each(obj, function() {
			   if(!isNaN(previous_poste)&&previous_poste==this['id'])
					html+='<option value="'+this['id']+'" selected="selected">'+this['designation']+'</option>';
				else
					html+='<option value="'+this['id']+'">'+this['designation']+'</option>';
			});
			$('select[name=poste]').html(html);
		});
	}
	$('select[name=direction]').change(function(){
		load_poste($(this).val());
	});
	load_poste(0);	

	$('#auto_password_gene').bind('click', function(){
		if($(this).is(':checked'))
		{
			$('#mdp,#mdp_conf').val('00000').attr('readonly',true);
		}
		else
		{
			$('#mdp,#mdp_conf').val('').attr('readonly',false);
		}
	});
});
</script>
<style>
	.form_table td {width:300px;}
	#error_shower {background-color:inherit;color:red;}
	
</style>
<h3> Enregistrement d'un nouveau membre du personnel </h3>
<h4>Les champs portant les astérisques (<span style="color:red;">*</span>) sont obligatoires! </h4>
<h4 id="error_shower"></h4>
<div align = "center" class="radius">
	<?php
		echo '<img class = "img_1" src = "uploads/intervenant/0.jpeg"/>';
	?>
	<form action="" method="post" enctype="multipart/form-data">
		<fieldset >
			<legend> INFORMATIONS GENERALES</legend>
			<table class="form_table">
				<tr>
					<td><label for="nom"><span style="color:red;">*</span>NOM</label></td>
					<td><input type="text" name="nom" id="nom" size="30" value="<?php if(isset($_POST['nom'])) echo $_POST['nom'];?>"/></td>
				</tr>	
				<tr>
					<td><label for="prenom"><span style="color:red;">*</span>PRENOMS</label></td>
					<td><input type="text" name="prenom" id="prenom" size="30" value="<?php if(isset($_POST['prenom'])) echo $_POST['prenom'];?>"/></td>
				</tr>
				<tr>
					<td><label for="genre"><span style="color:red;">*</span>GENRE</label></td> 
					<td> 
						M <input type="radio" id="genre" name="genre"  value="0" <?php if (isset($_POST['genre']) && $_POST['genre'] == 0) echo 'checked="checked"'; ?>/>
						F <input type="radio" id="genre" name="genre" value="1" <?php if (isset($_POST['genre']) && $_POST['genre'] == 1) echo 'checked="checked"'; ?>/>
					</td>
				</tr>
				<tr style="display:none;">
					<td><label for="matrimoniale"><span style="color:red;">*</span>SITUATION MATRIMONIALE</label></td>
					<td>
						<select name="matrimoniale">
							<option value="0" <?php if(isset($_POST['matrimoniale'])&&$_POST['matrimoniale']==0) echo 'selected="selected"'; else echo 'selected="selected"'?> >Célibataire</option>
							<option value="1" <?php if(isset($_POST['matrimoniale'])&&$_POST['matrimoniale']==1) echo 'selected="selected"'; ?>  >Marié(e)</option>
							<option value="2" <?php if(isset($_POST['matrimoniale'])&&$_POST['matrimoniale']==2) echo 'selected="selected"'; ?>  >Divorcé(e)</option>
						</select>
					</td>
				</tr>	
				<tr style="display:none;">
					<td><label for="nombre_enfant"><span style="color:red;">*</span>NOMBRE D'ENFANTS</label></td>
					<td><input type="text" name="nombre_enfant" id="nombre_enfant" size="30" value="<?php if(isset($_POST['nombre_enfant'])) echo $_POST['nombre_enfant']; else echo '0';?>"/></td>
				</tr>
				<tr>
					<td title="Séparer les numeros de téléphones par des virgules!"><label for="telephone"><span style="color:red;">*</span>TELEPHONES</label></td>
					<td title="Séparer les numeros de téléphones par des virgules!"><input type="text" name="telephone" id="telephone" size="30" value="<?php if(isset($_POST['telephone'])) echo $_POST['telephone'];?>"/></td>
				</tr>
				<tr>
					<td><label for="email">E-MAIL</label></td>
					<td><input type="text" name="email" id="email" size="30" value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>"/></td>
				</tr>
				<tr>
					<td><label for="photo">PHOTO</label></td>
					<td><input type="file" name="photo" id="photo" size="30" /></td>
				</tr>
			</table>
		</fieldset>
		<fieldset>
			<legend> INFORMATIONS ADMINISTRATIVES</legend>
			<table class="form_table">
				<tr>
					<td><label for="direction">DIRECTION</label></td>
					<td>
						<select name="direction">
							<?php
								$direction = $db->queryAllRecords('select * from direction');
								foreach($direction as $dir)
								{
									if(isset($_POST['direction'])&&$dir['id']==$_POST['direction'])
										echo '<option value="'.$dir['id'].'" selected="selected">'.$dir['designation'].'</option>';
									else
										echo '<option value="'.$dir['id'].'">'.$dir['designation'].'</option>';
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td><label for="poste"><span style="color:red;">*</span>POSTE OCCUPE</label></td>
					<td>
						<select name="poste">
						</select>
						<?php
							if(isset($_POST['poste'])) echo '<input type="hidden" name="previous_poste" id="previous_poste" value="'.$_POST['poste'].'" />';
						?>
					</td>
				</tr>
				<tr style="display:none;">
					<td><label for="salaire"><span style="color:red;">*</span>SALAIRE MENSUEL</label></td>
					<td><input type="text" name="salaire" id="salaire" size="30" value="<?php if(isset($_POST['salaire'])) echo $_POST['salaire']; else echo '0';?>"/></td>
				</tr>
				<tr style="display:none;">
					<td><label for="mode_payement"><span style="color:red;">*</span>MODE DE PAYEMENT</label></td>
					<td>
						<select name="mode_payement">
							<option value="0" <?php if(isset($_POST['mode_payement'])&&$_POST['mode_payement']==0) echo 'selected="selected"'?> >Espèce</option>
							<option value="1" <?php if(isset($_POST['mode_payement'])&&$_POST['mode_payement']==1) echo 'selected="selected"'?>  >Chèque</option>
						</select>
					</td>
				</tr>
				<tr style="display:none;">
					<td><label for="date_embauche"><span style="color:red;">*</span>DATE D'EMBAUCHE</label></td>
					<td><input type="text" name="date_embauche" id="date_embauche" class="dateField" size="30" value="<?php if(isset($_POST['date_embauche'])) echo $_POST['date_embauche']; else echo Systeme::dateToFrench(Systeme::now_date());?>"/></td>
				</tr>
			</table>
		</fieldset>
		<fieldset>
			<legend> INFORMATIONS POUR LA CONNEXION AU SYSTEME</legend>
			<table class="form_table">
				
				<tr>
					<td><label for="login"><span style="color:red;">*</span>LOGIN</label></td>
					<td><input type="text" name="login" id="login" size="30" value="<?php if(isset($_POST['login'])) echo $_POST['login'];?>"/></td>
				</tr>
				<tr>
					<td><label for="auto_password_gene">MOT DE PASSE PAR DEFAUT</label></td>
					<td><input type="checkbox" name="auto_password_gene" id="auto_password_gene" <?php if(isset($_POST['auto_password_gene'])) echo 'checked="checked"';?>/></td>
				</tr>
				<tr>
					<td><label for="mdp"><span style="color:red;">*</span>MOT DE PASSE</label></td>
					<td><input type="password" name="mdp" id="mdp" size="30" value="<?php if(isset($_POST['mdp'])) echo $_POST['mdp'];?>" <?php if(isset($_POST['auto_password_gene'])) echo 'readonly="readonly"';?> /></td>
				</tr>
				<tr>
					<td><label for="mdp_conf"><span style="color:red;">*</span>MOT DE PASSE (confirmation)</label></td>
					<td><input type="password" name="mdp_conf" id="mdp_conf" size="30" value="<?php if(isset($_POST['mdp_conf'])) echo $_POST['mdp_conf'];?>" <?php if(isset($_POST['auto_password_gene'])) echo 'readonly="readonly"';?> /></td>
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
   if(isset($_POST['valider']))
   {
      $nom = isset($_POST['nom'])?$db->escape($_POST['nom']):'';
      $prenom = isset($_POST['prenom'])?$db->escape($_POST['prenom']):'';
      $genre = isset($_POST['genre'])?$db->escape($_POST['genre']):'';
      $matrimoniale = isset($_POST['matrimoniale'])?$db->escape($_POST['matrimoniale']):0;
      $nombre_enfant = isset($_POST['nombre_enfant'])?$db->escape($_POST['nombre_enfant']):0;
      $telephone = isset($_POST['telephone'])?$db->escape($_POST['telephone']):'';
      $email = isset($_POST['email'])?$db->escape($_POST['email']):'';
      $photo_url='uploads/intervenant/0.jpeg';
	  $id_poste = isset($_POST['poste'])?$db->escape($_POST['poste']):0;
	  $salaire = isset($_POST['salaire'])?$db->escape($_POST['salaire']):'';
	  $mode_payement = isset($_POST['mode_payement'])?$db->escape($_POST['mode_payement']):0;
	  $date_embauche = isset($_POST['date_embauche'])?Systeme::dateToEnglish($db->escape($_POST['date_embauche'])):'';
	  $status = 0;
	  $login = isset($_POST['login'])?$db->escape($_POST['login']):'';
	  $mdp = isset($_POST['mdp'])?$db->escape($_POST['mdp']):'';
	  $mdp_conf = isset($_POST['mdp_conf'])?$db->escape($_POST['mdp_conf']):'';
	  $id_intervenant = $_SESSION['id_intervenant'];

	  if($nom!=''&&$prenom!=''&&$genre!=''&&$matrimoniale!=''&&$nombre_enfant!=''&&$telephone!=''&&$id_poste>0&&$salaire!=''&&$mode_payement>=0&&$date_embauche!=''&&$login!=''&&$mdp!=''&&$mdp==$mdp_conf)
	  {
	  	$login_exist = $db->countMatchedRows('intervenant',array('login'=>$login)) > 0;
        if(!$login_exist)
        {
			if($db->insertion('intervenant','',$nom,$prenom,$login,md5($mdp),$genre,$matrimoniale,$nombre_enfant,$telephone,$email,$photo_url,$id_poste,$salaire,$mode_payement,$date_embauche,$status,$id_intervenant,Systeme::now()))
			{
				$id_intervenant = $db->lastTabId('intervenant'); 
				$file_url = 'uploads/intervenant/'.$id_intervenant; 
				$extensions_autorisees = array('jpg','JPG', 'jpeg', 'gif','PNG','png');
				$upload_state = Systeme::upload_file('photo', $file_url, $extensions_autorisees, '1000000');
				if ($upload_state['etat'] == 'ok')
				{
					$photo_url = $file_url . '.'.$upload_state['extension'];
					$db->update('intervenant',array('photo_url'=>$photo_url),array('id'=>$id_intervenant));
				}
				else if($upload_state['etat']=='size_error')
				{					
					$validate_state = 1;
				}
				Systeme::redirect('index.php?m='.$_GET['m'].'&sm=8');				
			}
		}
		else
			$validate_state = 2;
	  }
	  else
	  {
		if($mdp!=$mdp_conf) $validate_state = 3;
	  }
   }
?>


<?php
	switch ($validate_state) {
		case 1:
			echo '<script>$(function(){$("#error_shower").html("Impossible de télécharger l\'image de profil. Veuillez sélectionner une image de petite taille ( < 1Mo)")});</script>';
			break;
		case 2:
			echo '<script>$(function(){$("#error_shower").html("Le login choisi existe déjà dans la base de données. Veuillez le changer puis soumettre le formulaire à nouveau.")});</script>';
			break;
		case 3:
			echo '<script>$(function(){$("#error_shower").html("Les mots de passe ne sont pas conformes.")});</script>';
			break;
		
		default:
			# code...
			break;
	}

?>