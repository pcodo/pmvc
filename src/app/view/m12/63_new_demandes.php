<?php
    //$mailer = new MyPHPMailer();
	//$mailer->sendMail('test PMVC', 'test contenu',array('codopaterne@gmail.com'));
?>
<script>
	$(function(){
			
	});
</script>
<div align="center" style="margin-top:50px;">
	
	<div align="left" class="action_button_list">
		<ul>
			<?php
				echo '<li><a class="buttonLink fancybox fancybox.iframe" href="'.Systeme::sm_path('63_addDemande_form').'">Ajouter</a></li>';	

				echo '<li><a class="buttonLink fancybox fancybox.iframe action_link single_action_link" href="'.Systeme::sm_path('63_detailDemande_popup').'">Détails</a></li>';

				echo '<li><a class="buttonLink fancybox fancybox.iframe action_link single_action_link" href="'.Systeme::sm_path('63_addDemande_form').'">Modifier</a></li>';

				echo '<li><a class="buttonLink fancybox fancybox.iframe action_link group_action_link" href="'.Systeme::sm_path('63_forwardDemande_form').'">Envoyer</a></li>';	
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
				<th>Plage horaire</th>
				<th>Enregistrement</th>                      
			</tr>
        </thead>
        <?php
			  $i = 0;
			  
			  $demandes = Demande::notForwarded($intervenant->id());
			  			  
			  foreach($demandes as $str)
			  {
				echo '<tr class="line">';
					echo '<td><input type="checkbox" name="dossier_'.$str->id().'" value="'.$str->id().'" /></td>';
					echo '<td>'.(++$i).'</td>';
					echo '<td>'.$str->numero().'</td>';
					echo '<td>'.$str->structure()->nom().'</td>';										
					echo '<td>'.$str->objet().'</td>';										
					echo '<td>'.$str->horaire().'</td>';										
					echo '<td>'.Systeme::dateTimeToFrench($str->insertDate()).' par '.$str->insertUser()->fullname().'</td>';										
					
				echo '</tr>';
			  }
		?>
    </table>
</div>