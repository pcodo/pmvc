<?php
	
?>
<script>
	$(function(){
			
	});
</script>
<div align="center" style="margin-top:50px;">
	
	<div align="left" class="action_button_list">
		<ul>
			<?php
				echo '<li><a class="buttonLink fancybox fancybox.iframe action_link single_action_link" href="'.Systeme::sm_path('63_detailDemande_popup').'">Détails</a></li>';
				echo '<li><a class="buttonLink fancybox fancybox.iframe action_link single_action_link" href="'.Systeme::sm_path('63_addNote_form').'">Ajouter une note</a></li>';	
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
				<th>Validation</th>                      
			</tr>
        </thead>
        <?php
			  $i = 0;
			  //$demandes = Demande::notForwardedUserDemandes($intervenant->id(),array(VALIDATED));
			  $demandes = Demande::inStates(array(VALIDATED),$intervenant->id());
			  			  
			  foreach($demandes as $str)
			  {
				echo '<tr class="line">';
					echo '<td><input type="checkbox" name="dossier_'.$str->id().'" value="'.$str->id().'" /></td>';
					echo '<td>'.(++$i).'</td>';
					echo '<td>'.$str->numero().'</td>';	
					echo '<td>'.$str->structure()->nom().'</td>';										
					echo '<td>'.$str->objet().'</td>';										
					$tokenProgress = $str->tokenProgressByState(VALIDATED);		
					echo '<td>'.Systeme::dateTimeToFrench($tokenProgress->insertDate()).' par '.$tokenProgress->insertUser()->fullname().'</td>';								
					
				echo '</tr>';
			  }
		?>
    </table>
</div>