<?php
require_once("../../requirements.php");
/*
 Auteur: CODO Paterne
 Date de création : 26/12/2017
*/
$id_demande = 0;
$demande = null;
$selected_items = Systeme::getJsonItemsOnDialog();
if(null!==$selected_items && count($selected_items)>0)
{
	$create_from_structure = isset($_GET['create_from_structure'])?$_GET['create_from_structure']:0;
	if($create_from_structure)
	{
		$id_structure = $selected_items[0];
		$demande = new Demande();
		$demande->setStructureId($id_structure);			
	}
	else
	{
		$id_demande = $selected_items[0];
		$demande = new Demande($id_demande);	
	}
}
$structure = $demande->structure();
$id_structure = $structure->id();

$tag = '``';
$salles = Salle::allDataAsRecords();
echo '<input type="hidden" size="80" name="data_salle_lists" id="data_salle_lists" value="'.preg_replace('#"#',$tag,json_encode($salles)).'" />';
?>

<script>
	$(function(){
		$('input[name=quitter]').bind('click',function(){
			parent.$.fancybox.close();
		});	
		raw_data_salle_lists = $('#data_salle_lists').val().replace(/``/g,'"');
        data_salle_lists = $.parseJSON(raw_data_salle_lists);		
        $('#reservation input[name=adding_reservation]').bind('click',function(){
        	indexObj = $('#reservation input[name=index]');
			index = parseInt(indexObj.val());
			var select_salle_html = '<select name="salle_'+index+'"> <option value="0">--Salle--</option>';
			$.each(data_salle_lists,function(){
				select_salle_html+='<option value="'+this['id']+'">'+this['nom']+' - '+(this['description']).substr(0,30)+'</option>';
			});			
			select_salle_html+='</select>';
            var heure_option = '';
            for(var i=0;i<24;i++)
            {
            	heure_option+='<option value="'+i+'">'+i+' H</option>';
            }
            var min_option = '';
            for(var i=0;i<60;i++)
            {
            	min_option+='<option value="'+i+'">'+i+' mn</option>';
            }
            var sec_option = '';
            for(var i=0;i<60;i++)
            {
            	sec_option+='<option value="'+i+'">'+i+' s</option>';
            }
            var input_date_debut_html = '<input type="text" class="dateField" name="date_debut_'+index+'"/><br/>';
            var select_heure_debut_html = '<select name="heure_debut_'+index+'">'+heure_option+'</select>';
            var select_min_debut_html = '<select name="min_debut_'+index+'">'+min_option+'</select>';
            var select_sec_debut_html = '<select name="sec_debut_'+index+'">'+sec_option+'</select>';

            var input_date_fin_html = '<input type="text" class="dateField" name="date_fin_'+index+'" /><br/>';
            var select_heure_fin_html = '<select name="heure_fin_'+index+'">'+heure_option+'</select>';
            var select_min_fin_html = '<select name="min_fin_'+index+'">'+min_option+'</select>';
            var select_sec_fin_html = '<select name="sec_fin_'+index+'">'+sec_option+'</select>';
        	var html = '<tr>';
        		html+='<td width="5%"><input type="checkbox" name="reservation_'+index+'" checked="checked"></td>';
        		html+='<td width="5%">'+index+'</td>';
        		html+='<td width="40%">'+select_salle_html+'</td>';
        		html+='<td width="25%">'+input_date_debut_html+select_heure_debut_html+select_min_debut_html+select_sec_debut_html+'</td>';
        		html+='<td width="25%">'+input_date_fin_html+select_heure_fin_html+select_min_fin_html+select_sec_fin_html+'</td>';
        		html+='</tr>';
        	$('#reservation').append(html);
        	$( ".dateField" ).datepicker();
        	$('input[name=date_debut_'+index+']').bind('change',function(){
        		$('input[name=date_fin_'+index+']').val($(this).val());
        	})
			indexObj.val(index+1);
        });
	});
</script>
<div align="center">
	<div id="form_header"> <h3>Formulaire d'ajout ou de modification d'une demande de salles</h3></div>
    <form action="" method="post" enctype="multipart/form-data">
		<fieldset >
			<legend> Informations de la demande </legend>
			<table class="form_table" style="text-align:left;">
				<tr >
	                <td><label for="structure"><span class="required">*</span>Structure</label></td>
	                <td colspan="3">
	                <?php
	                	echo '<input type="hidden" name="structure" value="'.$id_structure.'" />';
	                	echo $structure->nom();
	                ?>	                	             
	                </td>                
	            </tr>
				<tr>
					<td><label for="objet"><span class="required">*</span>Objet</label></td>
					<td>
						<input type="text" name="objet" id="objet" value="<?php if(isset($_POST['objet'])) echo $_POST['objet'];else if($id_demande>0) echo $demande->objet();?>" size="50"/>
					</td>
				</tr>
				<tr>
					<td><label for="description">Description</label></td>
					<td>
						<input type="text" name="description" id="description" value="<?php if(isset($_POST['description'])) echo $_POST['description'];else if($id_demande>0) echo $demande->description();?>" size="80"/>
					</td>
				</tr>									
			</table>			
		</fieldset>
		<fieldset>
			<legend>Réservations</legend>
			<table id="reservation" class="form_table" border="1" style="text-align:left;border-collapse:collapse;">
				<thead>
					<tr class="adder_line">
						<th style="text-align:right" colspan="5"><input type="button" name="adding_reservation" value="+"/></th>
					</tr>
					<tr>
						<th></th>
						<th>N°</th>
						<th>Salle</th>
						<th>Date début</th>
						<th>Date Fin</th>						
					</tr>
				</thead>
				<?php
				    $index = isset($_POST['index'])?$_POST['index']:0;
				    for($i=0;$i<$index;$i++)
				    {
				    	echo '<tr>';
				    		echo '<td  width="5%"><input type="checkbox" name="reservation_'.$i.'" '.(isset($_POST['reservation_'.$i])?'checked="checked"':'').'></td>';
				    		echo '<td  width="5%">'.$i.'</td>';
				    		echo '<td  width="40%">';
				    			echo '<select name="salle_'.$i.'">';
				    			echo '<option value="0">--salle--</option>';
				    			foreach ($salles as $key => $salle) {
				    				if(isset($_POST['salle_'.$i]) && $_POST['salle_'.$i]==$salle['id'])
				    					echo '<option value="'.$salle['id'].'" selected="selected">'.$salle['nom'].' - '.substr($salle['description'], 0,30).'</option>';
				    				else
				    					echo '<option value="'.$salle['id'].'">'.$salle['nom'].' - '.substr($salle['description'], 0,30).'</option>';
				    			}
				    			echo '</select>';
				    		echo '</td>';
				    		echo '<td width="25%">';
				    			echo '<input type="text" name="date_debut_'.$i.'" value="'.(isset($_POST['date_debut_'.$i])?$_POST['date_debut_'.$i]:'').'" ><br/>';
				    			echo '<select name="heure_debut_'.$i.'">';
				    				for($t=0;$t<24;$t++)
				    				{
				    					if(isset($_POST['heure_debut_'.$i])&&$_POST['heure_debut_'.$i]==$t)
				    						echo '<option value="'.$t.'" selected="selected">'.$t.' H</option>';
				    					else
				    						echo '<option value="'.$t.'">'.$t.' H</option>';
				    				}
				    			echo '</select>';
				    			echo '<select name="min_debut_'.$i.'">';
				    				for($t=0;$t<60;$t++)
				    				{
				    					if(isset($_POST['min_debut_'.$i])&&$_POST['min_debut_'.$i]==$t)
				    						echo '<option value="'.$t.'" selected="selected">'.$t.' mn</option>';
				    					else
				    						echo '<option value="'.$t.'">'.$t.' mn</option>';
				    				}
				    			echo '</select>';
				    			echo '<select name="sec_debut_'.$i.'">';
				    				for($t=0;$t<60;$t++)
				    				{
				    					if(isset($_POST['sec_debut_'.$i])&&$_POST['sec_debut_'.$i]==$t)
				    						echo '<option value="'.$t.'" selected="selected">'.$t.' s</option>';
				    					else
				    						echo '<option value="'.$t.'">'.$t.' s</option>';
				    				}
				    			echo '</select>';
				    		echo '</td>';
				    		echo '<td  width="25%">';
				    			echo '<input type="text" name="date_fin_'.$i.'" value="'.(isset($_POST['date_fin_'.$i])?$_POST['date_fin_'.$i]:'').'" ><br/>';
				    			echo '<select name="heure_fin_'.$i.'">';
				    				for($t=0;$t<24;$t++)
				    				{
				    					if(isset($_POST['heure_fin_'.$i])&&$_POST['heure_fin_'.$i]==$t)
				    						echo '<option value="'.$t.'" selected="selected">'.$t.' H</option>';
				    					else
				    						echo '<option value="'.$t.'">'.$t.' H</option>';
				    				}
				    			echo '</select>';
				    			echo '<select name="min_fin_'.$i.'">';
				    				for($t=0;$t<60;$t++)
				    				{
				    					if(isset($_POST['min_fin_'.$i])&&$_POST['min_fin_'.$i]==$t)
				    						echo '<option value="'.$t.'" selected="selected">'.$t.' mn</option>';
				    					else
				    						echo '<option value="'.$t.'">'.$t.' mn</option>';
				    				}
				    			echo '</select>';
				    			echo '<select name="sec_fin_'.$i.'">';
				    				for($t=0;$t<60;$t++)
				    				{
				    					if(isset($_POST['sec_fin_'.$i])&&$_POST['sec_fin_'.$i]==$t)
				    						echo '<option value="'.$t.'" selected="selected">'.$t.' s</option>';
				    					else
				    						echo '<option value="'.$t.'">'.$t.' s</option>';
				    				}
				    			echo '</select>';
				    		echo '</td>';
				    	echo '</tr>';
				    }
				    if($index == 0 && $id_demande > 0)
				    {
				    	$reservations = $demande->reservations();
				    	$i=0;
				    	foreach ($reservations as $key => $reservation) 
				    	{
				    		echo '<tr>';
					    		echo '<td  width="5%"><input type="checkbox" name="reservation_'.$i.'" checked="checked"></td>';
					    		echo '<td  width="5%">'.$i.'</td>';
					    		echo '<td  width="40%">';
					    			echo '<select name="salle_'.$i.'">';
					    			echo '<option value="0">--salle--</option>';
					    			foreach ($salles as $key => $salle) {
					    				if($reservation->salle()->id()==$salle['id'])
					    					echo '<option value="'.$salle['id'].'" selected="selected">'.$salle['nom'].' - '.substr($salle['description'], 0,30).'</option>';
					    				else
					    					echo '<option value="'.$salle['id'].'">'.$salle['nom'].' - '.substr($salle['description'], 0,30).'</option>';
					    			}
					    			echo '</select>';
					    		echo '</td>';
					    		echo '<td width="25%">';
					    			echo '<input type="text" name="date_debut_'.$i.'" class="dateField" value="'.(Systeme::dateToFrench($reservation->dateDebutWithoutTimes())).'" ><br/>';
					    			echo '<select name="heure_debut_'.$i.'">';
					    				for($t=0;$t<24;$t++)
					    				{
					    					if($reservation->heureDebut()==$t)
					    						echo '<option value="'.$t.'" selected="selected">'.$t.' H</option>';
					    					else
					    						echo '<option value="'.$t.'">'.$t.' H</option>';
					    				}
					    			echo '</select>';
					    			echo '<select name="min_debut_'.$i.'">';
					    				for($t=0;$t<60;$t++)
					    				{
					    					if($reservation->minuteDebut()==$t)
					    						echo '<option value="'.$t.'" selected="selected">'.$t.' mn</option>';
					    					else
					    						echo '<option value="'.$t.'">'.$t.' mn</option>';
					    				}
					    			echo '</select>';
					    			echo '<select name="sec_debut_'.$i.'">';
					    				for($t=0;$t<60;$t++)
					    				{
					    					if($reservation->secondeDebut()==$t)
					    						echo '<option value="'.$t.'" selected="selected">'.$t.' s</option>';
					    					else
					    						echo '<option value="'.$t.'">'.$t.' s</option>';
					    				}
					    			echo '</select>';
					    		echo '</td>';
					    		echo '<td  width="25%">';
					    			echo '<input type="text" class="dateField" name="date_fin_'.$i.'" value="'.(Systeme::dateToFrench($reservation->dateFinWithoutTimes())).'" ><br/>';
					    			echo '<select name="heure_fin_'.$i.'">';
					    				for($t=0;$t<24;$t++)
					    				{
					    					if($reservation->heureFin()==$t)
					    						echo '<option value="'.$t.'" selected="selected">'.$t.' H</option>';
					    					else
					    						echo '<option value="'.$t.'">'.$t.' H</option>';
					    				}
					    			echo '</select>';
					    			echo '<select name="min_fin_'.$i.'">';
					    				for($t=0;$t<60;$t++)
					    				{
					    					if($reservation->minuteFin()==$t)
					    						echo '<option value="'.$t.'" selected="selected">'.$t.' mn</option>';
					    					else
					    						echo '<option value="'.$t.'">'.$t.' mn</option>';
					    				}
					    			echo '</select>';
					    			echo '<select name="sec_fin_'.$i.'">';
					    				for($t=0;$t<60;$t++)
					    				{
					    					if($reservation->secondeFin()==$t)
					    						echo '<option value="'.$t.'" selected="selected">'.$t.' s</option>';
					    					else
					    						echo '<option value="'.$t.'">'.$t.' s</option>';
					    				}
					    			echo '</select>';
					    		echo '</td>';
					    	echo '</tr>';
					    	$i++;
				    	}
				    	$index = $i;
				    }
					echo '<input type="hidden" name="index" value="'.$index.'">';
				?>
			</table>
		</fieldset>
		
		<fieldset>
			<legend> Validation </legend>
			<table cellspacing="10" width="100%" align="left" style="text-align:right;">
				<tr>
					<td>
						<input type="submit" name="valider" value="Enregistrer"/>
						<input type="reset" name="annuler" value="Annuler"/>
						<input type="button" name="quitter" value="Quitter"/>
					</td>				
				</tr>							
			</table>
		</fieldset>
	</form>
</div>

<?php
	//Systeme::array_key_values()
    if(isset($_POST['valider']))
    {
    	if($id_demande>0)
    		$result = DemandeFormManager::processUpdateDemandeSubmit($intervenant->id(),$id_demande);
    	else
    		$result = DemandeFormManager::processAddDemandeSubmit($intervenant->id());

		AppFormErrorController::checkSubmitResult($result);
    }
	
?>