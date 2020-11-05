<?php
/*
  Formulaire de modification des menus et sous menus associés
  Auteur: CODO Paterne
  Date de création : 30/01/2013
*/
if(!isset($_GET['id_menu'])) exit();
$id_menu = $db->escape($_GET['id_menu']);
$menu= $db->queryOneRecord('select * from menu where id='.$id_menu)
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
		
		var ligne = tr_tds([td1,td2,td3,td4,td5,td6]);
		
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
<div align = "center" class="radius">
	<form action="" method="post">
		<fieldset >
			<legend> Choix de la catégorie </legend>
			<table class="form_table">
				<tr>
					<td>CATEGORIE</td>
					<td>
						<select name="categorie"">
						<option value="-1">Veuillez selectionner une catégorie</option>
						<?php
						  $categories = $db->query('select * from categorie_menu');
						  while($cat = $categories->fetch())
						  {
						    if((isset($_POST['categorie'])&&$_POST['categorie']==$cat['id'])||(isset($menu['id_categorie'])&&$menu['id_categorie']==$cat['id']))
								echo'<option value="'.$cat['id'].'" selected="selected">'.$cat['designation'].'</option>';
							else
								echo'<option value="'.$cat['id'].'">'.$cat['designation'].'</option>';
						  }
						?>
						<option value="0">Nouvelle catégorie</option>
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
					<td><input type="text" name="menu" id="menu" size="30" value="<?php if(isset($_POST['menu'])) echo $_POST['menu'];else if(isset($menu['designation'])) echo $menu['designation']; ?>"/></td>
					<?php
						if(isset($id_menu))
						{
							echo '<input type = "hidden" name = "id_menu" value="'.$id_menu.'">';
						}
					?>
				</tr>
				<tr>
					<td><label for="description">DESCRIPTION</label></td>
					<td><textarea name="description" id="description" cols="25"><?php if(isset($_POST['description'])) echo $_POST['description'];else if($menu['description']) echo $menu['description'];?></textarea></td>
				</tr>
				<tr>
					<td><label for="active_state">ETAT</label></td>
					<td>
						<select name="active_state">	
							<option value="1" <?php if( (isset($_POST['active_state'])&&$_POST['active_state']==1) || ( isset($menu['active_state']) && $menu['active_state'] == 1) ) echo 'selected = "selected"'?> > Activé </option>
							<option value="0" <?php if( (isset($_POST['active_state'])&&$_POST['active_state']==0) || ( isset($menu['active_state']) && $menu['active_state'] == 0) ) echo 'selected = "selected"'?> > Désactivé </option>
						</select>
					</td>
				</tr>
				<tr>
					<td><label for="show_help">INFO BULLE</label></td>
					<td>
						<select name="show_help">	
							<option value="1" <?php if( (isset($_POST['show_help'])&&$_POST['show_help']==1) || ( isset($menu['show_help']) && $menu['show_help'] == 1) ) echo 'selected = "selected"'?> > Afficher </option>
							<option value="0" <?php if( (isset($_POST['show_help'])&&$_POST['show_help']==0) || ( isset($menu['show_help']) && $menu['show_help'] == 0) ) echo 'selected = "selected"'?> > Ne pas afficher </option>
						</select>
					</td>
				</tr>
			</table>
		</fieldset>
		<fieldset>
			<legend> Modifier les sous-menus existants </legend>
			<table class="form_table">
				<thead>
					<tr >
						<th>N°</th>
						<th>NOM</th>
						<th>DESCRIPTION</th>
						<th>ETAT</th>
						<th>INFO BULLE</th>
						<th title="Accèss sur demande de mot de passe">PSSWD</th>
						<th>POSITION</th>
						<th style="text-align:right;"></th>
					</tr>
				</thead>
					<?php
						$reponse = $db->query('select * from sous_menu where id_menu='.$id_menu.' order by position');
						$i=0;
						while($sm = $reponse->fetch())
						{
						  echo '<tr>';
						  echo '<td><label for="">'.$i.'</label></td>';
						  echo '<td><input type="text" name="ex_sm_'.$i.'" id="ex_sm_'.$i.'" size="30" value="'.$sm['designation'].'"/></td>';
						  echo '<td><input type="text" name="ex_sm_desc_'.$i.'" id="ex_sm_desc_'.$i.'" size="40" value="'.$sm['description'].'" /></td>';
						  echo '<td>';
							echo '<select name="ex_active_state_'.$i.'">';
								  echo '<option value="1" '.( ($sm['active_state']==1)?'selected="selected"':'' ).'>Activé</option>';
								  echo '<option value="0" '.( ($sm['active_state']==0)?'selected="selected"':'' ).'>Désactivé</option>';
							echo '</select>';
						  echo '</td>';
						  
						  echo '<td>';
							echo '<select name="ex_show_help_'.$i.'">';
								  echo '<option value="1" '.( ($sm['show_help']==1)?'selected="selected"':'' ).'>Afficher</option>';
								  echo '<option value="0" '.( ($sm['show_help']==0)?'selected="selected"':'' ).'>Ne pas afficher</option>';
							echo '</select>';
						  echo '</td>';
						  echo '<td>';
							echo '<select name="ex_on_passwd_access_'.$i.'">';
								 echo '<option value="0" '.( ($sm['on_passwd_access']==0)?'selected="selected"':'' ).'>Non</option>';
								 echo '<option value="1" '.( ($sm['on_passwd_access']==1)?'selected="selected"':'' ).'>Oui</option>';
							echo '</select>';
						  echo '</td>';					  
						  echo '<td><input type="text" name="ex_sm_pos_'.$i.'" id="ex_sm_pos_'.$i.'" size="10" value="'.$sm['position'].'" /></td>';
						  echo '<td><input type="hidden" name="ex_sm_id_'.$i.'" id="ex_sm_id_'.$i.'" value="'.$sm['id'].'"/></td>';
						  echo '</tr>';
						  $i++;
						}
					?>
				
				
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
						<th class="adder_line_button"><input type="button" name="adding" value="+"/></th>
					</tr>
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
						  
						  echo '</tr>';
						  $i++;
						}
					?>
					<tr id="limit_sm">
						<input type="hidden" id="index_sm" name="index_sm" value="<?php echo ($i==0)?$i:($i-1);?>"/>
					</tr>
				</thead>
				
			</table>
		</fieldset>
		<fieldset>
			<legend> Validation </legend>
			<table cellspacing="10" width="100%" align="left" style="text-align:right;">
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
      $menu = $db->escape($_POST['menu']);
      $categorie = $db->escape($_POST['categorie']);
      $description = $db->escape($_POST['description']);
	  $active_state = $db->escape($_POST['active_state']);
      $show_help = $db->escape($_POST['show_help']);
	  if($categorie==0) // création d'une nouvelle catégorie
	  {
		$position = $db->lastTabId('categorie_menu');
		$db->insertion('categorie_menu','',$db->escape($_POST['new_categorie_name']),$db->escape($_POST['new_categorie_desc']),$position);
		$categorie = $db->lastTabId('categorie_menu');
	  }
	  	  
	  if(isset($_POST['id_menu'])) // si il existe la variable id_menu:: c'est qu'il y des sous_menu à modifier
	  {
			$id_menu_val = $db->escape($_POST['id_menu']);
			// mise à jour des attributs du menu actuel
			$db->update('menu', array('designation'=>$menu,'description'=>$description,'active_state'=>$active_state,'show_help'=>$show_help,'id_categorie'=>$categorie), array('id'=>$id_menu_val));
			
			// modification des sous menus appartenant déjà au menu actuel
			$i = 0;
			while(isset($_POST['ex_sm_'.$i]))
			{
			  $sm = $db->escape($_POST['ex_sm_'.$i]);
			  $id_sm = $db->escape($_POST['ex_sm_id_'.$i]);
			  $desc = $db->escape($_POST['ex_sm_desc_'.$i]);
			  $sm_active_state = $db->escape($_POST['ex_active_state_'.$i]);
			  $sm_show_help = $db->escape($_POST['ex_show_help_'.$i]);
			  $position = $db->escape($_POST['ex_sm_pos_'.$i]); 
			  $on_passwd_access = $db->escape($_POST['ex_on_passwd_access_'.$i]);
			  $db->update('sous_menu', array('designation'=>$sm,'description'=>$desc,'position'=>$position,'active_state'=>$sm_active_state,'show_help'=>$sm_show_help,'on_passwd_access'=>$on_passwd_access),array('id'=>$id_sm));
			  $i++;
			}
			
			// ajout d'autres sous menus au menu actuel
			$lastId = $db->lastTabId('sous_menu');
			$i = 0;
			while(isset($_POST['sm_'.$i])&&$_POST['sm_'.$i]!='')
			{
				$sm = $db->escape($_POST['sm_'.$i]);
				$desc = $db->escape($_POST['sm_desc_'.$i]);
				$sm_active_state = $db->escape($_POST['active_state_'.$i]);
			    $sm_show_help = $db->escape($_POST['show_help_'.$i]);
			    $on_passwd_access = $db->escape($_POST['on_passwd_access_'.$i]);
				$db->insertion('sous_menu','',$id_menu_val,$sm,$desc,$lastId+(++$i),$sm_active_state,$sm_show_help,$on_passwd_access);
			}
			Systeme::redirect('index.php?m='.$_GET['m'].'&sm=6');
		
	  
	  }
		
   }
?>