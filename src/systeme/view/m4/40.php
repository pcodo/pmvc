<?php
/*
  Formulaire de création de ménus avec des sous_menus
  Auteur: CODO Paterne
*/
?>
<script>
	$(function(){
				
		// Chargement des intervenants
		function setMail(select_val, index)
		{
		   var inter_item = (select_val).split('|');
		   if(inter_item.length>1)
		   {
			 $('#mail_'+index).val(inter_item[1]);
			 if(inter_item[1]==null || inter_item[1] =='') $('#mail_'+index).css('background-color:red;');
		   }
		}
			
		   $('.ln select').change(function(){
		       var index = parseInt($(this).attr('id').split('_')[1]);
			   setMail($(this).val(),index);
		   });
	 	   $.get(site_url+'ajax/request.php?req=5', function(data) {
		    var options = [];
			options.push({value:0,text:'Sélectionner un intervenant'});
		    var obj = $.parseJSON(data);
			$.each(obj, function() {
				options.push({value:this['id']+'|'+this['email'],text:this['nom']+' '+this['prenom']});
			});
			
			// Evenement de click sur le bouton de création de ligne			
			$('input[name=adding]').click(function(){
				var index =  parseInt($("#index").val());
				var td1 = td_content(label('',index));
				var select_obj = select('inter_'+index,'inter_'+index,'',options).change(function() {
				   setMail($(this).val(),index);
				});
				var td2 = td_content(select_obj);
				var td3 = td_content(input('text','mail_'+index,'mail_'+index,'',''));
				var ligne = tr_tds([td1,td2,td3]);
				ligne.attr('class','ln');
				$("#limit").before(ligne);
				$("#index").val(index+1);
			});
		});
	});
</script>
<h3>Création d'une nouvelle rubrique webgate</h3>
<div align = "center" class="radius">
	
	<img class="img_2" src = "img/webgate.png"/>'
	
	<form action="" method="post">
		<fieldset>
			<legend>Rubrique </legend>
			<table class="form_table">
				<tr>
					<td><label for="designation">DESIGNATION</label></td>
					<td><input type="text" name="designation" id="designation" size="30" value="<?php if(isset($_POST['designation'])) echo $_POST['designation'];?>"/></td>
				</tr>
				<tr>
					<td><label for="code">CODE D'IDENTIFICATION</label></td>
					<td><input type="text" name="code" id="code" size="30" value="<?php if(isset($_POST['code'])) echo $_POST['code'];?>"/></td>
				</tr>
				<tr>
					<td><label for="description">DESCRIPTION</label></td>
					<td><textarea name="description" id="description" cols="25"><?php if(isset($_POST['description'])) echo $_POST['description'];?></textarea></td>
				</tr>
				<tr>
					<td><label for="active_state">ETAT</label></td>
					<td>
						<select name="active_state">	
							<option value="1" <?php if(isset($_POST['active_state'])&&$_POST['active_state']==1) echo 'selected = "selected"'?> > Activé </option>
							<option value="0" <?php if(isset($_POST['active_state'])&&$_POST['active_state']==0) echo 'selected = "selected"'?> > Désactivé </option>
						</select>
					</td>
				</tr>
				<tr>
					<td><label for="periodicite_alerte" title="En nombre de jours">PERIODICITE D'ALERTE (jrs)</label></td>
					<td><input type="text" name="periodicite_alerte" id="periodicite_alerte" size="30" value="<?php if(isset($_POST['periodicite_alerte'])) echo $_POST['periodicite_alerte'];?>"/></td>
				</tr>				
			</table>
		</fieldset>
				
		<fieldset>
			<legend>Personnes à notifier </legend>
			<table class="form_table">
				<thead>
					<tr class="adder_line">
						<th>N°</th>
						<th>Nom & Prénoms</th>
						<th>Mail</th>
						<th class="adder_line_button"><input type="button" name="adding" value="+"/></th>
					</tr>
				</thead>
				<?php
					$i = 0;
					while(isset($_POST['inter_'.$i]))
					{
					  echo '<tr class="ln">';
					  echo '<td><label for="">'.$i.'</label></td>';
					  $intervenants = Systeme::userList();
					  echo '<td><select name="inter_'.$i.'" id="inter_'.$i.'" size="">';
					  echo '<option value="0">Sélectionner un employé</option>';
					  foreach($intervenants as $inter)
					  {
					    $items = explode('|',$_POST['inter_'.$i]);
						if($items[0]==$inter['id'])
							echo '<option value="'.$inter['id'].'|'.$inter['email'].'" selected="selected">'.$inter['nom'].' '.$inter['prenom'].'</option>';
						else
							echo '<option value="'.$inter['id'].'|'.$inter['email'].'" >'.$inter['nom'].' '.$inter['prenom'].'</option>';
					  }
					  echo '</select></td>';
					  
					  echo '<td><input type="text" name="mail_'.$i.'" id="mail_'.$i.'" size="40" value="'.$_POST['mail_'.$i].'" /></td>';
					  echo '</tr>';
					  $i++;
					}
				?>
				<tr id="limit">
					<input type="hidden" id="index" name="index_" value="<?php echo ($i==0)?$i:($i-1);?>" />
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
   if(isset($_POST['valider'])&&isset($_POST['designation'])&&$_POST['designation']!=''&&isset($_POST['code'])&&$_POST['code']!=''&&isset($_POST['periodicite_alerte'])&&is_numeric($_POST['periodicite_alerte'])&&$_POST['periodicite_alerte']>0)
   {
	  $designation = $db->escape($_POST['designation']);
	  $code = $db->escape($_POST['code']);
	  $description = $db->escape($_POST['description']);
	  $periode_alerte = $db->escape($_POST['periodicite_alerte']);
	  $active_state = isset($_POST['active_state'])?$db->escape($_POST['active_state']):0;
      $id_intervenant = $_SESSION['id_intervenant'];
	  if($db->insertion('webgate_rubrique','',$designation,$code,$description,$periode_alerte,$active_state,$id_intervenant,Systeme::now()))
	  {
		if(isset($_POST['inter_0'])) // si au moins un utilisateur a été sélectionné
		{
			$id_webgate_rubrique = $db->lastTabId('webgate_rubrique');
			$i = 0;
			while(isset($_POST['inter_'.$i])&&isset($_POST['mail_'.$i])&&$_POST['mail_'.$i]!='')
			{
			  $inter = $db->escape($_POST['inter_'.$i]);
			  $interItems = explode('|',$inter);
			  $id_inter = $interItems[0];
			  if($id_inter>0)
			  {
				$check_inter_ex = $db->queryOneRecord('select count(wg_user.id_intervenant_webgate) nbr from webgate_rubrique wr,  webgate_utilisateur wg_user where wr.id = wg_user.id_webgate_rubrique and wg_user.id_intervenant_webgate='.$id_inter.' and wr.id='.$id_webgate_rubrique);
				if($check_inter_ex['nbr']==0)
				$db->insertion('webgate_utilisateur','',$id_webgate_rubrique,$id_inter,$id_intervenant,Systeme::now());
			  }
			  
			  $i++;
			}
		}
		// redirection
		echo '<script language="javascript">document.location.href="index.php?m='.$_GET['m'].'&sm=41"</script>';
	  }		  
	   
   }
?>