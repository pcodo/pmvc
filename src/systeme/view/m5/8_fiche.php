<?php
/*
  Formulaire d'enregistrement des membres du personnel
  Auteur: CODO Paterne
*/
if(!isset($_GET['id_membre'])) exit();
$id_membre = $db->escape($_GET['id_membre']);
$membre= $db->queryOneRecord('select inter.*, d.id id_direction from intervenant inter,direction d, poste p  where inter.id_poste=p.id and p.id_direction = d.id and inter.id='.$id_membre);
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
</style>
<h3> Aperçu des informations du membre </h3>
<h4 id="error_shower"></h4>
<div align = "center" class="radius">
	<?php
		if(isset($membre['photo_url'])&&$membre['photo_url']!='') echo '<img class="img_1" src = "'.$membre['photo_url'].'"/>';
		else echo '<img class="img_1" src = "uploads/intervenant/0.jpg"/>';
	?>
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
							<option value="2" <?php if((isset($_POST['matrimoniale']) && $_POST['matrimoniale'] == 2)||(isset($membre['etat_matrimoniale'])&& $membre['etat_matrimoniale']==2)) echo 'selected="selected"'?>  >Divorcé(e)</option>
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
	
</div>

