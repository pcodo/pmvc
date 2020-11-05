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
				<th>Enregistrement</th>                      
				<th>Position</th>                      
			</tr>
        </thead>
        <?php
			  $i = 0;
			  
			  $demandes = Demande::all();
			  			  
			  foreach($demandes as $str)
			  {
				echo '<tr class="line">';
					echo '<td><input type="checkbox" name="dossier_'.$str->id().'" value="'.$str->id().'" /></td>';
					echo '<td>'.(++$i).'</td>';
					echo '<td>'.$str->numero().'</td>';
					echo '<td>'.$str->structure()->nom().'</td>';										
					echo '<td>'.$str->objet().'</td>';										
					echo '<td>'.Systeme::dateTimeToFrench($str->insertDate()).' par '.$str->insertUser()->fullname().'</td>';	
					echo '<td>'.$str->position().'</td>';										
					
				echo '</tr>';
			  }
		?>
    </table>
</div>