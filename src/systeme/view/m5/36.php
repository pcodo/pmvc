<?php
/*
 Auteur: CODO Paterne
 Date de création : 11/03/2013
*/
	
?>
<div align="center">
    <h3> Liste des ex membres utilisateurs du système </h3>
	<table border="0" id="dataTable" class="display datatable">
        <thead>
            <tr height="30" align="center">
				<th>N°</th>
                <th>Nom</th>
                <th>Prénoms</th>
				<th>Direction</th>
				<th>Poste</th>
				<th>Détails</th>
				<th>Date de retrait</th>
				<th>Motif retrait</th>
			</tr>
        </thead>
        <?php
		$i=0;
        $intervenants = Systeme::exUserlist();
        foreach($intervenants as $membre) {
			echo '<tr>';
			echo '<td>'.(++$i).'</td>';
			echo '<td>'.$membre['nom'].'</td>';
			echo '<td>'.$membre['prenom'].'</td>';
			echo '<td>'.$membre['direction'].'</td>';
			echo '<td>'.$membre['poste'].'</td>';
			echo '<td><a href="index.php?m='.$_GET['m'].'&sm=8_fiche&id_membre='.$membre['id'].'">Fiche</a></td>';
			echo '<td>'.Systeme::dateTimeToFrench($membre['date_enregistrement']).'</td>';
			echo '<td>'.$membre['motif_retrait'].'</td>';
			echo '</tr>';
        }
      	
		?>
    </table>
</div>
