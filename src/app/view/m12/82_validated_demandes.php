<?php
	$id_salle = 0;
	$date_debut = '';	  	
	$date_fin = '';	 
	if(isset($_GET['search']))
	{
		$id_salle = isset($_GET['id_salle'])?$db->escape($_GET['id_salle']):0;
		$date_debut = isset($_GET['date_debut'])?$db->escape($_GET['date_debut']):'';
		$date_fin = isset($_GET['date_fin'])?$db->escape($_GET['date_fin']):'';
		$search_params = array(
			'id_salle'=>$id_salle,
	  		'date_debut'=>$date_debut,	  		  		
	  		'date_fin'=>$date_fin	  		  		
	  	);	  	
	}
?>
<script>
	$(function(){
		
			$('input[name=search]').bind('click', function(e){
				 e.preventDefault();
				 var v_url = document.location.href+'&date_debut='+$('#date_debut').val()+'&date_fin='+$('#date_fin').val()+'&id_salle='+$('#id_salle').val()+'&search=1';
				 $.redirect(v_url);
			});
	});
</script>
<div align="center" style="margin-top:50px;">
	<form  name="search_filter" method="post" action="">
		<table style="border:thin inset;border-top:thin outset;border-left:thin outset;background-color:rgb(200,200,200)">
			
			<tr style="">
				<td><label for="date_debut">Réservations: Début</label></td>
				<td><input type="text" class="dateField" name="date_debut" id="date_debut" value="<?php if(isset($_GET['date_debut'])) echo $_GET['date_debut'];?>" placeHolder="jj/mm/yyyy"/></td>

				<td><label for="date_fin">Fin</label></td>
				<td><input type="text" class="dateField" name="date_fin" id="date_fin" value="<?php if(isset($_GET['date_fin'])) echo $_GET['date_fin'];?>" placeHolder="jj/mm/yyyy"/></td>
											    	
			</tr>
			<tr>
				<td>Salle</td>
				<td colspan="3">
					<select name="id_salle" id="id_salle">
					<?php
					  $salles = Salle::all();
					  echo '<option value="0">--select--</option>';
					  foreach($salles as $salle)
					  {
					    if( (isset($_POST['id_salle'])&&$_POST['id_salle']==$salle->id()))
							echo '<option title="'.$departement->description().'" value="'.$salle->id().'" selected="selected">'.$salle->details().'</option>';
						else
							echo '<option title="'.$salle->description().'" value="'.$salle->id().'">'.$salle->details().'</option>';
					  }
					?>
					</select>
				</td>
			</tr>						
			<tr>
				<td colspan="4" align="right"><input type="submit" name="search" value="Filtrer"/></td>		
			</tr>
		</table>		
	</form>
	<div align="left" class="action_button_list">
		<ul>
			<?php
				echo '<li><a class="buttonLink fancybox fancybox.iframe action_link single_action_link" href="'.Systeme::sm_path('63_detailDemande_popup').'">Détails</a></li>';
				echo '<li><a class="buttonLink fancybox fancybox.iframe action_link single_action_link" href="'.Systeme::sm_path('63_addNote_form').'">Ajouter une note</a></li>';	
				echo '<li><a class="buttonLink fancybox fancybox.iframe action_link single_action_link" href="'.Systeme::sm_path('63_notesDemande_popup').'">Notes</a></li>';
				if($date_fin!='' && $date_fin!='')
				{
					echo '<li><a class="buttonLink" href="'.Systeme::sm_path('63_reservation_export_pdf',array('date_debut'=>$date_debut,'date_fin'=>$date_fin,'id_salle'=>$id_salle)).'"> Exporter le planning</a></li>';
				}
					
			?>			
		</ul>
	</div>
	<table border="0" id="dataTable" class="display datatable">
        <thead>
            <tr height="30" align="center">
				<th style="text-align:left"><input type="checkbox" name="all_checker" /></th>
				<th></th>
				<th>N°</th>
				<th>Structure</th>
				<th>Objet</th>
				<th>Plage Horaire</th>
				<th>Validation</th>                      
			</tr>
        </thead>
        <?php
			  $i = 0;
			  $tab_filter_ids = null;
			  if(isset($_GET['search']))
			  {
			  	$records = Demande::searchByReservationDateAsRecords(Systeme::dateToEnglish($date_debut),Systeme::dateToEnglish($date_fin),$id_salle);
			  	$tab_filter_ids = Systeme::array_key_values($records,'id');

			  }			  
			  $demandes = Demande::inStates(array(VALIDATED),0,$tab_filter_ids);
			  		 
			  			  
			  foreach($demandes as $str)
			  {
				echo '<tr class="line">';
					echo '<td><input type="checkbox" name="dossier_'.$str->id().'" value="'.$str->id().'" /></td>';
					echo '<td>'.(++$i).'</td>';
					echo '<td>'.$str->numero().'</td>';	
					echo '<td>'.$str->structure()->nom().'</td>';										
					echo '<td>'.$str->objet().'</td>';										
					echo '<td>'.$str->horaire().'</td>';	
					$tokenProgress = $str->tokenProgressByState(VALIDATED);					
					echo '<td>'.Systeme::dateTimeToFrench($tokenProgress->insertDate()).' par '.$tokenProgress->insertUser()->fullname().'</td>';										
					
				echo '</tr>';
			  }

		?>
    </table>
</div>