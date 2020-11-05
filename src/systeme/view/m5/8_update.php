<?php
/*
  Formulaire d'enregistrement des membres du personnel
  Auteur: CODO Paterne
*/
if(!isset($_GET['id_membre'])) exit();
$id_membre = $db->escape($_GET['id_membre']);
$membre= $db->queryOneRecord('select inter.*, d.id id_direction from intervenant inter,direction d, poste p  where inter.id_poste=p.id and p.id_direction = d.id and inter.id='.$id_membre);
$currentMembreInterimaire = intervenantInterimaire::userInterimaireInter($id_membre);
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
});
</script>
<style>
	.form_table td {width:300px;}
	#error_shower {background-color:inherit;color:red;}
	#photo_membre{position:absolute;top:8px;right:6px;width:90px;height:100px;}
</style>
<h3> Modification des informations du membre </h3>
<h4>Les champs portant les astérisques (<span style="color:red;">*</span>) sont obligatoires! </h4>
<h4 id="error_shower"></h4>
<div align = "center" class="radius">
	<?php
		if(isset($membre['photo_url'])) echo '<img class="img_1" src = "'.$membre['photo_url'].'"/>';
		else echo '<img class="img_1" src = "uploads/intervenant/0.jpg"/>';
	?>
	<form action="" method="post" enctype="multipart/form-data">
		<fieldset >
			<legend> INFORMATIONS GENERALES</legend>
			<table class="form_table">
				<tr>
					<td><label for="nom"><span style="color:red;">*</span>NOM</label></td>
					<td><input type="text" name="nom" id="nom" size="30" value="<?php if(isset($_POST['nom'])) echo $_POST['nom'];else if(isset($membre['nom'])) echo $membre['nom'];?>"/></td>
				</tr>	
				<tr>
					<td><label for="prenom"><span style="color:red;">*</span>PRENOMS</label></td>
					<td><input type="text" name="prenom" id="prenom" size="30" value="<?php if(isset($_POST['prenom'])) echo $_POST['prenom'];else if(isset($membre['prenom'])) echo $membre['prenom'];?>"/></td>
				</tr>
				<tr>
					<td><label for="genre"><span style="color:red;">*</span>GENRE</label></td> 
					<td> 
						M <input type="radio" id="genre" name="genre"  value="0" <?php if((isset($_POST['genre']) && $_POST['genre'] == 0)||(isset($membre['genre'])&& $membre['genre']==0)) echo 'checked="checked"'; ?>/>
						F <input type="radio" id="genre" name="genre" value="1" <?php if((isset($_POST['genre']) && $_POST['genre'] == 1)||(isset($membre['genre'])&& $membre['genre']==1))echo 'checked="checked"'; ?>/>
					</td>
				</tr>
				<tr style="display:none;">
					<td><label for="matrimoniale"><span style="color:red;">*</span>SITUATION MATRIMONIALE</label></td>
					<td>
						<select name="matrimoniale">
							<option value="0" <?php if((isset($_POST['matrimoniale']) && $_POST['matrimoniale'] == 0)||(isset($membre['etat_matrimoniale'])&& $membre['etat_matrimoniale']==0)) echo 'selected="selected"'?> >Célibataire</option>
							<option value="1" <?php if((isset($_POST['matrimoniale']) && $_POST['matrimoniale'] == 1)||(isset($membre['etat_matrimoniale'])&& $membre['etat_matrimoniale']==1)) echo 'selected="selected"'?>  >Marié(e)</option>
						</select>
					</td>
				</tr>	
				<tr style="display:none;">
					<td><label for="nombre_enfant"><span style="color:red;">*</span>NOMBRE D'ENFANTS</label></td>
					<td><input type="text" name="nombre_enfant" id="nombre_enfant" size="30" value="<?php if(isset($_POST['nombre_enfant'])) echo $_POST['nombre_enfant'];else if(isset($membre['nombre_enfant'])) echo $membre['nombre_enfant'];?>"/></td>
				</tr>
				<tr>
					<td title="Séparer les numeros de téléphones par des virgules!"><label for="telephone"><span style="color:red;">*</span>TELEPHONES</label></td>
					<td title="Séparer les numeros de téléphones par des virgules!"><input type="text" name="telephone" id="telephone" size="30" value="<?php if(isset($_POST['telephone'])) echo $_POST['telephone'];else if(isset($membre['telephone'])) echo $membre['telephone'];?>"/></td>
				</tr>
				<tr>
					<td><label for="email">E-MAIL</label></td>
					<td><input type="text" name="email" id="email" size="30" value="<?php if(isset($_POST['email'])) echo $_POST['email'];else if(isset($membre['email'])) echo $membre['email'];?>"/></td>
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
									if((isset($_POST['direction'])&&$dir['id']==$_POST['direction'])||(isset($membre['id_direction'])&&$dir['id']==$membre['id_direction']))
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
							else if(isset($membre['id_poste'])) echo '<input type="hidden" name="previous_poste" id="previous_poste" value="'.$membre['id_poste'].'" />';
						?>
					</td>
				</tr>
				<tr style="display:none;">
					<td><label for="salaire"><span style="color:red;">*</span>SALAIRE MENSUEL</label></td>
					<td><input type="text" name="salaire" id="salaire" size="30" value="<?php if(isset($_POST['salaire'])) echo $_POST['salaire'];else if(isset($membre['salaire'])) echo $membre['salaire'];?>"/></td>
				</tr>
				<tr style="display:none;">
					<td><label for="mode_payement"><span style="color:red;">*</span>MODE DE PAYEMENT</label></td>
					<td>
						<select name="mode_payement">
							<option value="0" <?php if((isset($_POST['mode_payement']) && $_POST['mode_payement'] == 0)||(isset($membre['mode_payement'])&& $membre['mode_payement']==0)) echo 'selected="selected"'?> >Espèce</option>
							<option value="1" <?php if((isset($_POST['mode_payement']) && $_POST['mode_payement'] == 1)||(isset($membre['mode_payement'])&& $membre['mode_payement']==1)) echo 'selected="selected"'?>  >Chèque</option>
						</select>
					</td>
				</tr>
				<tr style="display:none;">
					<td><label for="date_embauche"><span style="color:red;">*</span>DATE D'EMBAUCHE</label></td>
					<td><input type="text" name="date_embauche" id="date_embauche" class="dateField" size="30" value="<?php if(isset($_POST['date_embauche'])) echo $_POST['date_embauche'];else if(isset($membre['date_embauche'])) echo Systeme::dateToFrench($membre['date_embauche']);?>"/></td>
				</tr>
					
			</table>
		</fieldset>
        <fieldset>
            <legend> Intérimaire </legend>
            <table cellspacing="10" class="form_table" style="display:none;">
                    <tr>
						<td><label for="selected_user_interimaire">Intérimaire actuel</label></td>
						<td>
							<select name="selected_user_interimaire" id="selected_user_interimaire">
								<option value="0">--</option>
								<?php
								$users = Systeme::userList(0,0,true);								
								foreach ($users as $key => $user) {
									if($user['id']!=$id_membre)
									{
										if($currentMembreInterimaire->id()>0 && $currentMembreInterimaire->id()==$user['id'])
											echo '<option value="'.$user['id'].'" selected="selected">'.$user['nom'].' '.$user['prenom'].'</option>';
										else
											echo '<option value="'.$user['id'].'">'.$user['nom'].' '.$user['prenom'].'</option>';
									}									
								}
								?>
							</select>						
						</td>						
					</tr>
            </table>
        </fieldset>
        <fieldset>
            <legend> Ré-initialisation de mot de passe </legend>
            <table cellspacing="10" class="form_table">
                    <tr>
                            <td>
                                    <label for="init_passwd">Ré-initialiser le mot de passe ?</label>
                               
                            </td>
                            <td>
                                  <input type="checkbox" name="init_passwd"/>
                            </td>

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
   	  $id_intervenant = $_SESSION['id_intervenant'];
      $nom = isset($_POST['nom'])?$db->escape($_POST['nom']):'';
      $prenom = isset($_POST['prenom'])?$db->escape($_POST['prenom']):'';
      $genre = isset($_POST['genre'])?$db->escape($_POST['genre']):'';
      $matrimoniale = isset($_POST['matrimoniale'])?$db->escape($_POST['matrimoniale']):0;
      $nombre_enfant = isset($_POST['nombre_enfant'])?$db->escape($_POST['nombre_enfant']):0;
      $telephone = isset($_POST['telephone'])?$db->escape($_POST['telephone']):'';
      $email = isset($_POST['email'])?$db->escape($_POST['email']):'';
      $photo_url='';// $_POST['photo']
	  $id_poste = isset($_POST['poste'])?$db->escape($_POST['poste']):0;
	  $salaire = isset($_POST['salaire'])?$db->escape($_POST['salaire']):'';
	  $mode_payement = isset($_POST['mode_payement'])?$db->escape($_POST['mode_payement']):0;
	  $date_embauche = isset($_POST['date_embauche'])?Systeme::dateToEnglish($db->escape($_POST['date_embauche'])):'';
	  if($nom!=''&&$prenom!=''&&$genre!=''&&$matrimoniale!=''&&$nombre_enfant!=''&&$telephone!=''&&$id_poste>0&&$salaire!=''&&$mode_payement>=0&&$date_embauche!='')
	  {
		// echo $date_service;exit();
		$db->update('intervenant',array('nom'=>$nom,'prenom'=>$prenom,'genre'=>$genre,'etat_matrimoniale'=>$matrimoniale,'nombre_enfant'=>$nombre_enfant,'telephone'=>$telephone,'email'=>$email,'id_poste'=>$id_poste,'salaire'=>$salaire,'mode_payement'=>$mode_payement,'date_embauche'=>$date_embauche), array('id'=>$id_membre));
		$file_url = 'uploads/intervenant/'.$id_membre; 
		$extensions_autorisees = array('jpg','JPG', 'jpeg', 'gif','PNG','png');
		$upload_state = Systeme::upload_file('photo', $file_url, $extensions_autorisees, '1000000');
		if ($upload_state['etat'] == 'ok')
		{
			$photo_url = $file_url .'.'.$upload_state['extension'];
			$db->update('intervenant',array('photo_url'=>$photo_url),array('id'=>$id_membre));
		}
		else if($upload_state['etat']=='size_error')
		{
			$validate_state = 1;							
		}
        $mdp = '00000';
        if(isset($_POST['init_passwd']) && $_POST['init_passwd']=='on')
        {
          $db->update('intervenant', array('mdp'=>md5($mdp)), array('id'=>$id_membre));
        }

        if(isset($_POST['selected_user_interimaire']))
        {
        	$selected_user_interimaire = $_POST['selected_user_interimaire'];
        	if($selected_user_interimaire>0 && $selected_user_interimaire!=$currentMembreInterimaire->id())
        	{
        		intervenantInterimaire::disableAllUserInterimaire($id_membre);

        		$interim = new intervenantInterimaire();
        		$interim->setLeavingInterId($id_membre)
        				->setInterimaireInterId($selected_user_interimaire)
        				->setDateDebut(Systeme::now())
        				->setEnabled(1)
        				->dbSave($id_intervenant);
        	}
        	else
        	{
        		intervenantInterimaire::disableAllUserInterimaire($id_membre);
        	}
        }


        if($validate_state==0)
        {
        	if(isset($_GET['action_source']) && $_GET['action_source'] =='user_plus' )
        		Systeme::redirect('index.php?m='.$_GET['m'].'&sm=38');        		
        	else
        		Systeme::redirect('index.php?m='.$_GET['m'].'&sm=8');         		
        }
        
	  }
	  else
	  {
		$validate_state = 3;
	  }
   }


   
	switch ($validate_state) {
		case 1:
			echo '<script>$(function(){$("#error_shower").html("Impossible de télécharger l\'image de profil. Veuillez sélectionner une image de petite taille ( < 1Mo)")});</script>';
			break;
		case 3:
			echo '<script>$(function(){$("#error_shower").html("Les mots de passe ne sont pas conformes.")});</script>';
			break;
		
		default:
			# code...
			break;
	}


?>

