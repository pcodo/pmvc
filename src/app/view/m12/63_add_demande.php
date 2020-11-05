<?php
	$search_params = array();
	$structures = array();
	if(isset($_POST['search']))
	{
		Systeme::redirect(Systeme::buildUrl(12,63,array('search'=>$_POST['search'],'nom'=>$_POST['nom'])));
	}
	if(isset($_GET['search']))
	{
		$nom = $db->escape($_GET['nom']);	  	
	  	$search_params = array(
	  		'nom'=>$nom	  		  		
	  	);	
	  	$structures = Structure::search($search_params);  	
	}
 			
?>
<script>
	$(function(){
		
	});
</script>
<div align="center" style="min-height:500px;">
    <?php
		
		$records = Demande::notForwardedAsRecords();
		echo '<p style="color:blue;font-weight:bold;clear:both;font-size:24px;">'.count($records).' nouvelle(s) demande(s) en attente d\'affectation.</p>';

	?>
    <form  name="search_usager" method="post" action="">
		<table style="border:thin inset;border-top:thin outset;border-left:thin outset;background-color:rgb(200,200,200)">
			
			<tr style="">
				<td><label for="nom">Nom (SIGLE)</label></td>
				<td><input type="text" size="60" name="nom" id="nom" value="<?php if(isset($_GET['nom'])) echo $_GET['nom'];?>"/></td>
											    	
			</tr>					
			<tr>
				<td colspan="4" align="right"><input type="submit" name="search" value="Rechercher"/></td>		
			</tr>
		</table>		
	</form>
	<div align="left" class="action_button_list">
		<ul>
			<?php
				if( isset($_GET['search']) && count($structures)>0)
				{
					echo '<li><a class="buttonLink fancybox fancybox.iframe single_action_link action_link" href="'.Systeme::sm_path('63_addDemandeFromSearch_form',array('id_section'=>0,'create_from_structure'=>1)).'"> Ajouter une demande</a></li>';
				}
				else if (isset($_GET['search'])) 
				{
					echo '<li><a class="buttonLink fancybox fancybox.iframe" href="'.Systeme::sm_path('63_addDemande_form',array('id_section'=>0,'nom_structure'=>$nom)).'"> Créer une structure</a></li>';					
				}
			?>
			
		</ul>
	</div>
	<?php  if (count($structures)>0) { ?>
	<table border="0" id="dataTable" class="display datatable">
        <thead>
            <tr height="30" align="center">
				<th></th>
				<th>N°</th>
				<th>Type</th>
				<th>Désignation</th>
                <th>Description</th>                
                <th>Téléphone</th>
                <th>Département</th>                    
                <th>Enregistrement</th> 
				<th>Dossiers</th>
		    </tr>
        </thead>
        <?php
			  foreach($structures as $str)
			  {
			  	echo '<tr class="line">';
					echo '<td><input type="checkbox" name="dossier_'.$str->id().'" value="'.$str->id().'"/></td>';
					echo '<td>'.(++$i).'</td>';
					echo '<td>'.$str->structureType()->nom().'</td>';										
					echo '<td>'.$str->nom().'</td>';										
					echo '<td>'.$str->description().'</td>';
					echo '<td>'.$str->telephone().'</td>';		
					echo '<td>'.$str->departement()->nom().'</td>';										
					echo '<td>'.Systeme::dateTimeToFrench($str->insertDate()).' par '.$str->insertUser()->fullname().'</td>';
					echo '<td>';
						//$demandes = $strcuture->demandes();						
					echo '</td>';
				echo '</tr>';				
			  }			  			  
		?>
    </table>
    <?php } ?>
   
</div>