<?php
	
?>
<script>
	$(function(){
			
	});
</script>
<div align="center" style="margin-top:50px;">
	
	<div align="left" class="action_button_list">
		<ul>
			<li><a class="buttonLink fancybox fancybox.iframe" href="<?php echo Systeme::sm_path('85_addDepartement_form')?>"> Ajouter</a></li>
			<li><a class="buttonLink fancybox fancybox.iframe action_link single_action_link" href="<?php echo Systeme::sm_path('85_addDepartement_form')?>">Modifier</a></li>
			<li><a class="buttonLink" href="<?php echo Systeme::sm_path('85_departements_pdf')?>"> Exporter en PDF</a></li>
		</ul>
	</div>
	<table border="0" id="dataTable" class="display datatable">
        <thead>
            <tr height="30" align="center">
				<th style="text-align:left"><input type="checkbox" name="all_checker" /></th>
				<th>N°</th>
				<th>Désignation</th>
                <th>Description</th>                          
                <th>Enregistrement</th>                      
			</tr>
        </thead>
        <?php
			  $i = 0;
			  
			  $departements = Departement::all();
			  			  
			  foreach($departements as $str)
			  {
				echo '<tr class="line">';
					echo '<td><input type="checkbox" name="dossier_'.$str->id().'" value="'.$str->id().'" /></td>';
					echo '<td>'.(++$i).'</td>';
					echo '<td>'.$str->nom().'</td>';					
					echo '<td>'.$str->description().'</td>';									
					echo '<td>'.Systeme::dateTimeToFrench($str->insertDate()).' par '.$str->insertUser()->fullname().'</td>';										
					
				echo '</tr>';
			  }
		?>
    </table>
</div>