<?php
/*
  Formulaire de création de poste avec ou sans des sous menus des sous_menus
  Auteur: CODO Paterne
*/

?>
<script>
$(function(){
	$('select[name=direction]').change(function(){
	   if($(this).val()==0)
	   {
		 $('.new_dir_zone').show();

		 $('#new_dir_update_name').val('').hide();
		 $('#update_dir_name').attr('checked',false).hide();
	   }
	   else
	   {
		 $('.new_dir_zone').hide();
		 if($(this).val()>0)
		 {
		 	name = $('select[name=direction] option:selected').text();
		 	$('#new_dir_update_name').val(name);
		 	$('#update_dir_name').show();
		 }
		 else
		 {
		 	 $('#new_dir_update_name').val('').hide();
		 	 $('#update_dir_name').attr('checked',false).hide();

		 }
	   }
	});	

	$('#update_dir_name').bind('click',function(){
		if($(this).is(':checked'))
		{
			$('#new_dir_update_name').show();
		}
		else
		{
			$('#new_dir_update_name').hide();
		}
	});
	$('#update_dir_name').hide();
	$('#new_dir_update_name').hide();


	$('select[name=service]').change(function(){
	   if($(this).val()==0)
	   {
		 $('.new_service_zone').show();

		 $('#new_service_update_name').val('').hide();
		 $('#update_service_name').attr('checked',false).hide();
	   }
	   else
	   {
		 $('.new_service_zone').hide();
		 if($(this).val()>0)
		 {
		 	name = $('select[name=service] option:selected').text();
		 	$('#new_service_update_name').val(name);
		 	$('#update_service_name').show();
		 }
		 else
		 {
		 	 $('#new_service_update_name').val('').hide();
		 	 $('#update_service_name').attr('checked',false).hide();

		 }
	   }
	});	
	$('#update_service_name').bind('click',function(){
		if($(this).is(':checked'))
		{
			$('#new_service_update_name').show();
		}
		else
		{
			$('#new_service_update_name').hide();
		}
	});
	$('#update_service_name').hide();
	$('#new_service_update_name').hide();


	$('select[name=division]').change(function(){
	   if($(this).val()==0)
	   {
		 $('.new_div_zone').show();

		 $('#new_div_update_name').val('').hide();
		 $('#update_div_name').attr('checked',false).hide();
	   }
	   else
	   {
		 $('.new_div_zone').hide();
		 if($(this).val()>0)
		 {
		 	name = $('select[name=div] option:selected').text();
		 	$('#new_div_update_name').val(name);
		 	$('#update_div_name').show();
		 }
		 else
		 {
		 	 $('#new_div_update_name').val('').hide();
		 	 $('#update_div_name').attr('checked',false).hide();

		 }
	   }
	});	
	$('#update_div_name').bind('click',function(){
		if($(this).is(':checked'))
		{
			$('#new_div_update_name').show();
		}
		else
		{
			$('#new_div_update_name').hide();
		}
	});
	$('#update_div_name').hide();
	$('#new_div_update_name').hide();


	$('.new_dir_zone').hide();
	$('.new_service_zone').hide();
	$('.new_div_zone').hide();

	$('input[name=config_sm]').click(function(){
		if($(this).is(':checked'))
		{
			$('#sm_config_zone').show();
		}
		else
		{
			$('#sm_config_zone').hide();
		}
					
	});
});
</script>
<h3> Création d'un nouveau poste </h3>
<div align = "center" class="radius">
	<form action="" method="post">
		<fieldset >
			<legend> Informations du poste </legend>
			<table class="form_table">
				<tr>
					<td><label for="designation">DESIGNATION DU POSTE</label></td>
					<td><input type="text" name="designation" id="designation" size="30" value="<?php if(isset($_POST['designation'])) echo $_POST['designation'];?>"/></td>
				</tr>
				<tr>
					<td><label for="description">DESCRIPTION</label></td>
					<td><textarea name="description" id="description" cols="25"><?php if(isset($_POST['description'])) echo $_POST['description'];?></textarea></td>
				</tr>
				<tr>
					<td><label for="direction">DIRECTION</label></td>
					<td>
						<select name="direction" id="direction">
						<option value="-1">Veuillez sélectionner la direction</option>
						<?php
						  $directions = $db->query('select * from direction');
						  $post_dir_name = '';
						  while($direct = $directions->fetch())
						  {
						    if(isset($_POST['direction'])&&$_POST['direction']==$direct['id'])
						    {
								echo'<option value="'.$direct['id'].'" selected="selected">'.$direct['designation'].'</option>';
								$post_dir_name = $direct['designation'];
						    }
							else
								echo'<option value="'.$direct['id'].'">'.$direct['designation'].'</option>';
						  }
						?>
						<option value="0" <?php if(isset($_POST['direction'])&&$_POST['direction']==0) echo 'selected="selected"';?>>Nouvelle direction</option>
						</select>
						<input type="checkbox" name="update_dir_name" id="update_dir_name" />
						<input type="text" name="new_dir_update_name" id="new_dir_update_name" value="<?php echo $post_dir_name;?>"/>
					</td>
					<td class="new_dir_zone" style="<?php if(isset($_POST['direction'])&&$_POST['direction']==0) echo 'display:block;'; else echo 'display:none;'; ?>"><input title="Désignation de la direction" type="text" name="new_direction_name" id="new_direction_name" size="30" value="<?php if(isset($_POST['new_direction_name'])) echo $_POST['new_direction_name'];?>"/></td>
					<td class="new_dir_zone" style="<?php if(isset($_POST['direction'])&&$_POST['direction']==0) echo 'display:block;'; else echo 'display:none;'; ?>"><input title="Breve description de la direction" type="text" name="new_direction_desc" id="new_direction_desc" size="30" value="<?php if(isset($_POST['new_direction_desc'])) echo $_POST['new_direction_desc'];?>"/></td>
				</tr>

				<tr>
					<td><label for="service">SERVICE</label></td>
					<td>
						<select name="service" id="service">
						<option value="-1">Veuillez sélectionner le service</option>
						<?php
						  $services = $db->query('select * from service');
						  $post_service_name = '';
						  while($service = $services->fetch())
						  {
						    if(isset($_POST['service'])&&$_POST['service']==$service['id'])
						    {
								echo'<option value="'.$service['id'].'" selected="selected">'.$service['designation'].'</option>';
								$post_service_name = $service['designation'];
						    }
							else
								echo'<option value="'.$service['id'].'">'.$service['designation'].'</option>';
						  }
						?>
						<option value="0" <?php if(isset($_POST['service'])&&$_POST['service']==0) echo 'selected="selected"';?>>Nouveau service</option>
						</select>
						<input type="checkbox" name="update_service_name" id="update_service_name" />
						<input type="text" name="new_service_update_name" id="new_service_update_name" value="<?php echo $post_service_name;?>"/>
					</td>
					<td class="new_service_zone" style="<?php if(isset($_POST['service'])&&$_POST['service']==0) echo 'display:block;'; else echo 'display:none;'; ?>"><input title="Désignation du service" type="text" name="new_service_name" id="new_service_name" size="30" value="<?php if(isset($_POST['new_service_name'])) echo $_POST['new_service_name'];?>"/></td>
					<td class="new_service_zone" style="<?php if(isset($_POST['service'])&&$_POST['service']==0) echo 'display:block;'; else echo 'display:none;'; ?>"><input title="Breve description du service" type="text" name="new_service_desc" id="new_service_desc" size="30" value="<?php if(isset($_POST['new_service_desc'])) echo $_POST['new_service_desc'];?>"/></td>
				</tr>

				<tr>
					<td><label for="division">DIVISION</label></td>
					<td>
						<select name="division" id="division">
						<option value="-1">Veuillez sélectionner la division</option>
						<?php
						  $divisions = $db->query('select * from division');
						  $post_div_name = '';
						  while($division = $divisions->fetch())
						  {
						    if(isset($_POST['division'])&&$_POST['division']==$division['id'])
						    {
								echo'<option value="'.$division['id'].'" selected="selected">'.$division['designation'].'</option>';
								$post_div_name = $division['designation'];
						    }
							else
								echo'<option value="'.$division['id'].'">'.$division['designation'].'</option>';
						  }
						?>
						<option value="0" <?php if(isset($_POST['division'])&&$_POST['division']==0) echo 'selected="selected"';?>>Nouvelle division</option>
						</select>
						<input type="checkbox" name="update_div_name" id="update_div_name" />
						<input type="text" name="new_div_update_name" id="new_div_update_name" value="<?php echo $post_div_name;?>"/>
					</td>
					<td class="new_div_zone" style="<?php if(isset($_POST['division'])&&$_POST['division']==0) echo 'display:block;'; else echo 'display:none;'; ?>"><input title="Désignation de la division" type="text" name="new_division_name" id="new_division_name" size="30" value="<?php if(isset($_POST['new_division_name'])) echo $_POST['new_division_name'];?>"/></td>
					<td class="new_div_zone" style="<?php if(isset($_POST['division'])&&$_POST['division']==0) echo 'display:block;'; else echo 'display:none;'; ?>"><input title="Breve description de la division" type="text" name="new_division_desc" id="new_division_desc" size="30" value="<?php if(isset($_POST['new_division_desc'])) echo $_POST['new_division_desc'];?>"/></td>
				</tr>
				<tr>
					<td><label for="chef_direction">Chef division?</label></td>
					<td>Oui<input type="radio" name="chef_direction" value="1" <?php if(isset($_POST['chef_direction'])&&$_POST['chef_direction']==1) echo 'checked="checked"'?> /> Non<input type="radio" name="chef_direction" value="0" <?php if(!isset($_POST['chef_direction'])||(isset($_POST['chef_direction'])&&$_POST['chef_direction']==0)) echo 'checked="checked"'?> /></td>
				</tr>
			</table>
		</fieldset>
		<fieldset>
			<legend> Configuration des droits du poste </legend>
			<table class="form_table">
				<tr>
					<td>
						<input type="checkbox" name="config_sm"/>
					</td>
					<td align="left"><label><strong>CONFIGURER LES DROITS DU POSTE </strong></label></td>
				</tr>
				<tr id="sm_config_zone" style="display:none;">
					<td colspan="3" style="text-align:center;">
						<?php
							$id_poste = $db->lastTabId('sous_menu')+1;// un id de poste inexistant
							include('config_poste_sous_menu.php');
						?>
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
   if(isset($_POST['valider'])&&isset($_POST['designation'])&&$_POST['designation']!=''&&isset($_POST['direction'])&&$_POST['direction']>=0)
   {
      $designation = $db->escape($_POST['designation']);
      $direction = $db->escape($_POST['direction']);
      $chef_direction = $db->escape($_POST['chef_direction']);
      $description = $db->escape($_POST['description']);
	  if($direction==0) // création d'une nouvelle catégorie
	  {
		$db->insertion('direction','',$_POST['new_direction_name'],$_POST['new_direction_desc']); // si l'insertion passe :: retourne l'id du nouvel enregistrement
		$direction = $db->lastTabId('direction');
      }
      elseif ($direction < 0) {
      	echo "Vueillez renseigner la direction";
      	exit();
      }

      
      $service = $db->escape($_POST['service']);
      if($service==0) // création d'un nouveau service
	  {
		$db->insertion('service','',$direction,$_POST['new_service_name'],$_POST['new_service_desc']); // si l'insertion passe :: retourne l'id du nouvel enregistrement
		$service = $db->lastTabId('service');
      }
      elseif ($service < 0) {
      	$service = 0;
      	$division = 0;     
      }

      $division = $db->escape($_POST['division']);
      if($division==0) // création d'un nouveau service
	  {
		$db->insertion('division','',$service,$_POST['new_division_name'],$_POST['new_division_desc']); // si l'insertion passe :: retourne l'id du nouvel enregistrement
		$division = $db->lastTabId('division');
      }
      elseif ($division < 0) {
      	$division = 0;  
      }
     
	  
	  if($db->insertion('poste','',$designation,$description,$direction,$service, $division, $chef_direction))
	  {
	  	$new_dir_update_name = isset($_POST['new_dir_update_name'])?$db->escape($_POST['new_dir_update_name']):'';
	    if(isset($_POST['update_dir_name']) && $new_dir_update_name!='')
	    {
	    	$db->update('direction',array('designation'=>$new_dir_update_name),array('id'=>$direction));
	    }

	    $id_poste = $db->lastTabId('sous_menu');
	    if(isset($_POST['config_sm']))
		{	
		   	
			$max_sous_menu_id = $db->lastTabId('sous_menu');
			$selected_sous_menus_ids = array();
			for($i=0;$i<=$max_sous_menu_id;$i++)
			{
				if(isset($_POST['sm_'.$i])&&$_POST['sm_'.$i]=='on')
				{
				   // l'indice $i represente en même temps le sous_menu à ajouter
				   $selected_sous_menus_ids[]=$i;
				}
			}
			// Actualisation des sous-menus du poste 
			Systeme::updatePosteSousMenu($id_poste,$selected_sous_menus_ids);
		}
		//redirection
		Systeme::redirect('index.php?m='.$_GET['m'].'&sm=10');		
	  }
   }
?>