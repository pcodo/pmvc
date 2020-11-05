<?php
/*
  Formulaire de création de ménus avec des sous_menus
  Auteur: CODO Paterne

*/
?>
<script>
$(function(){
    $('input[name=adding]').click(function(){
		var index_sm =  parseInt($("#index_sm").val()); 
		var td1 = td_content(label('',index_sm));
		var td2 = td_content(input('text','sm_'+index_sm,'sm_'+index_sm,30,''));
		var td3 = td_content(input('text','sm_desc_'+index_sm,'sm_desc_'+index_sm,40,''));
		var options = [{value:1,text:'Activé'},{value:0,text:'Désactivé'}];
		var active_select_obj = select('active_state_'+index_sm,'active_state_'+index_sm,'',options).change(function() {
		     // gerer les evenement de changement ici ! :: alert($(this).val());
		});
		var td4 = td_content(active_select_obj);
		
		options = [{value:1,text:'Afficher'},{value:0,text:'Ne pas afficher'}];
		var info_bulle_select_obj = select('show_help_'+index_sm,'show_help_'+index_sm,'',options).change(function() {
		     // gerer les evenement de changement ici ! :: alert($(this).val());
		});
		var td5 = td_content(info_bulle_select_obj);
		
		options = [{value:0,text:'Non'},{value:1,text:'Oui'}];
		var access_select_obj = select('on_passwd_access_'+index_sm,'on_passwd_access_'+index_sm,'',options).change(function() {
		     // gerer les evenement de changement ici ! :: alert($(this).val());
		});
		var td6 = td_content(access_select_obj);
		
		options = [{value:1,text:'Afficher'},{value:0,text:'Ne pas afficher'}];
		var show_at_startup_select_obj = select('show_at_startup_'+index_sm,'show_at_startup_'+index_sm,'',options).change(function() {
		     // gerer les evenement de changement ici ! :: alert($(this).val());
		});
		var td7 = td_content(show_at_startup_select_obj);
		var td8 = td_content(input('file','icone_'+index_sm,'icone_'+index_sm,5,''));
		
		var ligne = tr_tds([td1,td2,td3,td4,td5,td6,td7,td8]);
		$("#limit_sm").before(ligne);
		$("#index_sm").val(index_sm+1);
	});
	
	$('select[name=categorie]').change(function(){
	   if($(this).val()==0)
	   {
		 $('.new_cat_zone').show();
	   }
	   else
	   {
		 $('.new_cat_zone').hide();
	   }
	});	
});
</script>
<h3> Création d'un nouveau menu </h3>
<div align = "center" class="radius">
	<form action="" method="post" enctype="multipart/form-data">
		<fieldset >
			<legend> Choix de la catégorie </legend>
			<table class="form_table">
				<tr>
					<td>CATEGORIE</td>
					<td>
						<select name="categorie">
						<option value="-1">Veuillez selectionner une catégorie</option>
						<?php
						  $categories = $db->query('select * from categorie_menu');
						  while($cat = $categories->fetch())
						  {
						    if(isset($_POST['categorie'])&&$_POST['categorie']==$cat['id'])
								echo'<option value="'.$cat['id'].'" selected="selected">'.$cat['designation'].'</option>';
							else
								echo'<option value="'.$cat['id'].'">'.$cat['designation'].'</option>';
						  }
						?>
						<option value="0" <?php if(isset($_POST['categorie'])&&$_POST['categorie']==0) echo 'selected="selected"';?>>Nouvelle catégorie</option>
						</select>
					</td>
					<td class="new_cat_zone" style="<?php if(isset($_POST['categorie'])&&$_POST['categorie']==0) echo 'display:block;'; else echo 'display:none;'; ?>"><input title="Désignation de la catégorie" type="text" name="new_categorie_name" id="new_categorie_name" size="30" value="<?php if(isset($_POST['new_categorie_name'])) echo $_POST['new_categorie_name'];?>"/></td>
					<td class="new_cat_zone" style="<?php if(isset($_POST['categorie'])&&$_POST['categorie']==0) echo 'display:block;'; else echo 'display:none;'; ?>"><input title="Description de la catégorie" type="text" name="new_categorie_desc" id="new_categorie_desc" size="30" value="<?php if(isset($_POST['new_categorie_desc'])) echo $_POST['new_categorie_desc'];?>"/></td>
				</tr>
				
			</table>
		</fieldset>
		<fieldset>
			<legend> Création du ménu </legend>
			<table class="form_table">
				<tr>
					<td><label for="menu">NOM DU MENU</label></td>
					<td><input type="text" name="menu" id="menu" size="30" value="<?php if(isset($_POST['menu'])) echo $_POST['menu'];?>"/></td>
				</tr>
				<tr>
					<td><label for="description">DESCRIPTION</label></td>
					<td><textarea name="description" id="description" cols="25"><?php if(isset($_POST['description'])) echo $_POST['description'];?></textarea></td>
				</tr>
				<tr>
					<td><label for="m_active_state">ETAT</label></td>
					<td>
						<select name="m_active_state">	
							<option value="1" <?php if(isset($_POST['m_active_state'])&&$_POST['m_active_state']==1) echo 'selected = "selected"'?> > Activé </option>
							<option value="0" <?php if(isset($_POST['m_active_state'])&&$_POST['m_active_state']==0) echo 'selected = "selected"'?> > Désactivé </option>
						</select>
					</td>
				</tr>
				<tr>
					<td><label for="m_show_help">INFO BULLE</label></td>
					<td>
						<select name="m_show_help">	
							<option value="1" <?php if(isset($_POST['m_show_help'])&&$_POST['m_show_help']==1) echo 'selected = "selected"'?> > Afficher </option>
							<option value="0" <?php if(isset($_POST['m_show_help'])&&$_POST['m_show_help']==0) echo 'selected = "selected"'?> > Ne pas afficher </option>
						</select>
					</td>
				</tr>
				<tr>
					<td><label for="sm_folder">Type de sous - menus</label></td>
					<td>
						<select name="type_sm">	
							<?php
								$types_sm = $db->queryAllRecords('select * from type_sm');
								foreach($types_sm as $type_sm)
								{
									if(isset($_POST['type_sm']) && $_POST['type_sm'] == $type_sm['id'])
										echo '<option value="'.$type_sm['id'].'" selected="selected" >'.$type_sm['folder'].'</option>';
									else
										echo '<option value='.$type_sm['id'].'>'.$type_sm['folder'].'</option>';
								}
							?>
						</select>
					</td>
				</tr>
			</table>
		</fieldset>
		<fieldset>
			<legend> Ajouter des sous-menus à ce menu </legend>
			<table class="form_table">
				<thead>
					<tr class="adder_line">
						<th>N°</th>
						<th>NOM</th>
						<th>DESCRIPTION</th>
						<th>ETAT</th>
						<th>INFO BULLE</th>
						<th title="Accèss sur demande de mot de passe">PSSWD</th>
						<th title="Afficher à l'accueil">ACCUEIL</th>
						<th title="">IMAGE</th>
						<th class="adder_line_button"><input type="button" name="adding" value="+"/></th>
					</tr>
				</thead>
				<?php
					$i = 0;
					while(isset($_POST['sm_'.$i]))
					{
					  echo '<tr>';
						echo '<td><label for="">'.$i.'</label></td>';
						echo '<td><input type="text" name="sm_'.$i.'" id="sm_'.$i.'" size="30" value="'.$_POST['sm_'.$i].'"/></td>';
						echo '<td><input type="text" name="sm_desc_'.$i.'" id="sm_desc_'.$i.'" size="40" value="'.$_POST['sm_desc_'.$i].'" /></td>';
						echo '<td>';
							echo '<select name="active_state_'.$i.'">';
							  echo '<option value="1" '.( (isset($_POST['active_state_'.$i])&&$_POST['active_state_'.$i]==1)?'selected="selected"':'' ).'>Activé</option>';
							  echo '<option value="0" '.( (isset($_POST['active_state_'.$i])&&$_POST['active_state_'.$i]==0)?'selected="selected"':'' ).'>Désactivé</option>';
							echo '</select>';
						echo '</td>';
					  
						echo '<td>';
							echo '<select name="show_help_'.$i.'">';
							  echo '<option value="1" '.( (isset($_POST['show_help_'.$i])&&$_POST['show_help_'.$i]==1)?'selected="selected"':'' ).'>Afficher</option>';
							  echo '<option value="0" '.( (isset($_POST['show_help_'.$i])&&$_POST['show_help_'.$i]==0)?'selected="selected"':'' ).'>Ne pas afficher</option>';
							echo '</select>';
						echo '</td>';
						echo '<td>';
							echo '<select name="on_passwd_access_'.$i.'">';
								  echo '<option value="0" '.( (isset($_POST['on_passwd_access_'.$i])&&$_POST['on_passwd_access_'.$i]==0)?'selected="selected"':'' ).'>Non</option>';
								  echo '<option value="1" '.( (isset($_POST['on_passwd_access_'.$i])&&$_POST['on_passwd_access_'.$i]==1)?'selected="selected"':'' ).'>Oui</option>';
							echo '</select>';
						 echo '</td>';
						 echo '<td>';
							echo '<select name="show_at_startup_'.$i.'">';
								  echo '<option value="1" '.( (isset($_POST['show_at_startup_'.$i])&&$_POST['show_at_startup_'.$i]==1)?'selected="selected"':'' ).'>Afficher</option>';
								  echo '<option value="0" '.( (isset($_POST['show_at_startup_'.$i])&&$_POST['show_at_startup_'.$i]==0)?'selected="selected"':'' ).'>Ne pas afficher</option>';
							echo '</select>';
						  echo '</td>';
						  echo '<td><input type="text" name="icone_'.$i.'" id="icone_'.$i.'" size="" /></td>';
					echo '</tr>';
					  
					  $i++;
					}
				?>
				<tr id="limit_sm">
					<input type="hidden" id="index_sm" name="index_sm" value="<?php echo ($i==0)?$i:($i-1);?>" />
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
   if(isset($_POST['valider'])&&isset($_POST['menu'])&&$_POST['menu']!=''&&isset($_POST['categorie'])&&$_POST['categorie']>=0)
   { 
      $extensions_autorisees = array('jpg','JPG', 'jpeg', 'gif','PNG','png');
      $menu = $db->escape($_POST['menu']);
      $categorie = $db->escape($_POST['categorie']);
      $description = $db->escape($_POST['description']);
      $m_active_state = $db->escape($_POST['m_active_state']);
      $m_show_help = $db->escape($_POST['m_show_help']);
	  $cat_active_state = isset($_POST['cat_active_state'])?$db->escape($_POST['cat_active_state']):1;
	  $cat_show_help = isset($_POST['cat_show_help'])?$db->escape($_POST['cat_show_help']):1;
	  if($categorie==0) // création d'une nouvelle catégorie
	  {
		$position = $db->lastTabId('categorie_menu');
		// echo $db->insertionRequestText('categorie_menu','',$_POST['new_categorie_name'],$_POST['new_categorie_desc'],$cat_active_state,$cat_show_help,$position); // si l'insertion passe :: retourne l'id du nouvel enregistrement
		$categorie = $db->lastTabId('categorie_menu');
		
      }
	 
	  if($db->insertion('menu','',$menu,$description,$categorie,$m_active_state,$m_show_help))
	  {
		if(isset($_POST['sm_0'])) // si au moins un sous menu a été créé
		{
			$id_menu = $db->queryOneRecord('select max(id) as val from menu');
			$type_sm = $db->escape($_POST['type_sm']);
			$i = 0;
			while(isset($_POST['sm_'.$i])&&$_POST['sm_'.$i]!='')
			{			
			  $sm = $db->escape($_POST['sm_'.$i]);
			  $desc = $db->escape($_POST['sm_desc_'.$i]); 
			  $sm_active_state = $db->escape($_POST['active_state_'.$i]);
			  $sm_show_help = $db->escape($_POST['show_help_'.$i]);
			  $on_passwd_access = $db->escape($_POST['on_passwd_access_'.$i]);
			  $show_at_startup = $db->escape($_POST['show_at_startup_'.$i]);
			  $icone_url = '';
			  if($db->insertion('sous_menu','',$id_menu['val'],$sm,$desc,$i,$sm_active_state,$sm_show_help,$on_passwd_access,$show_at_startup,$icone_url,$type_sm))
			  {
					$id_sm = $db->lastTabId('sous_menu'); 
					$file_url = 'uploads/sm/'.$id_sm; 
					$upload_state = Systeme::upload_file('icone_'.$i, $file_url, $extensions_autorisees, '100000');
					if ($upload_state['etat'] == 'ok')
					{
						$icone_url = $file_url . '.'.$upload_state['extension'];
						$db->update('sous_menu',array('icone_url'=>$icone_url),array('id'=>$id_sm));
					}
			  }
			  $i++;
			}
		}
		// redirection
		Systeme::redirect('index.php?m='.$_GET['m'].'&sm=6');
		
	  }
   }
?>