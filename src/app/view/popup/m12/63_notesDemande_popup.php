<?php
require_once("../../requirements.php");
/*
 Auteur: CODO Paterne
 Date de création : 11/03/2013
*/
$demande = null;
$selected_items = Systeme::getJsonItemsOnDialog();
if(null!==$selected_items && count($selected_items)>0)
{
	$demande = new Demande($selected_items[0]);	
}

if($demande == null)
{
	Systeme::debug('Vueillez selectionner une demande!');
}

?>

<script>
	$(function(){
		$('input[name=quitter]').bind('click',function(){
			parent.$.fancybox.close();
		});		
	});
</script>
<div align="center">
	<div id="form_header"> <h3>Détails sur la demande N°<?php echo $demande->numero()?></h3></div>
	<fieldset>
		<legend> Enregistrement </legend>
		<table class="form_table" style="text-align:left;">
		<?php 
			echo '<tr>';
	    		echo '<th>Structure: </th>';
	    		echo '<td> '.$demande->structure()->nom().'</td>';
	    	echo '</tr>';

		 	echo '<tr>';
	    		echo '<th>Objet: </th>';
	    		echo '<td> '.$demande->objet().'</td>';
	    	echo '</tr>';

	    	echo '<tr>';
	    		echo '<th>Description: </th>';
	    		echo '<td> '.$demande->description().'</td>';
	    	echo '</tr>';
	    	
	    	echo '<tr>';
	    		echo '<th>Salles reservées: </th>';
	    		echo '<td> ';
	    			$reservations = $demande->reservations();
	    			echo '<ul style="margin-top:20px;text-align:left;margin-left:-30px;">';
	    				foreach ($reservations as $key => $reservation) {
	    					echo '<li>'.$reservation->salle()->nom().' '.$reservation->horaire().'</li>';
	    				}
	    			echo '</ul>';
	    		echo '</td>';
	    	echo '</tr>';	

	    	echo '<tr>';
	    		echo '<th>Enregistrement: </th>';
	    		echo '<td> '.Systeme::dateTimeToFrench($demande->insertDate()).' par '.$demande->insertUser()->fullname().'</td>';
	    	echo '</tr>';    
		?> 
		</table>
	</fieldset>

	<fieldset>
		<legend> Notes sur la demande </legend>
		<table class="form_table" style="text-align:left;">
			<thead>
				<th>Objet</th>
				<th>Description</th>
				<th>Fichier</th>
				<th>Date</th>
			</thead>
		<?php 
		$notes = $demande->notes();
		foreach ($notes as $key => $note) 
		{
			echo '<tr>';
				echo '<td>'.$note->objet().'</td>';
				echo '<td>'.$note->description().'</td>';
				echo '<td>';
					if($note->file()->id()>0)
					{
						echo '<a href="'.$note->file()->popupUrl().'" width="50px;">'.$note->file()->name().'</a>';
					}
					else
					{
						echo 'N/A';
					}
				echo '</td>';
				echo '<td>'.Systeme::dateTimeToFrench($note->insertDate()).' par '.$note->insertUser()->fullname().'</td>';
			echo '</tr>';
		}			  
		?> 
		</table>
	</fieldset>
	
	<fieldset>
		<legend> Fermer </legend>
		<table cellspacing="10" width="100%" align="left" style="text-align:right;">
			<tr>
				<td>
					<input type="button" name="quitter" value="Quitter"/>
				</td>
			
			</tr>				
		</table>
	</fieldset>
	
</div>
